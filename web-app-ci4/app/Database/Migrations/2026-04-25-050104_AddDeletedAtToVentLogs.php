<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToVentLogs extends Migration
{
    public function up()
    {
        $this->forge->addColumn('vent_logs', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('vent_logs', 'deleted_at');
    }
}
