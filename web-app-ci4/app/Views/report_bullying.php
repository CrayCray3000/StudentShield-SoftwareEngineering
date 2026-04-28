<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>StudentShield - Incident Report</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Responsive scaling */
        @media (max-width: 1440px) { html { font-size: 15px; } }
        @media (max-width: 1280px) { html { font-size: 14px; } }
        /* Rely natively on Tailwind breakpoints below 1024 for precise touch control */
        
        body { font-family: 'Inter', sans-serif; background-color: #06401E; overflow-x: hidden; }
        .font-heading { font-family: 'Outfit', sans-serif; }
        
        /* Dark Ambient Background */
        .bg-dark-mesh {
            background: linear-gradient(135deg, #166534 0%, #022c22 50%, #14532d 100%);
            background-size: 200% 200%;
            animation: gradientShift 15s ease infinite alternate;
            position: fixed; inset: 0; z-index: -2;
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
            position: fixed; inset: 0; z-index: -1; pointer-events: none;
        }

        /* Dark Glassmorphic Panel */
        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 
                0 40px 80px -20px rgba(0, 0, 0, 0.6), 
                0 0 0 1px rgba(255,255,255,0.05) inset;
            border-radius: 2rem;
            position: relative;
            z-index: 10;
        }

        /* Dark Inputs */
        .input-dark {
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid rgba(255, 255, 255, 0.08);
            color: #ffffff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .input-dark:focus {
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 20px -5px rgba(0, 0, 0, 0.3), inset 0 2px 5px rgba(0,0,0,0.01), 0 0 0 4px rgba(242, 195, 0, 0.2);
            border-color: #F2C300;
            outline: none;
            transform: translateY(-2px);
        }
        .input-dark::placeholder { color: rgba(255,255,255,0.6); }

        /* Submit Button */
        .submit-btn {
            background: linear-gradient(135deg, #F2C300, #FFD233);
            color: #042E15;
            position: relative; overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }
        .submit-btn::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.5), transparent);
            transform: skewX(-20deg) translateX(-150%);
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .submit-btn:hover {
            box-shadow: 0 15px 35px -5px rgba(242, 195, 0, 0.4);
            transform: translateY(-3px) scale(1.02);
            background: linear-gradient(135deg, #FFD233, #ffe066);
        }
        .submit-btn:hover::after { transform: skewX(-20deg) translateX(150%); }

        /* Floating Blobs */
        .blob { position: absolute; filter: blur(90px); z-index: -2; opacity: 0.15; animation: floatBlob 12s ease-in-out infinite alternate; pointer-events: none; }
        @keyframes floatBlob { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(-30px, 30px) scale(1.1); } }
        
        .anim-fade-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Accessibility: Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            *, ::before, ::after { animation-play-state: paused !important; transition: none !important; }
            .bg-dark-mesh, .blob { animation: none !important; }
        }
    </style>
</head>
<body class="text-white antialiased min-h-screen relative flex flex-col justify-center py-8 lg:py-12">

    <!-- Dark Ambient Elements -->
    <div class="bg-dark-mesh"></div>
    <div class="bg-grid"></div>
    <div class="blob bg-gold w-[300px] h-[300px] sm:w-[500px] sm:h-[500px] rounded-full top-[-5%] right-[-5%] mix-blend-screen"></div>
    <div class="blob bg-white w-[250px] h-[250px] sm:w-[400px] sm:h-[400px] rounded-full bottom-[-5%] left-[-5%] mix-blend-screen" style="animation-delay: -5s; animation-direction: alternate-reverse;"></div>

    <!-- Top Navigation Controls -->
    <div class="absolute top-4 sm:top-5 left-0 w-full px-4 sm:px-8 flex items-center justify-between z-50 pointer-events-none">
        <a href="<?= base_url('student/vent') ?>" class="pointer-events-auto flex items-center gap-2 sm:gap-2.5 bg-white/10 backdrop-blur-xl text-white hover:text-gold px-4 sm:px-5 py-2.5 sm:py-3 rounded-[1rem] sm:rounded-2xl border border-white/20 shadow-[0_5px_15px_rgba(0,0,0,0.2)] hover:shadow-[0_8px_25px_rgba(242,195,0,0.2)] hover:scale-[1.02] transition-all duration-300 font-bold text-[12px] sm:text-[14px]">
            <i class="fas fa-arrow-left text-white/60 group-hover:text-gold transition-colors"></i> <span class="hidden sm:inline">Go Back</span>
        </a>


    </div>

    <!-- Main Container -->
    <div class="w-full max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 mt-8 min-h-[80vh] flex flex-col justify-center">
        <div class="w-full relative z-20">
        
        <!-- Header Section -->
        <div class="text-center mb-8 sm:mb-12 anim-fade-up">
            <img src="<?= base_url('images/logo.png?v=6') ?>" alt="SVBSM Logo" class="w-20 h-20 sm:w-28 sm:h-28 object-contain mx-auto mb-5 sm:mb-6 drop-shadow-[0_10px_20px_rgba(0,0,0,0.5)]"
                 onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'40\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'5\'/><path d=\'M35 55l10 10 25-25\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'/></svg>';">
            
            <h1 class="text-[1.75rem] sm:text-[3rem] lg:text-[3.5rem] font-heading font-black text-white tracking-tight leading-[1.1] mb-3 sm:mb-4">
                You are <span class="text-gold">safe</span> here.
            </h1>
            <p class="text-white/60 font-medium text-[14px] sm:text-[17px] max-w-[500px] mx-auto leading-relaxed px-4 sm:px-0">
                This report is highly secure, encrypted, and completely confidential. We are here to listen and help.
            </p>
        </div>

        <!-- Alerts -->
        <div class="anim-fade-up" style="animation-delay: 0.1s;">
            <?php if(session()->getFlashdata('message')): ?>
                <div class="bg-emerald-500/10 backdrop-blur-md border border-emerald-500/20 p-4 sm:p-5 mb-6 sm:mb-8 rounded-[1rem] sm:rounded-[1.5rem] flex items-center gap-3 sm:gap-4 shadow-lg">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-emerald-300 text-sm sm:text-base"></i>
                    </div>
                    <span class="text-emerald-100 font-bold text-[13px] sm:text-[15px] leading-snug"><?= session()->getFlashdata('message') ?></span>
                </div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="bg-red-500/10 backdrop-blur-md border border-red-500/20 p-4 sm:p-5 mb-6 sm:mb-8 rounded-[1rem] sm:rounded-[1.5rem] flex items-center gap-3 sm:gap-4 shadow-lg">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-red-500/20 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-300 text-sm sm:text-base"></i>
                    </div>
                    <span class="text-red-100 font-bold text-[13px] sm:text-[15px] leading-snug"><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Glass Form Card -->
        <div class="glass-panel p-5 sm:p-10 lg:p-12 anim-fade-up" style="animation-delay: 0.2s;">
            <form action="<?= base_url('report-bullying/submit') ?>" method="POST" id="incidentForm" enctype="multipart/form-data" class="space-y-6 sm:space-y-8 lg:space-y-10">
                <?= csrf_field() ?>
                
                <div class="space-y-1.5 sm:space-y-2">
                    <label for="incident_type" class="block text-[12px] sm:text-[13px] font-bold text-white/80 ml-1 uppercase tracking-wider">What kind of incident occurred?</label>
                    <div class="relative group/input">
                        <select id="incident_type" name="incident_type" required
                                class="appearance-none w-full px-4 sm:px-6 py-3.5 sm:py-4 input-dark rounded-xl sm:rounded-[1.25rem] text-[14px] sm:text-[15px] font-semibold cursor-pointer text-gray-800">
                            <option value="" disabled selected>-- Select Incident Category --</option>
                            <option value="Physical Abuse">Physical Abuse (Hitting, Pushing, Fighting)</option>
                            <option value="Cyberbullying">Cyberbullying (Online Threats, Harassment)</option>
                            <option value="Verbal/Emotional">Verbal/Emotional (Mocking, Name-calling, Exclusion)</option>
                            <option value="Other">Other</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 sm:px-6 text-white/50">
                            <i class="fas fa-chevron-down text-xs sm:text-sm"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5 sm:space-y-2">
                    <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between ml-1 gap-1">
                        <label for="perpetrator_name" class="block text-[12px] sm:text-[13px] font-bold text-white/80 uppercase tracking-wider">
                            Name(s) of the Perpetrator / Bully
                        </label>
                        <span class="text-[10px] sm:text-[12px] text-white/50 font-semibold bg-white/5 border border-white/10 rounded-lg px-2 py-0.5 sm:px-2.5 sm:py-1 w-max">Optional if unknown</span>
                    </div>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center pointer-events-none transition-colors group-focus-within/input:text-red-400">
                            <i class="fas fa-users-slash text-white/30 group-focus-within/input:text-red-400 transition-colors text-[14px] sm:text-[16px]"></i>
                        </div>
                        <input type="text" id="perpetrator_name" name="perpetrator_name"
                               class="w-full pl-11 sm:pl-14 pr-4 sm:pr-5 py-3.5 sm:py-4 input-dark rounded-xl sm:rounded-[1.25rem] text-[14px] sm:text-[15px] font-semibold focus:border-red-400 focus:shadow-[0_0_0_4px_rgba(248,113,113,0.2)]" 
                               placeholder="Who was involved?">
                    </div>
                </div>

                <div class="space-y-1.5 sm:space-y-2">
                    <label for="description" class="block text-[12px] sm:text-[13px] font-bold text-white/80 ml-1 uppercase tracking-wider">
                        Describe what happened <span class="text-red-400 text-sm">*</span>
                    </label>
                    <textarea id="description" name="description" rows="5" required
                              class="w-full px-4 sm:px-6 py-4 sm:py-5 input-dark rounded-xl sm:rounded-[1.5rem] text-[14px] sm:text-[15px] font-medium leading-relaxed resize-none" 
                              placeholder="Where did it happen? When? Describe exactly what was said or done. The more detail you provide, the faster counselors can intervene safely."></textarea>
                </div>

                <!-- EVIDENCE UPLOAD -->
                <div class="space-y-1.5 sm:space-y-2">
                    <label for="evidence_file" class="block text-[12px] sm:text-[13px] font-bold text-white/80 ml-1 uppercase tracking-wider">
                        Attach Evidence (Photo/Video)
                    </label>
                    <div class="relative group/input">
                        <input type="file" id="evidence_file" name="evidence_file" accept="image/*,video/*"
                               class="w-full px-4 sm:px-6 py-3.5 sm:py-4 input-dark rounded-xl sm:rounded-[1.25rem] text-[14px] sm:text-[15px] font-medium file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gold file:text-[#042E15] hover:file:bg-yellow-400 cursor-pointer">
                        <p class="mt-2 text-xs text-white/50 ml-2">Max size: 10MB. Supported: JPG, PNG, HEIC, MP4, MOV, AVI, MKV, WEBM.</p>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit" id="submitBtn" class="submit-btn w-full font-black py-3.5 sm:py-4 px-4 sm:px-6 rounded-xl sm:rounded-[1.25rem] focus:outline-none focus:ring-4 focus:ring-gold/30 flex items-center justify-center gap-2 sm:gap-3 h-[54px] sm:h-[64px] mt-6 sm:mt-8 group/btn bg-gold">
                    <span id="btnText" class="font-heading text-[16px] sm:text-[18px] tracking-wide text-[#042E15] drop-shadow-sm mix-blend-multiply pt-0.5">Submit Official Report</span>
                    <i id="btnIcon" class="fas fa-lock text-[#042E15] text-[13px] sm:text-[15px] mix-blend-multiply group-hover/btn:scale-110 transition-transform duration-300"></i>
                    <svg id="btnSpinner" class="animate-spin h-5 w-5 sm:h-6 sm:w-6 text-[#042E15] hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-30" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
            </form>
            
            <!-- Trust Badges -->
            <div class="pt-6 sm:pt-8 mt-6 sm:mt-10 border-t border-white/10 flex flex-wrap items-center justify-center gap-x-6 sm:gap-x-8 gap-y-3 sm:gap-y-4 text-[10px] sm:text-[12px] text-white/40 font-extrabold uppercase tracking-widest">
                <span class="flex items-center gap-1.5 sm:gap-2"><i class="fas fa-user-secret text-gold/70 text-xs sm:text-sm"></i> 100% Anonymous</span>
                <span class="flex items-center gap-1.5 sm:gap-2"><i class="fas fa-shield-alt text-gold/70 text-xs sm:text-sm"></i> Admin Only</span>
            </div>
            
        </div>
        
        <!-- Privacy Security Widget (Stacked Below Form) -->
        <div class="w-full mt-6 mb-12 sm:mb-16">
            <div class="bg-white/5 backdrop-blur-3xl rounded-[2rem] p-8 border border-white/10 shadow-2xl relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-gold rounded-full opacity-5 blur-3xl"></div>
                <h3 class="text-lg font-heading font-black text-gold mb-4 flex items-center gap-3">
                    <i class="fas fa-user-shield"></i> Secure & Anonymous
                </h3>
                <div class="space-y-4 sm:space-y-0 sm:flex sm:gap-8">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="w-10 h-10 rounded-xl bg-gold/10 flex items-center justify-center flex-shrink-0 border border-gold/10">
                            <i class="fas fa-mask text-gold"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white mb-1">Your Identity is Hidden</h4>
                            <p class="text-[12px] text-white/40 leading-relaxed font-medium">Unless you choose to provide your name, this report is 100% anonymous.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 flex-1">
                        <div class="w-10 h-10 rounded-xl bg-gold/10 flex items-center justify-center flex-shrink-0 border border-gold/10">
                            <i class="fas fa-lock text-gold"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white mb-1">End-to-End Encryption</h4>
                            <p class="text-[12px] text-white/40 leading-relaxed font-medium">Data is encrypted before it reaches our servers, keeping your voice secure.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>

    <script>
        // Form Submission State Management
        document.getElementById('incidentForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const icon = document.getElementById('btnIcon');
            const spinner = document.getElementById('btnSpinner');
            
            btn.classList.add('opacity-80', 'cursor-wait');
            text.innerText = 'Encrypting & Sending...';
            icon.classList.add('hidden');
            spinner.classList.remove('hidden');
        });
    </script>
</body>
</html>
