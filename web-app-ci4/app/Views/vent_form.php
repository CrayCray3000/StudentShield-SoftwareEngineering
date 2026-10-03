<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title>StudentShield - Safe Space Portal</title>
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
    <link rel="stylesheet" href="<?= base_url('css/student-forms.css?v=7') ?>">
</head>
<body class="student-form-page student-form-page--vent antialiased min-h-screen">

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
                        <p class="text-white/70 text-[10px] font-semibold tracking-[0.2em] uppercase">Mental Wellness Portal</p>
                    </div>
                </a>

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
            <div class="relative z-10 space-y-4">
                <div class="flex items-center gap-3.5 group">
                    <div class="w-9 h-9 rounded-xl bg-white/[0.06] flex items-center justify-center flex-shrink-0 border border-white/5 group-hover:bg-white/10 transition-colors">
                        <svg class="w-4 h-4 text-gold/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-white/90 font-medium text-[13px]">Encrypted Storage</h3>
                        <p class="text-white/60 text-[11px] mt-0.5">Your entries are protected while stored.</p>
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
                        <h3 class="text-white/90 font-medium text-[13px]">Guidance Counselor Support</h3>
                        <p class="text-white/60 text-[11px] mt-0.5">School guidance personnel can review and respond.</p>
                    </div>
                </div>

                <!-- Sidebar Crisis Support Hotline -->
                <div class="p-4 rounded-2xl bg-black/20 border border-white/15 text-white space-y-2 shadow-sm backdrop-blur-sm">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0 border border-white/10">
                            <svg class="w-3.5 h-3.5 text-gold/90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <h4 class="font-heading font-semibold text-xs tracking-wider uppercase text-gold/90">24/7 Crisis Helpline</h4>
                    </div>
                    <p class="text-white/90 text-xs leading-relaxed">
                        If you need immediate support, the National Center for Mental Health is always here:
                    </p>
                    <div class="pt-0.5 text-white font-mono font-semibold text-xs tracking-wide">
                        Hotline: <span class="text-[#FCEEA1] font-bold text-sm">1553</span> <span class="text-white/60">·</span> <span class="text-[#FCEEA1] font-bold text-sm">0917-899-8727</span>
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

            <?= view('partials/student_mobile_form_header', ['page' => 'vent']) ?>

            <!-- Form content -->
            <div class="student-form-content mx-auto relative">
                <div class="student-form-body w-full relative">
                <?= view('partials/student_form_navigation', ['page' => 'vent']) ?>

                <!-- Page heading stays ahead of the form on every screen size. -->
                <header class="student-page-header">
                    <span class="student-section-label">
                        <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M21 12a9 9 0 0 1-9 9H3l2-4a9 9 0 1 1 16-5Z" /></svg>
                        Safe Space
                    </span>
                    <h2>How are you feeling today?</h2>
                    <p>Take your time. Write what is on your mind, and choose any feelings that fit.</p>
                    
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

                <!-- Vent Form -->
                <form action="<?= base_url('student/vent/submit') ?>" method="POST" id="ventForm" class="student-entry-form">
                    <div class="hidden"><?= csrf_field() ?></div>

                    <!-- Textarea — grows to fill space -->
                    <div class="vent-writing-field flex flex-col">
                        <div class="field-heading">
                            <label for="vent_text">What's on your mind?</label>
                            <span id="charCount" class="char-count">0 / 2000</span>
                        </div>
                        <p id="ventGuidance" class="breathing-guide">
                            <span id="breathDot" aria-hidden="true"></span>
                            <span id="breathText">Breathe in deeply...</span>
                        </p>
                        <textarea id="vent_text" name="vent_text" maxlength="2000" aria-describedby="ventGuidance"
                                  class="textarea-field" required></textarea>
                    </div>

                    <!-- Mood Quick-Select -->
                    <div class="mood-picker" id="moodPicker">
                        <p id="moodPrompt" class="mood-prompt">How do you feel right now?</p>
                        <p id="moodHelp" class="mood-help">Optional. Select any feelings that fit.</p>
                        <input type="hidden" name="emotions" id="emotionsInput" value="">
                        <button type="button" id="moodToggle" class="mood-toggle" aria-expanded="false" aria-controls="moodPanel">
                            <span id="moodSummary">Choose feelings</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div id="moodPanel" class="mood-panel">
                        <div id="moodSelect" role="group" aria-labelledby="moodPrompt" aria-describedby="moodHelp">
                            <?php
                            $moodList = [
                                'Okay'        => '😐',
                                'Calm'        => '😌',
                                'Happy'       => '😊',
                                'Hopeful'     => '🙂',
                                'Sad'         => '😔',
                                'Angry'       => '😠',
                                'Anxious'     => '😰',
                                'Lonely'      => '🥺',
                                'Overwhelmed' => '😵',
                                'Hurt'        => '🤕',
                                'Numb'        => '😶',
                                'Scared'      => '😨',
                            ];
                            foreach ($moodList as $mood => $emoji): ?>
                                <button type="button" data-mood="<?= esc($mood, 'attr') ?>" aria-pressed="false" class="mood-btn">
                                    <span class="mood-emoji" aria-hidden="true"><?= $emoji ?></span>
                                    <span><?= esc($mood) ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="moodDone" class="mood-done">Done</button>
                        </div>
                    </div>

                    <!-- Counselor notification preference -->
                    <label for="hide_from_parents" class="confidentiality-option">
                        <input type="checkbox" id="hide_from_parents" name="hide_from_parents" value="1" aria-labelledby="parentPreferenceLabel" aria-describedby="parentPreferenceHelp">
                        <span class="confidentiality-copy">
                            <span id="parentPreferenceLabel" class="confidentiality-title">Only alert my guidance counselor</span>
                            <span id="parentPreferenceHelp" class="confidentiality-help">Turn off automated parent or guardian alerts for this entry.</span>
                        </span>
                    </label>

                    <!-- Submit -->
                    <button type="submit" id="submitBtn"
                        class="submit-btn w-full text-white font-bold py-4 px-6 rounded-2xl shadow-[0_8px_20px_-6px_rgba(11,94,46,0.25)] focus:outline-none focus:ring-4 focus:ring-calm-200 flex items-center justify-center gap-3 h-14 lg:h-16 group transition-transform mb-2">
                        <span id="btnText" class="font-heading text-[18px] lg:text-[20px] tracking-wide">Submit Securely</span>
                        <svg id="btnIcon" class="h-5 w-5 text-gold/80 group-hover:-translate-y-0.5 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                        <svg id="btnSpinner" class="animate-spin h-5 w-5 text-white hidden" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </form>

                <!-- Reassurance and counseling access are secondary to the writing task. -->
                <aside class="student-secondary" id="meet-section" aria-label="Student support">
                    <div class="student-affirmation-card">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                            <span class="text-[11px] uppercase tracking-wider font-bold text-primary">Daily Encouragement</span>
                        </div>
                        <p id="dailyAffirmation" class="text-gray-700 text-sm leading-relaxed">"Your current situation is not your final destination. Take it one day at a time."</p>
                    </div>
                    <div class="student-meet-wrapper">
                        <?= view('partials/student_meet_card') ?>
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
        const textarea = document.getElementById('vent_text');
        const charCount = document.getElementById('charCount');
        textarea.addEventListener('input', function() {
            const len = this.value.length;
            charCount.textContent = len + ' / 2000';
            if (len > 1800) charCount.style.color = '#B3261E';
            else if (len > 1200) charCount.style.color = '#8A5700';
            else charCount.style.color = '#526259';
        });

        const moodPicker = document.getElementById('moodPicker');
        const moodToggle = document.getElementById('moodToggle');
        const moodPanel = document.getElementById('moodPanel');
        const mobileMoods = window.matchMedia('(max-width: 599px)');
        let moodsExpanded = false;
        function renderMoodPicker() {
            moodPanel.hidden = mobileMoods.matches && !moodsExpanded;
            moodToggle.setAttribute('aria-expanded', String(!moodPanel.hidden));
            const selected = [...document.querySelectorAll('.mood-btn.active')].map(button => button.dataset.mood);
            document.getElementById('moodSummary').textContent = selected.length ? selected.join(', ') : 'Choose feelings';
        }
        function closeMoodPicker() {
            moodsExpanded = false;
            renderMoodPicker();
            moodToggle.focus();
        }
        moodPicker.classList.add('is-enhanced');
        moodToggle.addEventListener('click', () => {
            moodsExpanded = !moodsExpanded;
            renderMoodPicker();
        });
        document.getElementById('moodDone').addEventListener('click', closeMoodPicker);
        moodPanel.addEventListener('keydown', event => {
            if (event.key === 'Escape' && mobileMoods.matches) {
                event.preventDefault();
                closeMoodPicker();
            }
        });
        mobileMoods.addEventListener('change', renderMoodPicker);
        renderMoodPicker();

        document.querySelectorAll('.mood-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.classList.toggle('active');
                this.setAttribute('aria-pressed', this.classList.contains('active') ? 'true' : 'false');
                
                // Update hidden input with active moods
                const activeMoods = [];
                document.querySelectorAll('.mood-btn.active').forEach(activeBtn => {
                    activeMoods.push(activeBtn.getAttribute('data-mood'));
                });
                document.getElementById('emotionsInput').value = activeMoods.join(',');
                renderMoodPicker();
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
                breathText.innerText = "Keep writing at your own pace.";
                breathDot.className = "w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]";
                return;
            }
            breathIdx = (breathIdx + 1) % breathStages.length;
            breathText.innerText = breathStages[breathIdx].text;
            breathDot.className = "w-3 h-3 rounded-full bg-emerald-400 transition-transform duration-[3000ms] ease-in-out " + breathStages[breathIdx].scale;
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
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded', 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: new URLSearchParams(new FormData(form)).toString()
            })
            .then(r => {
                // Session expired: server returned a non-JSON redirect or 401
                if (r.status === 401) {
                    btn.classList.remove('opacity-90', 'cursor-wait');
                    text.innerText = 'Submit Securely';
                    spinner.classList.add('hidden');
                    icon.classList.remove('hidden');
                    const errToast = document.createElement('div');
                    errToast.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-amber-500 text-white px-6 py-3.5 rounded-xl shadow-xl text-sm font-semibold flex items-center gap-2.5 z-[110]';
                    errToast.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg> Your session expired. <a href="/login" class="underline font-bold ml-1">Click here to log in again</a>.';
                    document.body.appendChild(errToast);
                    setTimeout(() => { errToast.style.opacity = '0'; errToast.style.transition = 'opacity 0.4s'; setTimeout(() => errToast.remove(), 400); }, 7000);
                    throw new Error('session_expired'); // stop the chain
                }
                if (!r.ok) {
                    return r.text().then(t => { throw new Error('Server error ' + r.status + ': ' + t.substring(0, 100)); });
                }
                return r.json();
            })
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
                text.innerText = 'Submitted Securely';
                spinner.classList.add('hidden');
                document.querySelectorAll('.mood-btn').forEach(b => {
                    b.classList.remove('active');
                    b.setAttribute('aria-pressed', 'false');
                });
                document.getElementById('emotionsInput').value = '';
                moodsExpanded = false;
                renderMoodPicker();

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
                    toast.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-primary text-white px-6 py-3.5 rounded-xl shadow-xl shadow-primary/25 text-sm font-semibold flex items-center gap-2.5 z-50';
                    toast.innerHTML = '<svg class="w-5 h-5 text-gold" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Thank you for sharing. We hear you.';
                    document.body.appendChild(toast);
                    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.5s'; setTimeout(() => toast.remove(), 500); }, 3500);
                }
                setTimeout(() => { btn.style.background = ''; text.innerText = 'Submit Securely'; icon.classList.remove('hidden'); }, 3000);
            })
            .catch(err => {
                if (err.message === 'session_expired') return; // already handled above
                console.error('Vent submission error:', err);
                text.innerText = 'Submit Securely'; spinner.classList.add('hidden'); icon.classList.remove('hidden');
                btn.classList.remove('opacity-90', 'cursor-wait');
                // Distinguish: SyntaxError = response wasn't JSON (unexpected server crash)
                // err.message starts with 'Server error' = we got an HTTP error back
                // Everything else = true network failure
                let userMsg = err instanceof SyntaxError
                    ? 'Server error. Your vent was not sent. Please try again.'
                    : (err.message && err.message.startsWith('Server error')
                        ? err.message.replace(/^Server error \d+:\s*/, '').trim() || 'Server error. Please try again.'
                        : 'Network error. Please check your connection and try again.');
                const errToast = document.createElement('div');
                errToast.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-red-500 text-white px-6 py-3.5 rounded-xl shadow-xl text-sm font-semibold flex items-center gap-2.5 z-[110]';
                errToast.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg> ' + userMsg;
                document.body.appendChild(errToast);
                setTimeout(() => { errToast.style.opacity = '0'; errToast.style.transition = 'opacity 0.4s'; setTimeout(() => errToast.remove(), 400); }, 5000);
            });
        });

    </script>
</body>
</html>
