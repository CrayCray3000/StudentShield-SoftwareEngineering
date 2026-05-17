<?php

namespace App\Libraries;

/**
 * Centralized notification service for SMS and Email delivery.
 * Eliminates duplication across VentController, AdminController, and BullyReportController.
 */
class NotificationService
{
    /**
     * Normalize a Philippine phone number to 09XXXXXXXXX format.
     */
    public static function normalizePhone(string $phone): string
    {
        $clean = ltrim($phone, '+');
        if (str_starts_with($clean, '63') && strlen($clean) == 12) {
            $clean = '0' . substr($clean, 2);
        }
        return $clean;
    }

    /**
     * Send SMS via the local Android phone gateway.
     */
    public static function sendSms(string $phone, string $message): bool
    {
        if (empty($phone)) return false;

        $gatewayUrl = env('sms.GatewayUrl', '');
        if (empty($gatewayUrl)) {
            log_message('warning', 'SMS Gateway URL not configured. Skipping SMS.');
            return false;
        }

        $cleanPhone = self::normalizePhone($phone);
        $payload = json_encode(['phone' => $cleanPhone, 'message' => $message]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $gatewayUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Connection: close'],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            log_message('error', "SMS Gateway cURL Error: {$err}");
            return false;
        }

        log_message('info', "SMS sent to {$cleanPhone}: " . $response);
        return true;
    }

    /**
     * Send a simple HTML email via SMTP.
     */
    public static function sendEmail(string $to, string $subject, string $htmlBody): bool
    {
        if (empty($to)) return false;

        $smtpUser = env('email.SMTPUser', '');
        $smtpPass = env('email.SMTPPass', '');

        if (empty($smtpUser) || empty($smtpPass)) {
            log_message('error', 'Email send failed: SMTP credentials not set in .env');
            return false;
        }

        try {
            $email = \Config\Services::email();
            $email->initialize([
                'protocol'    => 'smtp',
                'SMTPHost'    => env('email.SMTPHost', 'smtp.gmail.com'),
                'SMTPUser'    => $smtpUser,
                'SMTPPass'    => $smtpPass,
                'SMTPPort'    => (int) env('email.SMTPPort', 587),
                'SMTPCrypto'  => env('email.SMTPCrypto', 'tls'),
                'SMTPTimeout' => 10,
                'mailType'    => 'html',
                'charset'     => 'utf-8',
            ]);

            $email->setFrom($smtpUser, 'SVBSM Wellness Team');
            $email->setTo($to);
            $email->setSubject($subject);
            $email->setMessage($htmlBody);

            if (!$email->send(false)) {
                log_message('error', 'Email send failed: ' . $email->printDebugger(['headers', 'subject']));
                return false;
            }

            log_message('info', "Email sent to {$to}: {$subject}");
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Email exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Decrypt a hex-encoded encrypted field, with fallback for legacy plaintext.
     */
    public static function decryptField(string $value): string
    {
        if (!empty($value) && ctype_xdigit($value) && strlen($value) > 32) {
            try {
                return \Config\Services::encrypter()->decrypt(hex2bin($value));
            } catch (\Exception $e) {
                // Legacy plaintext that happens to look like hex — return as-is
            }
        }
        return $value;
    }
}
