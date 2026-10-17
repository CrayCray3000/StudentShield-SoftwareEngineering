<?php
namespace App\Controllers;

use App\Libraries\SchoolAnalytics;

class PrincipalController extends BaseController
{
    private function report(): array
    {
        $filters = SchoolAnalytics::filters($this->request->getGet());
        return (new SchoolAnalytics(\Config\Database::connect()))->summary($filters);
    }

    public function index()
    {
        if (!$this->hasRole(3)) return $this->forbidden();
        try {
            $report = $this->report();
            $options = (new SchoolAnalytics(\Config\Database::connect()))->options();
            return $this->response->setHeader('Cache-Control', 'private, no-store')->setBody(view('principal_dashboard', compact('report', 'options')));
        } catch (\InvalidArgumentException $e) {
            return $this->response->setStatusCode(422)->setContentType('text/plain')->setBody($e->getMessage());
        }
    }

    public function analytics()
    {
        if (!$this->hasRole(3)) return $this->forbidden();
        try { return $this->response->setHeader('Cache-Control', 'private, no-store')->setJSON($this->report()); }
        catch (\InvalidArgumentException $e) { return $this->response->setStatusCode(422)->setJSON(['message' => $e->getMessage()]); }
    }

    public function export()
    {
        if (!$this->hasRole(3)) return $this->forbidden();
        try { $report = $this->report(); }
        catch (\InvalidArgumentException $e) { return $this->response->setStatusCode(422)->setJSON(['message' => $e->getMessage()]); }
        $stream = fopen('php://temp', 'r+');
        $write = static function (array $row) use ($stream) {
            fputcsv($stream, array_map(static fn ($v) => preg_match('/^[=+@\-\t\r]/', (string) $v) ? "'" . $v : $v, $row));
        };
        $write(['StudentShield aggregate report', 'Value']);
        foreach ($report['filters'] as $key => $value) if ($key !== 'today') $write([$key, $value]);
        foreach ($report['metrics'] as $key => $value) $write([$key, $value]);
        $write(['previous_events', $report['previous_events']]);
        $write(['change_percent', $report['change_percent'] ?? 'No previous baseline']);
        foreach ($report['groups'] as $key => $rows) foreach ($rows as $row) $write([$key, $row['label'], $row['total']]);
        rewind($stream); $csv = stream_get_contents($stream); fclose($stream);
        $this->logAudit('EXPORT_AGGREGATE_REPORT', 'system', 0);
        return $this->response->setHeader('Cache-Control', 'private, no-store')->setHeader('Content-Disposition', 'attachment; filename="school-summary.csv"')->setContentType('text/csv')->setBody($csv);
    }
}
