<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    /**
     * Write an immutable audit log entry for compliance tracking.
     */
    private function logAudit(string $action, string $entityType, int $entityId): void
    {
        $db = \Config\Database::connect();
        $db->table('audit_logs')->insert([
            'user_id'     => session()->get('user_id'),
            'action'      => $action,
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'ip_address'  => $this->request->getIPAddress(),
            'user_agent'  => $this->request->getUserAgent()->getAgentString(),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }
    public function dashboard()
    {
        $db = \Config\Database::connect();
        // Fetch initially for SSR rendering
        try {
            $recent_vents = $db->table('vent_logs')
                ->select('vent_logs.*, users.name, users.student_id, users.section, users.adviser_name')
                ->join('users', 'users.id = vent_logs.user_id')
                ->where('vent_logs.deleted_at IS NULL')
                ->orderBy('created_at', 'DESC')
                ->get()->getResultArray();
        } catch (\Exception $e) {
            $recent_vents = $db->table('vent_logs')
                ->select('vent_logs.*, users.name, users.student_id, users.section, users.adviser_name')
                ->join('users', 'users.id = vent_logs.user_id')
                ->orderBy('created_at', 'DESC')
                ->get()->getResultArray();
        }
            
        $encrypter = \Config\Services::encrypter();
        foreach ($recent_vents as &$vent) {
            try {
                if (ctype_xdigit($vent['raw_text'])) {
                    $vent['raw_text'] = $encrypter->decrypt(hex2bin($vent['raw_text']));
                }
            } catch (\Exception $e) {
                // Fallback for older non-encrypted plain text vents
            }
        }
        $data['recent_vents'] = $recent_vents;

        return view('admin_dashboard', $data);
    }

    public function fetchVents()
    {
        if (!session()->get('isLoggedIn') || !in_array((int)session()->get('role'), [1, 2])) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }
        
        // AJAX endpoint
        $db = \Config\Database::connect();
        $isArchived = $this->request->getGet('archived') == '1';
        
        try {
            $query = $db->table('vent_logs')
                ->select('vent_logs.*, users.name, users.student_id, users.parent_phone, users.parent_email, users.section, users.adviser_name')
                ->join('users', 'users.id = vent_logs.user_id', 'left');
                
            if ($isArchived) {
                $query->where('vent_logs.deleted_at IS NOT NULL');
            } else {
                $query->where('vent_logs.deleted_at IS NULL');
            }
            
            $vents = $query->orderBy('created_at', 'DESC')
                ->limit(500)
                ->get()->getResultArray();
        } catch (\Exception $e) {
            $vents = $db->table('vent_logs')
                ->select('vent_logs.*, users.name, users.student_id, users.parent_phone, users.parent_email, users.section, users.adviser_name')
                ->join('users', 'users.id = vent_logs.user_id', 'left')
                ->orderBy('created_at', 'DESC')
                ->limit(500)
                ->get()->getResultArray();
        }
            
        $encrypter = \Config\Services::encrypter();
        foreach ($vents as &$vent) {
            try {
                if (ctype_xdigit($vent['raw_text'])) {
                    $vent['raw_text'] = $encrypter->decrypt(hex2bin($vent['raw_text']));
                }
            } catch (\Exception $e) {
                // Fallback for older non-encrypted plain text vents
            }
        }
            
        return $this->response->setJSON($vents);
    }

    public function resolveVent($id)
    {
        $db = \Config\Database::connect();
        $db->table('vent_logs')->where('id', $id)->update(['status' => 'Resolved']);
        $this->logAudit('RESOLVE', 'vent', (int)$id);
        return redirect()->to('/counselor/dashboard')->with('message', "Vent Entry #$id resolved.");
    }

    public function deleteVent($id)
    {
        $db = \Config\Database::connect();
        // Soft Delete: set deleted_at timestamp instead of permanent removal
        $db->table('vent_logs')->where('id', $id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
        $this->logAudit('SOFT_DELETE', 'vent', (int)$id);
        return redirect()->to('/counselor/dashboard')->with('message', "Vent Entry #$id archived (soft-deleted).");
    }

    public function deleteMultipleVents()
    {
        $ids = $this->request->getPost('vent_ids');
        if (!empty($ids) && is_array($ids)) {
            $db = \Config\Database::connect();
            // Soft Delete: set deleted_at timestamp instead of permanent removal
            $db->table('vent_logs')->whereIn('id', $ids)->update(['deleted_at' => date('Y-m-d H:i:s')]);
            foreach ($ids as $id) {
                $this->logAudit('BULK_SOFT_DELETE', 'vent', (int)$id);
            }
            return redirect()->to('/counselor/dashboard')->with('message', count($ids) . " Vents archived (soft-deleted).");
        }
        return redirect()->to('/counselor/dashboard')->with('error', "No vents selected for deletion.");
    }
    
    public function restoreVent($id)
    {
        $db = \Config\Database::connect();
        $db->table('vent_logs')->where('id', $id)->update(['deleted_at' => null]);
        $this->logAudit('RESTORE', 'vent', (int)$id);
        return redirect()->to('/counselor/dashboard')->with('message', "Vent Entry #$id unarchived and restored.");
    }

    public function restoreMultipleVents()
    {
        $ids = $this->request->getPost('vent_ids');
        if (!empty($ids) && is_array($ids)) {
            $db = \Config\Database::connect();
            $db->table('vent_logs')->whereIn('id', $ids)->update(['deleted_at' => null]);
            foreach ($ids as $id) {
                $this->logAudit('BULK_RESTORE', 'vent', (int)$id);
            }
            return redirect()->to('/counselor/dashboard')->with('message', count($ids) . " Vents unarchived and restored.");
        }
        return redirect()->to('/counselor/dashboard')->with('error', "No vents selected for restoration.");
    }
    
    public function fetchIncidents()
    {
        if (!session()->get('isLoggedIn') || !in_array((int)session()->get('role'), [1, 2])) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }
        
        $db = \Config\Database::connect();
        $isArchived = $this->request->getGet('archived') == '1';
        
        try {
            $query = $db->table('bully_reports')
                ->select('bully_reports.*, bully_reports.incident_details as description, bully_reports.reporter_student_id as reporter_id, users.name, users.parent_phone, users.parent_email')
                ->join('users', 'users.id = bully_reports.reporter_student_id', 'left');
                
            if ($isArchived) {
                $query->where('bully_reports.deleted_at IS NOT NULL');
            } else {
                $query->where('bully_reports.deleted_at IS NULL');
            }
            
            $incidents = $query->orderBy('created_at', 'DESC')
                ->limit(500)
                ->get()->getResultArray();
        } catch (\Exception $e) {
            $incidents = $db->table('bully_reports')
                ->select('bully_reports.*, bully_reports.incident_details as description, bully_reports.reporter_student_id as reporter_id, users.name, users.parent_phone, users.parent_email')
                ->join('users', 'users.id = bully_reports.reporter_student_id', 'left')
                ->orderBy('created_at', 'DESC')
                ->limit(500)
                ->get()->getResultArray();
        }
            
        $encrypter = \Config\Services::encrypter();
        foreach ($incidents as &$incident) {
            try {
                if (ctype_xdigit($incident['description'])) {
                    $incident['description'] = $encrypter->decrypt(hex2bin($incident['description']));
                }
            } catch (\Exception $e) {
                // Fallback for older non-encrypted plain text descriptions
            }
        }
            
        return $this->response->setJSON($incidents);
    }
    
    public function resolveIncident($id)
    {
        $db = \Config\Database::connect();
        $db->table('bully_reports')->where('id', $id)->update(['status' => 'resolved']);
        $this->logAudit('RESOLVE', 'incident', (int)$id);
        return redirect()->to('/counselor/dashboard')->with('message', "Incident Report #$id resolved.");
    }

    /**
     * Stage 2: Counselor Verification — Triggers Automated Parent SMS & Email Blast
     * 
     * This is the thesis core feature: "Automated Parent SMS Alerts"
     * The automation is counselor-gated to prevent fake/malicious reports from 
     * triggering parent panic. The counselor clicks one button, and the entire
     * notification pipeline fires automatically — no typing, no manual selection.
     */
    public function verifyIncident($id)
    {
        $db = \Config\Database::connect();
        
        // Update status to verified
        $db->table('bully_reports')->where('id', $id)->update([
            'status'     => 'verified',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        $this->logAudit('VERIFY_AND_ALERT', 'incident', (int)$id);
        
        // Fetch the full report record
        $report = $db->table('bully_reports')->where('id', $id)->get()->getRow();
        if (!$report) {
            return redirect()->to('/counselor/dashboard')->with('error', "Report #$id not found.");
        }

        // --- Execute Automated Parent SMS & Email Blast (Stage 2) ---
        
        // 1. Alert the Victim's Parent (SMS & Email)
        if (!empty($report->victim_student_id)) {
            $victim = $db->table('users')->where('id', $report->victim_student_id)->get()->getRow();
            if ($victim) {
                $victimMsg = "SVBSM Guidance Alert: A verified report indicates your child may be involved in a bullying incident as a victim. Please offer a warm check-in with your child tonight. Our counselors have confirmed the report and are actively investigating.";
                if (!empty($victim->parent_phone)) $this->sendLocalSms($victim->parent_phone, $victimMsg);
                if (!empty($victim->parent_email)) $this->sendLocalEmail($victim->parent_email, $victimMsg);
            }
        }
        
        // 2. Alert the Bully's Parent (SMS & Email)
        if (!empty($report->bully_student_id)) {
            $bully = $db->table('users')->where('id', $report->bully_student_id)->get()->getRow();
            if ($bully) {
                $bullyMsg = "SVBSM Guidance Alert: A verified report involving your child regarding a disciplinary incident has been confirmed by our guidance team. Our administration is currently investigating. Please contact the school office tomorrow.";
                if (!empty($bully->parent_phone)) $this->sendLocalSms($bully->parent_phone, $bullyMsg);
                if (!empty($bully->parent_email)) $this->sendLocalEmail($bully->parent_email, $bullyMsg);
            }
        }

        return redirect()->to('/counselor/dashboard')->with('message', "Incident Report #$id VERIFIED. Automated SMS & Email alerts have been sent to parents.");
    }

    /**
     * Reject a fake/malicious report without sending any parent alerts.
     */
    public function rejectIncident($id)
    {
        $db = \Config\Database::connect();
        $db->table('bully_reports')->where('id', $id)->update([
            'status'     => 'rejected_fake',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        $this->logAudit('REJECT_FAKE', 'incident', (int)$id);
        return redirect()->to('/counselor/dashboard')->with('message', "Incident Report #$id marked as unverified/fake. No parent alerts were sent.");
    }

    /**
     * Undo a verification or rejection — resets the report back to pending_review.
     * This lets counselors fix accidental button clicks.
     */
    public function undoIncident($id)
    {
        $db = \Config\Database::connect();
        $db->table('bully_reports')->where('id', $id)->update([
            'status'     => 'pending_review',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        $this->logAudit('UNDO_TO_PENDING', 'incident', (int)$id);
        return redirect()->to('/counselor/dashboard')->with('message', "Incident Report #$id has been reset to Pending Review.");
    }

    private function sendLocalSms($phone, $message)
    {
        if (empty($phone)) return false;

        $cleanPhone = ltrim($phone, '+');
        if (str_starts_with($cleanPhone, '63') && strlen($cleanPhone) == 12) {
            $cleanPhone = '0' . substr($cleanPhone, 2);
        }

        $payload = json_encode(['phone' => $cleanPhone, 'message' => $message]);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://192.168.1.3:8080/send-sms",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json", "Connection: close"],
        ]);
        curl_exec($curl);
        curl_close($curl);
        
        return true;
    }
    
    private function sendLocalEmail($emailAddress, $messageBody)
    {
        if(empty($emailAddress)) return false;
        try {
            $email = \Config\Services::email();
            
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

    public function deleteIncident($id)
    {
        $db = \Config\Database::connect();
        // Soft Delete
        $db->table('bully_reports')->where('id', $id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
        $this->logAudit('SOFT_DELETE', 'incident', (int)$id);
        return redirect()->to('/counselor/dashboard')->with('message', "Incident Report #$id archived (soft-deleted).");
    }

    public function deleteMultipleIncidents()
    {
        $ids = $this->request->getPost('incident_ids');
        if (!empty($ids) && is_array($ids)) {
            $db = \Config\Database::connect();
            // Soft Delete
            $db->table('bully_reports')->whereIn('id', $ids)->update(['deleted_at' => date('Y-m-d H:i:s')]);
            foreach ($ids as $id) {
                $this->logAudit('BULK_SOFT_DELETE', 'incident', (int)$id);
            }
            return redirect()->to('/counselor/dashboard')->with('message', count($ids) . " Incident Reports archived (soft-deleted).");
        }
        return redirect()->to('/counselor/dashboard')->with('error', "No incidents selected for deletion.");
    }
    
    public function restoreIncident($id)
    {
        $db = \Config\Database::connect();
        $db->table('bully_reports')->where('id', $id)->update(['deleted_at' => null]);
        $this->logAudit('RESTORE', 'incident', (int)$id);
        return redirect()->to('/counselor/dashboard')->with('message', "Incident Report #$id unarchived and restored.");
    }

    public function restoreMultipleIncidents()
    {
        $ids = $this->request->getPost('incident_ids');
        if (!empty($ids) && is_array($ids)) {
            $db = \Config\Database::connect();
            $db->table('bully_reports')->whereIn('id', $ids)->update(['deleted_at' => null]);
            foreach ($ids as $id) {
                $this->logAudit('BULK_RESTORE', 'incident', (int)$id);
            }
            return redirect()->to('/counselor/dashboard')->with('message', count($ids) . " Incident Reports unarchived and restored.");
        }
        return redirect()->to('/counselor/dashboard')->with('error', "No incidents selected for restoration.");
    }
    
    public function fetchAlertLogs()
    {
        if (!session()->get('isLoggedIn') || !in_array((int)session()->get('role'), [1, 2])) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }
        
        // Ensure alerts log file exists, otherwise return empty array
        $logFile = WRITEPATH . 'logs/alerts.json';
        if (!file_exists($logFile)) {
            return $this->response->setJSON([]);
        }

        $logsContent = file_get_contents($logFile);
        $logs = json_decode($logsContent, true) ?: [];
        
        // Sort descending by timestamp
        usort($logs, function($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });
        
        // Return latest 50
        $logs = array_slice($logs, 0, 50);

        return $this->response->setJSON($logs);
    }
    public function getStudentProfile($id)
    {
        if (!session()->get('isLoggedIn') || !in_array((int)session()->get('role'), [1, 2])) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();
        
        // 1. Fetch User Data
        $user = $db->table('users')->where('id', $id)->get()->getRowArray();
        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'User not found']);
        }

        // Remove sensitive info
        unset($user['passcode']);
        
        // 2. Fetch Vents (FAISS tracking data)
        $vents = $db->table('vent_logs')
            ->where('user_id', $id)
            ->where('deleted_at IS NULL')
            ->orderBy('created_at', 'ASC')
            ->limit(30)
            ->get()->getResultArray();
            
        $encrypter = \Config\Services::encrypter();
        $timelineData = [];
        foreach ($vents as &$vent) {
            try {
                if (ctype_xdigit($vent['raw_text'])) {
                    $vent['raw_text'] = $encrypter->decrypt(hex2bin($vent['raw_text']));
                }
            } catch (\Exception $e) {}
            
            // Format for ApexCharts
            $risk = $vent['ml_risk_level'] ?? 'Low';
            if ($risk === 'Critical') $y = 90;
            elseif ($risk === 'High') $y = 75;
            elseif ($risk === 'Medium') $y = 50;
            else $y = 20;

            $timelineData[] = [
                'x' => date('M d', strtotime($vent['created_at'])),
                'y' => $y,
                'sentiment' => $vent['ml_sentiment_score'] ?? '0.00'
            ];
        }

        // 3. Fetch Involved Incidents
        $incidents = $db->table('bully_reports')
            ->select('id, incident_type, status, created_at, reporter_student_id, victim_student_id, bully_student_id')
            ->where('deleted_at IS NULL')
            ->groupStart()
                ->where('reporter_student_id', $id)
                ->orWhere('victim_student_id', $id)
                ->orWhere('bully_student_id', $id)
            ->groupEnd()
            ->orderBy('created_at', 'DESC')
            ->limit(15)
            ->get()->getResultArray();

        return $this->response->setJSON([
            'user' => $user,
            'timeline' => $timelineData,
            'vents' => array_reverse($vents),
            'incidents' => $incidents
        ]);
    }
}
