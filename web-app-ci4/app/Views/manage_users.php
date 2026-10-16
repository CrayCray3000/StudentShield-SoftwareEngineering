<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentShield - User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?= view('partials/school_brand', ['studentPalette' => true]) ?>
        <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['Inter', 'sans-serif'], 
                        heading: ['Outfit', 'sans-serif'] 
                    },
                    colors: {
                        white: '#FBFDFB', // Anti-glare soft white
                        black: '#131A15', // Softened black
                        gray: {
                            50: '#F4F7F5',
                            100: '#E6EBE8',
                            200: '#CFD9D4',
                            300: '#AAB8B1',
                            400: '#82958B',
                            500: '#61756B',
                            600: '#4D5D55',
                            700: '#3D4943',
                            800: '#2E3833',
                            900: '#212825', // Replaces harsh text-gray-900 with deep sage
                        },
                        red: {
                            50: '#FDF7F7',
                            100: '#FAEDED',
                            200: '#F4D6D6',
                            500: '#D95A5A', // Less neon, softer warning red
                            600: '#C24545',
                            700: '#A33535',
                        },
                        primary: StudentShieldBrand.primary,
                        gold: { DEFAULT: '#D9AA2E', light: '#EAC255', bright: '#FCEEA1', dark: '#B5881D' },
                        calm: { 50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 600: '#16a34a' }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Responsive scaling for smaller monitors/laptops */
        @media (max-width: 1440px) { html { font-size: 15px; } }
        @media (max-width: 1280px) { html { font-size: 14px; } }
        @media (max-width: 1024px) { html { font-size: 13px; } }
        @media (max-width: 768px) { html { font-size: 16px; } } /* Reset for mobile */

        body { font-family: 'Inter', sans-serif; background: #FAFDF5; }
        h1, h2, h3, .font-heading { font-family: 'Outfit', sans-serif; }
        .modal-backdrop { background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); }
        .glass-header {
            background: linear-gradient(160deg, #0D7B34 0%, #096328 60%, #075826 100%);
            border-bottom: 3px solid #EAB308;
            box-shadow: 0 4px 20px -2px rgba(7, 88, 38, 0.35);
        }
        
        .dashboard-card {
            background: #ffffff;
            border: 1px solid #e5ebe5;
            border-radius: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08); /* Premium distinguished shadow */
        }
        
        .section-header {
            background: #ffffff;
            color: #0B5E2E;
            padding: 16px 24px;
            border-bottom: 1px solid #e5ebe5;
            border-left: 4px solid #0B5E2E;
            border-radius: 14px 14px 0 0;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #e5ebe5;
            border-radius: 12px;
            padding: 20px 24px;
            transition: all 0.25s ease;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08); /* Premium depth shadow */
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #EAB308, transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .stat-card:hover::after { opacity: 1; }
        .stat-card:hover {
            border-color: #d4ddd4;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.12); /* Lift hover shadow */
            transform: translateY(-2px);
        }
        .stat-card.blue-accent { border-left: 4px solid #3B82F6; }
        .stat-card.emerald-accent { border-left: 4px solid #10B981; }
        .stat-card.gold-accent { border-left: 4px solid #F59E0B; }
        
        .thead-green {
            background: #096328;
            color: white;
        }
        .thead-green th {
            color: rgba(255,255,255,0.92);
            font-weight: 600;
            letter-spacing: 0.05em;
            font-size: 0.75rem;
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('css/staff-layout.css?v=' . filemtime(FCPATH . 'css/staff-layout.css')) ?>">
</head>
<body class="school-staff-page staff-layout min-h-screen">

    <?= view('partials/staff_navigation', ['staffPage' => 'users', 'staffPageLabel' => 'Users']) ?>

    <main id="staffMain" class="staff-main container mx-auto px-6 py-8">

        <!-- Header -->
        <div class="staff-page-heading flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-heading font-bold text-gray-900">User Management</h2>
                <p class="text-gray-600 text-sm mt-1">Manage students, counselors, and administrators</p>
            </div>
            <button onclick="openModal('addModal')" class="bg-primary hover:bg-primary-light text-white font-semibold px-5 py-2.5 rounded-xl shadow-md shadow-primary/20 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add User
            </button>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl flex items-center gap-3">
                <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <p class="text-sm font-medium text-green-700"><?= session()->getFlashdata('success') ?></p>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl flex items-center gap-3">
                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <p class="text-sm font-medium text-red-700"><?= session()->getFlashdata('error') ?></p>
            </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <?php
            $totalStudents = 0; $totalCounselors = 0; $totalAdmins = 0;
            foreach ($users as $u) {
                if ($u['role'] == 0) $totalStudents++;
                elseif ($u['role'] == 1) $totalCounselors++;
                elseif ($u['role'] == 2) $totalAdmins++;
            }
        ?>
        <div class="staff-metrics grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8 mt-6">
            <div class="stat-card blue-accent flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-bold uppercase tracking-wider block mb-0.5">Students</span>
                    <p class="text-2xl font-heading font-bold text-gray-900 leading-none"><?= $totalStudents ?></p>
                </div>
            </div>
            <div class="stat-card emerald-accent flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-bold uppercase tracking-wider block mb-0.5">Counselors</span>
                    <p class="text-2xl font-heading font-bold text-gray-900 leading-none"><?= $totalCounselors ?></p>
                </div>
            </div>
            <div class="stat-card gold-accent flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-[#F59E0B]/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-[#F59E0B]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-bold uppercase tracking-wider block mb-0.5">Super Admins</span>
                    <p class="text-2xl font-heading font-bold text-gray-900 leading-none"><?= $totalAdmins ?></p>
                </div>
            </div>
        </div>

        <!-- Users List -->
        <div class="dashboard-card overflow-hidden mt-8">
            <div class="section-header flex justify-between items-center sm:flex-row flex-col gap-4">
                <h2 class="text-lg font-bold flex items-center gap-3">
                    <svg class="h-5 w-5 text-[#0B5E2E]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    System Directory
                </h2>
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterUsers('all')" id="filter-all" class="filter-btn px-3 py-1.5 text-xs font-semibold rounded-lg bg-primary text-white">All</button>
                    <button onclick="filterUsers('0')" id="filter-0" class="filter-btn px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-100 text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-colors">Students</button>
                    <button onclick="filterUsers('1')" id="filter-1" class="filter-btn px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-100 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Counselors</button>
                    <button onclick="filterUsers('2')" id="filter-2" class="filter-btn px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-100 text-gray-600 hover:bg-amber-50 hover:text-amber-700 transition-colors">Admins</button>
                </div>
            </div>
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="thead-green text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 border-b border-gray-200">Name</th>
                            <th class="px-6 py-4 border-b border-gray-200">Student ID</th>
                            <th class="px-6 py-4 border-b border-gray-200">Section</th>
                            <th class="px-6 py-4 border-b border-gray-200">Adviser Name</th>
                            <th class="text-center px-6 py-4 border-b border-gray-200">Role</th>
                            <th class="px-6 py-4 border-b border-gray-200">Parent Email</th>
                            <th class="px-6 py-4 border-b border-gray-200">Parent Phone</th>
                            <th class="text-center px-6 py-4 border-b border-gray-200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr class="user-row border-b border-gray-50 hover:bg-gray-50/50 transition-colors" data-role="<?= $user['role'] ?>">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <button onclick="openStudentProfile(<?= $user['id'] ?>)" class="hover:text-[#096328] hover:underline underline-offset-2 transition-colors">
                                    <?= esc($user['name']) ?>
                                </button>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-mono text-xs"><?= esc($user['student_id'] ?: '—') ?></td>
                            <td class="px-6 py-4 text-gray-600 font-medium text-xs"><?= esc($user['section'] ?: '—') ?></td>
                            <td class="px-6 py-4 text-gray-600 font-medium text-xs"><?= esc($user['adviser_name'] ?: '—') ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($user['role'] == 0): ?>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100">Student</span>
                                <?php elseif ($user['role'] == 1): ?>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100">Counselor</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gold/10 text-amber-700 border border-gold/30">Super Admin</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-xs"><?= esc($user['parent_email'] ?: '—') ?></td>
                            <td class="px-6 py-4 text-gray-600 text-xs"><?= esc($user['parent_phone'] ?: '—') ?></td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='openEditModal(<?= json_encode($user) ?>)' class="p-2 rounded-lg hover:bg-blue-50 text-gray-500 hover:text-blue-600 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <?php if ($user['id'] != session()->get('user_id')): ?>
                                    <form action="/admin/users/delete/<?= $user['id'] ?>" method="post" onsubmit="return confirm('Delete user <?= esc($user['name']) ?>? This cannot be undone.');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="p-2 rounded-lg hover:bg-red-50 text-gray-500 hover:text-red-600 transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($users)): ?>
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No users found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden flex flex-col divide-y divide-gray-100">
                <?php foreach ($users as $user): ?>
                <div class="user-card p-5 hover:bg-gray-50/50 transition-colors" data-role="<?= $user['role'] ?>">
                    <div class="flex justify-between items-start mb-3">
                        <div class="pr-3">
                            <button onclick="openStudentProfile(<?= $user['id'] ?>)" class="font-bold text-gray-900 hover:text-[#096328] hover:underline transition-colors text-left leading-tight"><?= esc($user['name']) ?></button>
                            <span class="text-xs text-gray-500 font-mono inline-block mt-0.5"><?= esc($user['student_id'] ?: 'No ID') ?></span>
                        </div>
                        <div class="flex-shrink-0">
                            <?php if ($user['role'] == 0): ?>
                                <span class="px-2 py-1 rounded text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-600">Student</span>
                            <?php elseif ($user['role'] == 1): ?>
                                <span class="px-2 py-1 rounded text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600">Counselor</span>
                            <?php else: ?>
                                <span class="px-2 py-1 rounded text-xs font-bold uppercase tracking-wider bg-gold/10 text-amber-700">Admin</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 mb-4 bg-gray-50/50 p-3 rounded-lg border border-gray-100/50">
                        <div>
                            <span class="block text-xs uppercase font-bold text-gray-500 mb-0.5">Section</span>
                            <span class="text-sm text-gray-800 font-medium truncate block"><?= esc($user['section'] ?: '—') ?></span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase font-bold text-gray-500 mb-0.5">Adviser Name</span>
                            <span class="text-sm text-gray-800 font-medium truncate block"><?= esc($user['adviser_name'] ?: '—') ?></span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase font-bold text-gray-500 mb-0.5">Parent Email</span>
                            <span class="text-sm text-gray-800 font-medium truncate block"><?= esc($user['parent_email'] ?: '—') ?></span>
                        </div>
                        <div>
                            <span class="block text-xs uppercase font-bold text-gray-500 mb-0.5">Parent Phone</span>
                            <span class="text-sm text-gray-800 font-medium truncate block"><?= esc($user['parent_phone'] ?: '—') ?></span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 border-t border-gray-100 pt-3">
                        <button onclick='openEditModal(<?= json_encode($user) ?>)' class="px-4 py-2 bg-blue-50/50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-bold transition-colors flex items-center gap-1.5" title="Edit">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg> Edit
                        </button>
                        <?php if ($user['id'] != session()->get('user_id')): ?>
                        <form action="/admin/users/delete/<?= $user['id'] ?>" method="post" onsubmit="return confirm('Delete user <?= esc($user['name']) ?>?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="px-4 py-2 bg-red-50/50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-bold transition-colors flex items-center gap-1.5" title="Delete">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> Delete
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($users)): ?>
                    <div class="px-6 py-12 text-center text-gray-500">No users found.</div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Add User Modal -->
    <div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center modal-backdrop">
        <div class="bg-white rounded-2xl p-5 sm:p-8 max-w-lg w-[95%] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] relative max-h-[90dvh] overflow-y-auto">
            <button onclick="closeModal('addModal')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <h3 id="addModalTitle" class="text-xl font-heading font-bold text-gray-900 mb-0.5">Add New User</h3>
            <p id="addModalSubtitle" class="text-sm text-gray-500 mb-5">Choose a role below to continue.</p>

                        <!-- Visual Role Picker -->
            <div class="grid grid-cols-3 gap-3 mb-6">
                <!-- Student card -->
                <button type="button" onclick="selectAddRole(0)" id="roleCard0"
                    class="flex flex-col items-center gap-2 p-3.5 rounded-xl border-2 transition-all cursor-pointer border-blue-500 bg-blue-50/70 ring-2 ring-blue-400/40 shadow-sm">
                    <div id="roleCircle0" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors bg-blue-600 text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <span id="roleLabel0" class="text-xs sm:text-sm font-bold text-blue-700">Student</span>
                </button>
                <!-- Counselor card -->
                <button type="button" onclick="selectAddRole(1)" id="roleCard1"
                    class="flex flex-col items-center gap-2 p-3.5 rounded-xl border-2 transition-all cursor-pointer border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50">
                    <div id="roleCircle1" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors bg-emerald-50 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <span id="roleLabel1" class="text-xs sm:text-sm font-medium text-gray-600">Counselor</span>
                </button>
                <!-- Super Admin card -->
                <button type="button" onclick="selectAddRole(2)" id="roleCard2"
                    class="flex flex-col items-center gap-2 p-3.5 rounded-xl border-2 transition-all cursor-pointer border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50">
                    <div id="roleCircle2" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors bg-amber-50 text-amber-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span id="roleLabel2" class="text-xs sm:text-sm font-medium text-gray-600">Super Admin</span>
                </button>
            </div>

            <form action="/admin/users/create" method="post" class="space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="role" id="addRoleValue" value="0">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/10" placeholder="e.g. Maria Santos">
                    </div>
                    <!-- Student-only: Student ID -->
                    <div id="addStudentIdWrap" class="col-span-2 sm:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Student ID <span class="text-red-500">*</span></label>
                        <input type="text" name="student_id" id="addStudentId" class="w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/10" placeholder="e.g. STU-001">
                    </div>
                    <!-- Student-only: Section -->
                    <div id="addSectionWrap" class="col-span-2 sm:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Section <span class="text-red-500">*</span></label>
                        <input type="text" name="section" id="addSection" class="w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/10" placeholder="e.g. 10-Rizal">
                    </div>
                    <!-- Student-only: Adviser -->
                    <div id="addAdviserWrap" class="col-span-2 sm:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Adviser Name <span class="text-red-500">*</span></label>
                        <input type="text" name="adviser_name" id="addAdviser" class="w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/10" placeholder="e.g. Mr. Juan Dela Cruz">
                    </div>
                    <!-- Contact email -->
                    <div id="addEmailWrap" class="col-span-2 sm:col-span-1">
                        <label id="addParentEmailLabel" class="block text-sm font-semibold text-gray-700 mb-1">Parent Email</label>
                        <input type="email" name="parent_email" id="addParentEmail" class="w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/10" placeholder="Optional">
                    </div>
                    <!-- Contact phone -->
                    <div id="addPhoneWrap" class="col-span-2 sm:col-span-1">
                        <label id="addParentPhoneLabel" class="block text-sm font-semibold text-gray-700 mb-1">Parent Phone</label>
                        <input type="text" name="parent_phone" id="addParentPhone" class="w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/10" placeholder="Optional">
                    </div>
                    <!-- Password -->
                    <div id="addPassWrap" class="col-span-2 sm:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password <span id="addPassReq" class="text-red-500"></span></label>
                        <input type="password" name="passcode" id="addPassInput" class="w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/10" placeholder="Enter password (optional)">
                    </div>
                </div>
                <button type="submit" id="addSubmitBtn" class="w-full mt-4 bg-primary hover:bg-primary-light text-white font-bold py-3 rounded-xl shadow-md transition-all hover:-translate-y-0.5">Create Student</button>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center modal-backdrop">
        <div class="bg-white rounded-2xl p-5 sm:p-8 max-w-lg w-[95%] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)] relative">
            <button onclick="closeModal('editModal')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <h3 class="text-xl font-heading font-bold text-gray-900 mb-6">Edit User</h3>
            <form id="editForm" method="post" class="space-y-4">
                <?= csrf_field() ?>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name *</label>
                        <input type="text" name="name" id="editName" required class="w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/10">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Student ID</label>
                        <input type="text" name="student_id" id="editStudentId" class="w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/10">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Role *</label>
                        <select name="role" id="editRole" required class="w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 bg-white">
                            <option value="0">Student</option>
                            <option value="1">Counselor</option>
                            <option value="2">Super Admin</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">New Password</label>
                        <input type="password" name="passcode" class="w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/10" placeholder="Leave blank to keep current">
                    </div>
                    <div>
                        <label id="editParentEmailLabel" class="block text-sm font-semibold text-gray-700 mb-1">Parent Email</label>
                        <input type="email" name="parent_email" id="editParentEmail" class="w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/10">
                    </div>
                    <div>
                        <label id="editParentPhoneLabel" class="block text-sm font-semibold text-gray-700 mb-1">Parent Phone</label>
                        <input type="text" name="parent_phone" id="editParentPhone" class="w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/10">
                    </div>
                    <div class="col-span-2 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Section <span class="text-red-500">*</span></label>
                            <input type="text" name="section" id="editSection" class="w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/10">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Adviser Name <span class="text-red-500">*</span></label>
                            <input type="text" name="adviser_name" id="editAdviser" class="w-full px-4 py-3 text-base sm:text-sm border border-gray-300 rounded-xl outline-none focus:border-primary focus:ring-2 focus:ring-primary/10">
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full mt-4 bg-primary hover:bg-primary-light text-white font-bold py-3 rounded-xl shadow-md transition-all hover:-translate-y-0.5">Save Changes</button>
            </form>
        </div>
    </div>

<script>
    function openModal(id) {
        const m = document.getElementById(id);
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeModal(id) {
        const m = document.getElementById(id);
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
    // Edit modal field toggling
    function toggleFieldsForRole(prefix) {
        var role = parseInt(document.getElementById(prefix + 'Role').value);
        var sectionInput = document.getElementById(prefix + 'Section');
        var adviserInput = document.getElementById(prefix + 'Adviser');
        var emailLabel   = document.getElementById(prefix + 'ParentEmailLabel');
        var phoneLabel   = document.getElementById(prefix + 'ParentPhoneLabel');
        var isStaff = (role === 1 || role === 2);
        if (sectionInput) sectionInput.required = !isStaff;
        if (adviserInput) adviserInput.required = !isStaff;
        if (emailLabel) emailLabel.textContent = isStaff ? 'Email' : 'Parent Email';
        if (phoneLabel) phoneLabel.textContent = isStaff ? 'Phone' : 'Parent Phone';
    }

        // Add modal role-card picker
    var ROLE_CONFIG = [
        {
            title: 'Add Student',
            sub: 'Create a student profile with section & adviser details.',
            btn: 'Create Student',
            passReq: false,
            cardActive: 'border-blue-500 bg-blue-50/70 ring-2 ring-blue-400/40 shadow-sm',
            circleActive: 'bg-blue-600 text-white shadow-sm',
            circleInactive: 'bg-blue-50 text-blue-600',
            labelActive: 'text-blue-700 font-bold',
            labelInactive: 'text-gray-600 font-medium'
        },
        {
            title: 'Add Counselor',
            sub: 'Create a counselor account with portal login credentials.',
            btn: 'Create Counselor',
            passReq: true,
            cardActive: 'border-emerald-500 bg-emerald-50/70 ring-2 ring-emerald-400/40 shadow-sm',
            circleActive: 'bg-emerald-600 text-white shadow-sm',
            circleInactive: 'bg-emerald-50 text-emerald-600',
            labelActive: 'text-emerald-800 font-bold',
            labelInactive: 'text-gray-600 font-medium'
        },
        {
            title: 'Add Super Admin',
            sub: 'Create a full administrator account with staff portal access.',
            btn: 'Create Admin',
            passReq: true,
            cardActive: 'border-amber-500 bg-amber-50/70 ring-2 ring-amber-400/40 shadow-sm',
            circleActive: 'bg-amber-500 text-white shadow-sm',
            circleInactive: 'bg-amber-50 text-amber-600',
            labelActive: 'text-amber-800 font-bold',
            labelInactive: 'text-gray-600 font-medium'
        }
    ];

    function selectAddRole(role) {
        role = parseInt(role);
        if (isNaN(role) || role < 0 || role > 2) role = 0;
        document.getElementById('addRoleValue').value = role;

        for (var i = 0; i < 3; i++) {
            var cfg = ROLE_CONFIG[i];
            var card = document.getElementById('roleCard' + i);
            var circle = document.getElementById('roleCircle' + i);
            var label = document.getElementById('roleLabel' + i);

            if (card && circle && label) {
                if (i === role) {
                    card.className = 'flex flex-col items-center gap-2 p-3.5 rounded-xl border-2 transition-all cursor-pointer ' + cfg.cardActive;
                    circle.className = 'w-10 h-10 rounded-full flex items-center justify-center transition-colors ' + cfg.circleActive;
                    label.className = 'text-xs ' + cfg.labelActive;
                } else {
                    card.className = 'flex flex-col items-center gap-2 p-3.5 rounded-xl border-2 transition-all cursor-pointer border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50';
                    circle.className = 'w-10 h-10 rounded-full flex items-center justify-center transition-colors ' + cfg.circleInactive;
                    label.className = 'text-xs ' + cfg.labelInactive;
                }
            }
        }

        var activeCfg = ROLE_CONFIG[role];
        var titleEl = document.getElementById('addModalTitle');
        var subEl   = document.getElementById('addModalSubtitle');
        var btnEl   = document.getElementById('addSubmitBtn');

        if (titleEl) titleEl.textContent = activeCfg.title;
        if (subEl)   subEl.textContent   = activeCfg.sub;
        if (btnEl)   btnEl.textContent   = activeCfg.btn;

        var isStudent = (role === 0);

        // Student-specific fields visibility & requirements
        var studentIdWrap = document.getElementById('addStudentIdWrap');
        var sectionWrap   = document.getElementById('addSectionWrap');
        var adviserWrap   = document.getElementById('addAdviserWrap');

        if (studentIdWrap) studentIdWrap.style.display = isStudent ? '' : 'none';
        if (sectionWrap)   sectionWrap.style.display   = isStudent ? '' : 'none';
        if (adviserWrap)   adviserWrap.style.display   = isStudent ? '' : 'none';

        var studentIdInput = document.getElementById('addStudentId');
        var sectionInput   = document.getElementById('addSection');
        var adviserInput   = document.getElementById('addAdviser');

        if (studentIdInput) studentIdInput.required = isStudent;
        if (sectionInput)   sectionInput.required   = isStudent;
        if (adviserInput)   adviserInput.required   = isStudent;

        // Dynamic labels & requirements for contact
        var emailInput = document.getElementById('addParentEmail');
        var emailLabel = document.getElementById('addParentEmailLabel');
        var phoneLabel = document.getElementById('addParentPhoneLabel');

        if (isStudent) {
            if (emailLabel) emailLabel.innerHTML = 'Parent Email <span class="text-xs text-gray-400 font-normal">(Optional)</span>';
            if (phoneLabel) phoneLabel.innerHTML = 'Parent Phone <span class="text-xs text-gray-400 font-normal">(Optional)</span>';
            if (emailInput) {
                emailInput.required = false;
                emailInput.placeholder = 'Optional';
            }
        } else {
            if (emailLabel) emailLabel.innerHTML = 'Staff Email <span class="text-red-500">*</span>';
            if (phoneLabel) phoneLabel.innerHTML = 'Phone <span class="text-xs text-gray-400 font-normal">(Optional)</span>';
            if (emailInput) {
                emailInput.required = true;
                emailInput.placeholder = 'e.g. staff@school.edu.ph';
            }
        }

        // Password requirement & styling
        var passInput = document.getElementById('addPassInput');
        var passReq   = document.getElementById('addPassReq');
        var passWrap  = document.getElementById('addPassWrap');

        if (activeCfg.passReq) {
            if (passReq) passReq.textContent = '*';
            if (passInput) {
                passInput.required = true;
                passInput.placeholder = 'Required for portal login';
            }
            if (passWrap) passWrap.className = 'col-span-2';
        } else {
            if (passReq) passReq.textContent = '';
            if (passInput) {
                passInput.required = false;
                passInput.placeholder = 'Enter password (optional)';
            }
            if (passWrap) passWrap.className = 'col-span-2 sm:col-span-1';
        }
    }
    selectAddRole(0); // initialise

    document.getElementById('editRole').addEventListener('change', function() {
        toggleFieldsForRole('edit');
    });
    function openEditModal(user) {
        document.getElementById('editForm').action = '/admin/users/update/' + user.id;
        document.getElementById('editName').value = user.name;
        document.getElementById('editStudentId').value = user.student_id || '';
        document.getElementById('editRole').value = user.role;
        document.getElementById('editParentEmail').value = user.parent_email || '';
        document.getElementById('editParentPhone').value = user.parent_phone || '';
        document.getElementById('editSection').value = user.section || '';
        document.getElementById('editAdviser').value = user.adviser_name || '';
        toggleFieldsForRole('edit');
        openModal('editModal');
    }
    // Close modals on backdrop click
    document.querySelectorAll('.modal-backdrop').forEach(m => {
        m.addEventListener('click', function(e) {
            if (e.target === this) closeModal(this.id);
        });
    });

    // Filtering logic
    function filterUsers(role) {
        // Reset button styles
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('bg-primary', 'text-white', 'bg-blue-50', 'text-blue-600', 'bg-emerald-50', 'text-emerald-600', 'bg-amber-50', 'text-amber-700');
            btn.classList.add('bg-gray-100', 'text-gray-600');
        });

        // Set active button style
        const activeBtn = document.getElementById('filter-' + role);
        activeBtn.classList.remove('bg-gray-100', 'text-gray-600');
        if (role === 'all') activeBtn.classList.add('bg-primary', 'text-white');
        else if (role === '0') activeBtn.classList.add('bg-blue-50', 'text-blue-600');
        else if (role === '1') activeBtn.classList.add('bg-emerald-50', 'text-emerald-600');
        else if (role === '2') activeBtn.classList.add('bg-amber-50', 'text-amber-700');

        // Filter rows and cards
        document.querySelectorAll('.user-row, .user-card').forEach(el => {
            if (role === 'all' || el.getAttribute('data-role') === role) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    }
</script>

    <?= view('reports/modals/student_profile') ?>
    <?= view('partials/session_idle_modal') ?>
    <!-- ── Floating Scroll-to-Top Button ── -->
    <button id="scrollTopBtn" title="Back to top"
        aria-label="Scroll back to top"
        style="
            position: fixed;
            bottom: 28px;
            right: 24px;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            background: linear-gradient(135deg, #D9AA2E 0%, #B5881D 100%);
            color: #083318;
            box-shadow: 0 6px 20px -4px rgba(0,0,0,0.35), 0 0 0 3px rgba(217,170,46,0.20);
            opacity: 0;
            transform: translateY(16px) scale(0.85);
            pointer-events: none;
            transition: opacity 0.28s ease, transform 0.28s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.2s ease;
        ">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2.5"
             stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"/>
        </svg>
    </button>
    <script>
    (function () {
        var btn = document.getElementById('scrollTopBtn');
        var SHOW_AT = 400;
        function update() {
            var scrolled = window.scrollY || document.documentElement.scrollTop;
            if (scrolled > SHOW_AT) {
                btn.style.opacity = '1';
                btn.style.transform = 'translateY(0) scale(1)';
                btn.style.pointerEvents = 'auto';
            } else {
                btn.style.opacity = '0';
                btn.style.transform = 'translateY(16px) scale(0.85)';
                btn.style.pointerEvents = 'none';
            }
        }
        window.addEventListener('scroll', update, { passive: true });
        btn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        btn.addEventListener('mouseenter', function () {
            this.style.boxShadow = '0 10px 28px -4px rgba(0,0,0,0.45), 0 0 0 4px rgba(217,170,46,0.30)';
            this.style.transform = 'translateY(-2px) scale(1.07)';
        });
        btn.addEventListener('mouseleave', function () {
            this.style.boxShadow = '0 6px 20px -4px rgba(0,0,0,0.35), 0 0 0 3px rgba(217,170,46,0.20)';
            this.style.transform = 'translateY(0) scale(1)';
        });
    })();
    </script>
</body>
</html>


