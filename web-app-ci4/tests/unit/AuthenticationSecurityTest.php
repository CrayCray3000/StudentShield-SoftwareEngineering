<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Filters\AuthFilter;
use App\Filters\RateLimitFilter;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\URI;
use CodeIgniter\HTTP\UserAgent;
use Config\App;
use Config\Services;

/**
 * Security & Session Controls Test Suite
 *
 * Verifies:
 * 1. Login & Email verification (rate limits, single-use OTP, expiration, generic errors)
 * 2. Shared computer controls (server-enforced idle timeouts, sign out destruction)
 * 3. Role-based access control & IDOR prevention (student vs counselor vs super admin)
 *
 * @internal
 */
final class AuthenticationSecurityTest extends CIUnitTestCase
{
    private string $rateLimitDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rateLimitDir = WRITEPATH . 'rate_limits/';
        $this->cleanRateLimits();
    }

    protected function tearDown(): void
    {
        $this->cleanRateLimits();
        if (session_status() === PHP_SESSION_ACTIVE) {
            session()->destroy();
        }
        parent::tearDown();
    }

    private function cleanRateLimits(): void
    {
        if (is_dir($this->rateLimitDir)) {
            $files = glob($this->rateLimitDir . '*.json');
            if ($files) {
                foreach ($files as $file) {
                    @unlink($file);
                }
            }
        }
    }

    private function createPostRequest(string $uri, string $ip = '192.168.1.50', bool $isAjax = false, array $post = []): IncomingRequest
    {
        service('superglobals')->setServer('REMOTE_ADDR', $ip);
        $_SERVER['REMOTE_ADDR'] = $ip;

        if ($isAjax) {
            service('superglobals')->setServer('HTTP_X_REQUESTED_WITH', 'XMLHttpRequest');
            $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        } else {
            service('superglobals')->unsetServer('HTTP_X_REQUESTED_WITH');
            unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        }

        service('superglobals')->setPostArray($post);
        $_POST = $post;

        $config = new App();
        $uriObj = new URI(base_url($uri));
        $userAgent = new UserAgent();

        $request = new IncomingRequest($config, $uriObj, null, $userAgent);
        $request->setMethod('POST');

        if ($isAjax) {
            $request->setHeader('X-Requested-With', 'XMLHttpRequest');
        }

        return $request;
    }

    public function testRateLimitFilterEnforcesMaxFiveLoginAttempts(): void
    {
        $filter = new RateLimitFilter();
        $testIp = '10.0.0.99';

        // 5 allowed attempts
        for ($i = 1; $i <= 5; $i++) {
            $req = $this->createPostRequest('login/authenticate', $testIp, true);
            $response = $filter->before($req);
            $this->assertNull($response, "Attempt {$i} should be allowed through.");
        }

        // 6th attempt must be locked out with HTTP 429
        $req6 = $this->createPostRequest('login/authenticate', $testIp, true);
        $response6 = $filter->before($req6);
        $this->assertNotNull($response6, '6th attempt must be blocked by RateLimitFilter.');
        $this->assertSame(429, $response6->getStatusCode());

        $body = json_decode($response6->getBody(), true);
        $this->assertSame('error', $body['status'] ?? null);
        $this->assertStringContainsString('Too many login attempts', $body['message'] ?? '');
    }

    public function testRateLimitFilterEnforcesMaxFiveOtpVerificationAttempts(): void
    {
        $filter = new RateLimitFilter();
        $testIp = '10.0.0.100';

        // 5 allowed OTP verification attempts
        for ($i = 1; $i <= 5; $i++) {
            $req = $this->createPostRequest('admin/verify-otp', $testIp, true);
            $response = $filter->before($req);
            $this->assertNull($response, "OTP attempt {$i} should be permitted.");
        }

        // 6th attempt must be locked out
        $req6 = $this->createPostRequest('admin/verify-otp', $testIp, true);
        $response6 = $filter->before($req6);
        $this->assertNotNull($response6, '6th OTP attempt must be rate-limited.');
        $this->assertSame(429, $response6->getStatusCode());

        $body = json_decode($response6->getBody(), true);
        $this->assertStringContainsString('Too many verification attempts', $body['message'] ?? '');
    }

    public function testRateLimitFilterEnforcesMaxThreeResendRequests(): void
    {
        $filter = new RateLimitFilter();
        $testIp = '10.0.0.101';

        // 3 allowed resend attempts
        for ($i = 1; $i <= 3; $i++) {
            $req = $this->createPostRequest('admin/resend-otp', $testIp, true);
            $response = $filter->before($req);
            $this->assertNull($response, "Resend request {$i} should be permitted.");
        }

        // 4th request must be locked out
        $req4 = $this->createPostRequest('admin/resend-otp', $testIp, true);
        $response4 = $filter->before($req4);
        $this->assertNotNull($response4, '4th resend request must be rate-limited.');
        $this->assertSame(429, $response4->getStatusCode());

        $body = json_decode($response4->getBody(), true);
        $this->assertStringContainsString('Too many resend requests', $body['message'] ?? '');
    }

    public function testOtpExpirationEnforcement(): void
    {
        session()->set([
            'temp_user_id' => 999,
            'otp'          => '123456',
            'otp_expires'  => time() - 30, // Expired 30 seconds ago
            'otp_attempts' => 0,
        ]);

        $controller = new \App\Controllers\AuthController();
        $controller->initController(
            $this->createPostRequest('admin/verify-otp', '192.168.1.50', false, ['otp' => '123456']),
            Services::response(),
            Services::logger()
        );

        $response = $controller->verifyOtp();

        // Expired OTP must clear temporary session and redirect to login
        $this->assertFalse(session()->has('otp'), 'Expired OTP must be purged from session.');
        $this->assertFalse(session()->has('temp_user_id'), 'Temporary user ID must be purged.');
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testOtpSingleUseEnforcement(): void
    {
        session()->set([
            'temp_user_id'   => 999,
            'temp_name'      => 'Test Counselor',
            'temp_role'      => 1,
            'temp_role_name' => 'counselor',
            'otp'            => '654321',
            'otp_expires'    => time() + 300,
            'otp_attempts'   => 0,
        ]);

        $controller = new \App\Controllers\AuthController();
        $controller->initController(
            $this->createPostRequest('admin/verify-otp', '192.168.1.50', false, ['otp' => '654321']),
            Services::response(),
            Services::logger()
        );

        $response = $controller->verifyOtp();

        // First verification should succeed and transition to authenticated session
        $this->assertTrue(session()->get('isLoggedIn'), 'User should be marked logged in.');
        $this->assertSame(999, session()->get('user_id'));
        $this->assertFalse(session()->has('otp'), 'OTP must be removed immediately upon first use (single-use).');
        $this->assertFalse(session()->has('temp_user_id'));

        // Attempting to verify again must fail (code does not exist anymore)
        $secondController = new \App\Controllers\AuthController();
        $secondController->initController(
            $this->createPostRequest('admin/verify-otp', '192.168.1.50', false, ['otp' => '654321']),
            Services::response(),
            Services::logger()
        );
        $secondResponse = $secondController->verifyOtp();
        $this->assertInstanceOf(RedirectResponse::class, $secondResponse);
    }

    public function testOtpIncorrectAttemptsLimitAndLockout(): void
    {
        session()->set([
            'temp_user_id'   => 888,
            'temp_name'      => 'Staff Target',
            'temp_role'      => 1,
            'temp_role_name' => 'counselor',
            'otp'            => '999888',
            'otp_expires'    => time() + 300,
            'otp_attempts'   => 0,
        ]);

        $controller = new \App\Controllers\AuthController();

        // Submit 4 wrong attempts
        for ($i = 1; $i <= 4; $i++) {
            $controller->initController(
                $this->createPostRequest('admin/verify-otp', '192.168.1.50', false, ['otp' => '000000']),
                Services::response(),
                Services::logger()
            );
            $controller->verifyOtp();
            $this->assertSame($i, session()->get('otp_attempts'));
            $this->assertTrue(session()->has('otp'));
        }

        // 5th wrong attempt must trigger lockout and destroy OTP session
        $controller->initController(
            $this->createPostRequest('admin/verify-otp', '192.168.1.50', false, ['otp' => '000000']),
            Services::response(),
            Services::logger()
        );
        $controller->verifyOtp();
        $this->assertFalse(session()->has('otp'), 'OTP must be destroyed after 5 failed guesses.');
        $this->assertFalse(session()->has('temp_user_id'), 'Temporary session must be destroyed after 5 failed guesses.');
    }

    public function testAuthFilterServerEnforcedIdleTimeout(): void
    {
        $filter = new AuthFilter();

        // Set logged-in session with last_activity older than 15 minutes (900 seconds)
        session()->set([
            'isLoggedIn'    => true,
            'user_id'       => 10,
            'name'          => 'Idle Student',
            'role'          => 0,
            'role_name'     => 'student',
            'last_activity' => time() - 950, // 950 seconds ago (expired)
        ]);

        $req = $this->createPostRequest('student/dashboard', '127.0.0.1', true);
        $response = $filter->before($req);

        // Must terminate session on the server and return 401
        $this->assertNotNull($response, 'Expired session must be intercepted by AuthFilter.');
        $this->assertSame(401, $response->getStatusCode());
        $body = json_decode($response->getBody(), true);
        $this->assertStringContainsString('Session expired due to inactivity', $body['message'] ?? '');
    }

    public function testAuthFilterAllowsActiveSessionWithinIdleTimeout(): void
    {
        $filter = new AuthFilter();

        // Active session within 15-minute window
        session()->set([
            'isLoggedIn'    => true,
            'user_id'       => 10,
            'name'          => 'Active Student',
            'role'          => 0,
            'role_name'     => 'student',
            'last_activity' => time() - 120, // 2 minutes ago
        ]);

        $config = new App();
        $uriObj = new URI(base_url('student/dashboard'));
        $req = new IncomingRequest($config, $uriObj, null, new UserAgent());

        $response = $filter->before($req);

        // Must be allowed through
        $this->assertNull($response, 'Active session should not be blocked.');
        $this->assertGreaterThanOrEqual(time() - 2, session()->get('last_activity'), 'last_activity must be updated.');
    }

    public function testAuthFilterBlocksStudentAccessToAdminRoutes(): void
    {
        $filter = new AuthFilter();

        session()->set([
            'isLoggedIn'    => true,
            'user_id'       => 50,
            'name'          => 'Sneaky Student',
            'role'          => 0, // Student
            'role_name'     => 'student',
            'last_activity' => time(),
        ]);

        $config = new App();
        $uriObj = new URI(base_url('admin/dashboard'));
        $req = new IncomingRequest($config, $uriObj, null, new UserAgent());

        $response = $filter->before($req);

        // Student accessing admin dashboard must be redirected to /student/vent
        $this->assertNotNull($response);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testAuthFilterBlocksCounselorAccessToSuperAdminRoutes(): void
    {
        $filter = new AuthFilter();

        session()->set([
            'isLoggedIn'    => true,
            'user_id'       => 60,
            'name'          => 'Counselor User',
            'role'          => 1, // Counselor
            'role_name'     => 'counselor',
            'last_activity' => time(),
        ]);

        $config = new App();
        $uriObj = new URI(base_url('admin/users'));
        $req = new IncomingRequest($config, $uriObj, null, new UserAgent());

        $response = $filter->before($req);

        // Counselor accessing /admin/users must be redirected
        $this->assertNotNull($response);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testAdminControllerEvidenceGuardsAgainstUnauthenticatedAndStudents(): void
    {
        $controller = new \App\Controllers\AdminController();

        // 1. Unauthenticated request
        session()->destroy();
        $controller->initController(
            $this->createPostRequest('admin/evidence/photo.jpg'),
            Services::response(),
            Services::logger()
        );
        $res1 = $controller->evidence('photo.jpg');
        $this->assertSame(401, $res1->getStatusCode(), 'Unauthenticated user must be denied evidence access.');

        // 2. Student session (role = 0)
        session()->set([
            'isLoggedIn' => true,
            'user_id'    => 10,
            'role'       => 0,
        ]);
        $res2 = $controller->evidence('photo.jpg');
        $this->assertSame(401, $res2->getStatusCode(), 'Student must be denied evidence access.');
    }
}
