<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;

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

            // Only auto-login from admin cookie when navigating to admin routes.
            // This prevents the admin session from hijacking the student portal.
            $uriPath = $request->getUri()->getPath();
            $isAdminRoute = (strpos($uriPath, 'admin') !== false || strpos($uriPath, 'counselor') !== false);

            if ($isAdminRoute && isset($_COOKIE['admin_trusted_device'])) {
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
                        if ($uriPath === 'admin/login' || $uriPath === 'admin/otp') {
                             return redirect()->to('/admin/dashboard');
                        }
                        return; // Let them continue to their admin destination
                    }
                }
            }

            $uriStr = $request->getUri()->getPath();

            // For AJAX/fetch requests, return JSON instead of an HTML redirect
            // This prevents silent failures where fetch().then(r=>r.json()) throws a SyntaxError
            if ($request instanceof IncomingRequest && $request->isAJAX()) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON(['status' => 'error', 'message' => 'Session expired. Please refresh the page and log in again.']);
            }

            if (strpos($uriStr, 'admin') !== false || strpos($uriStr, 'counselor') !== false) {
                return redirect()->to('/admin/login')->with('error', 'Please log in as Staff to access the dashboard.');
            }
            // Exclude the login page itself to prevent infinite redirects
            if ($uriStr !== 'login' && $uriStr !== '/' && $uriStr !== '') {
                return redirect()->to('/login')->with('error', 'Please log in to access StudentShield.');
            }
        }

        // ── SERVER-ENFORCED IDLE TIMEOUT (15 MINUTES = 900 SECONDS) ──
        $lastActivity = session()->get('last_activity');
        $idleTimeout = 900; // 15 minutes inactivity limit for shared school computers

        if ($lastActivity && (time() - (int)$lastActivity) > $idleTimeout) {
            $userRole = (int) session()->get('role');
            $userId   = session()->get('user_id');

            // Audit log timeout event
            if ($userId) {
                try {
                    $db = \Config\Database::connect();
                    if ($db->tableExists('audit_logs')) {
                        $db->table('audit_logs')->insert([
                            'user_id'     => $userId,
                            'action'      => 'SESSION_IDLE_TIMEOUT',
                            'entity_type' => 'system',
                            'entity_id'   => $userId,
                            'details'     => 'Session terminated due to 15-minute inactivity.',
                            'created_at'  => date('Y-m-d H:i:s')
                        ]);
                    }
                } catch (\Throwable $e) {}
            }

            session()->destroy();

            if ($request instanceof IncomingRequest && $request->isAJAX()) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON(['status' => 'error', 'message' => 'Session expired due to inactivity. Please log in again.']);
            }

            $redirectUrl = ($userRole === 1 || $userRole === 2) ? '/admin/login' : '/login';
            return redirect()->to($redirectUrl)->with('error', 'Session expired due to inactivity. Please log in again.');
        }

        // Keep last_activity current on valid user activity
        session()->set('last_activity', time());

        $role = session()->get('role');
        $uriStr = $request->getUri()->getPath();

        // Prevent Students from accessing Admin/Counselor Dashboards
        $trimmedUri = ltrim($uriStr, '/');
        if ($role == 0 && (str_starts_with($trimmedUri, 'admin') || str_starts_with($trimmedUri, 'counselor'))) {
            return redirect()->to('/student/vent')->with('error', 'Unauthorized access. You are a student.');
        }

        // Prevent Counselors from accessing Student Portal pages (student/vent, student/dashboard)
        if ($role == 1 && str_starts_with($trimmedUri, 'student/')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Staff must use the Dashboard portal.');
        }

        // Prevent Counselors from accessing Super Admin Management functions
        if ($role == 1 && (str_starts_with($trimmedUri, 'admin/users') || str_starts_with($trimmedUri, 'admin/audit-logs'))) {
            return redirect()->to('/admin/dashboard')->with('error', 'Unauthorized. Super Admin access required.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    // Do nothing
    }
}
