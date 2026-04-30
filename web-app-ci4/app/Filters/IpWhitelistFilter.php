<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class IpWhitelistFilter implements FilterInterface
{
    /**
     * The IP addresses allowed to access the admin dashboard
     * You can add multiple IPs to this array.
     */
    protected $allowedIps = [
        '136.158.58.234',  // User's public IP
        '112.209.179.217', // Whitelisted IP
        '112.201.103.171', // Whitelisted IP
        '113.19.143.148', // My love's whitelisted IP
        '127.0.0.1',       // Localhost (Laragon/XAMPP)
        '::1',             // Localhost IPv6
    ];

    public function before(RequestInterface $request, $arguments = null)
    {
        $clientIp = $request->getIPAddress();

        // If the client's IP is not in the allowed list, deny access completely
        if (!in_array($clientIp, $this->allowedIps, true)) {
            $response = service('response');
            $response->setStatusCode(403);
            $response->setBody('<h1>403 Forbidden</h1><p>Your IP Address (' . htmlspecialchars($clientIp) . ') is not authorized to access this Administrative Panel.</p>');
            return $response;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
    }
}
