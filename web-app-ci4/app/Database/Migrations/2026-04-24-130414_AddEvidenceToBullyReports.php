<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEvidenceToBullyReports extends Migration
{
    public function up()
    {
        $this->forge->addColumn('bully_reports', [
            'evidence_file' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'incident_details',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('bully_reports', 'evidence_file');
    }
}
