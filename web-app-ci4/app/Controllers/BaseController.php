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
            $db->table('audit_logs')->insert([
                'user_id'     => session()->get('user_id'),
                'action'      => $action,
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
                'ip_address'  => $this->request->getIPAddress(),
                'user_agent'  => $this->request->getUserAgent()->getAgentString(),
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Audit log failed: ' . $e->getMessage());
        }
    }

    /**
     * Write batch immutable audit log entries for bulk operations.
     * Shared by AdminController and UserController.
     */
    protected function logAuditBulk(string $action, string $entityType, array $entityIds): void
    {
        if (empty($entityIds)) {
            return;
        }

        try {
            $db = \Config\Database::connect();
            $userId    = session()->get('user_id');
            $ipAddress = $this->request->getIPAddress();
            $userAgent = $this->request->getUserAgent()->getAgentString();
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
