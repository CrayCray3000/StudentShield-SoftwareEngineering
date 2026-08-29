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
            $cookieData = \App\Libraries\CookieSigner::verify($_COOKIE['admin_trusted_device']);
            if ($cookieData !== null && strpos($cookieData, ':') !== false) {
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
        if (strtolower($this->request->getMethod()) === 'get') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $name = trim((string) $this->request->getPost('name'));
        $password = (string) $this->request->getPost('password');

        if (empty($name) || empty($password)) {
            return redirect()->back()->with('error', 'Please fill in all fields.');
        }

        // Find student by name (role = 0)
        $user = $db->table('users')->where(['name' => $name, 'role' => 0])->get()->getRowArray();

        $pwHash = $user['password_hash'] ?? $user['passcode'] ?? '';
        if ($user && password_verify($password, $pwHash)) {
            
            // Approval-Based Registration Check
            if (isset($user['is_active']) && $user['is_active'] == 0) {
                return redirect()->back()->with('error', 'Your account is still pending approval by the guidance office.');
            }

            // Remember Me Feature
            if ($this->request->getPost('remember') === 'on') {
                $token = bin2hex(random_bytes(32));
                $db->table('users')->where('id', $user['id'])->update([
                    'remember_token' => password_hash($token, PASSWORD_BCRYPT)
                ]);
                $cookieValue = \App\Libraries\CookieSigner::sign($user['id'] . ':' . $token);
                setcookie('student_remember', $cookieValue, [
                    'expires' => time() + (86400 * 30),
                    'path' => '/',
                    'secure' => $this->shouldUseSecureCookie(),
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
            $this->logAudit('USER_LOGIN', 'system', $user['id']);
            return redirect()->to('/student/dashboard');
        }
        return redirect()->back()->with('error', 'Invalid Student credentials.');
    }

    /**
     * Counselors (role=1) & Super Admins (role=2) login with name + password.
     */
    public function authenticateAdmin()
    {
        if (strtolower($this->request->getMethod()) === 'get') {
            return redirect()->to('/admin/login');
        }

        $db = \Config\Database::connect();
        $name = trim((string) $this->request->getPost('name'));
        $password = (string) $this->request->getPost('password');

        if (empty($name) || empty($password)) {
            return redirect()->back()->with('error', 'Please fill in all fields.');
        }

        // Find user by name AND must be counselor (role=1) or super_admin (role=2)
        $user = $db->table('users')
            ->select('users.*, COALESCE(counselors.counselor_email, admins.email) AS parent_email, COALESCE(counselors.counselor_phone, admins.phone) AS parent_phone')
            ->join('counselors', 'counselors.user_id = users.id', 'left')
            ->join('admins', 'admins.user_id = users.id', 'left')
            ->where('users.name', $name)
            ->whereIn('users.role', [1, 2])
            ->get()->getRowArray();

        if (!$user) {
            return redirect()->back()->with('error', 'Invalid credentials.');
        }

        // Authenticate with bcrypt only — plaintext fallback removed for security
        $pwHash = $user['password_hash'] ?? $user['passcode'] ?? '';
        $passwordValid = password_verify($password, $pwHash);

        if ($passwordValid) {
            $roleNames = [1 => 'counselor', 2 => 'super_admin'];

            // ── LAYER 5: TRUSTED DEVICE BYPASS ──
            if (isset($_COOKIE['admin_trusted_device'])) {
                $cookieData = \App\Libraries\CookieSigner::verify($_COOKIE['admin_trusted_device']);
                if ($cookieData !== null && strpos($cookieData, ':') !== false) {
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
                        $this->logAudit('USER_LOGIN', 'system', $user['id']);
                        return redirect()->to('/admin/dashboard');
                    }
                }
            }
            // ── LAYER 6: TWO-FACTOR AUTHENTICATION (OTP) ──
            $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            log_message('critical', "OTP is: " . $otp);
            
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
                $cookieValue = \App\Libraries\CookieSigner::sign(session()->get('temp_user_id') . ':' . $token);
                setcookie('admin_trusted_device', $cookieValue, [
                    'expires' => time() + (86400 * 30),
                    'path' => '/',
                    'secure' => $this->shouldUseSecureCookie(),
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);
            }

            // Read temp values BEFORE regenerate() to prevent data loss
            $userId   = session()->get('temp_user_id');
            $name     = session()->get('temp_name');
            $role     = session()->get('temp_role');
            $roleName = session()->get('temp_role_name');

            session()->regenerate();
            session()->set([
                'user_id'    => $userId,
                'name'       => $name,
                'role'       => $role,
                'role_name'  => $roleName,
                'isLoggedIn' => true,
            ]);

            $this->logAudit('USER_LOGIN', 'system', $userId);

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
        
        if (session()->get('user_id')) {
            $this->logAudit('USER_LOGOUT', 'system', session()->get('user_id'));
        }
        
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

    public function studentRegisterView()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/student/dashboard');
        }
        helper(['form', 'url']);
        return view('register');
    }

    public function processStudentRegistration()
    {
        helper(['form', 'url']);
        $db = \Config\Database::connect();
        $db->transStart();

        $name = trim((string) $this->request->getPost('name'));
        $gradeLevel = trim((string) $this->request->getPost('grade_level'));
        $section = trim((string) $this->request->getPost('section'));
        $parentContact = trim((string) $this->request->getPost('parent_contact'));
        $password = (string) $this->request->getPost('password');
        $confirmPassword = (string) $this->request->getPost('password_confirm');

        // Auto-generate a unique system student ID: SS-YYYYMMDD-XXXX
        $studentId = 'SS-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        // Detect if parentContact is email or phone
        $parentPhone = filter_var($parentContact, FILTER_VALIDATE_EMAIL) ? null : $parentContact;
        $parentEmail = filter_var($parentContact, FILTER_VALIDATE_EMAIL) ? $parentContact : null;

        $terms = $this->request->getPost('terms');

        if (empty($name) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Name and Password are required.');
        }

        if (!$terms) {
            return redirect()->back()->withInput()->with('error', 'You must agree to the Terms and Conditions.');
        }

        if ($password !== $confirmPassword) {
            return redirect()->back()->withInput()->with('error', 'Passwords do not match.');
        }

        if (strlen($password) < 8) {
            return redirect()->back()->withInput()->with('error', 'Password must be at least 8 characters long.');
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return redirect()->back()->withInput()->with('error', 'Password must contain at least one uppercase letter.');
        }

        if (!preg_match('/[a-z]/', $password)) {
            return redirect()->back()->withInput()->with('error', 'Password must contain at least one lowercase letter.');
        }

        if (!preg_match('/[0-9]/', $password)) {
            return redirect()->back()->withInput()->with('error', 'Password must contain at least one number.');
        }

        if (!preg_match('/[\W_]/', $password)) {
            return redirect()->back()->withInput()->with('error', 'Password must contain at least one special character (e.g., @, #, $, etc.).');
        }

        $existingUser = $db->table('users')->where('name', $name)->get()->getRow();
        if ($existingUser) {
            return redirect()->back()->withInput()->with('error', 'That name is already registered.');
        }

        // Insert into users table
        $db->table('users')->insert([
            'name' => $name,
            'role' => 0, // Student
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'is_active' => 0 // Pending approval
        ]);

        $userId = $db->insertID();

        // Insert into students table
        $db->table('students')->insert([
            'user_id' => $userId,
            'student_id' => $studentId,
            'grade_level' => $gradeLevel,
            'section' => $section,
            'parent_phone' => $parentPhone,
            'parent_email' => $parentEmail,
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
        }

        $this->logAudit('USER_REGISTERED', 'system', $userId);

        return redirect()->to('/login')->with('success', 'Registration successful! Your account is pending approval by the guidance office.');
    }

    private function shouldUseSecureCookie(): bool
    {
        return $this->request->isSecure()
            || strtolower((string) $this->request->getServer('HTTP_X_FORWARDED_PROTO')) === 'https';
    }
}
