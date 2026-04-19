<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentShield - Counselor Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                        primary: { DEFAULT: '#235D3A', light: '#2F754A', dark: '#19452B', darker: '#12331F', darkest: '#0A1C11' },
                        gold: { DEFAULT: '#D9AA2E', light: '#EAC255', bright: '#FCEEA1', dark: '#B5881D' },
                        calm: { 50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 600: '#16a34a' }
                    }
                }
            }
        }
    </script>
    <style>
        /* Responsive scaling for smaller monitors/laptops */
        @media (max-width: 1440px) { html { font-size: 15px; } }
        @media (max-width: 1280px) { html { font-size: 14px; } }
        @media (max-width: 1024px) { html { font-size: 13px; } }
        @media (max-width: 768px) { html { font-size: 16px; } } /* Reset for mobile */

        body {
            background-color: #f8faf6;
            background-image: radial-gradient(circle at 25px 25px, rgba(22, 101, 52, 0.02) 2px, transparent 0);
            background-size: 50px 50px;
        }

        .glass-header {
            background: #166534; /* Simple solid primary green */
            border-bottom: 3px solid #EAB308; /* Solid gold accent */
            /* Removed dark, heavy box-shadow */
        }

        .dashboard-card {
            background: #ffffff;
            border: 1px solid #e5ebe5;
            border-radius: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 2px rgba(0,0,0,0.02); /* Simple subtle shadow */
        }
        .dashboard-card:hover {
            box-shadow: 0 4px 12px rgba(22, 101, 52, 0.06); /* Lighter hover shadow */
            border-color: #cddacd;
        }

        .section-header {
            background: #ffffff;
            color: #166534;
            padding: 16px 24px;
            border-bottom: 1px solid #e5ebe5;
            border-left: 4px solid #166534;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #e5ebe5;
            border-radius: 12px;
            padding: 20px 24px;
            transition: all 0.25s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
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
        .stat-card:hover::after {
            opacity: 1;
        }
        .stat-card:hover {
            border-color: #d4ddd4;
            box-shadow: 0 4px 12px rgba(22, 101, 52, 0.05); /* Soft shadow */
            transform: translateY(-1px);
        }
        .stat-card.gold-accent {
            border-left: 4px solid #EAB308;
        }
        .stat-card.green-accent {
            border-left: 4px solid #166534;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f5f7f5;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c8dac8;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #0B5E2E;
        }

        /* Entry animations */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        .animate-slide-up {
            animation: slideUp 0.5s ease-out forwards;
        }
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        .live-dot {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-live 2s infinite;
        }
        @keyframes pulse-live {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .thead-green {
            background: #166534;
            color: white;
        }
        .thead-green th {
            color: rgba(255,255,255,0.92);
            font-weight: 600;
            letter-spacing: 0.05em;
            font-size: 0.7rem;
        }

        /* Row highlight animation for new entries */
        @keyframes newRow {
            0% { background-color: rgba(242, 195, 0, 0.12); }
            100% { background-color: transparent; }
        }

        .footer-bar {
            background: #166534;
            color: rgba(255,255,255,0.7);
            font-size: 0.75rem;
            padding: 12px 0;
            text-align: center;
            letter-spacing: 0.03em;
        }

        /* Mobile Card Tables */
        @media (max-width: 768px) {
            .mobile-card-table, .mobile-card-table thead, .mobile-card-table tbody, .mobile-card-table th, .mobile-card-table td, .mobile-card-table tr { display: block; width: 100%; }
            .mobile-card-table thead { display: none; }
            .mobile-card-table tr { margin-bottom: 1rem; background: #fff; border-radius: 12px; border: 1px solid #e5ebe5; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden; }
            .mobile-card-table td { border-bottom: 1px dashed #f3f4f6; padding: 12px 16px !important; display: flex; flex-direction: column; gap: 4px; text-align: left !important; }
            .mobile-card-table td:last-child { border-bottom: none; background: #f8faf6; }
            .mobile-card-table td::before { content: attr(data-label); font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 800; color: #9ca3af; }
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 min-h-screen flex flex-col relative">

    <!-- Navbar -->
    <nav class="glass-header text-white sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <img src="<?= base_url('images/logo.png?v=6') ?>" alt="SVBSM Logo" class="h-24 w-24 object-contain"
                     onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%23F2C300\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z\'/></svg>';">

                <div>
                    <h1 class="text-2xl font-bold tracking-tight">StudentShield</h1>
                    <span class="text-[#F2C300] text-xs font-semibold tracking-widest uppercase">Counselor Dashboard</span>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Live Philippine Clock -->
                <div class="hidden md:flex items-center gap-2 bg-white/10 px-4 py-2 rounded-xl border border-white/15">
                    <svg class="w-4 h-4 text-[#F2C300] flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div class="flex flex-col leading-tight">
                        <span id="liveDayDate" class="text-[10px] font-bold text-[#FACC15] uppercase tracking-wider"><?= date('l, M j, Y') ?></span>
                        <span id="liveTime" class="text-xs font-semibold text-white/90 tabular-nums"><?= date('g:i:s A') ?></span>
                    </div>
                </div>

                <!-- Status indicator -->
                <div class="hidden lg:flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-full border border-white/15">
                    <span class="w-2 h-2 rounded-full bg-[#EAB308] live-dot"></span>
                    <span class="text-xs font-semibold text-[#FACC15]">System Active</span>
                </div>

                <!-- Role Badge -->
                <?php if (session()->get('role') == 2): ?>
                    <span class="hidden md:inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#EAB308]/20 border border-[#EAB308]/30 text-[#FACC15] text-xs font-semibold">Super Admin</span>
                <?php else: ?>
                    <span class="hidden md:inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/10 border border-white/15 text-white/70 text-xs font-semibold">Counselor</span>
                <?php endif; ?>

                <!-- User Management (Super Admin Only) -->
                <?php if (session()->get('role') == 2): ?>
                <a href="/admin/users" class="flex items-center gap-2 text-sm font-medium text-[#EAB308]/80 hover:text-[#EAB308] hover:bg-white/10 px-3 py-2 rounded-lg transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Users
                </a>
                <?php endif; ?>

                <a href="<?= base_url('logout') ?>" class="flex items-center gap-2 text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 px-4 py-2 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 sm:px-6 py-8 flex-1 animate-fade-in">
        
        <!-- Welcome Metric Row -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-5 animate-slide-up" style="animation-delay: 0.1s">
            <div>
                <h2 class="text-2xl font-bold text-[#0B5E2E] flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#F2C300]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    Overview
                </h2>
                <p class="text-gray-500 text-sm mt-1">Real-time monitoring and analytics from the student portal.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                <div class="stat-card green-accent flex items-center gap-4">
                    <div class="h-10 w-10 rounded-lg bg-[#0B5E2E]/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#0B5E2E]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Total Active</span>
                        <span class="text-2xl font-bold text-[#0B5E2E] leading-tight" id="stat-total">--</span>
                    </div>
                </div>
                <div class="stat-card gold-accent flex items-center gap-4">
                    <div class="h-10 w-10 rounded-lg bg-[#F2C300]/15 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#B89100]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Critical Pending</span>
                        <span class="text-2xl font-bold text-[#E04B3F] leading-tight" id="stat-critical">--</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="dashboard-card p-6 animate-slide-up" style="animation-delay: 0.2s">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-base font-semibold text-[#0B5E2E] flex items-center gap-2">
                        <svg class="h-5 w-5 text-[#F2C300]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                        Risk Distribution
                    </h2>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="riskPieChart"></canvas>
                </div>
            </div>
            
            <div class="dashboard-card p-6 animate-slide-up" style="animation-delay: 0.3s">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-base font-semibold text-[#0B5E2E] flex items-center gap-2">
                        <svg class="h-5 w-5 text-[#F2C300]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        Last 7 Days Volume
                    </h2>
                </div>
                <div class="relative h-64 w-full">
                    <div id="ventsBarChart"></div>
                </div>
            </div>
        </div>

        <!-- Live Feed Table -->
        <div class="dashboard-card overflow-hidden animate-slide-up flex flex-col" style="animation-delay: 0.4s">
            <!-- Table Header Bar -->
            <div class="section-header flex justify-between items-center sm:flex-row flex-col gap-4">
                <h2 class="text-lg font-bold flex items-center gap-3">
                    <svg class="h-5 w-5 text-[#0B5E2E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Live Intelligence Feed
                </h2>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer bg-gray-50 hover:bg-gray-100 px-3 py-1.5 rounded-full border border-gray-200 transition-colors" for="toggleArchivedVents">
                        <input type="checkbox" id="toggleArchivedVents" class="rounded border-gray-300 text-[#0B5E2E] shadow-sm w-3.5 h-3.5" onchange="toggleVentsView(this.checked)">
                        <span class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Show Archived</span>
                    </label>
                    <div class="flex items-center gap-2 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 live-dot"></span>
                        <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-widest">Live Syncing</span>
                    </div>
                </div>
            </div>
            
            <!-- Table Container -->
            <form action="<?= base_url('counselor/vent/delete-multiple') ?>" method="POST" id="bulkDeleteVentsForm" class="overflow-x-auto custom-scrollbar flex flex-col">
                <div class="hidden"><?= csrf_field() ?></div>
                <div class="px-6 py-2 flex justify-end bg-white">
                    <button type="submit" onclick="return confirm('Archive selected vents?');" class="text-xs font-bold text-[#0B5E2E] bg-[#0B5E2E]/5 hover:bg-[#0B5E2E]/10 px-4 py-2 rounded-lg border border-[#0B5E2E]/20 transition-colors shadow-sm delete-btn-vents hidden">
                        📦 Archive Selected
                    </button>
                </div>
                <table class="w-full text-left border-collapse mobile-card-table">
                    <thead>
                        <tr class="thead-green text-xs uppercase tracking-wider">
                            <th class="px-4 py-4 w-10 text-center"><input type="checkbox" id="selectAllVents" class="rounded border-white/40 text-[#F2C300]"></th>
                            <th class="px-6 py-4">Timestamp</th>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4 w-1/3">NLP Extract</th>
                            <th class="px-6 py-4 text-center">Risk Indicator</th>
                            <th class="px-6 py-4 text-center">Threat Level</th>
                            <th class="px-6 py-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="ventsTableBody" class="divide-y divide-gray-100">
                        <!-- Populated by AJAX -->
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <svg class="animate-spin h-8 w-8 mx-auto mb-4 text-[#0B5E2E]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p>Initiating secure connection to StudentShield Core...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
        
        <!-- Bullying Incidents Table -->
        <div class="dashboard-card overflow-hidden animate-slide-up flex flex-col mt-8" style="animation-delay: 0.5s">
            <div class="section-header flex justify-between items-center sm:flex-row flex-col gap-4">
                <h2 class="text-lg font-bold flex items-center gap-3">
                    <svg class="h-5 w-5 text-[#E04B3F]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Bullying Incident Reports
                </h2>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer bg-gray-50 hover:bg-gray-100 px-3 py-1.5 rounded-full border border-gray-200 transition-colors" for="toggleArchivedIncidents">
                        <input type="checkbox" id="toggleArchivedIncidents" class="rounded border-gray-300 text-[#0B5E2E] shadow-sm w-3.5 h-3.5" onchange="toggleIncidentsView(this.checked)">
                        <span class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Show Archived</span>
                    </label>
                </div>
            </div>
            
            <form action="<?= base_url('counselor/incident/delete-multiple') ?>" method="POST" id="bulkDeleteIncidentsForm" class="overflow-x-auto custom-scrollbar flex flex-col">
                <div class="hidden"><?= csrf_field() ?></div>
                <div class="px-6 py-2 flex justify-end bg-white">
                    <button type="submit" onclick="return confirm('Archive selected incident reports?');" class="text-xs font-bold text-[#0B5E2E] bg-[#0B5E2E]/5 hover:bg-[#0B5E2E]/10 px-4 py-2 rounded-lg border border-[#0B5E2E]/20 transition-colors shadow-sm delete-btn-incidents hidden">
                        📦 Archive Selected
                    </button>
                </div>
                <table class="w-full text-left border-collapse mobile-card-table">
                    <thead>
                        <tr class="thead-green text-xs uppercase tracking-wider">
                            <th class="px-4 py-4 w-10 text-center"><input type="checkbox" id="selectAllIncidents" class="rounded border-white/40 text-[#F2C300]"></th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Reporter</th>
                            <th class="px-6 py-4">Perpetrator</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4 w-1/3">Description</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="incidentsTableBody" class="divide-y divide-gray-100">
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <p>Loading reports...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>

        <!-- Alert Logs Monitor -->
        <div class="dashboard-card overflow-hidden animate-slide-up flex flex-col mt-8" style="animation-delay: 0.6s">
            <div class="section-header flex justify-between items-center sm:flex-row flex-col gap-4">
                <h2 class="text-lg font-bold flex items-center gap-3">
                    <svg class="h-5 w-5 text-[#2A5DB0]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Alert Delivery Monitor
                </h2>
                <div class="flex items-center gap-2 bg-blue-50 px-3 py-1.5 rounded-full border border-blue-200">
                    <span class="w-2 h-2 rounded-full bg-blue-500 live-dot"></span>
                    <span class="text-[10px] font-bold text-blue-700 uppercase tracking-widest">System Log</span>
                </div>
            </div>
            
            <div class="overflow-x-auto custom-scrollbar flex flex-col max-h-[400px]">
                <table class="w-full text-left border-collapse relative mobile-card-table">
                    <thead class="sticky top-0 thead-green z-10">
                        <tr class="text-xs uppercase tracking-wider">
                            <th class="px-6 py-4">Timestamp</th>
                            <th class="px-6 py-4">Method</th>
                            <th class="px-6 py-4">Recipient</th>
                            <th class="px-6 py-4 w-1/2">Payload / Message</th>
                        </tr>
                    </thead>
                    <tbody id="alertsTableBody" class="divide-y divide-gray-100">
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                <p>Loading delivery logs...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Universal Detail Modal -->
        <div id="detailModal" class="fixed inset-0 z-[100] hidden items-center justify-center">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" id="detailBackdrop" onclick="closeDetailModal()"></div>
            <div class="bg-white p-6 sm:p-8 max-w-2xl w-[95%] rounded-2xl shadow-2xl relative z-10 flex flex-col max-h-[90vh] border border-[#e5ebe5]">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div id="modalBadge" class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest mb-2 border"></div>
                        <h2 id="modalTitle" class="text-2xl font-heading font-extrabold text-[#0B5E2E] tracking-tight">Report Details</h2>
                    </div>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-red-500 transition-colors p-2 bg-gray-50 hover:bg-red-50 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="flex items-center gap-6 mb-6 pb-6 border-b border-gray-100">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Involved Party / Sender</p>
                        <p id="modalSender" class="text-sm font-semibold text-gray-800">--</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Timestamp</p>
                        <p id="modalTime" class="text-sm font-semibold text-gray-800">--</p>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 pb-4">
                    <p class="text-[11px] font-bold text-[#0B5E2E] uppercase tracking-widest mb-2">Full Context Payload</p>
                    <div id="modalContent" class="text-[16px] leading-[1.85] tracking-wide text-gray-800 bg-[#f8faf6] border border-[#e5ebe5] shadow-inner p-7 rounded-2xl whitespace-pre-wrap font-medium">
                        --
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                    <button onclick="closeDetailModal()" class="px-6 py-3 bg-[#0B5E2E] hover:bg-[#0F6A34] text-white text-sm font-bold rounded-xl transition-colors shadow-sm">Confirm & Close</button>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-bar mt-auto">
        <div class="container mx-auto px-6">
            <span>© <?= date('Y') ?> StudentShield — St. Vincent Blessed School of Manila</span>
            <span class="mx-2">·</span>
            <span>Intelligent Student Wellness Platform</span>
        </div>
    </footer>

    <!-- AJAX Logic & Chart Rendering -->
    <script>
        const API_URL = "<?= base_url('counselor/api/vents') ?>";
        let pieChart, barChart;
        let previousVentsDataString = "";
        let isFirstLoadVents = true;
        
        // Enable jQuery AJAX caching to rely on browser cache if Etags match
        $.ajaxSetup({ cache: true });

        // Set modern defaults for Chart.js
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#64748b';
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(15, 23, 42, 0.9)';
        Chart.defaults.plugins.tooltip.padding = 12;
        Chart.defaults.plugins.tooltip.cornerRadius = 8;

        const pieCtx = document.getElementById('riskPieChart').getContext('2d');
        const pieConfig = {
            type: 'doughnut',
            data: {
                labels: ['Critical', 'High', 'Medium', 'Low'],
                datasets: [{
                    data: [0, 0, 0, 0],
                    backgroundColor: ['#E04B3F', '#F59E0B', '#2A5DB0', '#0B5E2E'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: { 
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                }
            }
        };
        pieChart = new Chart(pieCtx, pieConfig);

        const barCtx = document.querySelector("#ventsBarChart");
        
        const barConfig = {
            series: [{
                name: 'Vents Processed',
                data: [0,0,0,0,0,0,0]
            }],
            chart: {
                type: 'area',
                height: 250,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            colors: ['#0B5E2E'],
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.1, stops: [0, 90, 100] }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                categories: ['6d', '5d', '4d', '3d', '2d', '1d', 'Today'],
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: { show: false },
            grid: {
                borderColor: 'rgba(0,0,0,0.05)',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            }
        };
        
        let areaChart = new ApexCharts(barCtx, barConfig);
        areaChart.render();
        
        let cachedDateLabels = null;

        function updateCharts(data) {
            let critical = 0, high = 0, medium = 0, low = 0;
            let criticalPending = 0;
            
            data.forEach(v => {
                if(v.risk_level === 'CRITICAL') {
                    critical++;
                    if(v.status !== 'Resolved') criticalPending++;
                }
                else if(v.risk_level === 'HIGH') high++;
                else if(v.risk_level === 'MEDIUM') medium++;
                else low++;
            });

            $('#stat-total').text(data.length);
            $('#stat-critical').text(criticalPending);

            // Update Pie Chart seamlessly without destroying
            pieChart.data.datasets[0].data = [critical, high, medium, low];
            pieChart.update('none'); // Update without full animation redraw

            // Only calculate Date labels once per session to save CPU
            if (!cachedDateLabels) {
                cachedDateLabels = [];
                let today = new Date();
                today.setHours(0,0,0,0);
                for (let i = 6; i >= 0; i--) {
                    let d = new Date(today);
                    d.setDate(d.getDate() - i);
                    let label = d.toLocaleDateString('en-US', { weekday: 'short' });
                    cachedDateLabels.push(i === 0 ? 'Today' : label);
                }
                areaChart.updateOptions({ xaxis: { categories: cachedDateLabels } });
            }

            let daysData = [0, 0, 0, 0, 0, 0, 0];
            let today = new Date();
            today.setHours(0,0,0,0);
            
            data.forEach(v => {
                let vDate = parsePHDate(v.created_at);
                vDate.setHours(0,0,0,0);
                let diffTime = Math.abs(today - vDate);
                let diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                if (diffDays <= 6 && diffDays >= 0) {
                    daysData[6 - diffDays]++;
                }
            });

            areaChart.updateSeries([{ data: daysData }]);
        }

        // Bulletproof Date parsing for Safari & mobile browsers turning PHT times into UTC
        function parsePHDate(dateStr) {
            if (!dateStr) return new Date();
            if (typeof dateStr === 'string' && dateStr.includes(' ') && !dateStr.includes('+')) {
                return new Date(dateStr.replace(' ', 'T') + '+08:00');
            }
            return new Date(dateStr);
        }

        function escapeHtml(unsafe) {
            return (unsafe || '').toString()
                 .replace(/&/g, "&amp;")
                 .replace(/</g, "&lt;")
                 .replace(/>/g, "&gt;")
                 .replace(/"/g, "&quot;")
                 .replace(/'/g, "&#039;");
        }

        let showArchivedVents = false;
        let showArchivedIncidents = false;

        function toggleVentsView(isArchived) {
            showArchivedVents = isArchived;
            previousVentsDataString = ""; 
            fetchLiveVents();
            
            let form = document.getElementById('bulkDeleteVentsForm');
            let btn = form.querySelector('.delete-btn-vents');
            if (isArchived) {
                form.action = "<?= base_url('counselor/vent/restore-multiple') ?>";
                btn.innerHTML = "⟲ Restore Selected";
                btn.className = "text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-lg border border-blue-200 transition-colors shadow-sm delete-btn-vents " + (btn.classList.contains('hidden') ? 'hidden' : '');
            } else {
                form.action = "<?= base_url('counselor/vent/delete-multiple') ?>";
                btn.innerHTML = "📦 Archive Selected";
                btn.className = "text-xs font-bold text-[#0B5E2E] bg-[#0B5E2E]/5 hover:bg-[#0B5E2E]/10 px-4 py-2 rounded-lg border border-[#0B5E2E]/20 transition-colors shadow-sm delete-btn-vents " + (btn.classList.contains('hidden') ? 'hidden' : '');
            }
        }

        function toggleIncidentsView(isArchived) {
            showArchivedIncidents = isArchived;
            previousIncidentsDataString = ""; 
            fetchLiveIncidents();
            
            let form = document.getElementById('bulkDeleteIncidentsForm');
            let btn = form.querySelector('.delete-btn-incidents');
            if (isArchived) {
                form.action = "<?= base_url('counselor/incident/restore-multiple') ?>";
                btn.innerHTML = "⟲ Restore Selected";
                btn.className = "text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-lg border border-blue-200 transition-colors shadow-sm delete-btn-incidents " + (btn.classList.contains('hidden') ? 'hidden' : '');
            } else {
                form.action = "<?= base_url('counselor/incident/delete-multiple') ?>";
                btn.innerHTML = "📦 Archive Selected";
                btn.className = "text-xs font-bold text-[#0B5E2E] bg-[#0B5E2E]/5 hover:bg-[#0B5E2E]/10 px-4 py-2 rounded-lg border border-[#0B5E2E]/20 transition-colors shadow-sm delete-btn-incidents " + (btn.classList.contains('hidden') ? 'hidden' : '');
            }
        }

        function fetchLiveVents() {
            $.ajax({
                url: API_URL + (showArchivedVents ? "?archived=1" : ""),
                dataType: 'json',
                success: function(data) {
                    let currentDataString = JSON.stringify(data);
                    if (currentDataString === previousVentsDataString) return;
                    previousVentsDataString = currentDataString;
                    
                    let html = '';
                    window.ventsDataMap = {};

                    if (data.length === 0) {
                        html = `<tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">No entries found yet.</td></tr>`;
                    } else {
                    data.forEach((vent, index) => {
                        window.ventsDataMap[vent.id] = vent;
                        let textSnippet = vent.raw_text.length > 55 ? vent.raw_text.substring(0, 55) + '...' : vent.raw_text;
                        let bgColor = 'bg-white';
                        let badgeClass = 'bg-gray-100 text-gray-800';
                        let statusIcon = '';

                        // Threat logic (Color-coded Triage)
                        if (vent.risk_level === 'CRITICAL') {
                            bgColor = 'bg-white hover:bg-gray-50 border-l-4 border-l-[#E04B3F] relative';
                            badgeClass = 'bg-[#E04B3F] text-white font-bold border-none';
                        } else if (vent.risk_level === 'HIGH') {
                            bgColor = 'bg-white hover:bg-gray-50 border-l-4 border-l-[#F59E0B] relative';
                            badgeClass = 'bg-[#F59E0B] text-white font-bold border-none';
                        } else if (vent.risk_level === 'MEDIUM') {
                            bgColor = 'bg-white hover:bg-gray-50 border-l-4 border-l-[#2A5DB0]';
                            badgeClass = 'bg-[#2A5DB0]/10 text-[#2A5DB0] border border-[#2A5DB0]/20 font-semibold';
                        } else {
                            bgColor = 'bg-white hover:bg-gray-50 border-l-4 border-l-[#0B5E2E]';
                            badgeClass = 'bg-[#0B5E2E]/10 text-[#0B5E2E] border border-[#0B5E2E]/20';
                        }

                        // Status formatting
                        let isResolved = vent.status === 'Resolved';
                        let statusColor = isResolved ? 'text-[#0B5E2E] bg-[#0B5E2E]/10' : 'text-gray-500 bg-gray-50';
                        
                        // Formatting dates with day name for Philippine context
                        const dateObj = parsePHDate(vent.created_at);
                        const timeStr = dateObj.toLocaleTimeString('en-PH', {hour: '2-digit', minute:'2-digit'});
                        const dayName = dateObj.toLocaleDateString('en-PH', {weekday: 'short'});
                        const dateStr = dateObj.toLocaleDateString('en-PH', {month: 'short', day: 'numeric'});

                        // Animation delay for new loads
                        const animDelay = isFirstLoadVents ? `style="animation-delay: ${index * 0.05}s"` : '';
                        const animClass = isFirstLoadVents ? 'animate-slide-up opacity-0' : '';

                        html += `<tr class="transition-all duration-300 transform hover:-translate-y-[1px] ${bgColor} ${animClass}" ${animDelay}>
                            ${vent.risk_level === 'CRITICAL' && !isResolved ? `` : ''}
                            <td data-label="Select" class="px-4 py-4 whitespace-nowrap text-center relative z-10">
                                <input type="checkbox" name="vent_ids[]" value="${vent.id}" class="vent-checkbox rounded border-gray-300 text-primary h-4 w-4">
                            </td>
                            <td data-label="Timestamp" class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-800">${timeStr}</div>
                                <div class="text-xs text-gray-500">${dayName}, ${dateStr}</div>
                            </td>
                            <td data-label="Student" class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs mr-3">
                                        ${escapeHtml(vent.name.charAt(0))}
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900 block">${escapeHtml(vent.name)}</span>
                                        ${vent.section ? `<span class="text-[11px] text-gray-500 font-bold block mt-0.5">Section: <span class="font-normal text-gray-600">${escapeHtml(vent.section)}</span></span>` : ''}
                                        ${vent.adviser_name ? `<span class="text-[11px] text-gray-500 font-bold block mb-1">Adviser: <span class="font-normal text-gray-600">${escapeHtml(vent.adviser_name)}</span></span>` : ''}
                                        ${vent.parent_phone ? `<span class="text-[10px] text-gray-400 block pt-0.5 border-t border-gray-100 mt-1">📞 ${escapeHtml(vent.parent_phone)}</span>` : ''}
                                        ${vent.parent_email ? `<span class="text-[10px] text-gray-400 block">✉️ ${escapeHtml(vent.parent_email)}</span>` : ''}
                                    </div>
                                </div>
                            </td>
                            <td data-label="NLP Extract" class="px-6 py-4 cursor-pointer group" onclick="openVentModal(${vent.id})">
                                <p class="text-sm text-gray-600 italic line-clamp-2">"${escapeHtml(textSnippet)}"</p>
                                <span class="text-[10px] text-[#0B5E2E] font-bold mt-1 inline-block opacity-0 group-hover:opacity-100 transition-opacity">READ FULL ↗</span>
                            </td>
                            <td data-label="Risk Indicator" class="px-6 py-4 whitespace-nowrap text-center">
                                ${(() => {
                                    let s = vent.risk_percent;
                                    let s_float = parseFloat(s);
                                    
                                    // Fallback UI mapping for legacy database records (decimals)
                                    if (Math.abs(s_float) <= 1 && s_float !== 0 && s.toString().indexOf('.') !== -1) { 
                                        // Legacy: -1 was bad, 1 was good
                                        // Convert to new Threat Percentage where 100 is bad, 1 is good
                                        s = Math.round(((1.0 - s_float) / 2.0) * 99) + 1;
                                    } else {
                                        s = Math.round(s_float);
                                    }
                                    
                                    // High percentage = High Threat
                                    let c = s >= 50 ? 'text-[#E04B3F] bg-[#E04B3F]/10' : 'text-[#0B5E2E] bg-[#0B5E2E]/10';
                                    return `<span class="text-sm font-mono font-medium ${c} px-2 py-1 rounded min-w-[3rem] inline-block">${s}%</span>`;
                                })()}
                            </td>
                            <td data-label="Threat Level" class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full ${badgeClass} shadow-sm">
                                    ${escapeHtml(vent.risk_level)}
                                </span>
                            </td>
                            <td data-label="Action" class="px-6 py-4 whitespace-nowrap text-center">
                                ${!isResolved && !showArchivedVents ? `
                                    <form action="<?= base_url('counselor/vent/resolve/') ?>${vent.id}" method="POST" class="block w-full mb-1">
                                        <button type="submit" class="w-full text-xs font-semibold text-primary bg-primary/10 hover:bg-primary/20 px-3 py-1.5 rounded-md transition-colors">
                                            Mark Resolved
                                        </button>
                                    </form>
                                ` : (!showArchivedVents ? `
                                    <span class="text-xs font-semibold px-3 py-1.5 rounded-md ${statusColor} border border-[#0B5E2E]/20 flex items-center justify-center gap-1 mx-auto w-full mb-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Resolved
                                    </span>
                                ` : '')}
                                
                                ${showArchivedVents ? `
                                <form action="<?= base_url('counselor/vent/restore/') ?>${vent.id}" method="POST" class="block w-full">
                                    <button type="submit" onclick="return confirm('Restore this vent to the active dashboard?');" class="w-full text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition-colors">
                                        ⟲ Unarchive
                                    </button>
                                </form>
                                ` : `
                                <form action="<?= base_url('counselor/vent/delete/') ?>${vent.id}" method="POST" class="block w-full">
                                    <button type="submit" onclick="return confirm('Archive this vent?');" class="w-full text-xs font-semibold text-gray-500 bg-gray-50 hover:bg-gray-100 px-3 py-1.5 rounded-md transition-colors">
                                        Archive
                                    </button>
                                </form>
                                `}
                            </td>
                        </tr>`;
                    });
                }
                
                document.getElementById('ventsTableBody').innerHTML = html;
                
                updateCharts(data);
                isFirstLoadVents = false;
                
                toggleDeleteBtn('.vent-checkbox', '.delete-btn-vents');
                },
                error: function(xhr) {
                    if(xhr.status === 401) {
                        console.warn('Session expired. Please refresh and log in again.');
                    } else {
                        console.error("Vents API Error:", xhr.responseText);
                    }
                }
            });
        }

        // Incident Reports Polling
        const INCIDENTS_API_URL = "<?= base_url('counselor/api/incidents') ?>";
        let previousIncidentsDataString = "";

        function fetchLiveIncidents() {
            $.ajax({
                url: INCIDENTS_API_URL + (showArchivedIncidents ? "?archived=1" : ""),
                dataType: 'json',
                success: function(data) {
                    let currentDataString = JSON.stringify(data);
                    if (currentDataString === previousIncidentsDataString) return;
                    previousIncidentsDataString = currentDataString;
                    
                    let html = '';
                    window.incidentsDataMap = {};
                    
                    if (data.length === 0) {
                        html = `<tr><td colspan="8" class="px-6 py-8 text-center text-gray-500">No incident reports available.</td></tr>`;
                    } else {
                    data.forEach((incident, index) => {
                        window.incidentsDataMap[incident.id] = incident;
                        let textSnippet = incident.description.length > 55 ? incident.description.substring(0, 55) + '...' : incident.description;
                        let statusBadge = '';
                        let isPending = incident.status === 'pending_review';
                        let isVerified = incident.status === 'verified';
                        let isRejected = incident.status === 'rejected_fake';
                        let isResolved = incident.status === 'resolved' || incident.status === 'Resolved';
                        
                        if (isVerified) {
                            statusBadge = `<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold w-max mx-auto border border-green-200 block">✓ Verified</span>`;
                        } else if (isRejected) {
                            statusBadge = `<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold w-max mx-auto border border-red-200 block">✗ Rejected</span>`;
                        } else if (isResolved) {
                            statusBadge = `<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold w-max mx-auto border border-green-200 block">Resolved</span>`;
                        } else {
                            statusBadge = `<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold w-max mx-auto border border-yellow-200 animate-pulse block">⏳ Pending Review</span>`;
                        }

                        let reporterName = incident.name ? incident.name : 'Anonymous';
                        let perpetratorName = incident.perpetrator_name ? incident.perpetrator_name : 'Unknown';

                        const dateObj = parsePHDate(incident.created_at);
                        const dayName = dateObj.toLocaleDateString('en-PH', {weekday: 'short'});
                        const dateStr = dayName + ', ' + dateObj.toLocaleDateString('en-PH', {month: 'short', day: 'numeric', hour: '2-digit', minute:'2-digit'});

                        let bgColor = isPending ? 'bg-yellow-50/50 hover:bg-yellow-50 border-l-4 border-l-yellow-400' : (isRejected ? 'bg-red-50/30 hover:bg-red-50' : 'bg-white hover:bg-gray-50');

                        html += `<tr class="transition-colors duration-200 ${bgColor}">
                            <td data-label="Select" class="px-4 py-4 whitespace-nowrap text-center">
                                <input type="checkbox" name="incident_ids[]" value="${incident.id}" class="incident-checkbox rounded border-gray-300 text-primary h-4 w-4">
                            </td>
                            <td data-label="Date" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${dateStr}</td>
                            <td data-label="Reporter" class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900 block">${escapeHtml(reporterName)}</span>
                                ${incident.parent_phone ? `<span class="text-xs text-gray-500 block">📞 ${escapeHtml(incident.parent_phone)}</span>` : ''}
                                ${incident.parent_email ? `<span class="text-xs text-gray-500 block">✉️ ${escapeHtml(incident.parent_email)}</span>` : ''}
                            </td>
                            <td data-label="Perpetrator" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">${escapeHtml(perpetratorName)}</td>
                            <td data-label="Type" class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-semibold">${escapeHtml(incident.incident_type)}</td>
                            <td data-label="Description" class="px-6 py-4 cursor-pointer group" onclick="openIncidentModal(${incident.id})">
                                <p class="text-sm text-gray-600 italic line-clamp-2">"${escapeHtml(textSnippet)}"</p>
                                <span class="text-[10px] text-[#E04B3F] font-bold mt-1 inline-block opacity-0 group-hover:opacity-100 transition-opacity">READ FULL ↗</span>
                            </td>
                            <td data-label="Status" class="px-6 py-4 whitespace-nowrap text-center">${statusBadge}</td>
                            <td data-label="Action" class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col gap-2 min-w-[140px]">
                                ${isPending && !showArchivedIncidents ? `
                                    <form action="<?= base_url('counselor/incident/verify/') ?>${incident.id}" method="POST">
                                        <button type="submit" onclick="return confirm('VERIFY this report and send automated SMS & Email alerts to parents?');" class="w-full text-xs font-bold text-white bg-[#0B5E2E] hover:bg-[#0F6A34] px-3 py-2.5 rounded-lg transition-colors shadow-sm">
                                            ✓ Verify & Alert Parents
                                        </button>
                                    </form>
                                    <form action="<?= base_url('counselor/incident/reject/') ?>${incident.id}" method="POST">
                                        <button type="submit" onclick="return confirm('REJECT this report as fake/unverified? No parent alerts will be sent.');" class="w-full text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg transition-colors border border-red-200">
                                            ✗ Reject (Fake)
                                        </button>
                                    </form>
                                ` : ''}
                                ${isVerified && !showArchivedIncidents ? `
                                    <span class="text-xs font-semibold px-3 py-2 rounded-lg text-[#0B5E2E] bg-[#0B5E2E]/10 border border-[#0B5E2E]/20 flex items-center justify-center gap-1.5 w-full">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Alerts Sent
                                    </span>
                                    <form action="<?= base_url('counselor/incident/undo/') ?>${incident.id}" method="POST">
                                        <button type="submit" onclick="return confirm('Undo verification and reset to Pending Review? (Note: alerts already sent cannot be unsent)');" class="w-full text-[10px] font-semibold text-gray-500 hover:text-orange-600 bg-gray-50 hover:bg-orange-50 px-2 py-1.5 rounded-lg transition-colors border border-gray-200 hover:border-orange-200">
                                            ↩ Undo (Nagkamali?)
                                        </button>
                                    </form>
                                ` : ''}
                                ${isRejected && !showArchivedIncidents ? `
                                    <span class="text-xs font-semibold px-3 py-2 rounded-lg text-red-500 bg-red-50 border border-red-200 flex items-center justify-center gap-1 w-full">
                                        No Alerts Sent
                                    </span>
                                    <form action="<?= base_url('counselor/incident/undo/') ?>${incident.id}" method="POST">
                                        <button type="submit" onclick="return confirm('Undo rejection and reset to Pending Review so you can re-evaluate?');" class="w-full text-[10px] font-semibold text-gray-500 hover:text-orange-600 bg-gray-50 hover:bg-orange-50 px-2 py-1.5 rounded-lg transition-colors border border-gray-200 hover:border-orange-200">
                                            ↩ Undo (Nagkamali?)
                                        </button>
                                    </form>
                                ` : ''}
                                ${isResolved && !showArchivedIncidents ? `
                                    <span class="text-xs font-semibold px-3 py-2 rounded-lg text-[#0B5E2E] bg-[#0B5E2E]/10 border border-[#0B5E2E]/20 flex items-center justify-center gap-1.5 w-full">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Resolved
                                    </span>
                                ` : ''}
                                
                                <hr class="border-gray-100 my-0.5">
                                
                                ${showArchivedIncidents ? `
                                <form action="<?= base_url('counselor/incident/restore/') ?>${incident.id}" method="POST">
                                    <button type="submit" onclick="return confirm('Restore this incident report to the active dashboard?');" class="w-full text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                        ⟲ Unarchive
                                    </button>
                                </form>
                                ` : `
                                <form action="<?= base_url('counselor/incident/delete/') ?>${incident.id}" method="POST">
                                    <button type="submit" onclick="return confirm('Archive this incident report?');" class="w-full text-xs font-semibold text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 px-3 py-1.5 rounded-lg transition-colors">
                                        Archive
                                    </button>
                                </form>
                                `}
                                </div>
                            </td>
                        </tr>`;
                    });
                }
                
                document.getElementById('incidentsTableBody').innerHTML = html;
                
                toggleDeleteBtn('.incident-checkbox', '.delete-btn-incidents');
                },
                error: function(xhr) {
                    if(xhr.status === 401) {
                        console.warn('Session expired. Please refresh and log in again.');
                    } else {
                        console.error("Incidents API Error:", xhr.responseText);
                    }
                }
            });
        }

        // Alert Logs Polling
        const ALERTS_API_URL = "<?= base_url('counselor/api/alerts') ?>";
        let previousAlertsDataString = "";

        function fetchAlertLogs() {
            $.ajax({
                url: ALERTS_API_URL,
                dataType: 'json',
                success: function(data) {
                    let currentDataString = JSON.stringify(data);
                    if (currentDataString === previousAlertsDataString) return;
                    previousAlertsDataString = currentDataString;
                    
                    let html = '';
                    window.alertsDataMap = {};
                    
                    if (data.length === 0) {
                        html = `<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No automated alerts have been sent yet.</td></tr>`;
                    } else {
                        data.forEach((alert, index) => {
                            window.alertsDataMap[index] = alert;
                            let badgeClass = alert.type.includes('SMS') ? 'bg-blue-100 text-blue-700 border-blue-200' : 'bg-purple-100 text-purple-700 border-purple-200';
                            let icon = alert.type.includes('SMS') ? '📱' : '✉️';
                            
                            const dateObj = parsePHDate(alert.timestamp);
                            const dayName = dateObj.toLocaleDateString('en-PH', {weekday: 'short'});
                            const dateStr = dayName + ', ' + dateObj.toLocaleDateString('en-PH', {month: 'short', day: 'numeric', hour: '2-digit', minute:'2-digit'});

                            html += `<tr class="transition-colors duration-200 bg-white hover:bg-gray-50">
                                <td data-label="Timestamp" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">${dateStr}</td>
                                <td data-label="Method" class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-md border ${badgeClass}">
                                        ${icon} ${escapeHtml(alert.type)}
                                    </span>
                                </td>
                                <td data-label="Recipient" class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">${escapeHtml(alert.recipient)}</td>
                                <td data-label="Payload / Message" class="px-6 py-4 cursor-pointer group" onclick="openAlertModal(${index})">
                                    <p class="text-sm text-gray-600 line-clamp-2 italic">"${escapeHtml(alert.message)}"</p>
                                    <span class="text-[10px] text-[#2A5DB0] font-bold mt-1 inline-block opacity-0 group-hover:opacity-100 transition-opacity">VIEW PAYLOAD ↗</span>
                                </td>
                            </tr>`;
                        });
                    }
                    document.getElementById('alertsTableBody').innerHTML = html;
                },
                error: function(xhr) {
                    console.error("Alerts API Error:", xhr.responseText);
                }
            });
        }

        function openDetailModal(type, sender, timeStr, fullText, badgeText, badgeClass) {
            document.getElementById('modalTitle').innerText = type;
            document.getElementById('modalSender').innerText = sender;
            document.getElementById('modalTime').innerText = timeStr;
            document.getElementById('modalContent').innerText = fullText;
            
            const badge = document.getElementById('modalBadge');
            badge.innerText = badgeText;
            badge.className = `inline-flex px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest mb-2 border ${badgeClass}`;

            const modal = document.getElementById('detailModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Subtle pop animation
            const modalBox = modal.querySelector('div.bg-white');
            modalBox.animate([{ opacity: 0, transform: 'scale(0.95)' }, { opacity: 1, transform: 'scale(1)' }], { duration: 200, fill: 'forwards', easing: 'ease-out' });
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
        }

        function openVentModal(id) {
            const vent = window.ventsDataMap[id];
            if (!vent) return;
            const dateObj = parsePHDate(vent.created_at);
            const timeStr = dateObj.toLocaleTimeString('en-PH', {hour: '2-digit', minute:'2-digit'});
            const dateStr = dateObj.toLocaleDateString('en-PH', {weekday: 'long', month: 'short', day: 'numeric'});
            
            let senderInfo = vent.name || 'Anonymous';
            if (vent.section) senderInfo += `\nSection: ${vent.section}`;
            if (vent.adviser_name) senderInfo += `\nAdviser: ${vent.adviser_name}`;
            
            const badgeClass = vent.risk_level === 'CRITICAL' ? 'bg-[#E04B3F] text-white border-transparent' : (vent.risk_level === 'HIGH' ? 'bg-[#F59E0B] text-white border-transparent' : 'bg-[#0B5E2E]/10 text-[#0B5E2E] border-[#0B5E2E]/20');
            openDetailModal('Safe Space Vent Entry', senderInfo, `${dateStr} ${timeStr}`, vent.raw_text, `Threat Level: ${vent.risk_level}`, badgeClass);
        }

        function openIncidentModal(id) {
            const inc = window.incidentsDataMap[id];
            if (!inc) return;
            const reporter = inc.name || 'Anonymous';
            const perp = inc.perpetrator_name || 'Unknown';
            const dateObj = parsePHDate(inc.created_at);
            const dateStr = dateObj.toLocaleDateString('en-PH', {weekday: 'long', month: 'short', day: 'numeric', hour: '2-digit', minute:'2-digit'});
            const isRes = inc.status === 'Resolved';
            const badgeClass = isRes ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
            
            openDetailModal('Bullying Incident Report', `Reporter: ${reporter}\nAccused: ${perp}`, dateStr, inc.description, isRes ? 'Resolved' : 'Action Required', badgeClass);
        }

        function openAlertModal(index) {
            const alert = window.alertsDataMap[index];
            if (!alert) return;
            const dateObj = parsePHDate(alert.timestamp);
            const dateStr = dateObj.toLocaleDateString('en-PH', {weekday: 'long', month: 'short', day: 'numeric', hour: '2-digit', minute:'2-digit'});
            const isSms = alert.type.includes('SMS');
            const badgeClass = isSms ? 'bg-blue-100 text-blue-700 border-blue-200' : 'bg-purple-100 text-purple-700 border-purple-200';
            const icon = isSms ? '📱' : '✉️';
            
            openDetailModal('Automated Alert Log', alert.recipient, dateStr, alert.message, `${icon} ${alert.type}`, badgeClass);
        }

        function toggleDeleteBtn(checkboxClass, btnSelector) {
            if ($(checkboxClass + ':checked').length > 0) {
                $(btnSelector).removeClass('hidden');
            } else {
                $(btnSelector).addClass('hidden');
            }
        }

        // Initialize First Fetch
        $(document).ready(function() {
            // Checkbox logic
            $(document).on('change', '#selectAllVents', function() {
                $('.vent-checkbox').prop('checked', $(this).prop('checked'));
                toggleDeleteBtn('.vent-checkbox', '.delete-btn-vents');
            });
            $(document).on('change', '.vent-checkbox', function() {
                toggleDeleteBtn('.vent-checkbox', '.delete-btn-vents');
                if($('.vent-checkbox:checked').length !== $('.vent-checkbox').length) {
                    $('#selectAllVents').prop('checked', false);
                }
            });

            $(document).on('change', '#selectAllIncidents', function() {
                $('.incident-checkbox').prop('checked', $(this).prop('checked'));
                toggleDeleteBtn('.incident-checkbox', '.delete-btn-incidents');
            });
            $(document).on('change', '.incident-checkbox', function() {
                toggleDeleteBtn('.incident-checkbox', '.delete-btn-incidents');
                if($('.incident-checkbox:checked').length !== $('.incident-checkbox').length) {
                    $('#selectAllIncidents').prop('checked', false);
                }
            });

            // Fetch immediately upon load for 0-lag experience
            fetchLiveVents();
            fetchLiveIncidents();
            fetchAlertLogs();
            
            // Poll every 30 seconds for a snappy, real-time presentation feel while reducing server load
            setInterval(fetchLiveVents, 30000);
            setInterval(fetchLiveIncidents, 30000);
            setInterval(fetchAlertLogs, 30000);

            // Live Philippine Clock — seeded from server (Manila timezone guaranteed)
            const serverTimestamp = <?= (time() + 28800) * 1000 ?>; // +8 hours for Manila time (PHT) in ms
            const clientLoadTime = Date.now();
            const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
            const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

            function updateClock() {
                const elapsed = Date.now() - clientLoadTime;
                const phNow = new Date(serverTimestamp + elapsed);
                const dayDate = days[phNow.getUTCDay()] + ', ' + months[phNow.getUTCMonth()] + ' ' + phNow.getUTCDate() + ', ' + phNow.getUTCFullYear();
                const h = phNow.getUTCHours();
                const ampm = h >= 12 ? 'PM' : 'AM';
                const h12 = h % 12 || 12;
                const m = String(phNow.getUTCMinutes()).padStart(2, '0');
                const s = String(phNow.getUTCSeconds()).padStart(2, '0');
                document.getElementById('liveDayDate').textContent = dayDate;
                document.getElementById('liveTime').textContent = h12 + ':' + m + ':' + s + ' ' + ampm;
            }
            updateClock();
            setInterval(updateClock, 1000);
        });
    </script>
</body>
</html>
