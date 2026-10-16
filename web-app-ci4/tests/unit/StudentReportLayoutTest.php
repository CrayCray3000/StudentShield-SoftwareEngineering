<?php

use CodeIgniter\Test\CIUnitTestCase;

final class StudentReportLayoutTest extends CIUnitTestCase
{
    public function testReportLayoutPreservesNativeUploadAndReporterContracts(): void
    {
        helper(['url', 'form']);
        $dom = new DOMDocument();
        @$dom->loadHTML(view('reports/bullying_form'));
        $xpath = new DOMXPath($dom);

        $this->assertSame(1, $xpath->query('//form[@id="bullyingForm"][@method="POST"][@enctype="multipart/form-data"][contains(@action,"report-bullying/submit")]')->length);
        $this->assertSame(1, $xpath->query('//form[@id="bullyingForm"]//input[@name="' . csrf_token() . '"]')->length);
        $this->assertSame(1, $xpath->query('//input[@name="reporter_type"][@value="self"]')->length);
        $this->assertSame(1, $xpath->query('//button[@data-role="self"][@aria-pressed="true"]')->length);
        $this->assertSame(1, $xpath->query('//button[@data-role="bystander"][@aria-pressed="false"]')->length);
        $this->assertSame(1, $xpath->query('//input[@name="victim_name"][not(@required)]')->length);
        $this->assertSame(1, $xpath->query('//input[@name="bully_name"][@required]')->length);
        $this->assertSame(1, $xpath->query('//textarea[@name="incident_details"][@maxlength="2000"][@required]')->length);
        $this->assertSame(1, $xpath->query('//input[@name="evidence_file"][@type="file"][@accept="image/*,video/*"]')->length);
        $this->assertSame(1, $xpath->query('//button[@id="saveDraftBtn"][@type="button"]')->length);
        $this->assertSame(1, $xpath->query('//form[contains(@action,"/logout")]')->length);
        $this->assertSame(1, $xpath->query('//nav//a[contains(@href,"student/vent")]')->length);
    }
}
