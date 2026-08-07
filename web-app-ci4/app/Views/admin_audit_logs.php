<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentShield - Audit Logs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['Inter', 'sans-serif'], 
                        heading: ['Outfit', 'sans-serif'] 
                    },
                    colors: {
                        white: '#FBFDFB',
                        black: '#131A15',
                        primary: { DEFAULT: '#235D3A', light: '#2F754A', dark: '#19452B' },
                        gold: { DEFAULT: '#D9AA2E', light: '#EAC255' }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #FAFDF5; }
        h1, h2, h3, .font-heading { font-family: 'Outfit', sans-serif; }
        .glass-header {
            background: #166534;
            border-bottom: 3px solid #EAB308;
        }
        .dashboard-card {
            background: #ffffff;
            border: 1px solid #e5ebe5;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }
        .section-header {
            background: #ffffff;
            color: #166534;
            padding: 16px 24px;
            border-bottom: 1px solid #e5ebe5;
            border-left: 4px solid #166534;
            border-radius: 14px 14px 0 0;
        }
        .thead-green { background: #166534; color: white; }
        .thead-green th { color: rgba(255,255,255,0.92); font-weight: 600; letter-spacing: 0.05em; font-size: 0.75rem; }
    </style>
</head>
<body class="min-h-screen">

    <!-- Navbar -->
    <nav class="glass-header text-white sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <img src="<?= base_url('images/logo.png?v=6') ?>" alt="SVBSM Logo" class="h-24 w-24 object-contain"
                     onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%23F2C300\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z\'/></svg>';">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight font-heading">StudentShield</h1>
                    <span class="text-[#EAB308] text-xs font-semibold tracking-widest uppercase">Super Admin Panel</span>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Role Badge -->
                <span class="hidden md:inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#EAB308]/20 border border-[#EAB308]/30 text-[#FACC15] text-xs font-semibold">Super Admin</span>

                <a href="/admin/dashboard" class="flex items-center gap-2 text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition-colors border-l border-white/20 ml-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    Dashboard
                </a>
                <a href="/admin/users" class="flex items-center gap-2 text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Users
                </a>
                <a href="/admin/audit-logs" class="flex items-center gap-2 text-sm font-medium text-[#EAB308] bg-white/10 px-3 py-2 rounded-lg transition-colors">
                    <svg class="h-4 w-4 text-[#EAB308]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Audit Logs
                </a>
                <form action="/logout" method="POST" class="inline"><?= csrf_field() ?><button type="submit" class="flex items-center gap-2 text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 px-4 py-2 rounded-lg transition-colors border-l border-white/20 ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button></form>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-heading font-bold text-gray-900">System Audit Logs</h2>
                <p class="text-gray-600 text-sm mt-1">Track staff accountability, report verifications, and system actions.</p>
            </div>
        </div>

        <div class="dashboard-card overflow-hidden">
            <div class="section-header flex justify-between items-center sm:flex-row flex-col gap-4">
                <h2 class="text-lg font-bold flex items-center gap-3">
                    <svg class="h-5 w-5 text-[#0B5E2E]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Action History
                </h2>
            </div>
            <div class="px-6 py-3 flex justify-between items-center bg-white border-b border-gray-100 flex-wrap gap-2">
                <div class="flex items-center gap-2 flex-1 min-w-[200px] max-w-sm relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" id="searchLogs" placeholder="Search actor or action..." class="w-full pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg outline-none focus:border-[#0B5E2E] focus:ring-1 focus:ring-[#0B5E2E]/20 transition-all">
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <select id="filterCategoryLogs" class="text-sm px-3 py-1.5 border border-gray-200 rounded-lg outline-none focus:border-[#0B5E2E]">
                        <option value="all">All Categories</option>
                        <option value="INCIDENT">Incident</option>
                        <option value="VENT">Vent</option>
                        <option value="SYSTEM">System (Login/Logout)</option>
                        <option value="STUDENT">Student</option>
                        <option value="USER">User</option>
                    </select>
                    <input type="date" id="filterDateLogs" class="text-sm px-3 py-1.5 border border-gray-200 rounded-lg outline-none focus:border-[#0B5E2E] text-gray-600">
                    <button type="button" id="clearFiltersLogs" class="text-xs font-semibold text-gray-500 hover:text-gray-700 bg-gray-50 hover:bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200 transition-colors">✕ Clear</button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="logsTable">
                    <thead>
                        <tr class="thead-green text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 border-b border-gray-200">Time</th>
                            <th class="px-6 py-4 border-b border-gray-200">Actor</th>
                            <th class="px-6 py-4 border-b border-gray-200">Summary</th>
                            <th class="px-6 py-4 border-b border-gray-200">Category</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($logs as $log): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors" data-category="<?= strtoupper($log['entity_type']) ?>" data-date="<?= date('Y-m-d', strtotime($log['created_at'])) ?>">
                            <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">
                                <?= date('M d, Y h:i A', strtotime($log['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs shrink-0">
                                        <?= strtoupper(substr($log['actor_name'] ?? 'U', 0, 1)) ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm"><?= esc($log['actor_name'] ?? 'System / Unknown') ?></p>
                                        <p class="text-xs text-gray-500">
                                            <?php 
                                                if (isset($log['actor_role'])) {
                                                    echo $log['actor_role'] == 2 ? 'Super Admin' : ($log['actor_role'] == 1 ? 'Counselor' : 'Student');
                                                } else {
                                                    echo 'Unknown Role';
                                                }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php 
                                    $actionStr = str_replace('_', ' ', $log['action']);
                                    $summary = ucfirst(strtolower($actionStr)) . " (ID: " . $log['entity_id'] . ")";
                                    
                                    if ($log['action'] == 'VERIFY_AND_ALERT' || $log['action'] == 'VERIFY_INCIDENT') {
                                        $summary = "<span class='text-emerald-700 font-semibold'>Verified Bullying Report #{$log['entity_id']}</span>";
                                    } elseif ($log['action'] == 'REJECT_FAKE') {
                                        $summary = "<span class='text-red-600 font-semibold'>Rejected Fake Report #{$log['entity_id']}</span>";
                                    } elseif ($log['action'] == 'RESOLVE') {
                                        $summary = "<span class='text-blue-600 font-semibold'>Resolved Vent Log #{$log['entity_id']}</span>";
                                    } elseif ($log['action'] == 'SOFT_DELETE') {
                                        $summary = "<span class='text-gray-500 font-semibold'>Archived Vent Log #{$log['entity_id']}</span>";
                                    } elseif ($log['action'] == 'USER_LOGIN') {
                                        $summary = "<span class='text-indigo-600 font-semibold'>Logged into the system</span>";
                                    } elseif ($log['action'] == 'USER_LOGOUT') {
                                        $summary = "<span class='text-gray-500 font-semibold'>Logged out of the system</span>";
                                    }
                                    
                                    echo "<p class='text-sm text-gray-800'>{$summary}</p>";
                                ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php 
                                    $entity = strtoupper($log['entity_type']);
                                    $bgClass = 'bg-gray-100 text-gray-700';
                                    
                                    if ($entity == 'INCIDENT') {
                                        $bgClass = 'bg-orange-100 text-orange-800';
                                    } elseif ($entity == 'VENT') {
                                        $bgClass = 'bg-blue-100 text-blue-800';
                                    } elseif ($entity == 'STUDENT' || $entity == 'USER') {
                                        $bgClass = 'bg-purple-100 text-purple-800';
                                    } elseif ($entity == 'SYSTEM') {
                                        $bgClass = 'bg-indigo-100 text-indigo-800';
                                    }
                                ?>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider <?= $bgClass ?>">
                                    <?= $entity ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($logs)): ?>
                        <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">No audit logs found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
            <style>
                .pagination { display: flex; list-style: none; padding: 0; margin: 0; gap: 0.25rem; }
                .pagination li a, .pagination li span { padding: 0.5rem 0.75rem; border: 1px solid #e5ebe5; border-radius: 0.375rem; font-size: 0.875rem; color: #4b5563; text-decoration: none; background: white; }
                .pagination li.active a, .pagination li.active span { background-color: #166534; color: white; border-color: #166534; }
                .pagination li a:hover { background-color: #f9fafb; }
            </style>
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between flex-wrap gap-4">
                <span class="text-sm text-gray-500">
                    Showing <?= ($pager->getCurrentPage() - 1) * 50 + 1 ?> to <?= min($pager->getCurrentPage() * 50, $pager->getTotal()) ?> of <?= $pager->getTotal() ?> entries
                </span>
                <div class="flex items-center">
                    <?= $pager->links() ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function applyAuditFilters() {
            const search = document.getElementById('searchLogs').value.toLowerCase();
            const category = document.getElementById('filterCategoryLogs').value;
            const dateVal = document.getElementById('filterDateLogs').value;
            const rows = document.querySelectorAll('#logsTable tbody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const matchSearch = row.innerText.toLowerCase().includes(search);
                const matchCategory = category === 'all' || row.getAttribute('data-category') === category;
                const matchDate = !dateVal || row.getAttribute('data-date') === dateVal;

                if (matchSearch && matchCategory && matchDate) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update visible count badge
            const badge = document.getElementById('visibleCount');
            if (badge) badge.textContent = visibleCount + ' result' + (visibleCount !== 1 ? 's' : '');
        }

        document.getElementById('searchLogs').addEventListener('keyup', applyAuditFilters);
        document.getElementById('filterCategoryLogs').addEventListener('change', applyAuditFilters);
        document.getElementById('filterDateLogs').addEventListener('change', applyAuditFilters);
        document.getElementById('clearFiltersLogs').addEventListener('click', function() {
            document.getElementById('searchLogs').value = '';
            document.getElementById('filterCategoryLogs').value = 'all';
            document.getElementById('filterDateLogs').value = '';
            applyAuditFilters();
        });
    </script>
</body>
</html>
