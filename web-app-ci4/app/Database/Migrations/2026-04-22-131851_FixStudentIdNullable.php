<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixStudentIdNullable extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        // 1. Update existing empty strings to NULL
        $db->table('users')->where('student_id', '')->update(['student_id' => null]);
        
        // 2. Modify column to be nullable (optional but good for insurance)
        $fields = [
            'student_id' => [
                'name'       => 'student_id',
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
        ];
        $this->forge->modifyColumn('users', $fields);
    }

    public function down()
    {
        // No easy way to revert NULL to empty string reliably if we want to keep it unique
    }
}
