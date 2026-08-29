<?php

namespace Tests\Unit;

use App\Filters\RateLimitFilter;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\Test\CIUnitTestCase;
use ReflectionProperty;

/**
 * @internal
 */
final class RateLimitFilterTest extends CIUnitTestCase
{
    private string $testIp = '192.168.1.100';
    private string $expectedHash;
    private string $expectedFilePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->expectedHash = hash('sha256', $this->testIp);
        $this->expectedFilePath = WRITEPATH . 'rate_limits/' . $this->expectedHash . '.json';

        if (file_exists($this->expectedFilePath)) {
            unlink($this->expectedFilePath);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        if (file_exists($this->expectedFilePath)) {
            unlink($this->expectedFilePath);
        }
    }

    private function createMockRequest(string $method = 'POST', string $ip = '192.168.1.100'): IncomingRequest
    {
        /** @var IncomingRequest $request */
        $request = service('request', null, false);
        $request->setMethod($method);

        $refProp = new ReflectionProperty($request, 'ipAddress');
        $refProp->setValue($request, $ip);

        return $request;
    }

    public function testBeforeIgnoresNonPostRequests(): void
    {
        $filter = new RateLimitFilter();
        $request = $this->createMockRequest('GET', $this->testIp);

        $filter->before($request);

        $this->assertFileDoesNotExist($this->expectedFilePath);
    }

    public function testBeforeCreatesSha256HashedRateLimitFileOnPost(): void
    {
        $filter = new RateLimitFilter();
        $request = $this->createMockRequest('POST', $this->testIp);

        $filter->before($request);

        $this->assertEquals($this->testIp, $request->getIPAddress());
        $this->assertFileExists($this->expectedFilePath);

        $data = json_decode(file_get_contents($this->expectedFilePath), true);
        $this->assertIsArray($data);
        $this->assertEquals(1, $data['attempts']);
    }

    public function testBeforeIncrementsAttempts(): void
    {
        $filter = new RateLimitFilter();
        $request = $this->createMockRequest('POST', $this->testIp);

        $filter->before($request);
        $filter->before($request);

        $this->assertFileExists($this->expectedFilePath);
        $data = json_decode(file_get_contents($this->expectedFilePath), true);
        $this->assertEquals(2, $data['attempts']);
    }

    public function testAfterClearsRateLimitOnLoggedInSession(): void
    {
        $filter = new RateLimitFilter();
        $request = $this->createMockRequest('POST', $this->testIp);

        // Record attempt first
        $filter->before($request);
        $this->assertFileExists($this->expectedFilePath);

        // Simulate logged in session
        session()->set('isLoggedIn', true);

        $response = service('response');
        $filter->after($request, $response);

        $this->assertFileDoesNotExist($this->expectedFilePath);

        // Clean up session
        session()->remove('isLoggedIn');
    }
}
