<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentShield - Incident Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?= view('partials/school_brand') ?>
        <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 
                        sans: ['Inter', 'sans-serif'], 
                        heading: ['Outfit', 'sans-serif'] 
                    },
                    colors: {
                        white: '#FFFFFF', // Anti-glare soft white
                        black: '#131A15', // Softened black
                        gray: {
                            50: '#F4F7F5',
                            100: '#E6EBE8',
                            200: '#CFD9D4',
                            300: '#AAB8B1',
                            400: '#82958B',
                            500: '#526259',
                            600: '#4D5D55',
                            700: '#3D4943',
                            800: '#2E3833',
                            900: '#24352B', // Replaces harsh text-gray-900 with deep sage
                        },
                        red: {
                            50: '#FDF7F7',
                            100: '#FAEDED',
                            200: '#F4D6D6',
                            400: '#B3261E',
                            500: '#B3261E', // Muted red for report and error accents
                            600: '#B3261E',
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/student-forms.css?v=4') ?>">
</head>
<body class="student-form-page student-form-page--report antialiased min-h-screen">

    <!-- Student form layout -->
    <div class="student-form-layout w-full flex flex-col lg:flex-row">

        <!-- ===== LEFT — Calming Branding Panel (Expanded) ===== -->
        <div class="brand-panel hidden lg:flex w-full lg:w-[420px] 2xl:w-[550px] lg:min-h-screen flex-shrink-0 flex-col justify-between px-8 sm:px-10 xl:px-14 py-8 lg:py-12 relative">
            <div class="bg-grid"></div>

            <!-- Top — Logo & Brand -->
            <div class="relative z-10">
                <a href="<?= base_url('student/dashboard') ?>"
                   aria-label="Go to StudentShield action selection"
                   class="inline-flex items-center gap-3.5 mb-14 rounded-2xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gold focus-visible:ring-offset-4 focus-visible:ring-offset-primary-dark">
                    <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/10">
                        <img src="<?= base_url('images/logo.png?v=6') ?>" alt="" class="h-20 w-20 object-contain"
                             onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'40\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'6\'/><path d=\'M35 55l10 10 25-25\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'6\' stroke-linecap=\'round\' stroke-linejoin=\'round\'/></svg>';">
                    </div>
                    <div>
                        <h1 class="text-white font-heading font-bold text-lg tracking-tight">StudentShield</h1>
                        <p class="text-white/70 text-[10px] font-semibold tracking-[0.2em] uppercase">Incident Report Portal</p>
                    </div>
                </a>

                <div class="space-y-4">
                    <p class="text-gold/80 text-xs font-semibold tracking-widest uppercase">Secure & Confidential</p>
                    <h2 class="text-white font-heading font-extrabold text-[2.2rem] sm:text-[2.6rem] xl:text-[3.2rem] leading-[1.08] tracking-tight">
                        Stand Up,<br>Speak Out.
                    </h2>
                    <p class="text-white/80 text-[14px] sm:text-[15px] leading-relaxed max-w-sm">
                        This is a safe, completely confidential space to report an incident. Our counseling team is here to protect and support you.
                    </p>
                </div>
            </div>

            <!-- Middle — Trust Features -->
            <div class="relative z-10 space-y-4">
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
            <div class="relative z-10 space-y-5">

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
        <div class="student-form-main flex flex-col min-h-screen flex-1 relative items-center">

            <?= view('partials/student_mobile_form_header', ['page' => 'report']) ?>

            <!-- Form content -->
            <div class="student-form-content mx-auto relative">
                <div class="student-form-body w-full relative">
                <?= view('partials/student_form_navigation', ['page' => 'report']) ?>

                <!-- Page heading stays ahead of the form on every screen size. -->
                <header class="student-page-header">
                    <span class="student-section-label">
                        <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m10.3 3.9-8 14a2 2 0 0 0 1.7 3h16a2 2 0 0 0 1.7-3l-8-14a2 2 0 0 0-3.4 0ZM12 9v4m0 4h.01" /></svg>
                        Incident Report
                    </span>
                    <h2>Report an Incident</h2>
                    <p>Tell us what happened so your guidance counselor can review it.</p>
                </header>

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

                <!-- Incident Report Form -->
                <form action="<?= base_url('report-bullying/submit') ?>" method="POST" id="bullyingForm" enctype="multipart/form-data" class="student-entry-form">
                    <div class="hidden"><?= csrf_field() ?></div>

                    
                    <!-- Compact Form Layout -->
                    <div class="flex-1 flex flex-col space-y-4">
                        
                        <!-- Role Toggle -->
                        <div>
                            <p class="role-prompt mb-2.5">I am reporting as a:</p>
                            <input type="hidden" name="reporter_type" id="roleInput" value="self">
                            <div class="role-options">
                                <button type="button" onclick="setRole('self', this)" data-role="self" aria-pressed="true" class="role-btn active px-4 py-2.5 rounded-xl border border-calm-300 bg-calm-50 text-[13px] font-bold text-calm-700 transition-all shadow-[0_2px_10px_rgba(74,155,110,0.12)] flex items-center gap-2">
                                    <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    I am the victim
                                </button>
                                <button type="button" onclick="setRole('bystander', this)" data-role="bystander" aria-pressed="false" class="role-btn px-4 py-2.5 rounded-xl border border-gray-300 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] bg-white text-[13px] font-semibold text-gray-500 transition-all hover:bg-gray-50 flex items-center gap-2">
                                    <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    I am a bystander
                                </button>
                            </div>
                        </div>

                        <!-- Who is involved? -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div id="victimNameBox" class="hidden" style="display:none;">
                                <label for="victim_name" class="block text-xs font-semibold text-gray-800 mb-2">Who is being bullied? <span class="text-red-400">*</span></label>
                                <input type="text" id="victim_name" name="victim_name" class="w-full bg-white/80 border border-gray-300 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] rounded-xl px-4 py-3 text-[14px] focus:ring-2 focus:ring-calm-200 focus:border-calm-400 outline-none transition-all placeholder-gray-400 font-medium" placeholder="Full name of the victim">
                            </div>
                            <div id="aggressorNameBox" class="md:col-span-2">
                                <label for="aggressor_name" class="block text-xs font-semibold text-gray-800 mb-2">Who is the aggressor? <span class="text-red-400">*</span></label>
                                <input type="text" id="aggressor_name" name="bully_name" required class="w-full bg-white/80 border border-gray-300 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] rounded-xl px-4 py-3 text-[14px] focus:ring-2 focus:ring-calm-200 focus:border-calm-400 outline-none transition-all placeholder-gray-400 font-medium" placeholder="Full name of the aggressor">
                            </div>
                        </div>

                        <!-- Incident Details -->
                        <div class="flex flex-col flex-1 pb-4 pt-2">
                            <div class="flex items-center justify-between mb-2">
                                <label for="incident_details" class="block text-xs font-semibold text-gray-800">What happened? <span class="text-red-400">*</span></label>
                                <span id="charCount" class="char-count text-[11px] font-mono font-semibold text-gray-500 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100">0 / 2000</span>
                            </div>
                            <p class="field-help mb-2 flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Include who was involved, what happened, where and when it occurred.
                            </p>
                            <textarea id="incident_details" name="incident_details" maxlength="2000" class="w-full flex-1 min-h-[140px] px-5 py-4 textarea-field rounded-2xl outline-none resize-none text-gray-900 placeholder-gray-400/60 leading-[1.6] text-[15px] shadow-sm font-medium" placeholder="Take your time describing what happened..." required></textarea>
                        </div>
                        
                        <!-- Evidence Upload -->
                        <div class="flex flex-col pb-4">
                            <label for="evidence_file" class="block text-xs font-semibold text-gray-800 mb-2">Attach Evidence (Optional)</label>
                            <p class="field-help mb-2 flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Upload a photo or video (max 10MB)
                            </p>
                            <input type="file" name="evidence_file" id="evidence_file" accept="image/*,video/*" class="w-full bg-white/80 border border-gray-300 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] rounded-xl px-4 py-3 text-[14px] focus:ring-2 focus:ring-calm-200 focus:border-calm-400 outline-none transition-all font-medium text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-calm-50 file:text-calm-700 hover:file:bg-calm-100">
                        </div>
                    </div>
<!-- Submit -->
                    <button type="submit" id="submitBtn"
                        class="submit-btn w-full text-white font-bold py-4 px-6 rounded-2xl shadow-[0_8px_20px_-6px_rgba(11,94,46,0.25)] focus:outline-none focus:ring-4 focus:ring-calm-200 flex items-center justify-center gap-3 h-14 lg:h-16 group transition-transform mb-2">
                        <span id="btnText" class="font-heading text-[18px] lg:text-[20px] tracking-wide">Submit Report Securely</span>
                        <svg id="btnIcon" class="h-5 w-5 text-gold/80 group-hover:-translate-y-0.5 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                        <svg id="btnSpinner" class="animate-spin h-5 w-5 text-white hidden" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                    
                    <!-- Save as Draft -->
                    <button type="button" id="saveDraftBtn" class="group flex items-center justify-center gap-2 w-full text-center py-2 text-[12px] font-semibold text-calm-600 hover:text-calm-800 transition-colors bg-transparent border-0 cursor-pointer">
                        <svg class="w-4 h-4 text-calm-500 group-hover:text-calm-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        <span id="saveDraftText">Save as Draft to continue later</span>
                    </button>

                </form>

                <!-- Reassurance and counseling access are secondary to the writing task. -->
                <aside class="student-secondary" aria-label="Student support">
                    <div class="student-affirmation-card text-center flex flex-col items-center justify-center py-4 px-6 w-full">
                        <div class="inline-flex items-center gap-2 mb-1.5">
                            <svg class="w-4 h-4 text-primary flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                            <span class="text-[11px] uppercase tracking-wider font-bold text-primary">Support & Protection</span>
                        </div>
                        <p id="dailyAffirmation" class="text-gray-700 text-sm leading-relaxed italic text-center">"We are here to protect you."</p>
                    </div>
                </aside>

                <!-- Footer -->
                <div class="student-form-footer flex items-center justify-center">
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
        // --- Save As Draft LocalStorage Implementation ---
        const DRAFT_KEY = 'studentshield_bullying_report_draft';
        
        function loadDraft() {
            const draft = localStorage.getItem(DRAFT_KEY);
            if (draft) {
                try {
                    const data = JSON.parse(draft);
                    if (data.role) {
                        const btn = document.querySelector(`.role-btn[data-role="${data.role}"]`);
                        if (btn) setRole(data.role, btn);
                    }
                    if (data.victimName) document.getElementById('victim_name').value = data.victimName;
                    if (data.aggressorName) document.getElementById('aggressor_name').value = data.aggressorName;
                    if (data.incidentDetails) {
                        const ta = document.getElementById('incident_details');
                        ta.value = data.incidentDetails;
                        // Trigger input event to update char counter
                        ta.dispatchEvent(new Event('input'));
                    }
                    console.log('Draft loaded successfully');
                } catch (e) {
                    console.error('Failed to parse draft', e);
                }
            }
        }

        document.getElementById('saveDraftBtn').addEventListener('click', function() {
            const data = {
                role: document.getElementById('roleInput').value,
                victimName: document.getElementById('victim_name').value,
                aggressorName: document.getElementById('aggressor_name').value,
                incidentDetails: document.getElementById('incident_details').value
            };
            localStorage.setItem(DRAFT_KEY, JSON.stringify(data));
            
            const btnText = document.getElementById('saveDraftText');
            const originalText = btnText.innerText;
            btnText.innerText = 'Draft Saved Securely! ✓';
            btnText.classList.add('text-emerald-600');
            
            setTimeout(() => {
                btnText.innerText = originalText;
                btnText.classList.remove('text-emerald-600');
            }, 3000);
        });

        // Load draft on page load
        document.addEventListener('DOMContentLoaded', loadDraft);

        // Role Toggle Logic
        function setRole(role, btnElement) {
            const activeRoleClasses = "role-btn active px-4 py-2.5 rounded-xl border border-calm-300 bg-calm-50 text-[13px] font-bold text-calm-700 transition-all shadow-[0_2px_10px_rgba(74,155,110,0.12)] flex items-center gap-2";
            const inactiveRoleClasses = "role-btn px-4 py-2.5 rounded-xl border border-gray-300 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] bg-white text-[13px] font-semibold text-gray-500 transition-all hover:bg-gray-50 flex items-center gap-2";

            // Reset all buttons
            document.querySelectorAll('.role-btn').forEach(b => {
                b.className = inactiveRoleClasses;
                b.setAttribute('aria-pressed', 'false');
            });
            
            // Set the clicked button to active
            btnElement.className = activeRoleClasses;
            btnElement.setAttribute('aria-pressed', 'true');
            
            // Set the hidden input value
            document.getElementById('roleInput').value = role;

            // Toggle visibility of victim/aggressor fields
            const victimBox = document.getElementById('victimNameBox');
            const aggBox = document.getElementById('aggressorNameBox');
            const victimInput = document.getElementById('victim_name');

            if(role === 'bystander') {
                victimBox.classList.remove('hidden');
                victimBox.style.display = 'block';
                aggBox.className = ''; // remove col-span-2 so it takes 1 column
                victimInput.required = true;
            } else {
                victimBox.classList.add('hidden');
                victimBox.style.display = 'none';
                aggBox.className = 'md:col-span-2';
                victimInput.required = false;
                victimInput.value = ''; // clear if self
            }
        }

        // Character Counter
        const textarea = document.getElementById('incident_details');
        const charCount = document.getElementById('charCount');
        textarea.addEventListener('input', function() {
            const len = this.value.length;
            charCount.innerText = len + ' / 2000';
            if (len > 1800) {
                charCount.style.color = '#B3261E';
            } else if (len > 1000) {
                charCount.style.color = '#8A5700';
            } else {
                charCount.style.color = '';
            }
        });

        // Affirmations — report-appropriate
        const affirmations = [
            '"You are brave for speaking up."',
            '"Your voice can make a difference."',
            '"We are here to protect you."',
            '"Safety is your fundamental right."',
            '"Silence protects the bully, not the victim."'
        ];
        document.getElementById('dailyAffirmation').innerText = affirmations[Math.floor(Math.random() * affirmations.length)];

        // Form Submit Handler
        document.getElementById('bullyingForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const icon = document.getElementById('btnIcon');
            const spinner = document.getElementById('btnSpinner');

            // Client-side validation
            const aggressor = document.getElementById('aggressor_name').value.trim();
            const details = document.getElementById('incident_details').value.trim();
            const role = document.getElementById('roleInput').value;
            const victimName = document.getElementById('victim_name').value.trim();

            if (!aggressor) { e.preventDefault(); alert("Please enter the aggressor's name."); document.getElementById('aggressor_name').focus(); return; }
            if (!details) { e.preventDefault(); alert('Please describe what happened.'); document.getElementById('incident_details').focus(); return; }
            if (role === 'bystander' && !victimName) { e.preventDefault(); alert("Please enter the victim's name."); document.getElementById('victim_name').focus(); return; }

            // Show loading state — form will submit natively via POST redirect
            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            text.innerText = 'Encrypting & Sending...';
            icon.classList.add('hidden');
            spinner.classList.remove('hidden');
        });
    </script>
</body>
</html>
