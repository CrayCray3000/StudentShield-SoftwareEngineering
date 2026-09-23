<?php

use CodeIgniter\Test\CIUnitTestCase;

final class ReducedMotionTest extends CIUnitTestCase
{
    public function testReducedMotionCannotFreezeFormsAtTheirInvisibleFirstFrame(): void
    {
        // Guard the CSS policy behind the browser-reproduced opacity=0 failure.
        // Browser rendering is verified separately; this test needs no CDN or database.
        foreach (['login', 'admin_login', 'register', 'report_bullying'] as $view) {
            $source = file_get_contents(APPPATH . 'Views/' . $view . '.php');
            $this->assertMatchesRegularExpression('/@media\s*\(prefers-reduced-motion:\s*reduce\)/', $source, $view);
            $this->assertDoesNotMatchRegularExpression('/animation-play-state\s*:\s*paused/', $source,
                $view . ': pausing fadeInUp at its first frame leaves the form transparent.');
            $this->assertMatchesRegularExpression('/animation\s*:\s*none\s*!important/', $source, $view);
        }
    }
}
