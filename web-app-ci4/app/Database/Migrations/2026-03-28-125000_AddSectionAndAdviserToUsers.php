<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSectionAndAdviserToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'section' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'adviser_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['section', 'adviser_name']);
    }
}
