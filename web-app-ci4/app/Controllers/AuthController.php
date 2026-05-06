<?php

namespace App\Controllers;


class AuthController extends BaseController
{
    public function studentLoginView()
    {
        if (session()->get('isLoggedIn')) {
            $role = (int) session()->get('role');
            // Only auto-redirect students who are already logged in.
            // If an admin/counselor navigates here, let them see the student
            // login page so they can switch accounts on the same device.
            if ($role === 0) return redirect()->to('/student/dashboard');
        }
        return view('login');
    }

    public function adminLoginView()
    {
        if (session()->get('isLoggedIn')) {
            $role = (int) session()->get('role');
            if ($role === 1 || $role === 2) {
                return redirect()->to('/admin/dashboard');
            } else {
                session()->destroy();
                return redirect()->to('/admin/login');
            }
        }

        // ── AUTO-LOGIN VIA TRUSTED DEVICE COOKIE ──
        if (isset($_COOKIE['admin_trusted_device'])) {
            $cookieData = base64_decode($_COOKIE['admin_trusted_device']);
            if (strpos($cookieData, ':') !== false) {
                list($cookieUserId, $cookieToken) = explode(':', $cookieData, 2);
                $db = \Config\Database::connect();
                $user = $db->table('users')->where('id', $cookieUserId)->whereIn('role', [1, 2])->get()->getRowArray();
                
                if ($user && password_verify($cookieToken, $user['remember_token'])) {
                    $roleNames = [1 => 'counselor', 2 => 'super_admin'];
                    session()->regenerate();
                    session()->set([
                        'user_id'    => $user['id'],
                        'name'       => $user['name'],
                        'role'       => (int) $user['role'],
                        'role_name'  => $roleNames[(int)$user['role']] ?? 'counselor',
                        'isLoggedIn' => true,
                    ]);
                    return redirect()->to('/admin/dashboard');
                }
            }
        }

        return view('admin_login');
    }

    /**
     * Students login with name + password (kept as-is).
     */
    public function authenticateStudent()
    {
        $db = \Config\Database::connect();
        $name = trim($this->request->getPost('name'));
        $password = $this->request->getPost('password');

        if (empty($name) || empty($password)) {
            return redirect()->back()->with('error', 'Please fill in all fields.');
        }

        // Find student by name (role = 0)
        $user = $db->table('users')->where(['name' => $name, 'role' => 0])->get()->getRowArray();

        if ($user && ($user['passcode'] === $password || password_verify($password, $user['passcode']))) {
            // Auto-upgrade plaintext password to hash
            if ($user['passcode'] === $password && !password_verify($password, $user['passcode'])) {
                $db->table('users')->where('id', $user['id'])->update([
                    'passcode' => password_hash($password, PASSWORD_BCRYPT)
                ]);
            }

            // Remember Me Feature
            if ($this->request->getPost('remember') === 'on') {
                $token = bin2hex(random_bytes(32));
                $db->table('users')->where('id', $user['id'])->update([
                    'remember_token' => password_hash($token, PASSWORD_BCRYPT)
                ]);
                $cookieValue = base64_encode($user['id'] . ':' . $token);
                setcookie('student_remember', $cookieValue, [
                    'expires' => time() + (86400 * 30),
                    'path' => '/',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);
            }

            session()->regenerate();
            session()->set([
                'user_id'    => $user['id'],
                'name'       => $user['name'],
                'role'       => (int) $user['role'],
                'role_name'  => 'student',
                'isLoggedIn' => true,
            ]);
            return redirect()->to('/student/dashboard');
        }
        return redirect()->back()->with('error', 'Invalid Student credentials.');
    }

    /**
     * Counselors (role=1) & Super Admins (role=2) login with name + password.
     */
    public function authenticateAdmin()
    {
        $db = \Config\Database::connect();
        $name = trim($this->request->getPost('name'));
        $password = $this->request->getPost('password');

        if (empty($name) || empty($password)) {
            return redirect()->back()->with('error', 'Please fill in all fields.');
        }

        // Find user by name AND must be counselor (role=1) or super_admin (role=2)
        $user = $db->table('users')
            ->where('name', $name)
            ->whereIn('role', [1, 2])
            ->get()->getRowArray();

        if (!$user) {
            return redirect()->back()->with('error', 'Invalid credentials.');
        }

        // Check password — support both legacy plaintext and bcrypt hashed
        $passwordValid = false;
        if (password_verify($password, $user['passcode'])) {
            $passwordValid = true;
        } elseif ($user['passcode'] === $password) {
            // Legacy plaintext — auto-upgrade to bcrypt
            $passwordValid = true;
            $db->table('users')->where('id', $user['id'])->update([
                'passcode' => password_hash($password, PASSWORD_BCRYPT)
            ]);
        }

        if ($passwordValid) {
            $roleNames = [1 => 'counselor', 2 => 'super_admin'];

            // ── LAYER 5: TRUSTED DEVICE BYPASS ──
            if (isset($_COOKIE['admin_trusted_device'])) {
                $cookieData = base64_decode($_COOKIE['admin_trusted_device']);
                if (strpos($cookieData, ':') !== false) {
                    list($cookieUserId, $cookieToken) = explode(':', $cookieData, 2);
                    if ((int)$cookieUserId === (int)$user['id'] && password_verify($cookieToken, $user['remember_token'])) {
                        // Bypass OTP
                        session()->regenerate();
                        session()->set([
                            'user_id'    => $user['id'],
                            'name'       => $user['name'],
                            'role'       => (int) $user['role'],
                            'role_name'  => $roleNames[(int)$user['role']] ?? 'counselor',
                            'isLoggedIn' => true,
                        ]);
                        return redirect()->to('/admin/dashboard');
                    }
                }
            }

            // ── LAYER 6: TWO-FACTOR AUTHENTICATION (OTP) ──
            $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Suspend actual login, save temporary session data
            session()->set([
                'temp_user_id'   => $user['id'],
                'temp_name'      => $user['name'],
                'temp_role'      => (int) $user['role'],
                'temp_role_name' => $roleNames[(int)$user['role']] ?? 'counselor',
                'otp'            => $otp,
                'otp_expires'    => time() + 300 // 5 minutes strict expiration
            ]);

            // Attempt to send the OTP via Email to the Counselor's registered email (parent_email column)
            $counselorEmail = $user['parent_email'] ?? '';
            if (!empty($counselorEmail)) {
                try {
                    $email = \Config\Services::email();
                    $smtpUser = env('email.SMTPUser', '');
                    $smtpPass = env('email.SMTPPass', '');

                    if (!empty($smtpUser) && !empty($smtpPass)) {
                        $email->initialize([
                            'protocol' => 'smtp',
                            'SMTPHost' => env('email.SMTPHost', 'smtp.gmail.com'),
                            'SMTPUser' => $smtpUser,
                            'SMTPPass' => $smtpPass,
                            'SMTPPort' => (int)env('email.SMTPPort', 587),
                            'SMTPCrypto' => env('email.SMTPCrypto', 'tls'),
                            'SMTPTimeout' => 10,
                            'mailType' => 'html',
                            'charset' => 'utf-8',
                        ]);
                        $email->setFrom($smtpUser, 'SVBSM Security');
                        $email->setTo($counselorEmail);
                        $email->setSubject('Your 6-Digit Admin Verification Code');
                        
                        $html = "<div style='font-family: Arial; text-align: center; padding: 20px;'>
                                 <h2>Admin Portal Verification</h2>
                                 <p>Your one-time passcode is:</p>
                                 <h1 style='letter-spacing: 5px; color: #2A4365; border: 2px solid #E2E8F0; display: inline-block; padding: 10px 20px; border-radius: 8px;'>{$otp}</h1>
                                 <p>This code expires in exactly 5 minutes.</p>
                                 </div>";
                        $email->setMessage($html);
                        $email->send(false);
                    }
                } catch (\Exception $e) {
                    log_message('error', 'OTP Email Failed: ' . $e->getMessage());
                }
            } else {
                // If the counselor has no email in the DB, we output it to Laragon's log so testing is possible
                log_message('critical', "OTP generated for {$user['name']} but they have no email saved! OTP is: {$otp}");
            }

            // Redirect to the new OTP gateway
            return redirect()->to('/admin/otp');
        }

        return redirect()->back()->with('error', 'Invalid credentials.');
    }

    public function otpView()
    {
        // Guard: Must have passed the first step of authentication
        if (!session()->has('otp')) {
            return redirect()->to('/admin/login');
        }
        return view('admin_otp');
    }

    public function verifyOtp()
    {
        if (!session()->has('otp')) {
            return redirect()->to('/admin/login');
        }

        $inputOtp = trim((string)$this->request->getPost('otp'));
        $storedOtp = session()->get('otp');
        $expiresAt = session()->get('otp_expires');

        // Security: OTP brute-force protection (max 5 attempts)
        $otpAttempts = session()->get('otp_attempts') ?? 0;
        if ($otpAttempts >= 5) {
            session()->remove(['otp', 'otp_expires', 'otp_attempts', 'temp_user_id', 'temp_name', 'temp_role', 'temp_role_name']);
            return redirect()->to('/admin/login')->with('error', 'Too many failed verification attempts. Please log in again.');
        }

        if (time() > $expiresAt) {
            session()->remove(['otp', 'otp_expires', 'otp_attempts', 'temp_user_id', 'temp_name', 'temp_role', 'temp_role_name']);
            return redirect()->to('/admin/login')->with('error', 'Security code has expired. Please log in again.');
        }

        if ($inputOtp === $storedOtp) {
            // Official Authentication Authorization!
            
            // Check Trust Device
            if ($this->request->getPost('trust_device') == '1') {
                $token = bin2hex(random_bytes(32));
                $db = \Config\Database::connect();
                $db->table('users')->where('id', session()->get('temp_user_id'))->update([
                    'remember_token' => password_hash($token, PASSWORD_BCRYPT)
                ]);
                $cookieValue = base64_encode(session()->get('temp_user_id') . ':' . $token);
                setcookie('admin_trusted_device', $cookieValue, [
                    'expires' => time() + (86400 * 30),
                    'path' => '/',
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);
            }

            session()->regenerate();
            session()->set([
                'user_id'    => session()->get('temp_user_id'),
                'name'       => session()->get('temp_name'),
                'role'       => session()->get('temp_role'),
                'role_name'  => session()->get('temp_role_name'),
                'isLoggedIn' => true,
            ]);

            // Clear temporary OTP tokens
            session()->remove(['otp', 'otp_expires', 'otp_attempts', 'temp_user_id', 'temp_name', 'temp_role', 'temp_role_name']);

            return redirect()->to('/admin/dashboard');
        }

        session()->set('otp_attempts', $otpAttempts + 1);
        return redirect()->back()->with('error', 'Invalid verification code.');
    }

    public function logout()
    {
        $role = session()->get('role');
        
        // Remove DB token and cookies
        if (session()->get('user_id')) {
            $db = \Config\Database::connect();
            $db->table('users')->where('id', session()->get('user_id'))->update(['remember_token' => null]);
        }
        setcookie('student_remember', '', time() - 3600, '/');
        setcookie('admin_trusted_device', '', time() - 3600, '/');
        
        session()->destroy();
        
        if ($role === 1 || $role === 2) {
            return redirect()->to('/admin/login');
        }
        return redirect()->to('/login');
    }
}
