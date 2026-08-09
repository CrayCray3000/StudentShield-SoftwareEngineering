<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class VirtualMeetingController extends Controller
{
    /**
     * Student requests a video session with their counselor.
     */
    public function requestMeeting()
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') != 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $studentId = $session->get('user_id');

        $db = Database::connect();
        $this->ensureVirtualMeetingsTable($db);

        $builder = $db->table('virtual_meetings');

        // Check if there is already an active meeting or pending request
        $existing = $builder
            ->where('student_id', $studentId)
            ->whereIn('status', ['active', 'requested'])
            ->get()->getRowArray();

        if ($existing) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => $existing['status'] === 'active' ? 'Meeting already active' : 'Request already pending'
            ]);
        }

        $builder->insert([
            'student_id'   => $studentId,
            'counselor_id' => null, // Will be assigned when counselor starts the meeting
            'meet_link'    => '',
            'status'       => 'requested',
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Session request sent to your counselor.'
        ]);
    }

    /**
     * Student cancels their pending session request.
     */
    public function cancelRequest()
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') != 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $studentId = $session->get('user_id');
        $db = Database::connect();
        $builder = $db->table('virtual_meetings');

        $builder->where('student_id', $studentId)
                ->where('status', 'requested')
                ->update(['status' => 'cancelled']);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Request cancelled.']);
    }

    /**
     * Get count of pending session requests for a student (counselor side).
     */
    public function getPendingRequest($studentId = null)
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') == 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        if (!$studentId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Student ID required']);
        }

        $db = Database::connect();
        $this->ensureVirtualMeetingsTable($db);

        $pending = $db->table('virtual_meetings')
            ->where('student_id', $studentId)
            ->where('status', 'requested')
            ->orderBy('created_at', 'DESC')
            ->get()->getRowArray();

        return $this->response->setJSON([
            'status'  => 'success',
            'pending' => !empty($pending),
            'requested_at' => $pending['created_at'] ?? null,
        ]);
    }

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
            'counselor_id' => $counselorId,
            'meet_link' => $roomName,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Attempt to update a pending request if exists, otherwise insert new
        $updated = $builder->where('student_id', $studentId)
                           ->where('status', 'requested')
                           ->update($data);

        if (!$updated) {
            $data['student_id'] = $studentId;
            $builder->insert($data);
            $meetingId = $db->insertID();
        } else {
            $meetingId = $builder->where('student_id', $studentId)
                                 ->where('status', 'active')
                                 ->orderBy('id', 'DESC')
                                 ->get()->getRowArray()['id'];
        }

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

        // Mark active meetings as ended
        $builder->where('student_id', $studentId)
                ->where('status', 'active')
                ->update(['status' => 'ended']);

        // Also cancel any lingering 'requested' rows so student isn't stuck
        $db->table('virtual_meetings')
           ->where('student_id', $studentId)
           ->where('status', 'requested')
           ->update(['status' => 'cancelled']);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Meeting ended successfully'
        ]);
    }

    /**
     * Get active meeting status for a student (used by student dashboard polling).
     * Also returns whether a pending request exists.
     */
    public function getActiveMeeting()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $studentId = $session->get('user_id');

        $db = Database::connect();
        $this->ensureVirtualMeetingsTable($db);
        $builder = $db->table('virtual_meetings');

        // Check for active meeting first
        $activeMeeting = $builder->where('student_id', $studentId)
                                 ->where('status', 'active')
                                 ->orderBy('created_at', 'DESC')
                                 ->get()->getRowArray();

        if ($activeMeeting) {
            return $this->response->setJSON([
                'status'       => 'success',
                'has_meeting'  => true,
                'has_request'  => false,
                'meet_link'    => $activeMeeting['meet_link'],
                'counselor_id' => $activeMeeting['counselor_id']
            ]);
        }

        // Check for pending request
        $pending = $db->table('virtual_meetings')
            ->where('student_id', $studentId)
            ->where('status', 'requested')
            ->orderBy('created_at', 'DESC')
            ->get()->getRowArray();

        return $this->response->setJSON([
            'status'      => 'success',
            'has_meeting' => false,
            'has_request' => !empty($pending),
        ]);
    }

    private function ensureVirtualMeetingsTable($db)
    {
        if (!$db->tableExists('virtual_meetings')) {
            $forge = \Config\Database::forge();
            $forge->addField([
                'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'student_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'counselor_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'default' => null],
                'meet_link'    => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'status'       => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'requested'],
                'created_at'   => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addKey('id', true);
            $forge->createTable('virtual_meetings', true);
        }
    }
}
