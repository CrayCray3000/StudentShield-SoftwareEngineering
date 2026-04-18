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
                ->limit(50)
                ->get()->getResultArray();
        } catch (\Exception $e) {
            $vents = $db->table('vent_logs')
                ->select('vent_logs.*, users.name, users.student_id, users.parent_phone, users.parent_email, users.section, users.adviser_name')
                ->join('users', 'users.id = vent_logs.user_id', 'left')
                ->orderBy('created_at', 'DESC')
                ->limit(50)
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
                ->limit(50)
                ->get()->getResultArray();
        } catch (\Exception $e) {
            $incidents = $db->table('bully_reports')
                ->select('bully_reports.*, bully_reports.incident_details as description, bully_reports.reporter_student_id as reporter_id, users.name, users.parent_phone, users.parent_email')
                ->join('users', 'users.id = bully_reports.reporter_student_id', 'left')
                ->orderBy('created_at', 'DESC')
                ->limit(50)
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
}
