<?php
namespace App\Controllers;

class UserController extends BaseController
{
    public function index()
    {
        if (!$this->hasRole(2)) return $this->forbidden();
        // Account identifiers only; student profiles and guardian contacts are counselor-only.
        $users = \Config\Database::connect()->table('users')->select("id, CASE WHEN role = 0 THEN NULL ELSE name END AS name, role, access_disabled, registration_status, created_at", false)
            ->orderBy('role', 'DESC')->orderBy('name')->get()->getResultArray();
        return view('manage_accounts', ['users' => $users]);
    }

    public function create()
    {
        if (!$this->hasRole(2)) return $this->forbidden();
        return $this->save();
    }

    public function update($id)
    {
        if (!$this->hasRole(2)) return $this->forbidden();
        return $this->save((int) $id);
    }

    private function save(?int $id = null)
    {
        $db = \Config\Database::connect();
        $old = $id ? $db->table('users')->where('id', $id)->get()->getRowArray() : null;
        if ($id && !$old) return $this->response->setStatusCode(404);
        $name = trim((string) $this->request->getPost('name'));
        if ($old && (int) $old['role'] === 0) $name = $old['name'];
        $rawRole = (string) $this->request->getPost('role');
        $password = (string) $this->request->getPost('passcode');
        $email = trim((string) $this->request->getPost('email'));
        if (!in_array($rawRole, ['0', '1', '2', '3'], true) || $name === '' || mb_strlen($name) > 255
            || ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL))) {
            return redirect()->to('/admin/users')->with('error', 'Enter a valid account name, role and staff email.');
        }
        if (($id === null || $password !== '') && strlen($password) < 8) {
            return redirect()->to('/admin/users')->with('error', 'Use a password of at least 8 characters.');
        }
        $role = (int) $rawRole;
        if ($id === (int) session()->get('user_id') && $role !== 2) return redirect()->to('/admin/users')->with('error', 'You cannot demote your own account.');
        $duplicate = $db->table('users')->where('name', $name);
        if ($id) $duplicate->where('id !=', $id);
        if ($duplicate->countAllResults()) return redirect()->to('/admin/users')->with('error', 'Account name already exists.');
        $data = ['name' => $name, 'role' => $role, 'remember_token' => null,
            'auth_version' => (int) ($old['auth_version'] ?? 0) + 1, 'updated_at' => date('Y-m-d H:i:s')];
        if ($password !== '') $data['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        if (!$old || ((int) $old['role'] !== $role && $role === 0)) {
            $data['registration_status'] = $role === 0 ? 'pending' : 'approved';
            $data['is_active'] = $role === 0 ? 0 : 1;
        }
        try {
            $db->transStart();
            if ($id) $db->table('users')->where('id', $id)->update($data);
            else { $db->table('users')->insert($data); $id = (int) $db->insertID(); }
            if ($role === 0) {
                if (!$db->table('students')->where('user_id', $id)->countAllResults()) $db->table('students')->insert(['user_id' => $id, 'student_id' => 'SS-' . $id]);
            } else {
                $table = $role === 1 ? 'counselors' : 'admins';
                $contacts = $role === 1 ? ['counselor_email' => $email] : ['email' => $email];
                if ($db->table($table)->where('user_id', $id)->countAllResults()) {
                    if ($email !== '') $db->table($table)->where('user_id', $id)->update($contacts);
                }
                else $db->table($table)->insert(['user_id' => $id] + $contacts);
            }
            // Role changes retain existing student history; this screen never reads profile fields.
            $db->transComplete();
            if (!$db->transStatus()) throw new \RuntimeException('Account transaction failed');
            $this->logAudit(!$old ? 'CREATE_USER' : ((int) $old['role'] !== $role ? 'ROLE_CHANGED' : ($password !== '' ? 'PASSWORD_RESET' : 'UPDATE_USER')), 'user', $id);
            if ($id === (int) session()->get('user_id')) session()->set('auth_version', $data['auth_version']);
            return redirect()->to('/admin/users')->with('success', 'Account saved. Student accounts require counselor approval.');
        } catch (\Throwable $e) {
            log_message('error', 'Account update failed: {message}', ['message' => $e->getMessage()]);
            return redirect()->to('/admin/users')->with('error', 'Account could not be saved.');
        }
    }

    public function access($id)
    {
        if (!$this->hasRole(2)) return $this->forbidden();
        if ((int) $id === (int) session()->get('user_id')) return $this->response->setStatusCode(409)->setJSON(['message' => 'You cannot disable your own account.']);
        $disabled = $this->request->getPost('disabled');
        if (!in_array($disabled, ['0', '1'], true)) return $this->response->setStatusCode(422);
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id', (int) $id)->get()->getRowArray();
        if (!$user) return $this->response->setStatusCode(404);
        // Enabling system access never approves a pending or rejected student registration.
        $db->table('users')->where('id', (int) $id)->update(['access_disabled' => (int) $disabled,
            'remember_token' => null, 'auth_version' => (int) $user['auth_version'] + 1]);
        $this->logAudit($disabled === '1' ? 'ACCOUNT_DISABLED' : 'ACCOUNT_ENABLED', 'user', (int) $id);
        return redirect()->to('/admin/users')->with('success', 'Account access updated.');
    }

    public function delete($id)
    {
        if (!$this->hasRole(2)) return $this->forbidden();
        return $this->response->setStatusCode(405)->setJSON(['message' => 'Use account deactivation to retain records.']);
    }

    public function unlock($id)
    {
        if (!$this->hasRole(2)) return $this->forbidden();
        \Config\Database::connect()->table('users')->where('id', (int) $id)->update(['is_locked' => 0]);
        $this->logAudit('UNLOCK_ACCOUNT', 'user', (int) $id);
        return redirect()->to('/admin/users')->with('success', 'Account reporting lock cleared.');
    }
}
