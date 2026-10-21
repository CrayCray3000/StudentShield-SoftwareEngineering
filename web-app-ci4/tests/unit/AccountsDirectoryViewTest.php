<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

final class AccountsDirectoryViewTest extends CIUnitTestCase
{
    public function testDirectoryEscapesNamesAndPreservesProtectedAccountForms(): void
    {
        helper(['url', 'form']);
        session()->set(['role' => 2, 'user_id' => 2, 'name' => 'Preview admin']);
        $html = view('manage_accounts', ['users' => [
            ['id' => 2, 'name' => 'Own admin', 'role' => 2, 'access_disabled' => 0],
            ['id' => 5, 'name' => '<img src=x onerror=alert(1)>', 'role' => 0, 'access_disabled' => 0, 'registration_status' => 'approved', 'lrn_suffix' => '0041'],
        ]]);
        $this->assertStringNotContainsString('<img src=x', $html);
        $this->assertStringContainsString('&lt;img', $html);
        $this->assertStringContainsString('admin/users/update/5', $html);
        $this->assertStringContainsString('admin/users/access/5', $html);
        $this->assertStringNotContainsString('admin/users/access/2', $html);
        $this->assertStringContainsString('••••••••0041', $html);
        $this->assertStringContainsString('name="' . csrf_token() . '"', $html);
        $this->assertStringContainsString('data-role-filter="0"', $html);
        $this->assertStringContainsString('id="accountInspector"', $html);
        session()->destroy();
    }
}
