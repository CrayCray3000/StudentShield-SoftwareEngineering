<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTrajectoryToVentLogs extends Migration
{
    public function up()
    {
        $this->forge->addColumn('vent_logs', [
            'trajectory' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
                'default'    => null,
                'after'      => 'risk_level',
            ],
            'escalation_probability' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'default'    => null,
                'after'      => 'trajectory',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('vent_logs', ['trajectory', 'escalation_probability']);
    }
}
