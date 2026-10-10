<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

class AuditBatchTest extends CIUnitTestCase
{
    public function testBatchInsertVsSingleInsert()
    {
        $db = \Config\Database::connect();
        $auditTable = $db->prefixTable('audit_logs');
        $bullyTable = $db->prefixTable('bully_reports');

        // Ensure tables exist in SQLite memory DB for testing/benchmarking
        $db->query("CREATE TABLE IF NOT EXISTS {$auditTable} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INT NOT NULL,
            action VARCHAR(50) NOT NULL,
            entity_type VARCHAR(50) NOT NULL,
            entity_id INT NOT NULL,
            ip_address VARCHAR(45) NULL,
            user_agent VARCHAR(255) NULL,
            created_at DATETIME
        )");

        $db->query("CREATE TABLE IF NOT EXISTS {$bullyTable} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            deleted_at DATETIME NULL
        )");

        $ids = range(1, 100);

        // 1. Measure Baseline (Single inserts in loop)
        $startOld = microtime(true);
        foreach ($ids as $id) {
            $db->table('audit_logs')->insert([
                'user_id'     => 1,
                'action'      => 'BULK_RESTORE',
                'entity_type' => 'incident',
                'entity_id'   => (int) $id,
                'ip_address'  => '127.0.0.1',
                'user_agent'  => 'CLI',
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        }
        $timeOld = microtime(true) - $startOld;

        $oldCount = $db->table('audit_logs')->countAllResults();
        $this->assertEquals(100, $oldCount);

        // Reset table
        $db->table('audit_logs')->emptyTable();

        // 2. Measure Optimized (Batch insert)
        $startNew = microtime(true);
        $batch = [];
        $now = date('Y-m-d H:i:s');
        foreach ($ids as $id) {
            $batch[] = [
                'user_id'     => 1,
                'action'      => 'BULK_RESTORE',
                'entity_type' => 'incident',
                'entity_id'   => (int) $id,
                'ip_address'  => '127.0.0.1',
                'user_agent'  => 'CLI',
                'created_at'  => $now,
            ];
        }
        $db->table('audit_logs')->insertBatch($batch);
        $timeNew = microtime(true) - $startNew;

        $newCount = $db->table('audit_logs')->countAllResults();
        $this->assertEquals(100, $newCount);

        $this->assertLessThan($timeOld, $timeNew, "Batch insert should be faster than single inserts loop");
    }
}
