<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>StudentShield - Dashboard Path Selection</title>
    <link rel="preload" as="image" href="<?= base_url('images/logo.png?v=3') ?>">
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
        .brand-panel { box-shadow: inset -20px 0 50px -20px rgba(0,0,0,0.5); }
        .glass-card {
            background: padding-box linear-gradient(to bottom, rgba(255,255,255,1), rgba(255,255,255,0.85));
            border: 1px solid rgba(22, 101, 52, 0.1);
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08), 0 4px 10px -5px rgba(0,0,0,0.04);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(22, 101, 52, 0.2);
            border-color: rgba(22, 101, 52, 0.3);
        }
    </style>
</head>
<body class="text-gray-800 antialiased min-h-screen flex items-center justify-center p-0 relative overflow-x-hidden bg-white">

    <!-- Ambient Base glowing orbs -->
    <div class="hidden lg:block absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-20%] left-[-10%] w-[700px] h-[700px] rounded-full bg-calm-200/20 blur-3xl"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[900px] h-[900px] rounded-full bg-gold-light/10 blur-3xl"></div>
    </div>

    <!-- Main Fullscreen Glass Container -->
    <div class="w-full h-full min-h-screen flex flex-col lg:flex-row relative z-10 bg-white">

        <!-- ===== LEFT — Calming Branding Panel ===== -->
        <div class="brand-panel hidden lg:flex w-full lg:w-[480px] xl:w-[550px] lg:min-h-screen flex-shrink-0 flex-col justify-between px-8 sm:px-10 xl:px-14 py-8 lg:py-12 relative bg-[#0B5E2E] bg-gradient-to-br from-[#0B5E2E] via-[#0A4A24] to-[#0D3820]">
            <!-- Breathing circle -->
            <div class="absolute w-[280px] h-[280px] rounded-full bg-gold/10 top-[20%] right-[-60px] pointer-events-none"></div>
            <div class="absolute w-[180px] h-[180px] rounded-full bg-white/5 bottom-[15%] left-[-30px] pointer-events-none"></div>

            <!-- Top — Logo & Brand -->
            <div class="relative z-10">
                <div class="flex items-center gap-3.5 mb-14">
                    <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/10">
                        <img src="<?= base_url('images/logo.png?v=3') ?>" alt="SVBSM Logo" class="h-20 w-20 object-contain">
                    </div>
                    <div>
                        <h1 class="text-white font-heading font-bold text-lg tracking-tight">StudentShield</h1>
                        <p class="text-white/35 text-[10px] font-semibold tracking-[0.2em] uppercase">Mental Wellness Portal</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <p class="text-gold/80 text-xs font-semibold tracking-widest uppercase">Select Action</p>
                    <h2 class="text-white font-heading font-extrabold text-[2.2rem] sm:text-[2.6rem] xl:text-[3.2rem] leading-[1.08] tracking-tight">
                        How can we<br>help you today?
                    </h2>
                    <p class="text-white/50 text-[14px] sm:text-[15px] leading-relaxed max-w-sm">
                        Choose the path that best fits what you need right now. Whether you just need someone to listen, or you need immediate intervention, we are here for you.
                    </p>
                </div>
            </div>

            <!-- Bottom — Footer -->
            <div class="relative z-10 space-y-5">
                <div class="border-t border-white/8 pt-4 flex items-center justify-between">
                    <p class="text-white/25 text-[11px]">© 2026 StudentShield · SVBSM</p>
                    <div class="flex items-center gap-1.5 text-white/25 text-[11px]">
                        Protected
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== RIGHT — Option Form Panel ===== -->
        <div class="flex flex-col lg:min-h-screen flex-1 bg-[#FAFDF5] relative items-center justify-start lg:justify-stretch">

            <!-- Mobile Top Bar -->
            <nav class="lg:hidden sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-gray-100 shadow-sm">
                <div class="px-5 py-3.5 flex items-center justify-between">
                    <!-- Left: Branding -->
                    <div class="flex items-center gap-3">
                        <img src="<?= base_url('images/logo.png?v=3') ?>" alt="Logo" class="h-10 w-10 object-contain">
                        <div class="flex flex-col">
                            <span class="font-heading font-extrabold text-gray-900 text-[14px] leading-none">StudentShield</span>
                            <span class="text-[9px] text-primary/70 font-bold uppercase tracking-wider mt-1">Student Portal</span>
                        </div>
                    </div>
                    <a href="<?= base_url('logout') ?>" class="text-[12px] font-bold text-gray-500 hover:text-gray-800 transition-colors">Sign Out</a>
                </div>
            </nav>

            <!-- Main Interactive Choices -->
            <div class="w-full max-w-6xl mx-auto px-6 md:px-12 lg:px-16 xl:px-24 py-8 lg:py-12 relative flex flex-col flex-1">
                <div class="w-full relative z-20 flex flex-col flex-1 justify-center">

                    <!-- Mobile Title -->
                    <div class="lg:hidden text-center mb-8">
                        <h1 class="text-2xl font-heading font-extrabold text-gray-900 mb-2">How can we help?</h1>
                        <p class="text-sm text-gray-500">Choose the path that best fits what you need.</p>
                    </div>

                    <!-- Cards Container -->
                    <div class="flex flex-col xl:flex-row gap-6 w-full max-w-5xl self-center justify-center">
                        
                        <!-- PATH 1: VENTING -->
                        <a href="<?= base_url('student/vent') ?>" class="glass-card flex-1 rounded-[2rem] p-8 md:p-10 flex flex-col group relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-[#0B5E2E]/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 group-hover:bg-[#0B5E2E]/10 transition-colors duration-500"></div>
                            
                            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-emerald-100 transition-all duration-300 border border-emerald-100">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            
                            <h2 class="text-2xl font-heading font-extrabold text-gray-900 mb-3 group-hover:text-primary transition-colors">Safe Space (Vent)</h2>
                            <p class="text-gray-500 text-sm leading-relaxed mb-8 flex-1">
                                Empty your mind and let it out. This is an encrypted, judgment-free zone where you can write out your feelings, anxieties, or daily struggles. Only connected counselors can read what you write.
                            </p>
                            
                            <div class="mt-auto flex items-center text-primary font-bold text-[13px] tracking-wide uppercase">
                                Enter Safe Space
                                <svg class="w-4 h-4 ml-2 group-hover:ml-4 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </div>
                        </a>

                        <!-- PATH 2: INCIDENT REPORT -->
                        <a href="<?= base_url('report-bullying') ?>" class="glass-card flex-1 rounded-[2rem] p-8 md:p-10 flex flex-col group relative overflow-hidden border border-red-50 hover:border-red-200">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-red-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 group-hover:bg-red-500/10 transition-colors duration-500"></div>
                            
                            <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-red-100 transition-all duration-300 border border-red-100">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            
                            <h2 class="text-2xl font-heading font-extrabold text-gray-900 mb-3 group-hover:text-red-700 transition-colors">Incident Report</h2>
                            <p class="text-gray-500 text-sm leading-relaxed mb-8 flex-1">
                                Formal report for urgent matters. If you or someone you know is experiencing <strong>bullying, severe harassment, threats, or danger</strong>, use this path. This alerts personnel immediately for action.
                            </p>
                            
                            <div class="mt-auto flex items-center text-red-600 font-bold text-[13px] tracking-wide uppercase">
                                File a Formal Report
                                <svg class="w-4 h-4 ml-2 group-hover:ml-4 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </div>
                        </a>

                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 flex items-center justify-center gap-4 text-[11px] text-gray-400">
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

</body>
</html>
