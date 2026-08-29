<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Libraries\CookieSigner;

/**
 * @internal
 */
final class CookieSignerTest extends CIUnitTestCase
{
    public function testSignAndVerifyValidCookie(): void
    {
        $payload = '123:random_secure_token_abc';
        $signedCookie = CookieSigner::sign($payload);

        $this->assertNotEmpty($signedCookie);
        $this->assertNotEquals($payload, $signedCookie);

        $verifiedPayload = CookieSigner::verify($signedCookie);
        $this->assertEquals($payload, $verifiedPayload);
    }

    public function testTamperedPayloadRejected(): void
    {
        $payload = '123:random_secure_token_abc';
        $signedCookie = CookieSigner::sign($payload);

        $decoded = base64_decode($signedCookie);
        // Modify payload part (e.g. change user ID from 123 to 124)
        $tamperedDecoded = str_replace('123:', '124:', $decoded);
        $tamperedCookie = base64_encode($tamperedDecoded);

        $verifiedPayload = CookieSigner::verify($tamperedCookie);
        $this->assertNull($verifiedPayload);
    }

    public function testUnsignedLegacyCookieRejected(): void
    {
        // Legacy unsigned cookie: base64_encode("123:random_secure_token_abc")
        $legacyCookie = base64_encode('123:random_secure_token_abc');

        $verifiedPayload = CookieSigner::verify($legacyCookie);
        $this->assertNull($verifiedPayload);
    }

    public function testMalformedBase64Rejected(): void
    {
        $invalidCookie = '!!!not_valid_base64!!!';

        $verifiedPayload = CookieSigner::verify($invalidCookie);
        $this->assertNull($verifiedPayload);
    }

    public function testEmptyStringRejected(): void
    {
        $verifiedPayload = CookieSigner::verify('');
        $this->assertNull($verifiedPayload);
    }
}
