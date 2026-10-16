<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class AuditLogBulkTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        if (!$db->tableExists('audit_logs')) {
            $forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'user_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
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
            $forge->addKey('id', true);
            $forge->createTable('audit_logs', true);
        }
    }

    public function testLogAuditBulkInsertsCorrectRecords(): void
    {
        $db = \Config\Database::connect();
        $db->table('audit_logs')->truncate();

        $ids = [101, 102, 103, 104, 105];

        $controller = new class extends \App\Controllers\BaseController {
            public function testBulk(string $action, string $entityType, array $ids): void
            {
                $this->logAuditBulk($action, $entityType, $ids);
            }

            public function testSingle(string $action, string $entityType, int $id): void
            {
                $this->logAudit($action, $entityType, $id);
            }
        };

        $controller->testBulk('BULK_SOFT_DELETE', 'incident', $ids);

        $logs = $db->table('audit_logs')->where('action', 'BULK_SOFT_DELETE')->get()->getResultArray();
        $this->assertCount(5, $logs);

        $loggedIds = array_column($logs, 'entity_id');
        $this->assertEquals([101, 102, 103, 104, 105], array_map('intval', $loggedIds));
    }

    public function testLogAuditBulkPerformanceBenchmark(): void
    {
        $db = \Config\Database::connect();

        $sampleSize = 100;
        $ids = range(1, $sampleSize);

        $controller = new class extends \App\Controllers\BaseController {
            public function runLoopLog(array $ids): void
            {
                foreach ($ids as $id) {
                    $this->logAudit('LOOP_TEST', 'incident', (int) $id);
                }
            }

            public function runBulkLog(array $ids): void
            {
                $this->logAuditBulk('BULK_TEST', 'incident', $ids);
            }
        };

        // Measure Loop duration
        $startLoop = microtime(true);
        $controller->runLoopLog($ids);
        $loopDuration = microtime(true) - $startLoop;

        // Measure Bulk duration
        $startBulk = microtime(true);
        $controller->runBulkLog($ids);
        $bulkDuration = microtime(true) - $startBulk;

        // Verify count
        $loopCount = $db->table('audit_logs')->where('action', 'LOOP_TEST')->countAllResults();
        $bulkCount = $db->table('audit_logs')->where('action', 'BULK_TEST')->countAllResults();

        $this->assertEquals($sampleSize, $loopCount);
        $this->assertEquals($sampleSize, $bulkCount);

        fwrite(STDERR, sprintf(
            "\n[Benchmark] %d records | Loop logAudit: %.6f s | Batch logAuditBulk: %.6f s | Speedup: %.2fx\n",
            $sampleSize,
            $loopDuration,
            $bulkDuration,
            $bulkDuration > 0 ? $loopDuration / $bulkDuration : 0
        ));

        $this->assertTrue(true);
    }
}
