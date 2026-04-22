<?php

namespace App\Controllers;

class UserController extends BaseController
{
    /**
     * Display the User Management page (Super Admin only).
     */
    public function index()
    {
        if ((int) session()->get('role') !== 2) {
            return redirect()->to('/counselor/dashboard')->with('error', 'Access denied.');
        }

        $db = \Config\Database::connect();
        $users = $db->table('users')
            ->select('id, name, student_id, role, parent_email, parent_phone, section, adviser_name')
            ->orderBy('role', 'DESC')
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();

        return view('manage_users', ['users' => $users]);
    }

    /**
     * Create a new user.
     */
    public function create()
    {
        if ((int) session()->get('role') !== 2) {
            return redirect()->to('/counselor/dashboard')->with('error', 'Access denied.');
        }

        $db = \Config\Database::connect();
        $name        = trim($this->request->getPost('name'));
        $role        = (int) $this->request->getPost('role');
        $studentIdInput = trim($this->request->getPost('student_id') ?? '');
        $studentId   = ($studentIdInput === '') ? null : $studentIdInput;
        $parentEmail = trim($this->request->getPost('parent_email') ?? '');
        $parentPhone = trim($this->request->getPost('parent_phone') ?? '');
        $section     = trim($this->request->getPost('section') ?? '');
        $adviserName = trim($this->request->getPost('adviser_name') ?? '');
        $passcode    = $this->request->getPost('passcode') ?? '';

        if (empty($name)) {
            return redirect()->back()->with('error', 'Name is required.');
        }
        
        if ($role === 0) {
            if (empty($studentId)) {
                return redirect()->back()->with('error', 'Student ID is required for students.');
            }
            if (empty($section)) {
                return redirect()->back()->with('error', 'Section is required for students.');
            }
            if (empty($adviserName)) {
                return redirect()->back()->with('error', 'Adviser Name is required for students.');
            }
        }

        // Check duplicate name
        $existing = $db->table('users')->where('name', $name)->get()->getRow();
        if ($existing) {
            return redirect()->back()->with('error', 'A user with that name already exists.');
        }

        // Hash password for counselors & super admins
        $hashedPasscode = '';
        if ($role >= 1) {
            if (empty($passcode)) {
                return redirect()->back()->with('error', 'Password is required for staff accounts.');
            }
            $hashedPasscode = password_hash($passcode, PASSWORD_BCRYPT);
        } else {
            // Students also get a hashed password
            if (!empty($passcode)) {
                $hashedPasscode = password_hash($passcode, PASSWORD_BCRYPT);
            }
        }

        $db->table('users')->insert([
            'name'          => $name,
            'student_id'    => $studentId,
            'role'          => $role,
            'passcode'      => $hashedPasscode,
            'parent_email'  => $parentEmail,
            'parent_phone'  => $parentPhone,
            'section'       => $section,
            'adviser_name'  => $adviserName,
        ]);

        $this->logAudit('CREATE_USER', 'user', $db->insertID());

        return redirect()->to('/admin/users')->with('success', "User \"{$name}\" created successfully.");
    }

    /**
     * Update an existing user.
     */
    public function update($id)
    {
        if ((int) session()->get('role') !== 2) {
            return redirect()->to('/counselor/dashboard')->with('error', 'Access denied.');
        }

        $db = \Config\Database::connect();

        // Prevent editing yourself out of super admin
        $currentUser = (int) session()->get('user_id');
        $targetRole = (int) $this->request->getPost('role');
        if ($id == $currentUser && $targetRole !== 2) {
            return redirect()->back()->with('error', 'You cannot demote your own account.');
        }

        $studentIdInput = trim($this->request->getPost('student_id') ?? '');
        $updateData = [
            'name'         => trim($this->request->getPost('name')),
            'student_id'   => ($studentIdInput === '') ? null : $studentIdInput,
            'role'         => $targetRole,
            'parent_email' => trim($this->request->getPost('parent_email') ?? ''),
            'parent_phone' => trim($this->request->getPost('parent_phone') ?? ''),
            'section'      => trim($this->request->getPost('section') ?? ''),
            'adviser_name' => trim($this->request->getPost('adviser_name') ?? ''),
        ];

        if ($targetRole === 0) {
            if (empty($updateData['section'])) {
                return redirect()->back()->with('error', 'Section is required for students.');
            }
            if (empty($updateData['adviser_name'])) {
                return redirect()->back()->with('error', 'Adviser Name is required for students.');
            }
        }

        // Only update password if provided
        $newPasscode = $this->request->getPost('passcode');
        if (!empty($newPasscode)) {
            $updateData['passcode'] = password_hash($newPasscode, PASSWORD_BCRYPT);
        }

        $db->table('users')->where('id', $id)->update($updateData);
        $this->logAudit('UPDATE_USER', 'user', (int)$id);

        return redirect()->to('/admin/users')->with('success', 'User updated successfully.');
    }

    /**
     * Delete a user.
     */
    public function delete($id)
    {
        if ((int) session()->get('role') !== 2) {
            return redirect()->to('/counselor/dashboard')->with('error', 'Access denied.');
        }

        if ($id == session()->get('user_id')) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', $id)->get()->getRow();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $db->table('users')->where('id', $id)->delete();
        $this->logAudit('DELETE_USER', 'user', (int)$id);

        return redirect()->to('/admin/users')->with('success', "User \"{$user->name}\" deleted.");
    }

    /**
     * Write an audit log entry.
     */
    private function logAudit(string $action, string $entityType, int $entityId): void
    {
        $db = \Config\Database::connect();
        try {
            $db->table('audit_logs')->insert([
                'user_id'     => session()->get('user_id'),
                'action'      => $action,
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
                'ip_address'  => $this->request->getIPAddress(),
                'user_agent'  => $this->request->getUserAgent()->getAgentString(),
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Audit log failed: ' . $e->getMessage());
        }
    }
}
