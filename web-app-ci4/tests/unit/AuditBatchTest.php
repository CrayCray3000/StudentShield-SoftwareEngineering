<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\UserAgent;
use App\Controllers\AdminController;

final class AuditBatchTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $db = \Config\Database::connect();
        $prefix = $db->DBPrefix;

        // Ensure audit_logs table exists for test
        $db->query("CREATE TABLE IF NOT EXISTS {$prefix}audit_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INT NULL,
            action VARCHAR(50) NOT NULL,
            entity_type VARCHAR(50) NOT NULL,
            entity_id INT NOT NULL,
            ip_address VARCHAR(45) NULL,
            user_agent VARCHAR(255) NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        // Ensure vent_logs table exists
        $db->query("CREATE TABLE IF NOT EXISTS {$prefix}vent_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INT NULL,
            raw_text TEXT,
            deleted_at DATETIME NULL DEFAULT NULL
        )");

        // Ensure bully_reports table exists
        $db->query("CREATE TABLE IF NOT EXISTS {$prefix}bully_reports (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            incident_details TEXT,
            deleted_at DATETIME NULL DEFAULT NULL
        )");
    }

    public function testLogAuditBatchInsertsRowsCorrectly(): void
    {
        $db = \Config\Database::connect();
        $db->table('audit_logs')->emptyTable();

        $ids = [101, 102, 103, 104, 105];

        $request = $this->createMock(IncomingRequest::class);
        $request->method('getIPAddress')->willReturn('127.0.0.1');

        $userAgent = $this->createMock(UserAgent::class);
        $userAgent->method('getAgentString')->willReturn('PHPUnit Test Agent');
        $request->method('getUserAgent')->willReturn($userAgent);

        $controller = new AdminController();
        $controller->initController($request, response(), \Config\Services::logger());

        // Call reflection method logAuditBatch
        $refMethod = new \ReflectionMethod(AdminController::class, 'logAuditBatch');
        $refMethod->setAccessible(true);
        $refMethod->invoke($controller, 'BULK_RESTORE', 'vent', $ids);

        $count = $db->table('audit_logs')->countAllResults();
        $this->assertEquals(5, $count);

        $logs = $db->table('audit_logs')->get()->getResultArray();
        $this->assertEquals('BULK_RESTORE', $logs[0]['action']);
        $this->assertEquals('vent', $logs[0]['entity_type']);
        $this->assertEquals(101, $logs[0]['entity_id']);
        $this->assertEquals(105, $logs[4]['entity_id']);
    }

    public function testBenchmarkBatchVsSingleInserts(): void
    {
        $db = \Config\Database::connect();
        $db->table('audit_logs')->emptyTable();

        $ids = range(1, 100);

        $request = $this->createMock(IncomingRequest::class);
        $request->method('getIPAddress')->willReturn('127.0.0.1');

        $userAgent = $this->createMock(UserAgent::class);
        $userAgent->method('getAgentString')->willReturn('PHPUnit Test Agent');
        $request->method('getUserAgent')->willReturn($userAgent);

        $controller = new AdminController();
        $controller->initController($request, response(), \Config\Services::logger());

        $refSingle = new \ReflectionMethod(AdminController::class, 'logAudit');
        $refSingle->setAccessible(true);

        $refBatch = new \ReflectionMethod(AdminController::class, 'logAuditBatch');
        $refBatch->setAccessible(true);

        // Measure single loop inserts
        $startSingle = microtime(true);
        foreach ($ids as $id) {
            $refSingle->invoke($controller, 'BULK_RESTORE', 'vent', $id);
        }
        $timeSingle = microtime(true) - $startSingle;

        $db->table('audit_logs')->emptyTable();

        // Measure batch insert
        $startBatch = microtime(true);
        $refBatch->invoke($controller, 'BULK_RESTORE', 'vent', $ids);
        $timeBatch = microtime(true) - $startBatch;

        $this->assertLessThanOrEqual($timeSingle, $timeBatch);
        $this->assertEquals(100, $db->table('audit_logs')->countAllResults());
    }
}
