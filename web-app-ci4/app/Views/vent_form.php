<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentShield - Safe Space Portal</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Responsive scaling for smaller monitors/laptops */
        @media (max-width: 1440px) { html { font-size: 15px; } }
        @media (max-width: 1280px) { html { font-size: 14px; } }
        @media (max-width: 1024px) { html { font-size: 13px; } }
        @media (max-width: 768px) { html { font-size: 16px; } } /* Reset for mobile */

        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-heading { font-family: 'Outfit', sans-serif; }

        .split-layout { display: grid; grid-template-columns: 420px 1fr; min-height: 100vh; }
        @media (max-width: 1024px) { .split-layout { grid-template-columns: 1fr; } }
        @media (min-width: 1400px) { .split-layout { grid-template-columns: 480px 1fr; } }

        /* Left Panel — Calming therapeutic feel */
        .brand-panel {
            background: linear-gradient(-45deg, #14532d, #064e3b, #15803d, #14532d);
            background-size: 400% 400%;
            animation: gradientBG 20s ease infinite;
            position: relative; overflow: hidden;
        }
        .bg-ambient {
            background: linear-gradient(-45deg, #F0F7F4, #FFFCE8, #E8F5E9, #f4fcf6);
            background-size: 400% 400%;
            animation: gradientBG 20s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .brand-panel::before {
            content: ''; position: absolute; inset: 0;
            background:
                radial-gradient(ellipse at 30% 0%, rgba(242,195,0,0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 70% 100%, rgba(255,255,255,0.05) 0%, transparent 50%);
        }

        /* Organic wave shapes for calming feel */
        .wave-top, .wave-bottom { position: absolute; width: 100%; overflow: hidden; z-index: 1; }
        .wave-top { top: 0; height: 120px; }
        .wave-bottom { bottom: 0; height: 120px; transform: rotate(180deg); }

        /* Breathing circle animation — therapeutic */
        .breath-circle {
            animation: breathe 6s ease-in-out infinite;
        }
        @keyframes breathe {
            0%, 100% { transform: scale(1); opacity: 0.15; }
            50% { transform: scale(1.15); opacity: 0.25; }
        }

        /* Textarea (Premium Feel) */
        .textarea-field {
            background: rgba(255, 255, 255, 0.85);
            border: 1.5px solid #D4E2D9;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);
        }
        .textarea-field:focus {
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(11, 94, 46, 0.05), 0 15px 35px rgba(11, 94, 46, 0.04);
            border-color: #0B5E2E;
            outline: none;
        }
        .textarea-field::-webkit-scrollbar { width: 5px; }
        .textarea-field::-webkit-scrollbar-track { background: transparent; }
        .textarea-field::-webkit-scrollbar-thumb { background: #c5d0c2; border-radius: 99px; }

        /* Mood Chips */
        .mood-btn {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 13px;
        }
        .mood-btn:hover { transform: translateY(-1px); box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .mood-btn.active {
            background: #DCF0E4 !important; border-color: #4A9B6E !important;
            color: #2D6B47 !important; font-weight: 600 !important;
            box-shadow: 0 2px 10px rgba(74, 155, 110, 0.12);
        }

        /* Submit Button */
        .submit-btn {
            background: linear-gradient(135deg, #0B5E2E, #157A3E);
            position: relative; overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .submit-btn::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.2), transparent);
            transform: skewX(-20deg); transition: 0.5s;
        }
        .submit-btn:hover {
            box-shadow: 0 12px 28px rgba(11, 94, 46, 0.3);
            transform: translateY(-2px);
        }
        .submit-btn:hover::before { left: 150%; }

        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-4px); } 50% { transform: translateX(4px); } 75% { transform: translateX(-4px); } }

        .anim-1 { animation: slideUp 0.6s ease-out both; }
        .anim-2 { animation: slideUp 0.6s ease-out 0.12s both; }
        .anim-3 { animation: slideUp 0.6s ease-out 0.24s both; }
        .anim-4 { animation: slideUp 0.6s ease-out 0.36s both; }
        .anim-form { animation: fadeInUp 0.5s ease-out 0.1s both; }

        .char-count { transition: color 0.2s ease; }


    </style>
</head>
<body class="text-gray-800 antialiased min-h-screen flex items-center justify-center p-0 relative overflow-x-hidden bg-white">

    <!-- Ambient Base glowing orbs (Optimized for performance, removed heavy blur animations) -->
    <div class="hidden lg:block absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-20%] left-[-10%] w-[700px] h-[700px] rounded-full bg-calm-200/20 blur-3xl"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[900px] h-[900px] rounded-full bg-gold-light/10 blur-3xl"></div>
    </div>



    <!-- Main Fullscreen Glass Container (Edge-to-Edge Aesthetic) -->
    <div class="w-full h-full min-h-screen flex flex-col lg:flex-row relative z-10 bg-white">

        <!-- ===== LEFT — Calming Branding Panel (Expanded) ===== -->
        <div class="brand-panel hidden lg:flex w-full lg:w-[420px] 2xl:w-[550px] lg:min-h-screen flex-shrink-0 flex-col justify-between px-8 sm:px-10 xl:px-14 py-8 lg:py-12 relative">

            <!-- Breathing circle — calming ambient element -->
            <div class="breath-circle absolute w-[280px] h-[280px] rounded-full bg-gold/10 top-[20%] right-[-60px] pointer-events-none"></div>
            <div class="breath-circle absolute w-[180px] h-[180px] rounded-full bg-white/5 bottom-[15%] left-[-30px] pointer-events-none" style="animation-delay: 3s;"></div>

            <!-- Top — Logo & Brand -->
            <div class="relative z-10 anim-1">
                <div class="flex items-center gap-3.5 mb-14">
                    <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/10">
                        <img src="<?= base_url('images/logo.png?v=6') ?>" alt="SVBSM Logo" class="h-20 w-20 object-contain"
                             onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'40\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'6\'/><path d=\'M35 55l10 10 25-25\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'6\' stroke-linecap=\'round\' stroke-linejoin=\'round\'/></svg>';">
                    </div>
                    <div>
                        <h1 class="text-white font-heading font-bold text-lg tracking-tight">StudentShield</h1>
                        <p class="text-white/70 text-[10px] font-semibold tracking-[0.2em] uppercase">Mental Wellness Portal</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="text-gold/80 text-xs font-semibold tracking-widest uppercase">Safe & Confidential</p>
                    <h2 class="text-white font-heading font-extrabold text-[2.2rem] sm:text-[2.6rem] xl:text-[3.2rem] leading-[1.08] tracking-tight">
                        It's okay to<br>not be okay.
                    </h2>
                    <p class="text-white/80 text-[14px] sm:text-[15px] leading-relaxed max-w-sm">
                        Your feelings are valid. This is a judgment-free space where you can express yourself freely and get the support you need.
                    </p>
                </div>
            </div>

            <!-- Middle — Trust Features -->
            <div class="relative z-10 space-y-4 anim-2">
                <div class="flex items-center gap-3.5 group">
                    <div class="w-9 h-9 rounded-xl bg-white/[0.06] flex items-center justify-center flex-shrink-0 border border-white/5 group-hover:bg-white/10 transition-colors">
                        <svg class="w-4 h-4 text-gold/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-white/90 font-medium text-[13px]">End-to-End Encrypted</h3>
                        <p class="text-white/60 text-[11px] mt-0.5">Your entries are encrypted and secure.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3.5 group">
                    <div class="w-9 h-9 rounded-xl bg-white/[0.06] flex items-center justify-center flex-shrink-0 border border-white/5 group-hover:bg-white/10 transition-colors">
                        <svg class="w-4 h-4 text-gold/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-white/90 font-medium text-[13px]">Confidential & Private</h3>
                        <p class="text-white/60 text-[11px] mt-0.5">Stored securely for counselor review.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3.5 group">
                    <div class="w-9 h-9 rounded-xl bg-white/[0.06] flex items-center justify-center flex-shrink-0 border border-white/5 group-hover:bg-white/10 transition-colors">
                        <svg class="w-4 h-4 text-gold/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-white/90 font-medium text-[13px]">Professional Counselor Support</h3>
                        <p class="text-white/60 text-[11px] mt-0.5">Our trained counselors are ready to help.</p>
                    </div>
                </div>
            </div>

            <!-- Bottom — Helpline + Footer -->
            <div class="relative z-10 space-y-5 anim-3">

                <div class="border-t border-white/8 pt-4 flex items-center justify-between">
                    <p class="text-white/60 text-[11px]">© 2026 StudentShield · SVBSM</p>
                    <div class="flex items-center gap-1.5 text-white/60 text-[11px]">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Protected
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== RIGHT — Vent Form Panel (Fullscreen Expanded) ===== -->
        <div class="flex flex-col lg:min-h-screen flex-1 bg-[#FAFDF5] relative items-center justify-start lg:justify-stretch">

            <!-- Mobile Top Bar — Visible only on mobile since branding is hidden -->
            <nav class="lg:hidden sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-gray-100 shadow-sm support-backdrop-blur:bg-white/60">
                <div class="px-5 py-3.5 flex items-center justify-between">
                    <!-- Left: Branding -->
                    <div class="flex items-center gap-3">
                        <img src="<?= base_url('images/logo.png?v=6') ?>" alt="Logo" class="h-10 w-10 object-contain">
                        <div class="flex flex-col">
                            <span class="font-heading font-extrabold text-gray-900 text-[14px] leading-none">StudentShield</span>
                            <span class="text-[9px] text-emerald-500 font-bold uppercase tracking-wider flex items-center gap-1.5 mt-1">
                                <span class="relative flex h-1.5 w-1.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                                </span>
                                Safe Space
                            </span>
                        </div>
                    </div>

                </div>
            </nav>

            <!-- Form Area (Stretched to top and bottom to eat up whitespace, max-capped for extreme ultrawides) -->
            <div class="w-full max-w-6xl mx-auto px-6 md:px-12 lg:px-16 xl:px-24 py-4 lg:py-6 xl:py-8 relative flex flex-col flex-1 justify-center">
                <div class="w-full relative z-20 flex flex-col flex-1 justify-center">
                <!-- Mobile Action Links (Compact for max visibility of form) -->
                <div class="lg:hidden flex items-center justify-between gap-3 mb-4">
                    <a href="<?= base_url('report-bullying') ?>" class="flex-1 bg-red-50 text-red-700 border border-red-200 shadow-sm rounded-xl py-2 px-3 flex items-center justify-center gap-2 font-bold text-[12px] hover:bg-red-100 transition-colors">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        Report Bullying
                    </a>
                    <form action="/logout" method="POST" class="inline"><?= csrf_field() ?><button type="submit" class="bg-white text-gray-600 border border-gray-200 shadow-sm rounded-xl py-2.5 px-4 flex items-center justify-center gap-2 font-bold text-[13px] hover:bg-gray-50 transition-colors whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sign Out
                    </button></form>
                </div>

                <!-- Ultra-fast Ambient Depth for Mobile -->
                <div class="absolute top-[10%] right-[-15%] w-80 h-80 bg-primary/5 rounded-full blur-[60px] lg:hidden pointer-events-none"></div>

                <!-- Desktop Header -->
                <div class="hidden lg:flex items-center justify-between mb-4 anim-form">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                            <span class="text-[12px] font-bold text-emerald-600 tracking-wider uppercase">Safe Space Active</span>
                        </div>
                        <h2 class="text-3xl lg:text-4xl xl:text-[2.6rem] font-heading font-extrabold text-gray-900 tracking-tight">How are you feeling today?</h2>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="<?= base_url('report-bullying') ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-500 hover:text-white px-4 py-2.5 rounded-xl hover:bg-red-500 transition-all duration-300 border border-red-100 hover:border-red-500 hover:shadow-lg hover:shadow-red-500/15">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                            Report Incident
                        </a>
                        <form action="/logout" method="POST" class="inline"><?= csrf_field() ?><button type="submit" class="text-xs font-medium text-gray-500 hover:text-primary px-3 py-2.5 rounded-xl hover:bg-primary/5 transition-colors flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Sign Out
                        </button></form>
                    </div>
                </div>

                <!-- Daily Affirmation Banner -->
                <div class="bg-calm-50/50 rounded-xl p-5 border border-calm-100/60 relative mb-6 anim-form shadow-sm">
                    <p id="dailyAffirmation" class="text-[16px] xl:text-[18px] font-medium text-calm-800 text-center leading-relaxed italic font-serif">
                        "Your current situation is not your final destination. Take it one day at a time."
                    </p>
                </div>

                <!-- Video Session Area -->
                <div id="videoSessionContainer" class="mb-6 anim-form">
                    <!-- Idle State -->
                    <div id="requestSessionArea" class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Need to talk immediately?</h3>
                            <p class="text-gray-500 text-sm">Request a one-on-one virtual session with your assigned counselor.</p>
                        </div>
                        <button type="button" id="btnRequestSession" onclick="requestVideoSession()" class="whitespace-nowrap inline-flex items-center gap-2 bg-emerald-600 text-white px-5 py-2.5 rounded-lg font-bold hover:bg-emerald-700 transition-colors shadow-sm focus:ring-4 focus:ring-emerald-100">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg> Request Video Session
                        </button>
                    </div>

                    <!-- Pending State -->
                    <div id="pendingRequestBanner" class="hidden bg-amber-50 rounded-xl p-5 border border-amber-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <svg class="animate-spin h-6 w-6 text-amber-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg font-bold text-amber-700 mb-1">Request Pending...</h3>
                                <p class="text-amber-600/80 text-sm">Waiting for your counselor to start the session. Please stay on this page.</p>
                            </div>
                        </div>
                        <button type="button" onclick="cancelSessionRequest()" class="text-sm font-semibold text-red-500 hover:text-red-700 underline whitespace-nowrap">Cancel Request</button>
                    </div>

                    <!-- Active Meeting State -->
                    <div id="activeMeetingBanner" class="hidden bg-emerald-50 rounded-xl p-5 border border-emerald-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse flex-shrink-0"></div>
                            <div>
                                <h3 class="text-lg font-bold text-emerald-800 mb-1">Counselor is Ready!</h3>
                                <p class="text-emerald-600/80 text-sm">Your counselor has started the video session.</p>
                            </div>
                        </div>
                        <button type="button" onclick="joinMeeting()" class="whitespace-nowrap inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-emerald-700 transition-colors shadow-sm focus:ring-4 focus:ring-emerald-100">
                            Join Session
                        </button>
                    </div>
                </div>

                <!-- Mobile Header -->
                <div class="lg:hidden mb-5">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                        <span class="text-[11px] font-semibold text-emerald-600 tracking-wide uppercase">Safe Space Active</span>
                    </div>
                    <h2 class="text-2xl font-heading font-extrabold text-gray-900">How are you feeling?</h2>
                    <p class="text-gray-500 mt-1 text-sm">Take your time. There are no wrong answers.</p>
                </div>

                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('message')): ?>
                    <div class="bg-emerald-50 border-l-4 border-emerald-400 p-4 mb-5 rounded-r-xl flex items-center gap-3 animate-[shake_0.5s_ease-in-out]">
                        <svg class="h-5 w-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        <p class="font-medium text-sm text-emerald-700"><?= session()->getFlashdata('message') ?></p>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-5 rounded-r-xl flex items-center gap-3 animate-[shake_0.5s_ease-in-out]">
                        <svg class="h-5 w-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        <p class="font-medium text-sm text-red-700"><?= session()->getFlashdata('error') ?></p>
                    </div>
                <?php endif; ?>

                <!-- Vent Form -->
                <form action="<?= base_url('student/vent/submit') ?>" method="POST" id="ventForm" class="flex-1 flex flex-col space-y-5 anim-form">
                    <div class="hidden"><?= csrf_field() ?></div>

                    <!-- Textarea — grows to fill space -->
                    <div class="flex-1 flex flex-col pb-6">
                        <div class="flex items-center justify-between mb-4 bg-calm-50/60 p-4 rounded-xl border border-calm-100/50">
                            <label for="vent_text" class="text-[16px] font-bold text-calm-800 flex items-center gap-3">
                                <div id="breathDot" class="w-4 h-4 rounded-full bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.5)] transition-transform duration-[3000ms] ease-in-out"></div>
                                <span id="breathText" class="transition-opacity duration-1000 tracking-wide font-medium">Breathe in deeply...</span>
                            </label>
                            <span id="charCount" class="char-count text-[13px] font-mono font-bold text-gray-500 bg-white px-3 py-1.5 rounded-[8px] shadow-sm border border-gray-100">0 / 2000</span>
                        </div>
                        <textarea id="vent_text" name="vent_text" maxlength="2000"
                                  class="w-full flex-1 min-h-[180px] lg:min-h-[200px] xl:min-h-[250px] px-6 py-6 textarea-field rounded-2xl outline-none resize-none text-gray-900 placeholder-transparent focus:placeholder-gray-400/50 leading-[1.8] text-[18px] lg:text-[20px] shadow-sm"
                                  placeholder="Start typing whenever you feel ready..." required></textarea>
                    </div>

                    <!-- Mood Quick-Select -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-2.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            What best describes how you feel right now? (Select all that apply)
                        </p>
                        <input type="hidden" name="emotions" id="emotionsInput" value="">
                        <div class="flex flex-wrap gap-2.5" id="moodSelect">
                            <button type="button" data-mood="Sad" class="mood-btn px-3.5 lg:px-4 py-2 lg:py-2.5 rounded-full border border-gray-300 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] bg-white hover:bg-calm-50 hover:border-calm-300 text-[13px] lg:text-[14px] font-semibold text-gray-600 transition-all">😔 Sad</button>
                            <button type="button" data-mood="Angry" class="mood-btn px-3.5 lg:px-4 py-2 lg:py-2.5 rounded-full border border-gray-300 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] bg-white hover:bg-calm-50 hover:border-calm-300 text-[13px] lg:text-[14px] font-semibold text-gray-600 transition-all">😠 Angry</button>
                            <button type="button" data-mood="Anxious" class="mood-btn px-3.5 lg:px-4 py-2 lg:py-2.5 rounded-full border border-gray-300 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] bg-white hover:bg-calm-50 hover:border-calm-300 text-[13px] lg:text-[14px] font-semibold text-gray-600 transition-all">😰 Anxious</button>
                            <button type="button" data-mood="Lonely" class="mood-btn px-3.5 lg:px-4 py-2 lg:py-2.5 rounded-full border border-gray-300 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] bg-white hover:bg-calm-50 hover:border-calm-300 text-[13px] lg:text-[14px] font-semibold text-gray-600 transition-all">😞 Lonely</button>
                            <button type="button" data-mood="Overwhelmed" class="mood-btn px-3.5 lg:px-4 py-2 lg:py-2.5 rounded-full border border-gray-300 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] bg-white hover:bg-calm-50 hover:border-calm-300 text-[13px] lg:text-[14px] font-semibold text-gray-600 transition-all">😵 Overwhelmed</button>
                            <button type="button" data-mood="Hurt" class="mood-btn px-3.5 lg:px-4 py-2 lg:py-2.5 rounded-full border border-gray-300 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] bg-white hover:bg-calm-50 hover:border-calm-300 text-[13px] lg:text-[14px] font-semibold text-gray-600 transition-all">🤕 Hurt</button>
                            <button type="button" data-mood="Numb" class="mood-btn px-3.5 lg:px-4 py-2 lg:py-2.5 rounded-full border border-gray-300 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] bg-white hover:bg-calm-50 hover:border-calm-300 text-[13px] lg:text-[14px] font-semibold text-gray-600 transition-all">😶 Numb</button>
                            <button type="button" data-mood="Scared" class="mood-btn px-3.5 lg:px-4 py-2 lg:py-2.5 rounded-full border border-gray-300 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] bg-white hover:bg-calm-50 hover:border-calm-300 text-[13px] lg:text-[14px] font-semibold text-gray-600 transition-all">😨 Scared</button>
                        </div>
                    </div>

                    <!-- Premium Confidentiality Toggle Card -->
                    <label for="hide_from_parents" class="group relative flex items-start gap-3 lg:gap-4 p-4 lg:p-5 rounded-[16px] lg:rounded-[20px] bg-gradient-to-br from-calm-50/80 to-white border border-calm-100 shadow-[0_4px_20px_-8px_rgba(74,155,110,0.15)] hover:shadow-[0_8px_30px_-6px_rgba(74,155,110,0.25)] hover:border-calm-200 transition-all duration-300 cursor-pointer overflow-hidden transform hover:-translate-y-0.5">
                        
                        <!-- Soft Ambient Glow underneath the card -->
                        <div class="absolute -right-12 -top-12 w-32 h-32 bg-calm-200/20 rounded-full blur-2xl group-hover:bg-calm-300/30 transition-colors pointer-events-none"></div>

                        <!-- Custom Animated Checkbox -->
                        <div class="relative flex items-center justify-center mt-0.5 z-10 flex-shrink-0">
                            <input type="checkbox" id="hide_from_parents" name="hide_from_parents" value="1" 
                                   onchange="document.getElementById('checkSvg').style.opacity = this.checked ? '1' : '0'; document.getElementById('hide_from_parents').style.backgroundColor = this.checked ? '#10B981' : 'white'; document.getElementById('hide_from_parents').style.borderColor = this.checked ? '#10B981' : 'rgb(167 243 208 / 0.8)';"
                                   class="appearance-none w-4 h-4 lg:w-5 lg:h-5 rounded-[4px] lg:rounded-[6px] border-2 border-calm-200/80 bg-white focus:outline-none transition-all duration-200 cursor-pointer">
                            <svg id="checkSvg" style="opacity: 0;" class="absolute w-3 h-3 lg:w-3.5 lg:h-3.5 text-white transition-all duration-200 transform pointer-events-none stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        
                        <!-- Typography Content -->
                        <div class="flex-1 z-10">
                            <h4 class="text-calm-800 font-heading font-bold text-[13.5px] lg:text-[15px] flex items-center gap-1.5 lg:gap-2 mb-1 group-hover:text-calm-900 transition-colors">
                                <svg class="w-3.5 h-3.5 lg:w-4 lg:h-4 text-calm-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>
                                Let's keep this just between us.
                            </h4>
                            <p class="text-gray-500 text-[11.5px] lg:text-[13px] leading-[1.6] pr-2 font-medium">
                                It is completely okay if you aren't ready for your family to know yet. By checking this box, we promise to strictly notify our Guidance Counselors only. <span class="text-calm-600 font-semibold inline-block relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-full after:h-0.5 after:bg-calm-200/60 after:-z-10">You are safe here.</span>
                            </p>
                        </div>
                    </label>

                    <!-- Submit -->
                    <button type="submit" id="submitBtn"
                        class="submit-btn w-full text-white font-bold py-4 px-6 rounded-2xl shadow-[0_8px_20px_-6px_rgba(11,94,46,0.25)] focus:outline-none focus:ring-4 focus:ring-calm-200 flex items-center justify-center gap-3 h-14 lg:h-16 group transition-transform mb-2">
                        <span id="btnText" class="font-heading text-[18px] lg:text-[20px] tracking-wide">Submit Securely</span>
                        <svg id="btnIcon" class="h-5 w-5 text-gold/80 group-hover:-translate-y-0.5 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                        <svg id="btnSpinner" class="animate-spin h-5 w-5 text-white hidden" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </form>

                <!-- Footer -->
                <div class="mt-4 flex items-center justify-center gap-4 text-[11px] text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Protected by StudentShield
                    </span>
                    <span class="text-gray-300">·</span>
                    <span>Encrypted</span>
                </div>
            </div>

            </div>
        </div>
    </div>

    <!-- Empathy Modal -->
    <div id="empathyModal" class="fixed inset-0 z-[100] hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm transition-opacity duration-500 opacity-0" id="empathyBackdrop"></div>
        <div class="bg-white p-8 sm:p-10 max-w-md w-[90%] rounded-2xl text-center shadow-2xl relative z-10 scale-95 opacity-0 transition-all duration-500" id="empathyContent">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-gradient-to-br from-calm-50 to-calm-100 mb-5">
                <svg class="h-8 w-8 text-calm-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>
            </div>
            <h2 class="text-2xl font-heading font-bold text-gray-900 mb-2">We hear you.</h2>
            <p class="text-gray-500 leading-relaxed mb-2 text-sm">
                You've taken a brave step by sharing what you feel.
            </p>
            <p class="text-gray-600 leading-relaxed mb-6 text-sm">
                Remember — you don't have to carry this alone.<br>
                <span class="text-primary font-semibold">Our counseling team has been notified and is ready to support you.</span>
            </p>
            <button id="closeModalBtn" class="w-full submit-btn text-white font-bold py-3.5 px-6 rounded-xl shadow-lg">I understand</button>
        </div>
    </div>

    <script>
        const textarea = document.getElementById('vent_text');
        const charCount = document.getElementById('charCount');
        textarea.addEventListener('input', function() {
            const len = this.value.length;
            charCount.textContent = len + ' / 2000';
            if (len > 1800) charCount.style.color = '#ef4444';
            else if (len > 1200) charCount.style.color = '#f59e0b';
            else charCount.style.color = '#9ca3af';
        });

        document.querySelectorAll('.mood-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.classList.toggle('active');
                
                // Update hidden input with active moods
                const activeMoods = [];
                document.querySelectorAll('.mood-btn.active').forEach(activeBtn => {
                    activeMoods.push(activeBtn.getAttribute('data-mood'));
                });
                document.getElementById('emotionsInput').value = activeMoods.join(',');
            });
        });

        // Deep Breathing UX Guidance
        const breathStages = [
            { text: "Take a deep breath in...", scale: "scale-[1.8]" },
            { text: "Hold it for a moment...", scale: "scale-[1.8]" },
            { text: "Slowly breathe out...", scale: "scale-100" },
            { text: "You are in a safe space.", scale: "scale-100" },
            { text: "Whenever you are ready...", scale: "scale-100" }
        ];
        let breathIdx = 0;
        const breathText = document.getElementById('breathText');
        const breathDot = document.getElementById('breathDot');
        
        setInterval(() => {
            if(textarea.value.length > 0) {
                breathText.innerText = "Listening...";
                breathDot.className = "w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]";
                return;
            }
            breathText.style.opacity = 0;
            setTimeout(() => {
                breathIdx = (breathIdx + 1) % breathStages.length;
                breathText.innerText = breathStages[breathIdx].text;
                breathDot.className = "w-3 h-3 rounded-full bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.5)] transition-transform duration-[3000ms] ease-in-out " + breathStages[breathIdx].scale;
                breathText.style.opacity = 1;
            }, 1000);
        }, 4000);

        const affirmations = [
            '"Your current situation is not your final destination."',
            '"You survive 100% of your bad days. Keep going."',
            '"Healing is not linear, and that is perfectly okay."',
            '"You are stronger than the challenges you are facing."'
        ];
        document.getElementById('dailyAffirmation').innerText = affirmations[Math.floor(Math.random() * affirmations.length)];

        document.getElementById('ventForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const icon = document.getElementById('btnIcon');
            const spinner = document.getElementById('btnSpinner');
            const form = document.getElementById('ventForm');

            btn.classList.add('opacity-90', 'cursor-wait');
            text.innerText = 'Encrypting & Sending...';
            icon.classList.add('hidden');
            spinner.classList.remove('hidden');

            fetch(form.action, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: new URLSearchParams(new FormData(form)).toString()
            })
            .then(r => r.json())
            .then(data => {
                // QA BUG FIX: Do not clear textarea or show 'Success' if backend returns error/rate-limit!
                if (data.status === 'error') {
                    btn.classList.remove('opacity-90', 'cursor-wait');
                    text.innerText = 'Submit Securely';
                    spinner.classList.add('hidden');
                    icon.classList.remove('hidden');
                    
                    const errToast = document.createElement('div');
                    errToast.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-red-500 text-white px-6 py-3.5 rounded-xl shadow-xl shadow-red-500/20 text-sm font-semibold flex items-center gap-2.5 z-[110] animate-[slideUp_0.4s_ease-out]';
                    errToast.innerHTML = '<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg> ' + data.message;
                    document.body.appendChild(errToast);
                    setTimeout(() => { errToast.style.opacity = '0'; errToast.style.transition = 'opacity 0.4s'; setTimeout(() => errToast.remove(), 400); }, 4000);
                    return;
                }

                textarea.value = '';
                charCount.textContent = '0 / 2000';
                btn.classList.remove('opacity-90', 'cursor-wait');
                btn.style.background = 'linear-gradient(135deg, #059669, #10B981)';
                text.innerText = 'Sent Securely ✓';
                spinner.classList.add('hidden');
                document.querySelectorAll('.mood-btn').forEach(b => b.classList.remove('active'));
                document.getElementById('emotionsInput').value = '';

                if (data.risk_level === 'HIGH' || data.risk_level === 'CRITICAL') {
                    const modal = document.getElementById('empathyModal');
                    const bd = document.getElementById('empathyBackdrop');
                    const ct = document.getElementById('empathyContent');
                    modal.classList.remove('hidden'); modal.classList.add('flex');
                    setTimeout(() => { bd.classList.replace('opacity-0','opacity-100'); ct.classList.remove('scale-95','opacity-0'); ct.classList.add('scale-100','opacity-100'); }, 50);
                    document.getElementById('closeModalBtn').onclick = () => {
                        bd.classList.replace('opacity-100','opacity-0'); ct.classList.remove('scale-100','opacity-100'); ct.classList.add('scale-95','opacity-0');
                        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 500);
                    };
                } else {
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-primary text-white px-6 py-3.5 rounded-xl shadow-xl shadow-primary/25 text-sm font-semibold flex items-center gap-2.5 z-50 anim-form';
                    toast.innerHTML = '<svg class="w-5 h-5 text-gold" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Thank you for sharing. We hear you.';
                    document.body.appendChild(toast);
                    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.5s'; setTimeout(() => toast.remove(), 500); }, 3500);
                }
                setTimeout(() => { btn.style.background = ''; text.innerText = 'Submit Securely'; icon.classList.remove('hidden'); }, 3000);
            })
            .catch(err => {
                console.error('Error:', err);
                text.innerText = 'Submit Securely'; spinner.classList.add('hidden'); icon.classList.remove('hidden');
                btn.classList.remove('opacity-90', 'cursor-wait');
                alert("A network error occurred. Please try again.");
            });
        });

        // ---- Request a session ----
        let currentRoomName = null;
        let pollInterval = null;

        function requestVideoSession() {
            const btn = document.getElementById('btnRequestSession');
            btn.disabled = true;
            btn.textContent = 'Sending request...';

            fetch('<?= base_url('student/meeting/request') ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    showPendingState();
                } else {
                    btn.disabled = false;
                    btn.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg> Request Video Session`;
                }
            })
            .catch(() => { btn.disabled = false; });
        }

        function cancelSessionRequest() {
            fetch('<?= base_url('student/meeting/cancel-request') ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
            })
            .then(r => r.json())
            .then(() => showIdleState())
            .catch(() => {});
        }

        // ---- UI state helpers ----
        function showIdleState() {
            document.getElementById('activeMeetingBanner').classList.add('hidden');
            document.getElementById('pendingRequestBanner').classList.add('hidden');
            document.getElementById('requestSessionArea').classList.remove('hidden');
            const btn = document.getElementById('btnRequestSession');
            btn.disabled = false;
            btn.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg> Request Video Session`;
        }

        function showPendingState() {
            document.getElementById('activeMeetingBanner').classList.add('hidden');
            document.getElementById('pendingRequestBanner').classList.remove('hidden');
            document.getElementById('requestSessionArea').classList.add('hidden');
        }

        function showActiveMeetingState(roomName) {
            currentRoomName = roomName;
            document.getElementById('pendingRequestBanner').classList.add('hidden');
            document.getElementById('requestSessionArea').classList.add('hidden');
            document.getElementById('activeMeetingBanner').classList.remove('hidden');
        }

        function joinMeeting() {
            if (currentRoomName) {
                window.open('https://meet.jit.si/' + encodeURIComponent(currentRoomName), '_blank');
            }
        }

        // ---- Poll every 8 seconds for meeting status ----
        function pollMeetingStatus() {
            fetch('<?= base_url('student/api/meeting/status') ?>', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    if (data.has_meeting) {
                        showActiveMeetingState(data.meet_link);
                    } else if (data.has_request) {
                        showPendingState();
                    } else {
                        // only revert to idle if not currently showing an active meeting
                        // OR if a meeting just ended
                        if (!document.getElementById('activeMeetingBanner').classList.contains('hidden') ||
                            !document.getElementById('pendingRequestBanner').classList.contains('hidden')) {
                            showIdleState();
                        }
                    }
                }
            })
            .catch(() => {});
        }

        // Initialize polling
        pollMeetingStatus();
        pollInterval = setInterval(pollMeetingStatus, 8000);

    </script>
</body>
</html>
