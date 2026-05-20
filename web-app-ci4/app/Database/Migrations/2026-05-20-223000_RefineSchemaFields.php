<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RefineSchemaFields extends Migration
{
    public function up()
    {
        // 1. Rename users.passcode to users.password_hash
        $this->forge->modifyColumn('users', [
            'passcode' => [
                'name'       => 'password_hash',
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ]
        ]);

        // 2. Modify columns in vent_logs
        $this->forge->modifyColumn('vent_logs', [
            'risk_percent' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'status' => [
                'type'       => "ENUM('Pending', 'Reviewed', 'Flagged')",
                'default'    => 'Pending',
            ]
        ]);
    }

    public function down()
    {
        // 1. Rename users.password_hash back to users.passcode
        $this->forge->modifyColumn('users', [
            'password_hash' => [
                'name'       => 'passcode',
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ]
        ]);

        // 2. Revert columns in vent_logs
        $this->forge->modifyColumn('vent_logs', [
            'risk_percent' => [
                'type'       => 'FLOAT',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Pending',
            ]
        ]);
    }
}
