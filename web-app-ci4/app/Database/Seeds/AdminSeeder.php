<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $userData = [
            'name'            => 'admin',
            'role'            => 2, // Super Admin
            'password_hash'   => password_hash('admin123', PASSWORD_BCRYPT),
        ];

        // Check if admin already exists
        $existing = $this->db->table('users')->where('name', 'admin')->get()->getRow();
        if (!$existing) {
            $this->db->table('users')->insert($userData);
            $newUserId = $this->db->insertID();
            
            $this->db->table('admins')->insert([
                'user_id' => $newUserId,
                'email'   => 'admin@studentshield.org',
                'phone'   => '09000000000'
            ]);
            echo "Admin account created successfully. Username: admin, Password: admin123\n";
        } else {
            echo "Admin account already exists.\n";
        }
    }
}
