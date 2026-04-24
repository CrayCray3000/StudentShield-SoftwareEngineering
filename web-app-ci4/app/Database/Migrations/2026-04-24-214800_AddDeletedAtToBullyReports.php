<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeletedAtToBullyReports extends Migration
{
    public function up()
    {
        $this->forge->addColumn('bully_reports', [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('bully_reports', 'deleted_at');
    }
}
