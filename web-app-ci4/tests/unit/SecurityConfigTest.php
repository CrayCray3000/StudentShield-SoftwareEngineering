<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use Config\Filters;
use Config\Security;
use App\Controllers\VirtualMeetingController;

/**
 * @internal
 */
final class SecurityConfigTest extends CIUnitTestCase
{
    public function testCsrfFilterIsGloballyActive(): void
    {
        $filters = config(Filters::class);
        $this->assertContains('csrf', $filters->globals['before']);
    }

    public function testCsrfTokenDoesNotRegeneratePerAjaxRequest(): void
    {
        $security = config(Security::class);
        $this->assertFalse(
            $security->regenerate,
            'CSRF regeneration must be disabled so multiple AJAX calls in one page view do not invalidate tokens.'
        );
    }

    public function testDebugMeetingRouteAndMethodDoNotExist(): void
    {
        // 1. Ensure method was removed from VirtualMeetingController
        $this->assertFalse(
            method_exists(VirtualMeetingController::class, 'debugMeeting'),
            'debugMeeting() method must be permanently deleted.'
        );

        // 2. Ensure /debug-meeting route is not registered
        $routes = service('routes');
        $routesDefined = $routes->getRoutes();
        $this->assertArrayNotHasKey('debug-meeting', $routesDefined);
    }

    public function testToolbarIsNotInRequiredAfterFilters(): void
    {
        $filters = config(Filters::class);
        $this->assertNotContains(
            'toolbar',
            $filters->required['after'],
            'DebugToolbar must not be present in $required[\'after\'] filters.'
        );
    }
}
