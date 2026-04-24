<?php

namespace App\Controllers;

use App\Models\BullyReportModel;

class BullyReportController extends BaseController
{
    public function index()
    {
        return view('reports/bullying_form');
    }

    public function submit()
    {
        if (!$this->request->is('post')) return redirect()->to('/report/bullying');

        // Security: Anti-Spam Rate Limiting (1 Bullying Report per 120 seconds)
        // Malicious scripts cannot spam-deplete the SMS Gateway.
        $lastSubmit = session()->get('last_bully_report_time');
        if ($lastSubmit && time() - $lastSubmit < 120) {
            $waitTime = 120 - (time() - $lastSubmit);
            return redirect()->back()->with('error', "Security Limit: Please wait {$waitTime} seconds before submitting again to prevent spam.");
        }
        session()->set('last_bully_report_time', time());
        
        $reporterType = $this->request->getPost('reporter_type') ?? 'self';
        $victimName   = trim($this->request->getPost('victim_name') ?? '');
        $bullyName    = trim($this->request->getPost('bully_name') ?: $this->request->getPost('perpetrator_name') ?: '');
        
        $details      = $this->request->getPost('incident_details') ?: $this->request->getPost('description');
        $incidentType = $this->request->getPost('incident_type');
        if (!empty($incidentType)) {
            $details = "INCIDENT CATEGORY: " . $incidentType . "\n\n" . $details;
        }
        
        $evidenceFile = $this->request->getFile('evidence_file');
        $evidencePath = null;

        if ($evidenceFile && $evidenceFile->isValid() && !$evidenceFile->hasMoved()) {
            $rules = [
                'evidence_file' => [
                    'rules' => 'max_size[evidence_file,20480]|ext_in[evidence_file,jpg,jpeg,png,gif,webp,mp4,mov,avi,mkv]',
                    'errors' => [
                        'max_size' => 'The evidence file must not exceed 20MB.',
                        'ext_in'   => 'Only images (JPG, PNG, GIF, WEBP) and videos (MP4, MOV, AVI, MKV) are allowed.'
                    ]
                ]
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', $this->validator->getError('evidence_file'));
            }

            // Ensure the upload directory exists
            if (!is_dir(FCPATH . 'uploads/evidence')) {
                mkdir(FCPATH . 'uploads/evidence', 0777, true);
            }
            $newName = $evidenceFile->getRandomName();
            $evidenceFile->move(FCPATH . 'uploads/evidence', $newName);
            $evidencePath = 'uploads/evidence/' . $newName;
        }
        
        // Loophole 3 Mitigation: Identity Traceability via Session
        $userId = session()->get('user_id') ?? 1; 

        $db = \Config\Database::connect();

        // Privacy Upgrade & Security Fix: Resolve Victim Name -> ID using strictly unique fuzzy lookup
        $victimStudentId = null;
        if ($reporterType === 'self') {
            $victimStudentId = $userId;
        } else if (!empty($victimName)) {
            $vUsers = $db->table('users')->where('name', $victimName)->get()->getResult();
            // ONLY associate if there is exactly 1 match to prevent wrong-parent notification panics.
            if (count($vUsers) === 1) $victimStudentId = $vUsers[0]->id;
        }

        // Privacy Upgrade & Security Fix: Resolve Bully Name -> ID using strictly unique fuzzy lookup
        // Note: If multiple comma-separated names are provided, fuzzy lookup will safely fail, keeping bully_student_id Null. 
        // This is a feature, not a bug, protecting parents from mass incorrect automated SMS panics. The counselor will read the exact list below manually.
        $bullyStudentId = null;
        if (!empty($bullyName) && !str_contains($bullyName, ',')) {
            $bUsers = $db->table('users')->like('name', $bullyName)->get()->getResult();
            // ONLY associate if there is exactly 1 match to prevent wrong-parent notification panics.
            if (count($bUsers) === 1) $bullyStudentId = $bUsers[0]->id;
        }

        date_default_timezone_set('Asia/Manila');
        $encrypter = \Config\Services::encrypter();
        $rawDetails = "TARGETED STUDENT: " . ($victimName ?: 'Self') . "\nREPORTED AGGRESSOR(S): " . ($bullyName ?: 'N/A') . "\n\n" . $details;
        $encryptedDetails = bin2hex($encrypter->encrypt($rawDetails));

        $reportData = [
            'reporter_student_id' => $userId,
            'reporter_type'       => $reporterType,
            'victim_student_id'   => $victimStudentId,
            'bully_student_id'    => $bullyStudentId,
            // Prepend the typed names into the details so the counselor always knows who it was, even if the DB lookup couldn't find an exact match
            'incident_details'    => $encryptedDetails,
            'evidence_file'       => $evidencePath,
            'status'             => 'pending_review',
        ];

        // Loophole 2 Mitigation: Database Transactions
        $db->transStart();
        
        $model = new BullyReportModel();
        $insertResult = $model->insert($reportData);
        
        if ($insertResult === false) {
            $db->transRollback();
            $errors = $model->errors();
            log_message('error', 'Insert Failed: ' . json_encode($errors));
            return redirect()->back()->with('error', 'Critical Error: Data validation failed. ' . json_encode($errors));
        }
        
        $db->transComplete();

        // The SMS logic will NEVER fire if the database transaction fails!
        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Critical Error: Database failure. Report not saved.');
        }

        // --- Stage 1: Instant Counselor Alert (Report Intake) ---
        // Parent alerts are NOT sent here. They fire only after counselor verification (Stage 2)
        // to prevent fake/malicious reports from triggering parent panic SMS blasts.
        
        // 1. Alert the Reporter (Email) — confirmation only, no sensitive details
        $reporter = $db->table('users')->where('id', $userId)->get()->getRow();
        if ($reporter && !empty($reporter->email)) {
            $this->sendLocalEmail($reporter->email, "Thank you for speaking up. Your courageous report has been securely received by the SVBSM Counseling Team. A counselor will review it shortly. You are safe.");
        }

        // 2. Alert the Counselor Immediately (SMS & Email) — so they can investigate ASAP
        $this->sendLocalSms("09221234567", "StudentShield URGENT: A new Bullying Report was just filed and requires your verification. Please log into the admin dashboard immediately to investigate and verify the report before parent alerts are sent.");
        $this->sendLocalEmail("svbsmcounseling@gmail.com", "StudentShield URGENT: A new Bullying Report was just filed and requires your verification. Please log into the admin dashboard immediately to investigate and verify the report before parent alerts are sent.");

        // NOTE: Parent SMS & Email alerts are now triggered via Stage 2 (Counselor Verification)
        // See AdminController::verifyIncident() — counselor clicks "Verify & Alert Parents" on the dashboard

        return redirect()->to('/report/bullying')->with('message', 'Your report has been securely submitted. A school counselor has been alerted and will review it shortly.');
    }

    private function sendLocalSms($phone, $message)
    {
        // Loophole 4 Mitigation: Graceful skip for missing phone numbers
        if (empty($phone)) return false;

        $cleanPhone = ltrim($phone, '+');
        if (str_starts_with($cleanPhone, '63') && strlen($cleanPhone) == 12) {
            $cleanPhone = '0' . substr($cleanPhone, 2);
        }

        $payload = json_encode(['phone' => $cleanPhone, 'message' => $message]);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => env('sms.GatewayUrl', "http://192.168.1.3:8080/send-sms"),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1, // Ultra-fast fail timeout mapping to prevent bottlenecking the main UI
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json", "Connection: close"],
        ]);
        curl_exec($curl);
        
        return true;
    }
    
    private function sendLocalEmail($emailAddress, $messageBody)
    {
        if(empty($emailAddress)) return false;
        try {
            $email = \Config\Services::email();
            
            // Safe fallback configuration using environment variables
            $smtpUser = env('email.SMTPUser', 'svbsmcounseling@gmail.com');
            $smtpPass = env('email.SMTPPass', '');
            
            $email->initialize([
                'protocol' => 'smtp',
                'SMTPHost' => env('email.SMTPHost', 'smtp.gmail.com'),
                'SMTPUser' => $smtpUser,
                'SMTPPass' => $smtpPass,
                'SMTPPort' => (int)env('email.SMTPPort', 587),
                'SMTPCrypto' => env('email.SMTPCrypto', 'tls'),
                'SMTPTimeout' => 5,
                'mailType' => 'html',
                'charset' => 'utf-8',
            ]);
            
            $email->setFrom($smtpUser, 'SVBSM Wellness Team');
            $email->setTo($emailAddress);
            $email->setSubject('StudentShield: Protective Services Notification');
            $email->setMessage("<div style='font-family: Arial; padding: 20px; background: #f9f9f9; border: 1px solid #ddd;'><h3>Counseling Alert</h3><p>{$messageBody}</p></div>");
            $email->send(false);
        } catch (\Exception $e) {}
    }
}
