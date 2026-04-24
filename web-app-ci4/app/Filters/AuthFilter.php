<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            // First check if they have a remember-me cookie for students
            if (isset($_COOKIE['student_remember'])) {
                $cookieData = base64_decode($_COOKIE['student_remember']);
                if (strpos($cookieData, ':') !== false) {
                    list($cookieUserId, $cookieToken) = explode(':', $cookieData, 2);
                    $db = \Config\Database::connect();
                    $user = $db->table('users')->where('id', $cookieUserId)->where('role', 0)->get()->getRowArray();
                    
                    if ($user && password_verify($cookieToken, $user['remember_token'])) {
                        // Restore Session
                        session()->regenerate();
                        session()->set([
                            'user_id'    => $user['id'],
                            'name'       => $user['name'],
                            'role'       => (int) $user['role'],
                            'role_name'  => 'student',
                            'isLoggedIn' => true,
                        ]);
                        // Only auto-redirect if they were trying to access /login directly
                        $uriStr = $request->getUri()->getPath();
                        if ($uriStr === 'login' || $uriStr === '/' || $uriStr === '') {
                             return redirect()->to('/student/vent');
                        }
                        return; // Let them continue to wherever they were going!
                    }
                }
            }

            if (isset($_COOKIE['admin_trusted_device'])) {
                $cookieData = base64_decode($_COOKIE['admin_trusted_device']);
                if (strpos($cookieData, ':') !== false) {
                    list($cookieUserId, $cookieToken) = explode(':', $cookieData, 2);
                    $db = \Config\Database::connect();
                    $user = $db->table('users')->where('id', $cookieUserId)->whereIn('role', [1, 2])->get()->getRowArray();
                    
                    if ($user && password_verify($cookieToken, $user['remember_token'])) {
                        // Restore Session
                        $roleNames = [1 => 'counselor', 2 => 'super_admin'];
                        session()->regenerate();
                        session()->set([
                            'user_id'    => $user['id'],
                            'name'       => $user['name'],
                            'role'       => (int) $user['role'],
                            'role_name'  => $roleNames[(int)$user['role']] ?? 'counselor',
                            'isLoggedIn' => true,
                        ]);
                        $uriStr = $request->getUri()->getPath();
                        if ($uriStr === 'admin/login' || $uriStr === 'admin/otp' || $uriStr === '/' || $uriStr === '') {
                             return redirect()->to('/admin/dashboard');
                        }
                        return; // Let them continue
                    }
                }
            }

            $uriStr = $request->getUri()->getPath();
            if (strpos($uriStr, 'admin') !== false || strpos($uriStr, 'counselor') !== false) {
                return redirect()->to('/admin/login')->with('error', 'Please log in as Staff to access the dashboard.');
            }
            // Exclude the login page itself to prevent infinite redirects
            if ($uriStr !== 'login' && $uriStr !== '/' && $uriStr !== '') {
                return redirect()->to('/login')->with('error', 'Please log in to access StudentShield.');
            }
        }

        $role = session()->get('role');
        $uriStr = $request->getUri()->getPath();

        // Prevent Students from accessing Counselor Dashboards
        if ($role == 0 && strpos($uriStr, 'counselor') !== false) {
            return redirect()->to('/student/vent')->with('error', 'Unauthorized access. You are a student.');
        }

        // Prevent Counselors from accessing Student Vents (Counselors should review via dashboard)
        if ($role == 1 && strpos($uriStr, 'student') !== false) {
            return redirect()->to('/admin/dashboard')->with('error', 'Staff must use the Dashboard portal.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    // Do nothing
    }
}
