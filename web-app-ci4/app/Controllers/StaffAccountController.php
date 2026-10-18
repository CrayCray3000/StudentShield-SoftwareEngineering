<?php
namespace App\Controllers;

use App\Libraries\RoleAccess;
use Config\Database;

class StaffAccountController extends BaseController
{
    private function allowed(): bool
    {
        return session()->get('isLoggedIn') && in_array((int) session()->get('role'), [1, 2, 3], true);
    }
    public function settings() { if (!$this->allowed()) return $this->forbidden(); return view('staff_preferences'); }
    public function profile() { return $this->page('profile'); }
    private function page(string $page)
    {
        if (!$this->allowed()) return $this->forbidden();
        $user = Database::connect()->table('users')->select('id, name, role')->where('id', (int) session()->get('user_id'))->get()->getRowArray();
        if (!$user) return $this->forbidden();
        return view('staff_account', ['page' => $page, 'account' => $user, 'roleName' => RoleAccess::NAMES[(int) $user['role']]]);
    }
}
