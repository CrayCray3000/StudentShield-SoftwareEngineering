<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name'            => 'admin',
            'student_id'      => null,
            'role'            => 2, // Super Admin
            'passcode'        => password_hash('admin123', PASSWORD_BCRYPT),
        ];

        // Check if admin already exists
        $existing = $this->db->table('users')->where('name', 'admin')->get()->getRow();
        if (!$existing) {
            $this->db->table('users')->insert($data);
            echo "Admin account created successfully. Username: admin, Password: admin123\n";
        } else {
            echo "Admin account already exists.\n";
        }
    }
}
