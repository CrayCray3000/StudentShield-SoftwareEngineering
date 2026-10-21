<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

final class DailyTrendChartTest extends CIUnitTestCase
{
    public function testClusteredEventDaysDoNotClusterAxisLabels(): void
    {
        $rows = [];
        for ($i = 0; $i < 30; $i++) {
            $rows[] = ['label' => date('Y-m-d', strtotime('2026-09-23 +' . $i . ' days')), 'total' => in_array($i, [1, 10, 11, 24, 28], true) ? 1 : 0];
        }
        $html = view('partials/daily_trend_chart', ['trendRows' => $rows]);
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);
        $dates = $xpath->query('//div[@class="daily-trend-dates"]/span');
        $this->assertCount(5, $dates);
        $this->assertSame('Sep 23', $dates->item(0)->textContent);
        $this->assertSame('Oct 22', $dates->item(4)->textContent);
        $previous = -100;
        foreach ($dates as $date) {
            preg_match('/left:([\d.]+)%/', $date->getAttribute('style'), $match);
            $position = (float) $match[1];
            $this->assertGreaterThanOrEqual(20, $position - $previous);
            $previous = $position;
        }
        $this->assertCount(30, $xpath->query('//select[@data-trend-day]/option'));
    }
}
