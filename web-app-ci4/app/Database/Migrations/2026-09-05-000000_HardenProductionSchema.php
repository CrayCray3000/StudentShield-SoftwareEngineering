<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class HardenProductionSchema extends Migration
{
    public function up()
    {
        $userFields = $this->db->getFieldNames('users');

        if (!in_array('is_active', $userFields, true)) {
            $this->forge->addColumn('users', [
                'is_active' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => false,
                    'after'      => 'remember_token',
                ],
            ]);
        } else {
            $this->db->query('ALTER TABLE users MODIFY COLUMN is_active TINYINT(1) NOT NULL DEFAULT 0');
        }

        if (!in_array('is_locked', $userFields, true)) {
            $this->forge->addColumn('users', [
                'is_locked' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => false,
                    'after'      => 'is_active',
                ],
            ]);
        }

        $this->db->query("ALTER TABLE bully_reports MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'pending_review'");

        if ($this->db->tableExists('virtual_meetings')) {
            $this->db->query('ALTER TABLE virtual_meetings MODIFY COLUMN counselor_id INT(11) NULL DEFAULT NULL');
            $this->db->query("ALTER TABLE virtual_meetings MODIFY COLUMN meet_link VARCHAR(255) NOT NULL DEFAULT ''");
            $this->db->query("ALTER TABLE virtual_meetings MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'requested'");
        }
    }

    public function down()
    {
        // Deliberately non-destructive. These compatibility columns and wider
        // status fields may already contain production data.
    }
}
