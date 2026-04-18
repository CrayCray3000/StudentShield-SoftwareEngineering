<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>StudentShield - Path Selection</title>
    <link rel="preload" as="image" href="<?= base_url('images/logo.png') ?>">
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
                        gold: { DEFAULT: '#EAB308', light: '#FACC15', bright: '#FEF08A', dark: '#CA8A04' }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { background-color: #FAFDF5; }
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
<body class="text-gray-800 antialiased min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- Top Navigation -->
    <nav class="w-full sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-gray-100 shadow-sm px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="<?= base_url('images/logo.png') ?>" alt="Logo" class="h-10 w-10 object-contain">
            <div class="flex flex-col">
                <span class="font-heading font-extrabold text-gray-900 text-[16px] leading-none">StudentShield</span>
                <span class="text-[10px] text-primary/70 font-bold uppercase tracking-wider mt-1">Student Portal</span>
            </div>
        </div>
        <a href="<?= base_url('logout') ?>" class="text-xs font-bold text-gray-500 hover:text-gray-800 bg-gray-50 hover:bg-gray-100 border border-gray-200 px-4 py-2 rounded-xl transition-colors">
            Sign Out
        </a>
    </nav>

    <!-- Main Content -->
    <div class="flex flex-col flex-1 items-center justify-center p-6 md:p-12 relative z-10 w-full max-w-6xl mx-auto">
        
        <div class="text-center mb-12 sm:mb-16">
            <h1 class="text-3xl md:text-5xl font-heading font-extrabold text-gray-900 mb-4 tracking-tight">How can we help you today?</h1>
            <p class="text-gray-500 text-sm md:text-base max-w-xl mx-auto leading-relaxed">
                Choose the path that best fits what you need right now. Whether you just need someone to listen, or you need immediate intervention, we are here for you.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-5xl">
            
            <!-- PATH 1: VENTING -->
            <a href="<?= base_url('student/vent') ?>" class="glass-card rounded-[2rem] p-8 md:p-10 flex flex-col group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-[#0B5E2E]/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 group-hover:bg-[#0B5E2E]/10 transition-colors duration-500"></div>
                
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-emerald-100 transition-all duration-300">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                
                <h2 class="text-2xl font-heading font-extrabold text-gray-900 mb-3 group-hover:text-primary transition-colors">Safe Space (Vent)</h2>
                <p class="text-gray-500 text-sm leading-relaxed mb-8 flex-1">
                    Empty your mind and let it out. This is an encrypted, judgment-free zone where you can write out your feelings, anxieties, or daily struggles. Only connected counselors can read what you write.
                </p>
                
                <div class="mt-auto flex items-center text-primary font-bold text-sm tracking-wide">
                    Enter Safe Space
                    <svg class="w-4 h-4 ml-2 group-hover:ml-4 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

            <!-- PATH 2: INCIDENT REPORT -->
            <a href="<?= base_url('report-bullying') ?>" class="glass-card rounded-[2rem] p-8 md:p-10 flex flex-col group relative overflow-hidden border-red-100">
                <div class="absolute top-0 right-0 w-64 h-64 bg-red-500/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 group-hover:bg-red-500/10 transition-colors duration-500"></div>
                
                <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-red-100 transition-all duration-300">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <h2 class="text-2xl font-heading font-extrabold text-gray-900 mb-3 group-hover:text-red-600 transition-colors">Incident Report</h2>
                <p class="text-gray-500 text-sm leading-relaxed mb-8 flex-1">
                    Formal report for urgent matters. If you or someone you know is experiencing <strong>bullying, severe harassment, threats, or danger</strong>, use this path. This alerts personnel immediately for disciplinary and protective action.
                </p>
                
                <div class="mt-auto flex items-center text-red-600 font-bold text-sm tracking-wide">
                    File a Formal Report
                    <svg class="w-4 h-4 ml-2 group-hover:ml-4 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </div>
            </a>

        </div>

    </div>

</body>
</html>
