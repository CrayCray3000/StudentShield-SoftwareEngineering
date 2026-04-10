<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'entity_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'entity_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => true,
            ],
            'user_agent' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('audit_logs');
    }

    public function down()
    {
        $this->forge->dropTable('audit_logs');
    }
}
