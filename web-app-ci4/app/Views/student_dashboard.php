<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
                        primary: { DEFAULT: '#166534', light: '#15803d', dark: '#14532d', darker: '#064e3b', darkest: '#022c22' },
                        gold: { DEFAULT: '#EAB308', light: '#FACC15', bright: '#FEF08A', dark: '#CA8A04' },
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
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.3);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            backdrop-filter: blur(20px);
        }
        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
            background: padding-box linear-gradient(to bottom, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0.95));
        }
        .bg-theme-glow {
            background-image: radial-gradient(circle at 15% 50%, rgba(234, 179, 8, 0.15), transparent 40%),
                              radial-gradient(circle at 85% 30%, rgba(255, 255, 255, 0.05), transparent 40%);
        }
    </style>
</head>
<body class="text-white antialiased min-h-screen flex flex-col items-center justify-center relative overflow-x-hidden bg-[#0B5E2E] bg-gradient-to-br from-[#0B5E2E] via-[#0A4A24] to-[#0D3820] bg-theme-glow">

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
                <span class="text-[10px] text-white/50 font-bold uppercase tracking-widest mt-1">Mental Wellness Portal</span>
            </div>
        </div>
        <a href="<?= base_url('logout') ?>" class="text-[13px] font-bold text-white/60 hover:text-white transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </a>
    </nav>

    <!-- Main Content (Centered) -->
    <div class="w-full max-w-6xl mx-auto px-6 py-16 mt-10 relative z-20 flex flex-col items-center">
        
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
                
                <div class="mt-auto flex items-center text-primary font-bold text-[13px] tracking-wide uppercase border-t border-gray-100 pt-5">
                    Enter Safe Space
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-2 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

            <!-- PATH 2: INCIDENT REPORT -->
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
                
                <div class="mt-auto flex items-center text-red-600 font-bold text-[13px] tracking-wide uppercase border-t border-gray-100 pt-5">
                    File a Formal Report
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-2 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

        </div>

        <!-- Footer Footer -->
        <div class="mt-16 text-center">
            <p class="text-white/20 text-[11px] font-medium tracking-wider">© 2026 StudentShield · SVBSM</p>
        </div>
    </div>

</body>
</html>
