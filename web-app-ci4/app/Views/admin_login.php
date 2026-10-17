<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Staff Portal - Secure Login</title>
    <link rel="preload" as="image" href="<?= base_url('images/logo.png?v=6') ?>">
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        @media (max-width: 1440px) { html { font-size: 15px; } }
        @media (max-width: 1280px) { html { font-size: 14px; } }
        @media (max-width: 768px) { html { font-size: 16px; } } /* Reset for mobile */

        body { font-family: 'Inter', sans-serif; background-color: #0F8A3A; overflow-x: hidden; }
        h1, h2, h3, .font-heading { font-family: 'Outfit', sans-serif; }

        .bg-admin {
            background: var(--school-header-background);
        }

        .bg-grid {
            position: absolute; inset: 0; z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .admin-card {
            background: rgba(255, 255, 255, 0.10) !important;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.22) !important;
            box-shadow:
                0 32px 64px -16px rgba(0, 0, 0, 0.45),
                0 0 0 1px rgba(255, 255, 255, 0.10) inset,
                0 1px 0 rgba(255, 255, 255, 0.25) inset !important;
        }

        .input-admin {
            background: rgba(0, 0, 0, 0.35) !important;
            border: 1.5px solid rgba(255, 255, 255, 0.22) !important;
            color: #FFFFFF !important;
            transition: background 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .input-admin:hover {
            background: rgba(0, 0, 0, 0.32) !important;
            border-color: rgba(255, 255, 255, 0.30) !important;
        }
        .input-admin:focus {
            background: rgba(0, 0, 0, 0.45) !important;
            box-shadow: 0 0 0 3px rgba(242, 195, 0, 0.22) !important;
            border-color: #F2C300 !important;
            outline: none;
        }
        .input-admin::placeholder { color: rgba(255,255,255,0.75) !important; }
        #submitBtn { background: linear-gradient(135deg, #cda434, #e6c55e); color: #0A1C11; }
        #submitBtn:hover { background: linear-gradient(135deg, #dfb745, #f5d675); }

        .glow-blob {
            position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.10; z-index: 0;
            animation: pulseGlow 10s ease infinite alternate; pointer-events: none;
        }
        @keyframes pulseGlow {
            0% { opacity: 0.06; transform: scale(1); }
            100% { opacity: 0.14; transform: scale(1.08); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
        }
        
        /* Splash Screen Animations */
        @keyframes scaleIn {
            0% { transform: translateY(20px) scale(0.9); opacity: 0; }
            100% { transform: translateY(0) scale(1); opacity: 1; }
        }
        @keyframes typing {
            from { width: 0; opacity: 1; }
            to { width: 100%; opacity: 1; }
        }
        @keyframes blink {
            50% { border-color: transparent; }
        }
        @keyframes fadeInSplash {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes progress {
            0% { width: 0%; }
            40% { width: 60%; }
            100% { width: 100%; }
        }

        /* Accessibility: Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            /* Remove entrance animations; pausing their first frame hides the form at opacity: 0. */
            *, ::before, ::after { animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body class="bg-admin min-h-[100dvh] w-full flex flex-col items-center justify-center p-3 sm:p-6 lg:p-8 relative overflow-x-hidden overflow-y-auto">

            <!-- Premium Fast-Load Splash Screen -->
    <div id="splashScreen" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-admin backdrop-blur-xl transition-all duration-700 ease-in-out">
        <div class="relative flex flex-col items-center z-10 w-full max-w-sm px-6">
            <!-- Logo -->
            <div class="relative mb-6 flex items-center justify-center w-24 h-24 sm:w-32 sm:h-32">
                <img src="<?= base_url('images/logo.png?v=6') ?>" alt="SVBSM Logo" 
                     class="relative w-full h-full object-contain z-10 drop-shadow-[0_0_20px_rgba(242,195,0,0.3)] animate-pulse"
                     id="splashLogoImg">
            </div>
            
            <!-- Branding -->
            <h1 class="text-3xl sm:text-4xl font-heading font-black tracking-[0.05em] text-white uppercase text-center drop-shadow-lg mb-2">
                Student<span class="text-gold">Shield</span>
            </h1>
            
            <p class="text-white/70 text-[10px] sm:text-[11px] font-semibold tracking-[0.15em] uppercase font-mono mb-8" id="splashStatusText">Authenticating Environment...</p>

            <!-- Sleek Loading Bar -->
            <div class="w-48 sm:w-64 h-1.5 bg-black/40 rounded-full overflow-hidden border border-white/5 relative">
                <div class="absolute top-0 bottom-0 left-0 bg-gradient-to-r from-[#D9AA2E] to-[#FCEEA1] shadow-[0_0_10px_#D9AA2E] rounded-full transition-all duration-200 ease-out" id="splashProgressBar" style="width: 0%;"></div>
            </div>
        </div>
    </div>

<div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
    <div class="bg-grid"></div>
    <!-- Scaled absolute blobs so they don't break flex alignments on tiny phones -->
    <div class="glow-blob bg-gold w-[300px] h-[300px] sm:w-[500px] sm:h-[500px] top-[-10%] right-[-10%]"></div>
    <div class="glow-blob bg-white w-[250px] h-[250px] sm:w-[350px] sm:h-[350px] bottom-[-10%] left-[5%]" style="animation-delay: 3s;"></div>
</div>

<div class="w-full max-w-md z-10 animate-[fadeInUp_0.7s_ease-out] my-auto py-4 sm:py-6">
    <div class="admin-card rounded-2xl sm:rounded-[2rem] p-5 sm:p-8 md:p-10">

        <!-- Header -->
        <div class="text-center mb-6 sm:mb-8">
            <div class="flex flex-col items-center justify-center mb-3 sm:mb-4">
                <img src="<?= base_url('images/logo.png?v=6') ?>" alt="SVBSM Logo" class="w-20 h-20 sm:w-24 sm:h-24 object-contain mx-auto mb-2 drop-shadow-xl"
                     onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><path d=\'M50 15L15 30v30c0 20 35 30 35 30s35-10 35-30V30L50 15z\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'5\' stroke-linejoin=\'round\'/><path d=\'M35 55l10 10 25-25\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'/></svg>';">
                <p class="text-white/90 font-semibold text-[12px] sm:text-[14px] tracking-wide">St. Vincent Blessed School of Manila, Inc.</p>
            </div>
            <h1 class="text-2xl sm:text-3xl font-heading font-extrabold tracking-tight text-white leading-tight">Staff <span class="text-gold">Portal</span></h1>
            <div class="inline-flex items-center gap-2 mt-2.5 px-3.5 py-1 rounded-full bg-black/40 border border-white/20 shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-[9.5px] sm:text-[10.5px] font-bold text-white uppercase tracking-widest whitespace-nowrap">🛡️ Authorized Personnel Only</span>
            </div>
        </div>

        <!-- Error Flash -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-500/10 border border-red-500/30 p-3.5 sm:p-4 mb-5 sm:mb-6 rounded-xl animate-[shake_0.5s_ease-in-out]">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-red-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-[13px] sm:text-sm font-medium text-red-200"><?= session()->getFlashdata('error') ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="<?= base_url('admin/login/authenticate') ?>" method="post" class="space-y-4 sm:space-y-5">
            <?= csrf_field() ?>
            <div>
                <label for="name" class="block text-[11px] sm:text-xs font-semibold text-white/80 mb-1.5 sm:mb-2 ml-1 uppercase tracking-wider">Staff account</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 sm:pl-4 flex items-center pointer-events-none">
                        <svg class="h-[18px] w-[18px] sm:h-5 sm:w-5 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input type="text" name="name" id="name" required
                           class="w-full pl-10 sm:pl-11 pr-4 py-3 sm:py-3.5 input-admin rounded-xl text-[16px] font-medium outline-none"
                           placeholder="Enter staff identifier">
                </div>
            </div>

            <div>
                <label for="password" class="block text-[11px] sm:text-xs font-semibold text-white/80 mb-1.5 sm:mb-2 ml-1 uppercase tracking-wider">Access Key</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 sm:pl-4 flex items-center pointer-events-none">
                        <svg class="h-[18px] w-[18px] sm:h-5 sm:w-5 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <input type="password" name="password" id="password" required
                           class="w-full pl-10 sm:pl-11 pr-4 py-3 sm:py-3.5 input-admin rounded-xl text-[16px] font-medium outline-none tracking-widest"
                           placeholder="••••••••">
                </div>
            </div>

            <button type="submit" id="submitBtn"
                class="w-full mt-2 sm:mt-4 bg-gold hover:bg-gold-light text-[#083318] font-black py-3.5 sm:py-4 px-4 rounded-xl shadow-lg shadow-gold/25 transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-gold/30 flex items-center justify-center gap-2 group min-h-[50px]">
                <span id="btnText" class="font-heading text-[16px] sm:text-lg tracking-wide">Sign In to Dashboard</span>
                <svg id="btnIcon" class="h-[18px] w-[18px] sm:h-5 sm:w-5 text-[#083318] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <svg id="btnSpinner" class="animate-spin h-[18px] w-[18px] sm:h-5 sm:w-5 text-[#083318] hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-5 sm:mt-8 pt-4 sm:pt-5 border-t border-white/10 text-center">
            <a href="<?= base_url('login') ?>" class="inline-flex items-center gap-1.5 sm:gap-2 text-[12px] sm:text-xs text-white/50 hover:text-gold transition-colors font-semibold py-2 px-3 rounded-lg hover:bg-white/5 min-h-[44px]">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back to Student Portal
            </a>
        </div>
    </div>
</div>

<script>
            document.addEventListener("DOMContentLoaded", function() {
        const splash = document.getElementById('splashScreen');
        if (!sessionStorage.getItem('adminIntroPlayed')) {
            let hasLoaded = false;
            let minTimePassed = false;
            let progress = 0;
            const bar = document.getElementById('splashProgressBar');
            const status = document.getElementById('splashStatusText');
            
            const hideSplash = () => {
                splash.classList.add('opacity-0', 'backdrop-blur-none');
                splash.style.pointerEvents = 'none';
                setTimeout(() => { splash.style.display = 'none'; }, 700);
                sessionStorage.setItem('adminIntroPlayed', 'true');
            };

            const progressInterval = setInterval(() => {
                if(!hasLoaded) {
                    progress += Math.random() * 12;
                    if(progress > 85) progress = 85 + Math.random() * 5; // Hang around 85-90%
                    if(bar) bar.style.width = progress + '%';
                }
            }, 100);

            setTimeout(() => {
                minTimePassed = true;
                if(hasLoaded) finishLoading();
            }, 1000); // Wait at least 1s
            
            const finishLoading = () => {
                clearInterval(progressInterval);
                if(bar) bar.style.width = '100%';
                if(status) status.innerText = 'Connection Secured';
                setTimeout(hideSplash, 300); // Fade out slightly after hitting 100%
            };

            window.addEventListener('load', () => {
                hasLoaded = true;
                if(minTimePassed) finishLoading();
            });

            setTimeout(finishLoading, 4000); // 4s absolute max
        } else {
            splash.style.display = 'none';
        }
    });

    document.querySelector('form').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        const text = document.getElementById('btnText');
        const icon = document.getElementById('btnIcon');
        const spinner = document.getElementById('btnSpinner');
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-wait');
        text.innerText = 'Authenticating...';
        icon.classList.add('hidden');
        spinner.classList.remove('hidden');
    });
</script>
</body>
</html>


