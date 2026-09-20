<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title>StudentShield - Path Selection</title>
    <link rel="preload" as="image" href="<?= base_url('images/logo.png?v=6') ?>">
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;700;800;900&display=swap" rel="stylesheet">
    <style>
        .glass-card {
            background: padding-box linear-gradient(to bottom, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            backdrop-filter: blur(20px);
        }
        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
            background: padding-box linear-gradient(to bottom, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0.95));
        }
        .bg-theme-glow {
            background-image: radial-gradient(circle at 15% 50%, rgba(234, 179, 8, 0.15), transparent 40%),
                              radial-gradient(circle at 85% 30%, rgba(255, 255, 255, 0.05), transparent 40%);
        }
    </style>
</head>
<body class="text-white antialiased min-h-screen flex flex-col items-center justify-center relative overflow-x-hidden bg-primary-dark bg-gradient-to-br from-primary-dark via-primary-darker to-primary-darkest bg-theme-glow">

    <!-- Breathing circles -->
    <div class="absolute w-[350px] h-[350px] rounded-full bg-gold/5 top-[10%] right-[-5%] pointer-events-none blur-3xl"></div>
    <div class="absolute w-[250px] h-[250px] rounded-full bg-calm-200/5 bottom-[10%] left-[-5%] pointer-events-none blur-3xl"></div>

    <!-- Top Navigation -->
    <nav class="absolute top-0 w-full z-50 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/10">
                <img src="<?= base_url('images/logo.png?v=6') ?>" alt="Logo" class="h-10 w-10 object-contain">
            </div>
            <div class="flex flex-col">
                <span class="font-heading font-extrabold text-white text-[16px] leading-none tracking-wide">StudentShield</span>
                <span class="text-[10px] text-white/80 font-bold uppercase tracking-widest mt-1">Mental Wellness Portal</span>
            </div>
        </div>
        <form action="<?= base_url('logout') ?>" method="POST" class="inline"><?= csrf_field() ?><button type="submit" class="text-[13px] font-bold text-white/60 hover:text-white transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </button></form>
    </nav>

    <!-- Main Content (Centered) -->
    <div class="w-full max-w-6xl mx-auto px-6 py-16 mt-10 relative z-20 flex flex-col items-center">
        
        <?= view('partials/student_meet_card') ?>

        
        <!-- Header Section -->
        <div class="text-center mb-12 sm:mb-16">
            <p class="text-gold/90 text-sm font-semibold tracking-widest uppercase mb-4">Select Action</p>
            <h1 class="text-4xl sm:text-5xl md:text-[3.5rem] font-heading font-extrabold text-white mb-6 tracking-tight leading-tight">
                How can we help you today?
            </h1>
            <p class="text-white/60 text-[15px] sm:text-base max-w-xl mx-auto leading-relaxed">
                Choose the path that best fits what you need right now. Whether you just need someone to listen, or you need immediate intervention, we are here for you.
            </p>
        </div>

        <!-- Choice Cards Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 w-full max-w-5xl">
            
            <!-- PATH 1: VENTING -->
            <a href="<?= base_url('student/vent') ?>" class="glass-card rounded-[2rem] p-8 md:p-10 flex flex-col group relative overflow-hidden">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-emerald-100 transition-all duration-300 border border-emerald-100 shadow-sm">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                
                <h2 class="text-2xl font-heading font-extrabold text-gray-900 mb-3 group-hover:text-primary transition-colors">Safe Space (Vent)</h2>
                <p class="text-gray-600 text-sm leading-relaxed mb-8 flex-1">
                    Empty your mind and let it out. This is an encrypted, judgment-free zone where you can write out your feelings, anxieties, or daily struggles. Only connected counselors can read what you write.
                </p>
                
                <div class="mt-auto flex items-center text-primary font-bold text-[13px] tracking-wide uppercase border-t border-gray-200 pt-5">
                    Enter Safe Space
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-2 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

            <!-- PATH 2: INCIDENT REPORT -->
            <?php if (!empty($isLocked)): ?>
            <!-- LOCKED: Student cannot file reports -->
            <div class="glass-card rounded-[2rem] p-8 md:p-10 flex flex-col relative overflow-hidden opacity-50 cursor-not-allowed select-none">
                <div class="absolute inset-0 bg-gray-900/10 backdrop-blur-[1px] rounded-[2rem] z-10 flex items-center justify-center">
                    <div class="bg-white/95 rounded-2xl px-6 py-4 shadow-lg text-center border border-red-200">
                        <svg class="w-8 h-8 text-red-500 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        <p class="text-red-700 font-bold text-sm">Reporting Suspended</p>
                        <p class="text-gray-500 text-xs mt-1">Visit the Guidance Office</p>
                    </div>
                </div>
                <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mb-6 border border-red-100 shadow-sm">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-heading font-extrabold text-gray-900 mb-3">Incident Report</h2>
                <p class="text-gray-600 text-sm leading-relaxed mb-8 flex-1">
                    Your reporting privilege has been temporarily suspended due to multiple false reports. Please visit the Guidance Office to restore access.
                </p>
                <div class="mt-auto flex items-center text-gray-400 font-bold text-[13px] tracking-wide uppercase border-t border-gray-200 pt-5">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    Access Locked
                </div>
            </div>
            <?php else: ?>
            <a href="<?= base_url('report-bullying') ?>" class="glass-card rounded-[2rem] p-8 md:p-10 flex flex-col group relative overflow-hidden">
                <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-red-100 transition-all duration-300 border border-red-100 shadow-sm">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <h2 class="text-2xl font-heading font-extrabold text-gray-900 mb-3 group-hover:text-red-700 transition-colors">Incident Report</h2>
                <p class="text-gray-600 text-sm leading-relaxed mb-8 flex-1">
                    Formal report for urgent matters. If you or someone you know is experiencing <strong>bullying, severe harassment, threats, or danger</strong>, use this path. This alerts personnel immediately for action.
                </p>
                
                <div class="mt-auto flex items-center text-red-600 font-bold text-[13px] tracking-wide uppercase border-t border-gray-200 pt-5">
                    File a Formal Report
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-2 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>
            <?php endif; ?>

        </div>

        <!-- Footer Footer -->
        <div class="mt-16 text-center">
            <p class="text-white/20 text-[11px] font-medium tracking-wider">© 2026 StudentShield · SVBSM</p>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!-- FALSE REPORT STRIKE WARNING MODAL                          -->
    <!-- Progressive discipline: Strike 1 = Warning, 2 = Final, 3 = Locked -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    <?php $strikes = $falseReportStrikes ?? 0; ?>
    <?php if ($strikes >= 1 && $strikes < 3): ?>
    <div id="strikeWarningModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(6px);">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 text-center relative animate-[scaleIn_0.3s_ease-out]">
            
            <?php if ($strikes === 1): ?>
            <!-- STRIKE 1: Gentle Warning -->
            <div class="w-16 h-16 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mx-auto mb-5 border-2 border-yellow-200">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <h3 class="text-xl font-heading font-extrabold text-gray-900 mb-2">Accountability Notice</h3>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold mb-4 border border-yellow-200">
                <span class="w-2 h-2 rounded-full bg-yellow-500"></span> Strike 1 of 3
            </div>
            <p class="text-gray-600 text-sm leading-relaxed mb-6">
                One of your recent incident reports was reviewed and <strong class="text-red-600">flagged as a false report</strong> by the school counselor. 
                StudentShield is a serious safety tool designed to protect students. Please use it responsibly.
            </p>
            <p class="text-gray-400 text-xs mb-6">Filing false reports wastes school resources and could put innocent people at risk.</p>

            <?php elseif ($strikes === 2): ?>
            <!-- STRIKE 2: Final Warning — Parent will be notified -->
            <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-5 border-2 border-red-200">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <h3 class="text-xl font-heading font-extrabold text-red-700 mb-2">⚠ Final Warning</h3>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold mb-4 border border-red-200">
                <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span> Strike 2 of 3
            </div>
            <p class="text-gray-600 text-sm leading-relaxed mb-4">
                You now have <strong class="text-red-600">2 reports flagged as false</strong>. Your parent/guardian has been notified of this behavior.
            </p>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <p class="text-red-800 text-xs font-bold leading-relaxed">
                    🚨 WARNING: One more false report will result in <strong>automatic suspension of your reporting privileges</strong>. You will need to physically visit the Guidance Office to restore access.
                </p>
            </div>
            <?php endif; ?>

            <button onclick="document.getElementById('strikeWarningModal').remove()" 
                    class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3.5 rounded-xl text-sm transition-colors shadow-lg">
                I Understand
            </button>
        </div>
    </div>
    <style>
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
    <?php endif; ?>

    <?php if (!empty($isLocked)): ?>
    <!-- STRIKE 3: FULL LOCK OVERLAY -->
    <div class="fixed inset-0 z-[9998] flex items-center justify-center p-4" style="background: rgba(10,10,10,0.85); backdrop-filter: blur(10px);">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-10 text-center">
            <div class="w-20 h-20 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-6 border-2 border-red-200">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            </div>
            <h3 class="text-2xl font-heading font-extrabold text-red-700 mb-3">Reporting Privileges Suspended</h3>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold mb-5 border border-red-200">
                <span class="w-2 h-2 rounded-full bg-red-500"></span> Strike 3 of 3 — Account Locked
            </div>
            <p class="text-gray-600 text-sm leading-relaxed mb-4">
                Your incident reporting access has been <strong class="text-red-600">temporarily suspended</strong> due to <strong>3 confirmed false reports</strong>. 
                This action was taken to protect other students and maintain the integrity of the reporting system.
            </p>
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                <p class="text-amber-800 text-xs font-bold leading-relaxed">
                    📋 To restore your reporting privileges, please visit the <strong>Guidance Office</strong> for a disciplinary meeting. 
                    You may still access the <strong>Safe Space (Vent)</strong> feature.
                </p>
            </div>
            <a href="<?= base_url('student/vent') ?>" class="block w-full bg-primary hover:bg-primary-dark text-white font-bold py-3.5 rounded-xl text-sm transition-colors shadow-lg mb-3">
                Go to Safe Space Instead
            </a>
            <form action="<?= base_url('logout') ?>" method="POST" class="inline w-full"><?= csrf_field() ?>
                <button type="submit" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-3 rounded-xl text-sm transition-colors">
                    Sign Out
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>

</body>
</html>

