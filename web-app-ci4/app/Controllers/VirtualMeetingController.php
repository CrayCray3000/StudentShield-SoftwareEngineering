<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class VirtualMeetingController extends Controller
{
    /**
     * Start a new virtual meeting for a student.
     */
    public function start($studentId = null)
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') == 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $counselorId = $session->get('user_id');

        if (!$studentId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Student ID is required']);
        }

        $db = Database::connect();
        
        // Ensure table exists (though handled in CaseSummaryController, good to be safe)
        $this->ensureVirtualMeetingsTable($db);

        // Check if there is already an active meeting for this student
        $builder = $db->table('virtual_meetings');
        $activeMeeting = $builder->where('student_id', $studentId)
                                 ->where('status', 'active')
                                 ->get()
                                 ->getRowArray();

        if ($activeMeeting) {
             return $this->response->setJSON([
                 'status' => 'success',
                 'meeting_id' => $activeMeeting['id'],
                 'room_name' => $activeMeeting['meet_link'],
                 'message' => 'Meeting already active'
             ]);
        }

        // Generate a unique room name
        $roomName = 'StudentShield_' . $studentId . '_' . bin2hex(random_bytes(4));

        $data = [
            'student_id' => $studentId,
            'counselor_id' => $counselorId,
            'meet_link' => $roomName,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $builder->insert($data);
        $meetingId = $db->insertID();

        return $this->response->setJSON([
            'status' => 'success',
            'meeting_id' => $meetingId,
            'room_name' => $roomName,
            'message' => 'Meeting started successfully'
        ]);
    }

    /**
     * End an active virtual meeting.
     */
    public function end($studentId = null)
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') == 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        if (!$studentId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Student ID is required']);
        }

        $db = Database::connect();
        $builder = $db->table('virtual_meetings');
        
        // Update all active meetings for this student to ended
        $builder->where('student_id', $studentId)
                ->where('status', 'active')
                ->update(['status' => 'ended']);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Meeting ended successfully'
        ]);
    }

    /**
     * Get active meeting status for a student (used by student dashboard).
     */
    public function getActiveMeeting()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $studentId = $session->get('user_id');

        $db = Database::connect();
        $builder = $db->table('virtual_meetings');
        $activeMeeting = $builder->where('student_id', $studentId)
                                 ->where('status', 'active')
                                 ->orderBy('created_at', 'DESC')
                                 ->get()
                                 ->getRowArray();

        if ($activeMeeting) {
            return $this->response->setJSON([
                'status' => 'success',
                'has_meeting' => true,
                'meet_link' => $activeMeeting['meet_link'],
                'counselor_id' => $activeMeeting['counselor_id']
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'has_meeting' => false
        ]);
    }

    private function ensureVirtualMeetingsTable($db)
    {
        if (!$db->tableExists('virtual_meetings')) {
            $forge = \Config\Database::forge();
            $forge->addField([
                'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'student_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'counselor_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'meet_link'    => ['type' => 'VARCHAR', 'constraint' => 255],
                'status'       => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'active'],
                'created_at'   => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addKey('id', true);
            $forge->createTable('virtual_meetings', true);
        }
    }
}
