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
            return redirect()->to('/admin/dashboard')->with('error', 'Access denied.');
        }

        $db = \Config\Database::connect();
        $users = $db->table('users')
            ->select('users.id, users.name, users.role, 
                      students.student_id, students.section, students.adviser_name, students.grade_level,
                      COALESCE(students.parent_phone, counselors.counselor_phone, admins.phone) AS parent_phone,
                      COALESCE(students.parent_email, counselors.counselor_email, admins.email) AS parent_email')
            ->join('students', 'students.user_id = users.id', 'left')
            ->join('counselors', 'counselors.user_id = users.id', 'left')
            ->join('admins', 'admins.user_id = users.id', 'left')
            ->orderBy('users.role', 'DESC')
            ->orderBy('users.name', 'ASC')
            ->get()->getResultArray();

        return view('manage_users', ['users' => $users]);
    }

    /**
     * Create a new user.
     */
    public function create()
    {
        if ((int) session()->get('role') !== 2) {
            return redirect()->to('/admin/dashboard')->with('error', 'Access denied.');
        }

        try {
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
                'role'          => $role,
                'password_hash' => $hashedPasscode,
            ]);
            $newUserId = $db->insertID();

            if ($role === 0) {
                $db->table('students')->insert([
                    'user_id'      => $newUserId,
                    'student_id'   => $studentId,
                    'parent_phone' => $parentPhone,
                    'parent_email' => $parentEmail,
                    'section'      => $section,
                    'adviser_name' => $adviserName,
                    'grade_level'  => null,
                ]);
            } elseif ($role === 1) {
                $db->table('counselors')->insert([
                    'user_id'          => $newUserId,
                    'counselor_email' => $parentEmail,
                    'counselor_phone' => $parentPhone,
                ]);
            } elseif ($role === 2) {
                $db->table('admins')->insert([
                    'user_id' => $newUserId,
                    'email'   => $parentEmail,
                    'phone'   => $parentPhone,
                ]);
            }

            $this->logAudit('CREATE_USER', 'user', $newUserId);

            return redirect()->to('/admin/users')->with('success', "User \"{$name}\" created successfully.");
        } catch (\Exception $e) {
            log_message('error', 'UserController::create failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create user. Please check the database and try again.');
        }
    }

    /**
     * Update an existing user.
     */
    public function update($id)
    {
        if ((int) session()->get('role') !== 2) {
            return redirect()->to('/admin/dashboard')->with('error', 'Access denied.');
        }

        try {
            $db = \Config\Database::connect();

            // Prevent editing yourself out of super admin
            $currentUser = (int) session()->get('user_id');
            $targetRole = (int) $this->request->getPost('role');
            if ($id == $currentUser && $targetRole !== 2) {
                return redirect()->back()->with('error', 'You cannot demote your own account.');
            }

            $studentIdInput = trim($this->request->getPost('student_id') ?? '');
            $parentEmail = trim($this->request->getPost('parent_email') ?? '');
            $parentPhone = trim($this->request->getPost('parent_phone') ?? '');
            $section = trim($this->request->getPost('section') ?? '');
            $adviserName = trim($this->request->getPost('adviser_name') ?? '');

            if ($targetRole === 0) {
                if (empty($section)) {
                    return redirect()->back()->with('error', 'Section is required for students.');
                }
                if (empty($adviserName)) {
                    return redirect()->back()->with('error', 'Adviser Name is required for students.');
                }
            }

            $userUpdate = [
                'name' => trim($this->request->getPost('name')),
                'role' => $targetRole,
            ];

            // Only update password if provided
            $newPasscode = $this->request->getPost('passcode');
            if (!empty($newPasscode)) {
                $userUpdate['password_hash'] = password_hash($newPasscode, PASSWORD_BCRYPT);
            }

            $db->transStart();

            $db->table('users')->where('id', $id)->update($userUpdate);

            // Delete profile records in other subclass tables to keep them clean
            if ($targetRole !== 0) $db->table('students')->where('user_id', $id)->delete();
            if ($targetRole !== 1) $db->table('counselors')->where('user_id', $id)->delete();
            if ($targetRole !== 2) $db->table('admins')->where('user_id', $id)->delete();

            // Upsert core profile fields into appropriate subclass table
            if ($targetRole === 0) {
                $studentData = [
                    'student_id'   => ($studentIdInput === '') ? null : $studentIdInput,
                    'parent_phone' => $parentPhone,
                    'parent_email' => $parentEmail,
                    'section'      => $section,
                    'adviser_name' => $adviserName,
                ];
                if ($db->table('students')->where('user_id', $id)->countAllResults() > 0) {
                    $db->table('students')->where('user_id', $id)->update($studentData);
                } else {
                    $studentData['user_id'] = $id;
                    $studentData['grade_level'] = null;
                    $db->table('students')->insert($studentData);
                }
            } elseif ($targetRole === 1) {
                $counselorData = [
                    'counselor_email' => $parentEmail,
                    'counselor_phone' => $parentPhone,
                ];
                if ($db->table('counselors')->where('user_id', $id)->countAllResults() > 0) {
                    $db->table('counselors')->where('user_id', $id)->update($counselorData);
                } else {
                    $counselorData['user_id'] = $id;
                    $db->table('counselors')->insert($counselorData);
                }
            } elseif ($targetRole === 2) {
                $adminData = [
                    'email' => $parentEmail,
                    'phone' => $parentPhone,
                ];
                if ($db->table('admins')->where('user_id', $id)->countAllResults() > 0) {
                    $db->table('admins')->where('user_id', $id)->update($adminData);
                } else {
                    $adminData['user_id'] = $id;
                    $db->table('admins')->insert($adminData);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed.');
            }

            $this->logAudit('UPDATE_USER', 'user', (int)$id);

            return redirect()->to('/admin/users')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            log_message('error', '[UserController::update] ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update user. Please check the database and try again.');
        }
    }

    /**
     * Delete a user.
     */
    public function delete($id)
    {
        if ((int) session()->get('role') !== 2) {
            return redirect()->to('/admin/dashboard')->with('error', 'Access denied.');
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
}
