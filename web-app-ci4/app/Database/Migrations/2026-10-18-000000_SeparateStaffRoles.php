<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeparateStaffRoles extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('registration_status', 'users')) {
            $this->forge->addColumn('users', ['registration_status' => ['type' => 'VARCHAR', 'constraint' => 12, 'default' => 'approved']]);
            $this->db->table('users')->where('role', 0)->where('is_active', 0)->update(['registration_status' => 'pending']);
        }
        if (!$this->db->fieldExists('access_disabled', 'users')) {
            $this->forge->addColumn('users', ['access_disabled' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0]]);
        }
        if (!$this->db->fieldExists('auth_version', 'users')) {
            $this->forge->addColumn('users', ['auth_version' => ['type' => 'INT', 'default' => 1]]);
        }
        // Legacy staff is_active defaults were not enforced. access_disabled provides explicit revocation.
        // Principal uses the existing admins contact table; no student profile is created.
    }

    public function down()
    {
        // Keep recorded decisions and disabled-account state when rolling back code.
    }
}
