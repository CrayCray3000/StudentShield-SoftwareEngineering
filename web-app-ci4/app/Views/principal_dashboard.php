<?php $f = $report['filters']; $m = $report['metrics']; $groups = $report['groups']; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>StudentShield · Principal overview</title>
<script src="https://cdn.tailwindcss.com"></script>
<?= view('partials/school_brand') ?>
<link rel="stylesheet" href="<?= base_url('css/staff-layout.css?v=' . filemtime(FCPATH . 'css/staff-layout.css')) ?>"><link rel="stylesheet" href="<?= base_url('css/role-workspaces.css?v=' . filemtime(FCPATH . 'css/role-workspaces.css')) ?>">
</head><body class="school-staff-page staff-layout role-workspace">
<?= view('partials/staff_navigation', ['staffPage' => 'principal', 'staffPageLabel' => 'School overview']) ?>
<main id="staffMain" class="staff-main role-main principal-workspace">
<header><h1>School overview</h1><p>School-level trends for administrative planning. <?= esc($f['start']) ?> – <?= esc($f['end']) ?> · Philippine time</p></header>
<details class="analytics-filter-panel" id="analyticsFilterPanel"><summary>Refine report <span><?= esc($f['start']) ?> – <?= esc($f['end']) ?></span></summary>
<form method="get" class="role-panel analytics-filters" aria-label="Analytics filters">
<label>Period<select name="period" id="analyticsPeriod"><?php foreach (['today' => 'Today', 'yesterday' => 'Yesterday', 'last7' => 'Last 7 days', 'last30' => 'Last 30 days', 'week' => 'This week', 'month' => 'This month', 'custom' => 'Custom date range'] as $value => $label): ?><option value="<?= $value ?>" <?= $f['period'] === $value ? 'selected' : '' ?>><?= esc($label) ?></option><?php endforeach; ?></select></label>
<label>Start date<input type="date" name="start" value="<?= esc($f['start'], 'attr') ?>" max="<?= esc($f['today'], 'attr') ?>"></label>
<label>End date<input type="date" name="end" value="<?= esc($f['end'], 'attr') ?>" max="<?= esc($f['today'], 'attr') ?>"></label>
<label>Grade level<select name="grade"><option value="">All grades</option><?php foreach ($options['grades'] as $row): ?><option value="<?= esc($row['label'], 'attr') ?>" <?= $f['grade'] === $row['label'] ? 'selected' : '' ?>><?= esc($row['label']) ?></option><?php endforeach; ?></select></label>
<label>Section<select name="section"><option value="">All sections</option><?php foreach ($options['sections'] as $row): ?><option value="<?= esc($row['label'], 'attr') ?>" <?= $f['section'] === $row['label'] ? 'selected' : '' ?>><?= esc($row['label']) ?></option><?php endforeach; ?></select></label>
<label>Event type<select name="category"><?php foreach (['all' => 'All event types', 'Safe Space' => 'Safe Space', 'Bullying' => 'Bullying reports'] as $value => $label): ?><option value="<?= esc($value, 'attr') ?>" <?= $f['category'] === $value ? 'selected' : '' ?>><?= esc($label) ?></option><?php endforeach; ?></select></label>
<label>Risk classification<select name="risk"><?php foreach (['all' => 'All classifications', 'LOW' => 'Low', 'MEDIUM' => 'Medium', 'HIGH' => 'High', 'CRITICAL' => 'Critical'] as $value => $label): ?><option value="<?= $value ?>" <?= $f['risk'] === $value ? 'selected' : '' ?>><?= $label ?></option><?php endforeach; ?></select></label>
<div class="analytics-actions"><button type="submit">Apply filters</button><a class="role-button secondary" href="<?= base_url('principal/dashboard') ?>">Reset filters</a><a class="role-button secondary" href="<?= base_url('principal/reports/export?' . http_build_query(array_diff_key($f, ['today' => true]))) ?>"><svg style="width:16px;height:16px;margin-right:6px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>Export filtered summary</a></div>
</form></details>
<details class="analytics-counting-note"><summary>How these numbers are counted</summary><p>All summaries use the selected filters. Registered and active counts refer to accounts registered during this period; event and risk filters further limit those counts to accounts with matching events. Today/week/month counts are intersected with your selected period. Active means approved and enabled, not currently online.</p></details>
<section class="metric-grid" aria-label="Filtered key metrics">
<?php foreach (['registered' => 'Students registered in period', 'active' => 'Active student accounts', 'events' => 'Events in selected period', 'attention' => 'Students requiring counselor attention', 'today_count' => 'Events today', 'week_count' => 'Events this week', 'month_count' => 'Events this month'] as $key => $label): ?><div class="metric"><span><?= esc($label) ?></span><strong><?= number_format($m[$key]) ?></strong></div><?php endforeach; ?>
<div class="metric"><span>Compared with previous <?= count($groups['trend']) ?> days</span><strong style="color: <?= $report['change_percent'] > 0 ? '#b91c1c' : ($report['change_percent'] < 0 ? '#0b7533' : '#61756b') ?>;"><?= $report['change_percent'] === null ? '—' : esc(($report['change_percent'] > 0 ? '+' : '') . $report['change_percent'] . '%') ?></strong><span><?= (int) $report['previous_events'] ?> previous events<?= $report['change_percent'] === null ? ' · No baseline' : '' ?></span></div>
</section>
<details class="analytics-counting-note"><summary>Event and attention definitions</summary><p>Attention means an unreviewed High or Critical Safe Space entry. Bullying reports are counted once, grouped by the linked victim’s grade/section, or the reporter’s when no victim account is linked. Archived records are excluded.</p></details>
<div class="analytics-charts"><section class="role-panel" aria-labelledby="trendTitle"><h2 id="trendTitle">Events over time</h2>
<?php $max = max(1, ...array_column($groups['trend'], 'total')); $last = max(1, count($groups['trend']) - 1); $points = []; foreach ($groups['trend'] as $i => $row) $points[] = round(10 + $i * 980 / $last, 2) . ',' . round(145 - $row['total'] * 130 / $max, 2); $areaPoints = "10,145 " . implode(' ', $points) . " 990,145"; ?>
<svg class="trend-chart" viewBox="0 0 1000 160" role="img" aria-label="Daily event counts. Accessible values are in the table below." preserveAspectRatio="none"><defs><linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#0f8a3a" stop-opacity="0.22"/><stop offset="100%" stop-color="#0f8a3a" stop-opacity="0.01"/></linearGradient></defs><line x1="10" y1="15" x2="990" y2="15" stroke="#edf3ef" stroke-dasharray="4 4" stroke-width="1"/><line x1="10" y1="80" x2="990" y2="80" stroke="#edf3ef" stroke-dasharray="4 4" stroke-width="1"/><path d="M10 145H990" stroke="#d8e3db" stroke-width="1.5"/><polygon points="<?= esc($areaPoints, 'attr') ?>" fill="url(#chartGradient)"/><polyline points="<?= esc(implode(' ', $points), 'attr') ?>" fill="none" stroke="#0f8a3a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke"/><?php foreach ($groups['trend'] as $i => $row): if ($row['total'] > 0): ?><circle cx="<?= round(10 + $i * 980 / $last, 2) ?>" cy="<?= round(145 - $row['total'] * 130 / $max, 2) ?>" r="4.5" fill="#0f8a3a" stroke="#ffffff" stroke-width="2"/><?php endif; endforeach; ?></svg>
<div class="chart-dates"><span><?= esc($f['start']) ?></span><span>Daily events &middot; maximum <?= (int) $max ?></span><span><?= esc($f['end']) ?></span></div><details><summary>View daily counts</summary><div class="aggregate-scroll"><table class="aggregate-table"><thead><tr><th>Date</th><th>Events</th></tr></thead><tbody><?php foreach ($groups['trend'] as $row): ?><tr><td><?= esc($row['label']) ?></td><td><?= $row['total'] ?></td></tr><?php endforeach; ?></tbody></table></div></details>
</section>
<?php $chartGroups = ['risk' => 'Safe Space risk distribution']; ?>
<?php foreach ($chartGroups as $key => $label): ?>
<section class="role-panel"><h2><?= esc($label) ?></h2>
<?php if (!$groups[$key]): ?><p>No matching events in this period.</p><?php else: $max = max(1, ...array_column($groups[$key], 'total')); ?>
<?php foreach ($groups[$key] as $row): ?><div class="bar-row <?= $key === 'risk' ? 'risk-' . esc($row['label'], 'attr') : '' ?>"><span><?= esc($row['label'] ?: 'Not classified') ?></span><div class="bar-track" aria-hidden="true"><div class="bar-fill" style="width:<?= round($row['total'] * 100 / $max, 2) ?>%"></div></div><strong><?= $row['total'] ?></strong></div><?php endforeach; endif; ?>
<?php if ($key === 'risk'): ?>
<div class="risk-guide" aria-label="Risk level guide"><h3>Understanding risk levels</h3>
<dl>
<div><dt><span class="risk-key low"></span>Low <span>1–30%</span></dt><dd>Lower screening concern; routine review.</dd></div>
<div><dt><span class="risk-key medium"></span>Medium <span>31–60%</span></dt><dd>Signs of distress; counselor follow-up may be needed.</dd></div>
<div><dt><span class="risk-key high"></span>High <span>61–85%</span></dt><dd>Elevated concern; prioritized counselor triage.</dd></div>
<div><dt><span class="risk-key critical"></span>Critical <span>86–100%</span></dt><dd>Urgent concern; immediate counselor and eligible guardian notification attempts.</dd></div>
</dl><p>These are system screening bands, not diagnoses or probabilities of harm. Counselor review determines the response. Bullying reports do not receive these risk levels.</p></div>
<?php endif; ?>
</section><?php endforeach; ?></div>
<div class="analytics-grid">
<?php $chartGroups = ['category' => 'Events by category', 'grade' => 'Events by grade level', 'section' => 'Events by section']; ?>
<?php foreach ($chartGroups as $key => $label): ?>
<section class="role-panel"><h2><?= esc($label) ?></h2>
<?php if (!$groups[$key]): ?><p>No matching events in this period.</p><?php else: $max = max(1, ...array_column($groups[$key], 'total')); ?>
<?php foreach ($groups[$key] as $row): ?><div class="bar-row <?= $key === 'risk' ? 'risk-' . esc($row['label'], 'attr') : '' ?>"><span><?= esc($row['label'] ?: 'Not classified') ?></span><div class="bar-track" aria-hidden="true"><div class="bar-fill" style="width:<?= round($row['total'] * 100 / $max, 2) ?>%"></div></div><strong><?= $row['total'] ?></strong></div><?php endforeach; endif; ?>
<?php if ($key === 'risk'): ?><p>Risk classifications apply to Safe Space entries. Bullying reports are not assigned these risk levels.</p><?php endif; ?>
</section><?php endforeach; ?></div>
</main>
<script>
const period = document.getElementById('analyticsPeriod');
const dates = [...document.querySelectorAll('.analytics-filters input[type="date"]')];
const syncDates = () => dates.forEach(input => { input.closest('label').hidden = period.value !== 'custom'; input.disabled = period.value !== 'custom'; });
period.addEventListener('change', syncDates);
syncDates();
</script>
<?= view('partials/session_idle_modal') ?>
</body></html>
