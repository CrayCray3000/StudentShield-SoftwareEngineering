<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Counselor Portal - Secure Login</title>
    <link rel="preload" as="image" href="<?= base_url('images/logo.png?v=6') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: { DEFAULT: '#166534', light: '#15803d', dark: '#14532d', darker: '#064e3b', darkest: '#022c22' },
                        gold: { DEFAULT: '#EAB308', light: '#FACC15', bright: '#FEF08A', dark: '#CA8A04' }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        @media (max-width: 1440px) { html { font-size: 15px; } }
        @media (max-width: 1280px) { html { font-size: 14px; } }
        /* Disable aggressive scaling below 1024 to respect touch breakpoints */

        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
        h1, h2, h3, .font-heading { font-family: 'Outfit', sans-serif; }

        .bg-admin {
            background: linear-gradient(135deg, #166534 0%, #022c22 50%, #14532d 100%);
            background-size: 200% 200%;
            animation: gradientShift 15s ease infinite alternate;
        }
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .bg-grid {
            background-size: 40px 40px;
            background-image:
                linear-gradient(to right, rgba(255,255,255,0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.04) 1px, transparent 1px);
            mask-image: radial-gradient(circle at center, black 30%, transparent 80%);
            -webkit-mask-image: radial-gradient(circle at center, black 30%, transparent 80%);
            position: absolute; inset: 0; z-index: 0;
        }

        .admin-card {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4);
        }

        .input-admin {
            background: rgba(255, 255, 255, 0.06);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            color: #f1f5f9;
            transition: all 0.3s ease;
        }
        .input-admin:focus {
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 3px rgba(242, 195, 0, 0.25);
            border-color: #F2C300;
        }
        .input-admin::placeholder { color: rgba(255,255,255,0.6); }

        .glow-blob {
            position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.12; z-index: 0;
            animation: pulseGlow 8s ease infinite alternate; pointer-events: none;
        }
        @keyframes pulseGlow {
            0% { opacity: 0.08; transform: scale(1); }
            100% { opacity: 0.18; transform: scale(1.1); }
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
            *, ::before, ::after { animation-play-state: paused !important; transition: none !important; }
        }
    </style>
</head>
<body class="bg-admin min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 py-10 lg:py-16 relative overflow-x-hidden overflow-y-auto">

    <!-- Intro Splash Screen -->
    <div id="splashScreen" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-admin transition-opacity duration-1000">
        <div class="relative flex flex-col items-center z-10">
            <!-- Animated Logo -->
            <img src="<?= base_url('images/logo.png?v=6') ?>" alt="SVBSM Logo" class="w-24 h-24 sm:w-32 sm:h-32 object-contain mb-6 animate-[scaleIn_0.8s_cubic-bezier(0.16,1,0.3,1)_forwards] opacity-0"
                 style="animation-delay: 0.2s;"
                 onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'40\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'5\'/><path d=\'M35 55l10 10 25-25\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'/></svg>';">
            
            <!-- Animated Text -->
            <h1 class="text-3xl sm:text-4xl font-heading font-black tracking-tight text-white mb-8 overflow-hidden whitespace-nowrap border-r-[3px] border-gold animate-[typing_1s_steps(13,end)_forwards,blink_0.8s_step-end_infinite] opacity-0 w-0" style="animation-delay: 0.8s;">
                Student<span class="text-gold">Shield</span>
            </h1>
            
            <!-- Loading Progress -->
            <div class="w-48 sm:w-64 h-1.5 bg-white/10 rounded-full overflow-hidden animate-[fadeInSplash_0.5s_ease_forwards] opacity-0" style="animation-delay: 1.8s;">
                <div class="h-full bg-gold rounded-full animate-[progress_1s_ease-in-out_forwards]" style="animation-delay: 1.8s; width: 0%;"></div>
            </div>
        </div>
    </div>

<div class="bg-grid"></div>
<!-- Scaled absolute blobs so they don't break flex alignments on tiny phones -->
<div class="glow-blob bg-gold w-[300px] h-[300px] sm:w-[500px] sm:h-[500px] top-[-10%] right-[-10%]"></div>
<div class="glow-blob bg-white w-[250px] h-[250px] sm:w-[350px] sm:h-[350px] bottom-[-10%] left-[5%]" style="animation-delay: 3s;"></div>

<div class="w-full max-w-md z-10 animate-[fadeInUp_0.7s_ease-out]">
    <div class="admin-card rounded-[2rem] p-6 sm:p-10">

        <!-- Header -->
        <div class="text-center mb-8">
            <img src="<?= base_url('images/logo.png?v=6') ?>" alt="SVBSM Logo" class="w-24 h-24 sm:w-32 sm:h-32 object-contain mx-auto mb-4 sm:mb-5 drop-shadow-xl"
                 onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><path d=\'M50 15L15 30v30c0 20 35 30 35 30s35-10 35-30V30L50 15z\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'5\' stroke-linejoin=\'round\'/><path d=\'M35 55l10 10 25-25\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'/></svg>';">
            <h1 class="text-2xl sm:text-3xl font-heading font-bold tracking-tight text-white leading-tight">Counselor <span class="text-gold">Portal</span></h1>
            <div class="inline-flex items-center gap-2 mt-3 px-3 py-1 rounded-full bg-gold/10 border border-gold/20">
                <span class="w-1.5 h-1.5 rounded-full bg-gold animate-pulse shadow-[0_0_6px_#F2C300]"></span>
                <span class="text-[9px] sm:text-[10px] font-semibold text-gold/80 uppercase tracking-widest whitespace-nowrap">Secure Connection</span>
            </div>
        </div>

        <!-- Error Flash -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-500/10 border border-red-500/30 p-4 mb-6 rounded-xl animate-[shake_0.5s_ease-in-out]">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-red-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-[13px] sm:text-sm font-medium text-red-200"><?= session()->getFlashdata('error') ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="/admin/login/authenticate" method="post" class="space-y-4 sm:space-y-5">
            <?= csrf_field() ?>
            <div>
                <label for="name" class="block text-[11px] sm:text-xs font-semibold text-white/80 mb-1.5 sm:mb-2 ml-1 uppercase tracking-wider">Admin ID</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 sm:pl-4 flex items-center pointer-events-none">
                        <svg class="h-[18px] w-[18px] sm:h-5 sm:w-5 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input type="text" name="name" id="name" required
                           class="w-full pl-10 sm:pl-11 pr-4 py-3 sm:py-3.5 input-admin rounded-xl text-[14px] sm:text-base font-medium outline-none"
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
                           class="w-full pl-10 sm:pl-11 pr-4 py-3 sm:py-3.5 input-admin rounded-xl text-[14px] sm:text-base font-medium outline-none tracking-widest"
                           placeholder="••••••••">
                </div>
            </div>

            <button type="submit" id="submitBtn"
                class="w-full mt-2 sm:mt-4 bg-gold hover:bg-gold-light text-primary font-bold py-3.5 sm:py-4 px-4 rounded-xl shadow-lg shadow-gold/20 transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-gold/30 flex items-center justify-center gap-2 group">
                <span id="btnText" class="font-heading text-[16px] sm:text-lg tracking-wide">Sign In</span>
                <svg id="btnIcon" class="h-[18px] w-[18px] sm:h-5 sm:w-5 text-primary group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <svg id="btnSpinner" class="animate-spin h-[18px] w-[18px] sm:h-5 sm:w-5 text-primary hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
        </form>

        <!-- Footer -->
        <div class="mt-6 sm:mt-8 pt-4 sm:pt-5 border-t border-white/10 text-center">
            <a href="/login" class="inline-flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-white/40 hover:text-gold transition-colors font-semibold">
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
            setTimeout(() => {
                splash.classList.add('opacity-0');
                splash.style.pointerEvents = 'none';
                setTimeout(() => { splash.style.display = 'none'; }, 1000);
                sessionStorage.setItem('adminIntroPlayed', 'true');
            }, 3200);
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
