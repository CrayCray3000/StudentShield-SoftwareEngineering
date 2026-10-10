<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * Benchmark and correctness tests for Audit Log operations.
 *
 * @internal
 */
final class AuditLogTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure audit_logs table exists in the test database
        $db = \Config\Database::connect();
        if (!$db->tableExists('audit_logs')) {
            $forge = \Config\Database::forge();
            $forge->addField([
                'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'user_id'     => ['type' => 'INT', 'constraint' => 11, 'null' => true],
                'action'      => ['type' => 'VARCHAR', 'constraint' => '50'],
                'entity_type' => ['type' => 'VARCHAR', 'constraint' => '50'],
                'entity_id'   => ['type' => 'INT', 'constraint' => 11],
                'ip_address'  => ['type' => 'VARCHAR', 'constraint' => '45', 'null' => true],
                'user_agent'  => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
                'created_at'  => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addKey('id', true);
            $forge->createTable('audit_logs', true);
        }
    }

    public function testSingleInsertVsBulkInsertPerformance(): void
    {
        $db = \Config\Database::connect();
        $sampleIds = range(1, 100);

        // Benchmark sequential single inserts (baseline)
        $startSingle = microtime(true);
        foreach ($sampleIds as $id) {
            $db->table('audit_logs')->insert([
                'user_id'     => 1,
                'action'      => 'BULK_SOFT_DELETE',
                'entity_type' => 'vent',
                'entity_id'   => (int) $id,
                'ip_address'  => '127.0.0.1',
                'user_agent'  => 'CLI Benchmark',
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        }
        $singleDuration = microtime(true) - $startSingle;

        // Clear audit_logs for bulk benchmark
        $db->table('audit_logs')->emptyTable();

        // Benchmark bulk insert (optimized)
        $startBulk = microtime(true);
        $rows = [];
        $now = date('Y-m-d H:i:s');
        foreach ($sampleIds as $id) {
            $rows[] = [
                'user_id'     => 1,
                'action'      => 'BULK_SOFT_DELETE',
                'entity_type' => 'vent',
                'entity_id'   => (int) $id,
                'ip_address'  => '127.0.0.1',
                'user_agent'  => 'CLI Benchmark',
                'created_at'  => $now,
            ];
        }
        $db->table('audit_logs')->insertBatch($rows);
        $bulkDuration = microtime(true) - $startBulk;

        // Verify count of records
        $count = $db->table('audit_logs')->countAllResults();
        $this->assertEquals(100, $count);
        $this->assertGreaterThan(0, $singleDuration);
        $this->assertGreaterThan(0, $bulkDuration);
    }
}
