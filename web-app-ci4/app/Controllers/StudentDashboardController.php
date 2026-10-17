<?php

namespace App\Controllers;

class StudentDashboardController extends BaseController
{
    public function index()
    {
        if (!$this->hasRole(0)) return $this->forbidden();
        $userId = session()->get('user_id');
        $db = \Config\Database::connect();

        // ── False Report Strike System ──
        // Count how many of this student's reports were flagged as fake by the counselor
        $falseReportCount = 0;
        $isLocked = false;

        try {
            $falseReportCount = (int) $db->table('bully_reports')
                ->where('reporter_student_id', $userId)
                ->where('status', 'rejected_fake')
                ->where('deleted_at IS NULL')
                ->countAllResults();

            // Check if account is locked (3+ strikes)
            $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
            $isLocked = !empty($user['is_locked']);
        } catch (\Exception $e) {
            // Gracefully skip if table doesn't exist
        }

        // Fetch active virtual meeting
        $activeMeeting = null;
        if ($db->tableExists('virtual_meetings')) {
            $activeMeeting = $db->table('virtual_meetings')
                                ->where('student_id', $userId)
                                ->where('status', 'active')
                                ->orderBy('created_at', 'DESC')
                                ->get()
                                ->getRowArray();
        }

        return view('student_dashboard', [
            'falseReportStrikes' => $falseReportCount,
            'isLocked'           => $isLocked,
            'activeMeeting'      => $activeMeeting,
        ]);
    }
}
