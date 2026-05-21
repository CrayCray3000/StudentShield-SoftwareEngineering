<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * Rate Limit Filter — prevents brute-force login attacks.
 * Allows max 5 login attempts per IP within 15 minutes.
 * Uses file-based tracking (no extra DB table needed).
 */
class RateLimitFilter implements FilterInterface
{
    private const MAX_ATTEMPTS = 10;
    private const LOCKOUT_SECONDS = 300; // 5 minutes
    private const STORAGE_DIR = WRITEPATH . 'rate_limits/';

    public function before(RequestInterface $request, $arguments = null)
    {
        // Only rate limit POST requests (actual credential submissions)
        if (strtolower($request->getMethod()) !== 'post') {
            return;
        }

        $ip = $request->getIPAddress();
        $key = md5($ip);
        $file = self::STORAGE_DIR . $key . '.json';

        // Ensure directory exists
        if (!is_dir(self::STORAGE_DIR)) {
            mkdir(self::STORAGE_DIR, 0755, true);
        }

        // Read existing attempts
        $data = ['attempts' => 0, 'first_attempt' => time()];
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true) ?: $data;

            // Reset if lockout window has passed
            if ((time() - $data['first_attempt']) > self::LOCKOUT_SECONDS) {
                $data = ['attempts' => 0, 'first_attempt' => time()];
            }
        }

        // Check if locked out
        if ($data['attempts'] >= self::MAX_ATTEMPTS) {
            $remaining = self::LOCKOUT_SECONDS - (time() - $data['first_attempt']);
            $minutes = ceil($remaining / 60);
            
            // Safe redirect back with fallback if no Referer header exists (prevents RouterException)
            $redirect = redirect();
            if ($request->getServer('HTTP_REFERER')) {
                return $redirect->back()->with('error', "Too many login attempts. Please try again in {$minutes} minutes.");
            }
            
            $uriPath = $request->getUri()->getPath();
            $target = (strpos($uriPath, 'admin') !== false) ? '/admin/login' : '/login';
            return $redirect->to($target)->with('error', "Too many login attempts. Please try again in {$minutes} minutes.");
        }

        // Increment attempt count (will be called on POST to login endpoints)
        $data['attempts']++;
        file_put_contents($file, json_encode($data), LOCK_EX);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // On successful login, clear the rate limit
        if (session()->get('isLoggedIn')) {
            $ip = $request->getIPAddress();
            $file = self::STORAGE_DIR . md5($ip) . '.json';
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}
