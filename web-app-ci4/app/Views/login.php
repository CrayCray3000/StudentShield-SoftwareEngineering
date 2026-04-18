<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Student Portal - Secure Login</title>
    <link rel="preload" as="image" href="<?= base_url('images/logo.png?v=3') ?>">
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Responsive scaling */
        @media (max-width: 1440px) { html { font-size: 15px; } }
        @media (max-width: 1280px) { html { font-size: 14px; } }
        /* Disable automatic shrinking below 1024 to rely natively on tailwind mobile classes instead for pixel-perfect touch control */
        
        body { font-family: 'Inter', sans-serif; background-color: #06401E; overflow-x: hidden; }
        h1, h2, h3, .font-heading { font-family: 'Outfit', sans-serif; }

        .bg-student {
            background: linear-gradient(135deg, #166534 0%, #022c22 50%, #14532d 100%);
            background-size: 200% 200%;
            animation: gradientShift 15s ease infinite alternate;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        .bg-grid {
            background-size: 50px 50px;
            background-image:
                linear-gradient(to right, rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.03) 1px, transparent 1px);
            mask-image: radial-gradient(circle at center, black 40%, transparent 90%);
            -webkit-mask-image: radial-gradient(circle at center, black 40%, transparent 90%);
            position: absolute; inset: 0; z-index: 0;
        }

        .student-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255,255,255,0.05) inset;
        }

        .input-dark {
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid rgba(255, 255, 255, 0.08); /* Fixed parsing error inside my head, continuing */
            color: #ffffff;
            transition: all 0.3s ease;
        }
        .input-dark:focus {
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 4px rgba(242, 195, 0, 0.2);
            border-color: #F2C300;
        }
        .input-dark::placeholder { color: rgba(255,255,255,0.6); }

        .glow-blob {
            position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.15; z-index: 0;
            animation: pulseGlow 10s ease-in-out infinite alternate; pointer-events: none;
        }
        @keyframes pulseGlow {
            0% { opacity: 0.1; transform: scale(0.9) translate(0,0); }
            100% { opacity: 0.2; transform: scale(1.1) translate(-20px, 20px); }
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
        }
        
        /* Gold gradient button */
        .submit-btn {
            background: linear-gradient(135deg, #F2C300, #FFD233);
            color: #042E15;
            position: relative; overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 8px 25px -5px rgba(242, 195, 0, 0.4);
        }
        .submit-btn::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.5), transparent);
            transform: skewX(-20deg) translateX(-150%);
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .submit-btn:hover {
            box-shadow: 0 15px 35px -5px rgba(242, 195, 0, 0.6);
            transform: translateY(-2px) scale(1.02);
            background: linear-gradient(135deg, #FFD233, #ffe066);
        }
        .submit-btn:hover::after { transform: skewX(-20deg) translateX(150%); }
        
        /* Dynamic Spotlight Effect */
        .spotlight {
            background: radial-gradient(
                700px circle at var(--x) var(--y),
                rgba(242, 195, 0, 0.15), /* Gold glow */
                transparent 80%
            );
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
<body class="bg-student min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8 py-10 lg:py-16 relative overflow-x-hidden overflow-y-auto" onmousemove="updateSpotlight(event)">

    <!-- Intro Splash Screen -->
    <div id="splashScreen" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-student transition-opacity duration-1000">
        <div class="relative flex flex-col items-center z-10">
            <!-- Animated Logo -->
            <img src="<?= base_url('images/logo.png?v=3') ?>" alt="SVBSM Logo" class="w-24 h-24 sm:w-32 sm:h-32 object-contain mb-6 animate-[scaleIn_0.8s_cubic-bezier(0.16,1,0.3,1)_forwards] opacity-0"
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

    <!-- Interactive Spotlight Overlay -->
    <div id="spotlight-overlay" class="spotlight absolute inset-0 pointer-events-none z-10"></div>

    <!-- Deep Ambient Background -->
    <div class="bg-grid"></div>
    <!-- Adjusted blob positioning to prevent horizontal overflow constraints on mobile -->
    <div class="glow-blob bg-gold w-[300px] h-[300px] sm:w-[500px] sm:h-[500px] top-[-5%] right-[-10%] mix-blend-screen"></div>
    <div class="glow-blob bg-primary-light w-[400px] h-[400px] sm:w-[600px] sm:h-[600px] bottom-[-10%] left-[-15%] mix-blend-screen" style="animation-delay: -5s; animation-direction: alternate-reverse;"></div>

    <div class="w-full max-w-[480px] z-20 relative animate-[fadeInUp_0.8s_cubic-bezier(0.16,1,0.3,1)] mt-4 sm:mt-0">
        
        <!-- Adjusted p-6 for tight mobile grids, p-12 for tablets/desktop -->
        <div class="student-card p-6 sm:p-8 md:p-12 rounded-[2rem] sm:rounded-[2.5rem] relative overflow-hidden group hover:shadow-[0_40px_80px_-20px_rgba(0,0,0,0.6)] transition-shadow duration-500">
            
            <div class="text-center mb-8 sm:mb-10">
                <!-- Logo: smaller on mobile, larger on larger screens -->
                <img src="<?= base_url('images/logo.png?v=3') ?>" alt="SVBSM Logo" class="w-24 h-24 sm:w-32 sm:h-32 object-contain mx-auto mb-5 sm:mb-6 drop-shadow-[0_10px_20px_rgba(0,0,0,0.5)]"
                     onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'40\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'5\'/><path d=\'M35 55l10 10 25-25\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'/></svg>';">
                
                <h1 class="text-[1.75em] sm:text-[2rem] font-heading font-black tracking-tight text-white mb-2 leading-tight">Student <span class="text-gold">Portal</span></h1>
                <p class="text-white/60 font-medium text-[14px] sm:text-[15px]">Sign in to your private dashboard</p>
                
                <div class="inline-flex items-center gap-2 mt-4 sm:mt-5 px-3 sm:px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]"></span>
                    <span class="text-[10px] sm:text-[11px] font-bold text-emerald-300 uppercase tracking-widest whitespace-nowrap">End-to-End Encrypted</span>
                </div>
            </div>

            <!-- Error Flash -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-500/10 backdrop-blur-md border border-red-500/30 p-4 mb-6 sm:mb-8 rounded-2xl animate-[shake_0.5s_ease-in-out] shadow-lg">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-red-500/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-exclamation text-red-400 text-sm"></i>
                        </div>
                        <p class="text-[13px] sm:text-[14px] font-bold text-red-200 leading-snug tracking-tight pt-1 pr-1"><?= session()->getFlashdata('error') ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="/login/authenticate" method="post" class="space-y-5 sm:space-y-6 relative z-10 w-full">
                <?= csrf_field() ?>
                
                <div class="space-y-1.5 sm:space-y-2">
                    <label for="name" class="block text-[12px] sm:text-[13px] font-bold text-white/80 ml-1 uppercase tracking-wider">Student Name</label>
                    <div class="relative group/input">
                        <!-- pl-4 on mobile to not eat space, text-base rather than text-lg for icons on small -->
                        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center pointer-events-none transition-colors group-focus-within/input:text-gold">
                            <i class="fas fa-user text-white/40 group-focus-within/input:text-gold transition-colors text-[16px] sm:text-[18px]"></i>
                        </div>
                        <input type="text" name="name" id="name" required
                               class="w-full pl-11 sm:pl-14 pr-4 sm:pr-12 py-3.5 sm:py-4 input-dark rounded-xl sm:rounded-[1.25rem] text-[15px] sm:text-[16px] font-bold outline-none"
                               placeholder="Your registered name">
                    </div>
                </div>

                <div class="space-y-1.5 sm:space-y-2">
                    <label for="password" class="block text-[12px] sm:text-[13px] font-bold text-white/80 ml-1 uppercase tracking-wider">Secure Passcode</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center pointer-events-none transition-colors group-focus-within/input:text-gold">
                            <i class="fas fa-lock text-white/40 group-focus-within/input:text-gold transition-colors text-[16px] sm:text-[18px]"></i>
                        </div>
                        <input type="password" name="password" id="password" required
                               class="w-full pl-11 sm:pl-14 pr-4 sm:pr-12 py-3.5 sm:py-4 input-dark rounded-xl sm:rounded-[1.25rem] text-[15px] sm:text-[16px] font-bold outline-none tracking-[0.1em]"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center mt-2 group">
                    <input id="remember" name="remember" type="checkbox"
                           class="w-4 h-4 text-gold bg-white/10 border-white/20 rounded focus:ring-gold/50 focus:ring-2 cursor-pointer appearance-none checked:bg-gold checked:border-gold relative before:content-['✓'] before:absolute before:text-black before:text-xs before:font-bold before:opacity-0 checked:before:opacity-100 before:top-1/2 before:left-1/2 before:-translate-x-1/2 before:-translate-y-1/2 before:transition-opacity">
                    <label for="remember" class="ml-2.5 text-[13px] sm:text-[14px] font-semibold text-white/70 group-hover:text-white transition-colors cursor-pointer select-none">
                        Remember me on this device
                    </label>
                </div>

                <button type="submit" id="submitBtn" class="submit-btn w-full font-black py-3.5 sm:py-4 px-4 sm:px-6 rounded-xl sm:rounded-[1.25rem] focus:outline-none focus:ring-4 focus:ring-gold/30 flex items-center justify-center gap-2 sm:gap-3 h-[54px] sm:h-[60px] mt-8 sm:mt-10 group/btn">
                    <span id="btnText" class="font-heading text-[16px] sm:text-[18px] tracking-wide pt-0.5 text-primary-light mix-blend-multiply">Access Portal</span>
                    <i id="btnIcon" class="fas fa-arrow-right text-primary-light mix-blend-multiply text-[13px] sm:text-sm group-hover/btn:translate-x-1.5 group-hover/btn:scale-110 transition-all duration-300"></i>
                    <svg id="btnSpinner" class="animate-spin h-5 w-5 text-primary-light mix-blend-multiply hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-30" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
            </form>
            
        </div>
        
    </div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const splash = document.getElementById('splashScreen');
        if (!sessionStorage.getItem('studentIntroPlayed')) {
            setTimeout(() => {
                splash.classList.add('opacity-0');
                splash.style.pointerEvents = 'none';
                setTimeout(() => { splash.style.display = 'none'; }, 1000);
                sessionStorage.setItem('studentIntroPlayed', 'true');
            }, 3200); // Fade out after animations complete
        } else {
            splash.style.display = 'none';
        }
    });

    function updateSpotlight(e) {
        const overlay = document.getElementById('spotlight-overlay');
        overlay.style.setProperty('--x', `${e.clientX}px`);
        overlay.style.setProperty('--y', `${e.clientY}px`);
    }

    document.querySelector('form').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        const text = document.getElementById('btnText');
        const icon = document.getElementById('btnIcon');
        const spinner = document.getElementById('btnSpinner');
        btn.classList.add('opacity-80', 'cursor-wait', 'scale-[0.98]');
        text.innerText = 'Authenticating...';
        icon.classList.add('hidden');
        spinner.classList.remove('hidden');
    });
</script>
</body>
</html>
