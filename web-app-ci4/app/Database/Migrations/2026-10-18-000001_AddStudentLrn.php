<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
final class AddStudentLrn extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('lrn', 'students')) {
            $this->forge->addColumn('students', ['lrn' => ['type' => 'VARCHAR', 'constraint' => 12, 'null' => true]]);
            $this->forge->addUniqueKey('lrn', 'students_lrn_unique');
            $this->forge->processIndexes('students');
        }
    }
    public function down()
    {
        if ($this->db->fieldExists('lrn', 'students')) $this->forge->dropColumn('students', 'lrn');
    }
}
