<?php

use CodeIgniter\Test\CIUnitTestCase;

final class StaffPagesTest extends CIUnitTestCase
{
    public function testNonStaffCannotOpenAWorkspaceBeforeAnyDatabaseAccess(): void
    {
        foreach ([null, 0] as $role) {
            session()->set(['isLoggedIn' => $role !== null, 'role' => $role]);
            $response = (new \App\Controllers\AdminController())->dashboard('sessions');
            $this->assertStringEndsWith('/admin/login', $response->getHeaderLine('Location'));
        }
        session()->remove(['isLoggedIn', 'role']);
    }

    public function testUnknownWorkspaceIsRejectedBeforeAnyDatabaseAccess(): void
    {
        session()->set(['isLoggedIn' => true, 'role' => 2]);
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);
        try {
            (new \App\Controllers\AdminController())->dashboard('unknown');
        } finally {
            session()->remove(['isLoggedIn', 'role']);
        }
    }

    public function testEachStaffPageOnlyRendersItsOwnWorkspace(): void
    {
        helper(['url', 'form']);
        session()->set(['isLoggedIn' => true, 'role' => 2, 'user_id' => 1]);
        $sections = ['vents' => 'section-vents', 'incidents' => 'section-incidents',
            'sessions' => 'section-sessions', 'alerts' => 'section-alerts', 'analytics' => 'section-charts'];

        foreach ($sections as $page => $section) {
            $html = view('admin_dashboard', ['dashboard_page' => $page, 'pending_meetings_count' => 0]);
            $dom = new DOMDocument();
            @$dom->loadHTML($html);
            $xpath = new DOMXPath($dom);
            foreach ($sections as $candidate) {
                $this->assertSame($candidate === $section ? 1 : 0,
                    $xpath->query('//*[@id="' . $candidate . '"]')->length, $page . ': ' . $candidate);
            }
            $current = $xpath->query('//nav[@aria-label="Staff pages"]/a[@aria-current="page"]');
            $this->assertCount(1, $current);
            $this->assertStringEndsWith('/admin/' . $page, $current->item(0)->getAttribute('href'));
            $this->assertSame(1, $xpath->query('//header//form[contains(@action,"/logout")]')->length);
            $this->assertSame(0, $xpath->query('//*[@id="pauseUpdatesToggle"]')->length);
            $this->assertStringNotContainsString('href="#section-', $html);
        }
        session()->remove(['isLoggedIn', 'role', 'user_id']);
    }

    public function testCounselorMenuDoesNotExposeSuperAdminLinks(): void
    {
        helper(['url', 'form']);
        session()->set(['isLoggedIn' => true, 'role' => 1, 'user_id' => 1]);
        $html = view('admin_dashboard', ['dashboard_page' => 'sessions', 'pending_meetings_count' => 1]);
        $this->assertStringNotContainsString('href="/admin/users"', $html);
        $this->assertStringNotContainsString('href="/admin/audit-logs"', $html);
        $this->assertStringContainsString('/admin/pending-registrations', $html);
        session()->remove(['isLoggedIn', 'role', 'user_id']);
    }
}
