<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentShield — Pending Registrations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?= view('partials/school_brand') ?>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif']
                    },
                    colors: {
                        primary: StudentShieldBrand.primary,
                        gold: { DEFAULT: '#D9AA2E', light: '#EAC255', dark: '#B5881D' },
                    }
                }
            }
        }
    </script>
    <style>
        @media (max-width: 1440px) { html { font-size: 15px; } }
        @media (max-width: 1280px) { html { font-size: 14px; } }
        body { background-color: #f8faf6; font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Outfit', sans-serif; }
        .glass-header {
            background: linear-gradient(160deg, #0D7B34 0%, #096328 60%, #075826 100%);
            border-bottom: 3px solid #EAB308;
            box-shadow: 0 4px 20px -2px rgba(7, 88, 38, 0.35);
        }
        .dashboard-card {
            background: #ffffff;
            border: 1px solid #e5ebe5;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }
        .section-header {
            background: #ffffff;
            color: #0B5E2E;
            padding: 16px 24px;
            border-bottom: 1px solid #e5ebe5;
            border-left: 4px solid #0B5E2E;
        }
        .thead-green {
            background: linear-gradient(90deg, #0B5E2E, #096328);
            color: #fff;
        }
        .badge-pending {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #FDE68A;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up { animation: slideUp 0.4s ease both; }
        .approve-btn {
            background: #096328;
            color: white;
            transition: all 0.2s ease;
        }
        .approve-btn:hover {
            background: #075826;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(11, 94, 46, 0.3);
        }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f5f7f5; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #c8dac8; border-radius: 10px; }
        .live-dot { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }
    </style>
</head>
<body class="school-staff-page min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="glass-header text-white sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-2.5 sm:py-3.5 flex justify-between items-center">
            <div class="flex items-center gap-2.5 sm:gap-4 min-w-0">
                <img src="<?= base_url('images/logo.png?v=6') ?>" alt="SVBSM Logo" class="h-10 w-10 sm:h-12 sm:w-12 md:h-14 md:w-14 object-contain flex-shrink-0">
                <div class="truncate">
                    <h1 class="text-base sm:text-xl font-bold tracking-tight leading-tight truncate">StudentShield</h1>
                    <span class="text-[#F2C300] text-[10px] sm:text-xs font-semibold tracking-wider sm:tracking-widest uppercase block truncate">
                        <?= session()->get('role') == 2 ? 'Super Admin' : 'Counselor' ?> · Pending Registrations
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Role Badge -->
                <?php if (session()->get('role') == 2): ?>
                    <span class="hidden md:inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#EAB308]/20 border border-[#EAB308]/30 text-[#FACC15] text-xs font-semibold">Super Admin</span>
                <?php else: ?>
                    <span class="hidden md:inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/10 border border-white/15 text-white/70 text-xs font-semibold">Counselor</span>
                <?php endif; ?>

                <!-- Nav Links -->
                <a href="/admin/dashboard" class="flex items-center gap-2 text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <?php if (session()->get('role') == 2): ?>
                    <a href="/admin/users" class="flex items-center gap-2 text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Users
                    </a>
                    <a href="/admin/audit-logs" class="flex items-center gap-2 text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Audit Logs
                    </a>
                <?php endif; ?>

                <!-- Active page indicator -->
                <span class="flex items-center gap-2 text-sm font-bold text-[#EAB308] bg-white/10 px-3 py-2 rounded-lg border border-[#EAB308]/30">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Registrations
                </span>

                <form action="<?= base_url('logout') ?>" method="POST" class="inline"><?= csrf_field() ?>
                    <button type="submit" class="flex items-center gap-2 text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 px-4 py-2 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 sm:px-6 py-8 flex-1">

        <!-- Page Header -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 animate-slide-up">
            <div>
                <h2 class="text-2xl font-bold text-[#0B5E2E] flex items-center gap-3">
                    <svg class="w-7 h-7 text-[#F2C300]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Pending Student Registrations
                </h2>
                <p class="text-gray-500 text-sm mt-1">Review and approve new student accounts awaiting activation.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 bg-amber-50 px-4 py-2.5 rounded-xl border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-400 live-dot"></span>
                    <span class="text-xs font-bold text-amber-700 uppercase tracking-widest">
                        <?= count($pendingUsers) ?> Pending
                    </span>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-center gap-3 animate-slide-up">
                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-green-800 font-semibold text-sm"><?= session()->getFlashdata('success') ?></p>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3 animate-slide-up">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <p class="text-red-800 font-semibold text-sm"><?= session()->getFlashdata('error') ?></p>
            </div>
        <?php endif; ?>

        <!-- Table Card -->
        <div class="dashboard-card overflow-hidden animate-slide-up" style="animation-delay: 0.1s">
            <div class="section-header flex justify-between items-center">
                <h3 class="text-base font-bold flex items-center gap-2">
                    <svg class="h-5 w-5 text-[#0B5E2E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Students Awaiting Approval
                </h3>
                <span class="text-xs text-gray-500 font-medium">Accounts are inactive until approved</span>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <?php if (empty($pendingUsers)): ?>
                    <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
                        <div class="w-20 h-20 rounded-full bg-green-50 flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-700 mb-1">All Clear!</h4>
                        <p class="text-gray-500 text-sm max-w-sm">No pending student registrations at this time. New registrations will appear here when students sign up.</p>
                    </div>
                <?php else: ?>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="thead-green text-xs uppercase tracking-wider">
                                <th class="px-6 py-4">#</th>
                                <th class="px-6 py-4">Student Name</th>
                                <th class="px-6 py-4">Student ID</th>
                                <th class="px-6 py-4">Grade & Section</th>
                                <th class="px-6 py-4">Parent Contact</th>
                                <th class="px-6 py-4">Registered On</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($pendingUsers as $i => $student): 
                                $uid = (int)($student['user_id'] ?? $student['id']);
                            ?>
                            <tr id="row-user-<?= $uid ?>" class="hover:bg-gray-50 transition-all duration-300">
                                <td class="px-6 py-4 text-sm text-gray-400 font-medium"><?= $i + 1 ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-[#0B5E2E]/10 flex items-center justify-center flex-shrink-0">
                                            <span class="text-sm font-bold text-[#0B5E2E]">
                                                <?= strtoupper(substr($student['name'], 0, 1)) ?>
                                            </span>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-800"><?= esc($student['name']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-mono font-medium text-gray-700"><?= esc($student['student_id'] ?? '—') ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-800">
                                        <span class="font-semibold"><?= esc($student['grade_level'] ?? '—') ?></span>
                                        <?php if (!empty($student['section'])): ?>
                                            <span class="text-gray-400"> · </span>
                                            <span class="text-gray-600"><?= esc($student['section']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700 space-y-0.5">
                                        <?php if (!empty($student['parent_phone'])): ?>
                                            <div class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                <span><?= esc($student['parent_phone']) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($student['parent_email'])): ?>
                                            <div class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                <span class="text-xs"><?= esc($student['parent_email']) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (empty($student['parent_phone']) && empty($student['parent_email'])): ?>
                                            <span class="text-gray-400">—</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">
                                        <?= date('M d, Y', strtotime($student['created_at'])) ?>
                                    </span>
                                    <div class="text-xs text-gray-400"><?= date('g:i A', strtotime($student['created_at'])) ?></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="badge-pending inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        Pending
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" onclick="handleAction('approve', <?= $uid ?>, '<?= esc(addslashes($student['name'])) ?>', this)" class="approve-btn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wide shadow-sm cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>Approve</span>
                                        </button>
                                        <button type="button" onclick="handleAction('reject', <?= $uid ?>, '<?= esc(addslashes($student['name'])) ?>', this)" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium text-red-600 hover:text-red-800 hover:bg-red-50 border border-red-200 transition-colors cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            <span>Reject</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Toast Notification Container -->
        <div id="toastContainer" class="fixed top-20 right-6 z-50 flex flex-col gap-2 pointer-events-none"></div>

        <!-- Info Note -->
        <div class="mt-6 flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl p-4 animate-slide-up" style="animation-delay: 0.2s">
            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-blue-800 text-sm font-semibold">Approval Policy</p>
                <p class="text-blue-700 text-xs mt-0.5 leading-relaxed">
                    Students registered through the portal cannot log in until approved. Approving an account activates it immediately and logs the action in the system audit trail. Both the Guidance Counselor and Super Admin may approve registrations.
                </p>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="mt-auto py-4 bg-white border-t border-gray-100">
        <div class="container mx-auto px-6 text-center text-xs text-gray-400">
            © <?= date('Y') ?> StudentShield — St. Vincent Blessed School of Manila
        </div>
    </footer>

    <script>
        function showToast(message, isSuccess = true) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `px-5 py-3 rounded-xl shadow-lg border text-sm font-semibold flex items-center gap-2 transform transition-all duration-300 pointer-events-auto ${
                isSuccess 
                    ? 'bg-green-50 text-green-800 border-green-200' 
                    : 'bg-red-50 text-red-800 border-red-200'
            }`;
            toast.innerHTML = `
                <svg class="w-5 h-5 flex-shrink-0 ${isSuccess ? 'text-green-600' : 'text-red-600'}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${isSuccess ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'}" />
                </svg>
                <span>${message}</span>
            `;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        async function handleAction(action, userId, studentName, btn) {
            const isApprove = (action === 'approve');
            const endpoint = isApprove ? `/admin/approve-student/${userId}` : `/admin/reject-student/${userId}`;
            
            // Visual loading state on button
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = `<span class="inline-block animate-spin mr-1">⏳</span> ${isApprove ? 'Approving...' : 'Rejecting...'}`;

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });

                const data = await response.json();

                if (response.ok && data.status === 'success') {
                    showToast(isApprove ? `Account for "${studentName}" approved!` : `Registration for "${studentName}" rejected.`, true);

                    // Animate row removal
                    const row = document.getElementById(`row-user-${userId}`);
                    if (row) {
                        row.style.transition = 'all 0.4s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            row.remove();
                            // Check if no more rows left
                            const remainingRows = document.querySelectorAll('tbody tr');
                            if (remainingRows.length === 0) {
                                location.reload();
                            }
                        }, 400);
                    }
                } else {
                    showToast(data.message || 'Action could not be completed. Please try again.', false);
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            } catch (err) {
                console.error('Fetch error:', err);
                // Fallback: direct browser navigation if fetch is blocked
                window.location.href = endpoint;
            }
        }
    </script>
    <?= view('partials/session_idle_modal') ?>
</body>
</html>
