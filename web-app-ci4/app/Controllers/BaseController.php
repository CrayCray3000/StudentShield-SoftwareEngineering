<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    /**
     * Write an immutable audit log entry for compliance tracking.
     * Shared by AdminController and UserController.
     */
    protected function logAudit(string $action, string $entityType, int $entityId): void
    {
        try {
            $db = \Config\Database::connect();
            $request   = $this->request ?? \Config\Services::request();
            $ipAddress = $request ? $request->getIPAddress() : '127.0.0.1';
            $userAgent = ($request && $request->getUserAgent()) ? $request->getUserAgent()->getAgentString() : '';

            $db->table('audit_logs')->insert([
                'user_id'     => session()->get('user_id'),
                'action'      => $action,
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
                'ip_address'  => $ipAddress,
                'user_agent'  => $userAgent,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Audit log failed: ' . $e->getMessage());
        }
    }

    /**
     * Write multiple immutable audit log entries in a single batch insert operation.
     * Shared by AdminController and UserController.
     *
     * @param string $action
     * @param string $entityType
     * @param array<int|string> $entityIds
     */
    protected function logAuditBulk(string $action, string $entityType, array $entityIds): void
    {
        if (empty($entityIds)) {
            return;
        }

        try {
            $db = \Config\Database::connect();
            $request   = $this->request ?? \Config\Services::request();
            $userId    = session()->get('user_id');
            $ipAddress = $request ? $request->getIPAddress() : '127.0.0.1';
            $userAgent = ($request && $request->getUserAgent()) ? $request->getUserAgent()->getAgentString() : '';
            $createdAt = date('Y-m-d H:i:s');

            $rows = [];
            foreach ($entityIds as $entityId) {
                $rows[] = [
                    'user_id'     => $userId,
                    'action'      => $action,
                    'entity_type' => $entityType,
                    'entity_id'   => (int) $entityId,
                    'ip_address'  => $ipAddress,
                    'user_agent'  => $userAgent,
                    'created_at'  => $createdAt,
                ];
            }

            $db->table('audit_logs')->insertBatch($rows);
        } catch (\Exception $e) {
            log_message('error', 'Bulk audit log failed: ' . $e->getMessage());
        }
    }
}
