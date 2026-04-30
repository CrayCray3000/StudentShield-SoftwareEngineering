<?php

namespace App\Controllers;

class VentController extends BaseController
{
    public function index()
    {
        return view('vent_form');
    }

    public function submit()
    {
        $text = $this->request->getPost('vent_text');
        $userId = session()->get('user_id') ?? 1;

        // Security: Anti-Spam Rate Limiting (1 vent per 30 seconds)
        $lastSubmit = session()->get('last_vent_time');
        if ($lastSubmit && time() - $lastSubmit < 30) {
            $waitTime = 30 - (time() - $lastSubmit);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => "Security Cooldown: Please wait {$waitTime} seconds before submitting again."]);
            }
            return redirect()->back()->with('error', "Security Cooldown: Please wait {$waitTime} seconds before submitting again.");
        }
        session()->set('last_vent_time', time());

        if (empty($text)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Please enter text.']);
            }
            return redirect()->back()->with('error', 'Please enter some text before submitting.');
        }

        // --- 1. CALL ML SERVICE FOR RISK ANALYSIS ---
        $ch = curl_init('https://ashlogg-student-shield-official.hf.space/analyze-text');
        $payload = json_encode(['raw_text' => $text, 'student_id' => (string)$userId]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-API-KEY: ' . env('ML_API_KEY', 'svbsm-production-ml-key-v1')
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $mlResult = json_decode($response, true);
        $sentimentScore = 0.0;
        $riskLevel = 'LOW';

        if ($mlResult && isset($mlResult['risk_level'])) {
            $sentimentScore = isset($mlResult['risk_score']) ? $mlResult['risk_score'] : (isset($mlResult['risk_percent']) ? $mlResult['risk_percent'] : 0.0);
            $riskLevel = $mlResult['risk_level'];
        }
        else {
            log_message('error', 'ML Service Error (HTTP ' . $httpCode . '): ' . ($curlError ?: 'Invalid response: ' . $response));
        }

        // --- LOCAL KEYWORD FALLBACK (SAFETY NET OVERRIDE) ---
        // Always run the local heuristic to catch severe threats (like Taglish slang) the ML might miss or under-score
        $localResult = $this->localKeywordFallback($text);
        
        // Severity mapping for comparison
        $severityLevels = ['LOW' => 1, 'MEDIUM' => 2, 'HIGH' => 3, 'CRITICAL' => 4];
        
        $currentSeverity = $severityLevels[$riskLevel] ?? 1;
        $localSeverity = $severityLevels[$localResult['risk_level']] ?? 1;
        
        // Override if local heuristic detects a strictly higher risk level
        if ($localSeverity > $currentSeverity) {
            log_message('info', "Safety Net Override: Risk upgraded from {$riskLevel} to {$localResult['risk_level']} based on local keywords.");
            $riskLevel = $localResult['risk_level'];
            // Adopt the higher score to reflect the increased risk severity
            $sentimentScore = max($sentimentScore, $localResult['risk_percent']);
        }

        // --- 2. DATA PRIVACY SECURE ENCRYPTION ---
        $encrypter = \Config\Services::encrypter();
        $encryptedText = bin2hex($encrypter->encrypt($text));

        // --- 3. SAVE TO DATABASE ---
        $db = \Config\Database::connect();
        $emotions = $this->request->getPost('emotions') ?? '';

        date_default_timezone_set('Asia/Manila');
        $db->table('vent_logs')->insert([
            'user_id' => $userId,
            'raw_text' => $encryptedText,
            'emotions' => $emotions,
            'risk_percent' => $sentimentScore,
            'risk_level' => $riskLevel,
            'status' => 'Pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $hideFromParents = $this->request->getPost('hide_from_parents') === '1';

        // --- 4. TRIGGER ALERTS IF CRITICAL ---
        if ($riskLevel === 'CRITICAL') {
            $user = $db->table('users')->where('id', $userId)->get()->getRow();
            if ($user) {
                $fullNameStr = $user->name ?? 'your child';
                $firstName = explode(' ', trim($fullNameStr))[0];

                if ($hideFromParents) {
                    // DOMESTIC ABUSE PRECAUTION: Do not notify parents! Over-notify the counselor instead!
                    $studentName = $user->name ?? 'A student';
                    $this->triggerCounselorEmergencyAlert("RESTRICTED PROTOCOL: $studentName filed a CRITICAL vent and explicitly requested their parents NOT be notified. Review dashboard immediately.", "Counselor Emergency (Safety Override)");
                }
                else {
                    if (!empty($user->parent_phone) && !$this->isAlertOnCooldown($user->parent_phone)) {
                        $this->triggerSmsAlert($user->parent_phone, $firstName);
                    }
                    if (!empty($user->parent_email) && !$this->isAlertOnCooldown($user->parent_email)) {
                        $this->triggerEmailAlert($user->parent_email, $firstName);
                    }
                }
            }
        }

        // --- 5. RETURN RESPONSE ---
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Vent securely submitted.',
                'risk_level' => $riskLevel
            ]);
        }

        return redirect()->to('/student/vent')->with('message', 'Vent securely submitted.');
    }

    // --- 3. ALERT FATIGUE COOLDOWN (RATE LIMITING) ---
    private function isAlertOnCooldown($recipient)
    {
        $file = WRITEPATH . 'logs/alerts.json';
        if (!file_exists($file))
            return false;

        $alerts = json_decode(file_get_contents($file), true);
        if (!is_array($alerts))
            return false;

        // 1-minute cooldown for TESTING purposes (originally 1 hour)
        $oneHourAgo = strtotime('-1 minute');

        foreach ($alerts as $alert) {
            if ($alert['recipient'] === $recipient) {
                if (strtotime($alert['timestamp']) > $oneHourAgo) {
                    return true;
                }
            }
        }
        return false;
    }

    private function triggerSmsAlert($parentPhone, $studentName = 'your child')
    {
        $message = "StudentShield: We noticed your child shared some heavy feelings on our portal today. Please offer a warm check-in tonight. SVBSM Counselors are always here.";
        $this->logAlert('SMS (Local Phone)', $parentPhone, $message);

        // Normalize phone number to 09XXXXXXXXX since that's what the local app expects
        $cleanPhone = ltrim($parentPhone, '+');
        if (str_starts_with($cleanPhone, '63') && strlen($cleanPhone) == 12) {
            $cleanPhone = '0' . substr($cleanPhone, 2);
        }

        $payload = json_encode([
            'phone' => $cleanPhone,
            'message' => $message
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => env('sms.GatewayUrl', "http://192.168.1.3:8080/send-sms"),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30, // Keep timeout reasonably long because sending via real SIM can take a few seconds
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            log_message('error', "Local Gateway cURL Error #: " . $err);
        }
        else {
            log_message('info', "Local Gateway Response for {$cleanPhone}: " . $response);
        }
    }

    private function triggerEmailAlert($parentEmail, $studentName = 'your child')
    {
        $subject = 'StudentShield: Support Alert for ' . ucfirst($studentName);

        $htmlMessage = "
        <div style=\"background-color: #f7fafc; padding: 40px 20px; font-family: 'Inter', Helvetica, Arial, sans-serif;\">
            <div style=\"max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;\">
                
                <!-- Header -->
                <div style=\"background: linear-gradient(135deg, #2F855A 0%, #38A169 100%); padding: 30px; text-align: center;\">
                    <img src=\"" . base_url('images/logo.png') . "\" alt=\"SVBSM Logo\" style=\"width: 95px; height: auto; margin-bottom: 12px; display: inline-block; background-color: transparent;\">
                    <h1 style=\"color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; letter-spacing: 0.5px;\">StudentShield Portal</h1>
                    <p style=\"color: #c6f6d5; margin: 8px 0 0 0; font-size: 15px;\">Wellness Support Update</p>
                </div>

                <!-- Body -->
                <div style=\"padding: 35px 30px; color: #4a5568;\">
                    <p style=\"font-size: 16px; margin-top: 0;\">Dear Parent/Guardian,</p>
                    
                    <div style=\"background-color: #ebf8f6; border-left: 4px solid #319795; padding: 15px 20px; margin: 25px 0; border-radius: 0 8px 8px 0;\">
                        <p style=\"margin: 0; font-size: 16px; line-height: 1.6; color: #234e52;\">
                            Our intelligent wellness portal recently reviewed a submission from <strong>$studentName</strong> and noted some heavy emotions being processed today.
                        </p>
                    </div>

                    <p style=\"font-size: 16px; line-height: 1.6;\">This is a completely normal part of student life. We are sending you this proactive courtesy update because we believe in partnering with parents to ensure every student feels supported.</p>
                    
                    <h3 style=\"color: #2d3748; font-size: 18px; margin: 30px 0 15px 0; border-bottom: 2px solid #edf2f7; padding-bottom: 8px;\"> How you can help tonight:</h3>
                    
                    <ul style=\"padding-left: 20px; margin: 0;\">
                        <li style=\"margin-bottom: 12px; font-size: 15px; line-height: 1.5;\"><strong>Offer a warm check-in:</strong> Just asking about their day creates a safe, non-judgmental space.</li>
                        <li style=\"margin-bottom: 12px; font-size: 15px; line-height: 1.5;\"><strong>Listen actively:</strong> Sometimes they just need to vent and be heard, rather than having a problem 'solved'.</li>
                        <li style=\"margin-bottom: 0px; font-size: 15px; line-height: 1.5;\"><strong>Remind them of support:</strong> Let them know you are always in their corner.</li>
                    </ul>

                    <div style=\"margin-top: 35px; text-align: center;\">
                        <a href=\"#\" style=\"display: inline-block; background-color: #2F855A; color: #ffffff; text-decoration: none; padding: 13px 28px; border-radius: 8px; font-weight: 600; font-size: 15px; box-shadow: 0 2px 4px rgba(47, 133, 90, 0.3);\">Contact School Counselor</a>
                    </div>
                </div>

                <!-- Footer -->
                <div style=\"background-color: #f8fafc; padding: 25px 30px; text-align: center; border-top: 1px solid #e2e8f0;\">
                    <p style=\"margin: 0; color: #718096; font-size: 14px; line-height: 1.5;\">
                        <strong>The Counseling & Wellness Team</strong><br>
                        St. Vincent Blessed School of Manila
                    </p>
                    <p style=\"margin: 10px 0 0 0; color: #a0aec0; font-size: 12px;\">This is an automated message. Please do not reply directly to this email.</p>
                </div>
            </div>
        </div>
        ";

        $this->logAlert('Email', $parentEmail, "StudentShield Alert: Sent wellness check email regarding $studentName.");

        try {
            $email = \Config\Services::email();

            // Explicitly set SMTP config from .env to guarantee it's applied
            $smtpUser = env('email.SMTPUser', '');
            $smtpPass = env('email.SMTPPass', '');

            if (empty($smtpUser) || empty($smtpPass)) {
                log_message('error', 'Auto Email Failed: SMTP credentials not set in .env (email.SMTPUser, email.SMTPPass)');
                return;
            }

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

            $email->setFrom($smtpUser, 'SVBSM Wellness Team');
            $email->setTo($parentEmail);
            $email->setSubject($subject);

            // Attach the logo inline and replace the img src with its generated Content-ID (CID)
            // This guarantees the image loads in Gmail even when running off offline localhost (Laragon)!
            $logoPath = FCPATH . 'images/logo.png';
            if (file_exists($logoPath)) {
                $email->attach($logoPath, 'inline');
                $cid = $email->setAttachmentCID($logoPath);
                $htmlMessage = str_replace(base_url('images/logo.png'), "cid:" . $cid, $htmlMessage);
            }

            $email->setMessage($htmlMessage);

            if (!$email->send(false)) {
                log_message('error', 'Auto Email Failed: ' . $email->printDebugger(['headers', 'subject']));
            }
            else {
                log_message('info', "Auto Email sent successfully to {$parentEmail} for student {$studentName}");
            }
        }
        catch (\Exception $e) {
            log_message('error', 'Auto Email Exception: ' . $e->getMessage());
        }
    }

    private function triggerCounselorEmergencyAlert($message, $subject)
    {
        // 1. Text the default Counselor Phone
        $this->logAlert('SMS (Counselor Override)', "09221234567", $message);
        $payload = json_encode([
            'phone' => "09221234567", // Default Counselor Hotline mapped in config
            'message' => "StudentShield URGENT: " . $message
        ]);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => env('sms.GatewayUrl', "http://192.168.1.3:8080/send-sms"),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 2,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json", "Connection: close"],
        ]);
        curl_exec($curl);
        curl_close($curl);

        // 2. Email the Counselor Office
        try {
            $email = \Config\Services::email();
            $smtpUser = env('email.SMTPUser', '');
            $smtpPass = env('email.SMTPPass', '');
            if (!empty($smtpUser) && !empty($smtpPass)) {
                $email->initialize([
                    'protocol' => 'smtp', 'SMTPHost' => env('email.SMTPHost', 'smtp.gmail.com'),
                    'SMTPUser' => $smtpUser, 'SMTPPass' => $smtpPass, 'SMTPPort' => (int)env('email.SMTPPort', 587),
                    'SMTPCrypto' => env('email.SMTPCrypto', 'tls'), 'mailType' => 'html'
                ]);
                $email->setFrom($smtpUser, 'SVBSM Wellness Dashboard');
                $email->setTo("svbsmcounseling@gmail.com");
                $email->setSubject($subject);
                $email->setMessage(
                    "<div style='border: 2px solid #ef4444; padding: 20px; font-family: sans-serif;'>" .
                    "<h2>🚨 " . $subject . "</h2>" .
                    "<p style='color: #b91c1c; font-size: 16px;'><strong>" . $message . "</strong></p>" .
                    "<p>Log into your dashboard to read the full encrypted entry securely.</p>" .
                    "</div>"
                );
                $email->send(false);
            }
        }
        catch (\Exception $e) {
        }
    }

    private function logAlert($type, $recipient, $message)
    {
        $file = WRITEPATH . 'logs/alerts.json';
        $alerts = [];
        if (file_exists($file) && filesize($file) > 0) {
            $decoded = json_decode(file_get_contents($file), true);
            if (is_array($decoded)) {
                $alerts = $decoded;
            }
        }

        // Add new alert at the top
        array_unshift($alerts, [
            'type' => $type,
            'recipient' => $recipient,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ]);

        file_put_contents($file, json_encode($alerts, JSON_PRETTY_PRINT), LOCK_EX);
    }

    /**
     * Local keyword-based risk classification fallback.
     * Used when the ML microservice is offline/unreachable.
     * Mirrors the ML service's keyword categories for consistency.
     */
    private function localKeywordFallback(string $text): array
    {
        $lower = mb_strtolower($text);

        // Gen-Z sarcasm check — before keyword scanning
        $sarcasticContext = false;
        $jokeMarkers = ['haha', 'lol', 'lmao', 'char', 'charot', 'jk', 'joke lang', '😂', '💀', '🤣', 'eme', 'eme lang', 'dejk', 'charizz', 'chz'];
        $academicContext = ['exam', 'test', 'quiz', 'math', 'homework', 'assignment', 'school', 'class', 'project'];

        foreach ($jokeMarkers as $jm) {
            if (mb_strpos($lower, $jm) !== false) {
                $sarcasticContext = true;
                break;
            }
        }
        // "kms after math" / "i wanna die this exam" = sarcasm
        if (!$sarcasticContext) {
            foreach ($academicContext as $ac) {
                if (mb_strpos($lower, $ac) !== false) {
                    foreach (['kms', 'kill me', 'end me', 'wanna die', 'want to die'] as $casual) {
                        if (mb_strpos($lower, $casual) !== false) {
                            $sarcasticContext = true;
                            break 2;
                        }
                    }
                }
            }
        }

        // CRITICAL EXTREME — Explicit Suicide Intent (NEVER DOWNGRADED BY SARCASM)
        $critical_extreme = [
            'suicide', 'kill myself', 'cut myself', 'magpakamatay', 'laslas',
            'tatalon', 'bibigti', 'patayin sarili ko', 'magbibigti',
            'slit my wrist', 'self-harm', 'self harm', 'bigti', 'bitay',
            'i wanna end this shit', 'i wanna end this na', 'sana kunin na ko ni lord', 'tapusin ko na buhay ko', 'sana matulog at di na magising'
        ];

        // CRITICAL PASSIVE — Passive Ideation (Can be downgraded to MEDIUM if joking)
        $critical_passive = [
            'end it all', 'no reason to live', 'ayoko na mabuhay',
            'gusto ko na mawala', 'gusto ko nang mamatay', 'gusto nako mamatay',
            'mamatay', 'unalive myself', 'better off dead',
            'sana di na lang ako pinanganak', 'wala nang pag-asa',
            'i wanna die', 'i want to die', 'end my life', 'hang myself',
            'wish i was never born', 'wish i could disappear',
            'done with life', 'tired of living', 'gusto ko maglaho na lang', 'wala nang tutulong sakin'
        ];

        // HIGH — Emotional Distress 
        $high_emotional = [
            'pagod na ako', 'depressed', 'depression', 'di ko na kaya', 'ayoko na',
            'sobrang lungkot', 'sobrang bigat', 'hirap na hirap', 'sawang sawa na',
            'walang silbi', 'wala akong kwenta', 'pabigat ako', 'suko na ko',
            'di na ko lalaban', 'deds na ko', 'game over na', 'namamatay', 'durug na durog', 'wasak na wasak', 'sukong suko na', 'parang sasabog utak ko', "can't take this anymore"
        ];

        // STRICT HIGH — Physical Violence (Never downgraded by sarcasm)
        $high_physical = [
            'punched', 'kicked', 'choked', 'slapped', 'beat me up', 'pushed', 'cornered',
            'hit me', 'weapon', 'bleeding', 'bruises', 'sinapak', 'binugbog', 'sinampal',
            'binatukan', 'tinulak', 'sinabunutan', 'sinipa', 'sinakal', 'hinawakan',
            'sinuntok', 'sinaktan', 'sinasaktan', 'pinagtulungan', 'pinagmalupitan',
            'death threat', 'harassed', 'extort', 'blackmail', 'kidnap', 'strangled',
            'ginulpi', 'pananakit', 'inabuso', 'pinagbantaan', 'tatakutin',
            'dumugo', 'sugat', 'nananakit', 'tinadyakan', 'sabunot', 'dinukot', 'kinuyog', 'pinagkaisahan', 'kinuwelyuhan', 'sinungalngal',
        ];

        // MEDIUM — Verbal Abuse, Cyberbullying
        $medium = [
            'ugly', 'loser', 'slut', 'whore', 'dumb', 'stupid', 'hate you',
            'bobo', 'tanga', 'pangit', 'mataba', 'pokpok', 'squatter', 'gago',
            'tarantado', 'putangina', 'inutil', 'idyota', 'manyak', 'malandi',
            'toxic', 'humiliated', 'shamed', 'bullied', 'bully',
            'pinahiya', 'tinawanan', 'siniraan', 'pinagchismisan', 'chismis',
            'cyberbully', 'doxx', 'exposed', 'na-bash',
            'walang kwenta', 'insecure', 'buang', 'salot', 'bida-bida',
            'pinagtatawanan', 'pabida', 'epal', 'plastik', 'sipsip', 'kupal', 'papansin', 'jejemon', 'feelingera', 'walang ambag',
        ];

        // 1. Process STRICT CRITICAL (Ignores Sarcasm)
        foreach ($critical_extreme as $kw) {
            if (mb_strpos($lower, $kw) !== false) {
                return ['risk_level' => 'CRITICAL', 'risk_percent' => 99];
            }
        }

        // 2. Process PASSIVE CRITICAL (Susceptible to Sarcasm)
        foreach ($critical_passive as $kw) {
            if (mb_strpos($lower, $kw) !== false) {
                if ($sarcasticContext) {
                    return ['risk_level' => 'MEDIUM', 'risk_percent' => 60];
                }
                return ['risk_level' => 'CRITICAL', 'risk_percent' => 95];
            }
        }
        foreach ($high_physical as $kw) {
            if (mb_strpos($lower, $kw) !== false) {
                // Physical abuse is NEVER a joke. Ignore sarcasm completely.
                return ['risk_level' => 'HIGH', 'risk_percent' => 85];
            }
        }
        foreach ($high_emotional as $kw) {
            if (mb_strpos($lower, $kw) !== false) {
                if ($sarcasticContext) {
                    return ['risk_level' => 'MEDIUM', 'risk_percent' => 45];
                }
                return ['risk_level' => 'HIGH', 'risk_percent' => 75];
            }
        }
        foreach ($medium as $kw) {
            if (mb_strpos($lower, $kw) !== false) {
                if ($sarcasticContext) {
                    return ['risk_level' => 'LOW', 'risk_percent' => 20];
                }
                return ['risk_level' => 'MEDIUM', 'risk_percent' => 50];
            }
        }

        return ['risk_level' => 'LOW', 'risk_percent' => 10];
    }
}
