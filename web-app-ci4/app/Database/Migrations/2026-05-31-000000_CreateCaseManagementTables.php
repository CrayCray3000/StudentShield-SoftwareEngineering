<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCaseManagementTables extends Migration
{
    public function up()
    {
        // counselor_notes
        if (!$this->db->tableExists('counselor_notes')) {
            $this->forge->addField([
                'id'           => ['type' => 'INT', 'auto_increment' => true],
                'student_id'   => ['type' => 'INT', 'null' => false],
                'counselor_id' => ['type' => 'INT', 'null' => false],
                'note_type'    => ['type' => 'ENUM', 'constraint' => ['observation', 'recommendation', 'intervention', 'follow_up', 'general'], 'default' => 'general'],
                'content'      => ['type' => 'TEXT', 'null' => false],
                'created_at'   => ['type' => 'DATETIME', 'null' => true],
                'updated_at'   => ['type' => 'DATETIME', 'null' => true],
                'deleted_at'   => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addForeignKey('student_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('counselor_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('counselor_notes');
        }

        // case_status_history
        if (!$this->db->tableExists('case_status_history')) {
            $this->forge->addField([
                'id'         => ['type' => 'INT', 'auto_increment' => true],
                'student_id' => ['type' => 'INT', 'null' => false],
                'status'     => ['type' => 'ENUM', 'constraint' => ['active_monitoring', 'intervention_ongoing', 'parent_consultation_required', 'escalated', 'under_investigation', 'resolved', 'closed'], 'null' => false],
                'remarks'    => ['type' => 'TEXT', 'null' => true],
                'changed_by' => ['type' => 'INT', 'null' => false],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addForeignKey('student_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('changed_by', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('case_status_history');
        }
    }

    public function down()
    {
        $this->forge->dropTable('case_status_history', true);
        $this->forge->dropTable('counselor_notes', true);
    }
}
