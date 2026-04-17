<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>StudentShield - Incident Report</title>
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

        /* Dark Radio Cards */
        .radio-card {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .radio-card:has(input:checked) {
            border-color: #F2C300;
            background: rgba(242, 195, 0, 0.05);
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.3);
            transform: translateY(-2px);
        }
        .radio-card:hover:not(:has(input:checked)) {
            border-color: rgba(255, 255, 255, 0.2); transform: translateY(-1px); background: rgba(255,255,255,0.08);
        }

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
<body class="text-white antialiased min-h-screen relative flex flex-col justify-center py-16 sm:py-20 lg:py-24">

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
    <div class="w-full max-w-[800px] mx-auto px-4 sm:px-6 lg:px-8 relative z-20 mt-8 sm:mt-0">
        
        <!-- Header Section -->
        <div class="text-center mb-8 sm:mb-12 anim-fade-up">
            <img src="<?= base_url('images/logo.png') ?>" alt="SVBSM Logo" class="w-20 h-20 sm:w-28 sm:h-28 object-contain mx-auto mb-5 sm:mb-6 drop-shadow-[0_10px_20px_rgba(0,0,0,0.5)]"
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
            <form action="<?= base_url('report-bullying/submit') ?>" method="POST" id="bullyingForm" class="space-y-6 sm:space-y-8 lg:space-y-10">
                
                <!-- Reporter Type -->
                <div class="space-y-3 sm:space-y-4">
                    <label class="block text-[13px] sm:text-[15px] font-bold text-white/80 uppercase tracking-widest flex items-center gap-2.5 sm:gap-3">
                        <span class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-gold/10 text-gold border border-gold/20"><i class="fas fa-user-shield text-[10px] sm:text-xs"></i></span>
                        Your connection to the incident
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <label class="radio-card flex items-start gap-3 sm:gap-4 cursor-pointer p-4 sm:p-5 rounded-xl sm:rounded-[1.25rem]">
                            <input type="radio" name="reporter_type" value="self" checked class="mt-0.5 sm:mt-1 w-4 h-4 sm:w-5 sm:h-5 text-gold border-white/20 bg-transparent focus:ring-gold focus:ring-opacity-50 cursor-pointer transition-colors" onclick="toggleVictim()">
                            <div class="flex flex-col">
                                <span class="text-[14px] sm:text-[16px] font-bold text-white mb-0.5">I am the Victim</span>
                                <span class="text-[12px] sm:text-[13px] text-white/50 font-medium leading-tight">I am reporting what happened to me directly.</span>
                            </div>
                        </label>
                        <label class="radio-card flex items-start gap-3 sm:gap-4 cursor-pointer p-4 sm:p-5 rounded-xl sm:rounded-[1.25rem]">
                            <input type="radio" name="reporter_type" value="bystander" class="mt-0.5 sm:mt-1 w-4 h-4 sm:w-5 sm:h-5 text-gold border-white/20 bg-transparent focus:ring-gold focus:ring-opacity-50 cursor-pointer transition-colors" onclick="toggleVictim()">
                            <div class="flex flex-col">
                                <span class="text-[14px] sm:text-[16px] font-bold text-white mb-0.5">I am a Bystander</span>
                                <span class="text-[12px] sm:text-[13px] text-white/50 font-medium leading-tight">I saw or heard about this happening to someone else.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- VICTIM TEXT (Hidden by default) -->
                <div id="victimDiv" class="hidden overflow-hidden transition-all duration-500 ease-in-out">
                    <div class="pt-1 sm:pt-2 pb-1">
                        <label class="block text-[12px] sm:text-[13px] font-bold text-white/80 mb-1.5 sm:mb-2 ml-1 uppercase tracking-wider">
                            Who is the Victim? <span class="text-red-400 text-sm">*</span>
                        </label>
                        <div class="relative group/input">
                            <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center pointer-events-none transition-colors group-focus-within/input:text-gold">
                                <i class="fas fa-user-friends text-white/30 group-focus-within/input:text-gold transition-colors text-[14px] sm:text-[16px]"></i>
                            </div>
                            <input type="text" name="victim_name" id="victimName" class="w-full pl-11 sm:pl-14 pr-4 sm:pr-5 py-3.5 sm:py-4 input-dark rounded-xl sm:rounded-[1.25rem] text-[14px] sm:text-[15px] font-semibold" placeholder="Enter the victim's full name">
                        </div>
                    </div>
                </div>

                <!-- BULLY TEXT -->
                <div class="space-y-1.5 sm:space-y-2">
                    <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between ml-1 gap-1">
                        <label class="block text-[12px] sm:text-[13px] font-bold text-white/80 uppercase tracking-wider">
                            Who was the Aggressor? <span class="text-red-400 text-sm">*</span>
                        </label>
                        <span class="text-[10px] sm:text-[12px] text-white/50 font-semibold bg-white/5 border border-white/10 rounded-lg px-2 py-0.5 sm:px-2.5 sm:py-1 w-max">Separate multiple names with commas</span>
                    </div>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center pointer-events-none transition-colors group-focus-within/input:text-red-400">
                            <i class="fas fa-users-slash text-white/30 group-focus-within/input:text-red-400 transition-colors text-[14px] sm:text-[16px]"></i>
                        </div>
                        <input type="text" name="bully_name" required class="w-full pl-11 sm:pl-14 pr-4 sm:pr-5 py-3.5 sm:py-4 input-dark rounded-xl sm:rounded-[1.25rem] text-[14px] sm:text-[15px] font-semibold focus:border-red-400 focus:shadow-[0_0_0_4px_rgba(248,113,113,0.2)]" placeholder="Who did this?">
                    </div>
                </div>

                <!-- DETAILS -->
                <div class="space-y-1.5 sm:space-y-2">
                    <label class="block text-[12px] sm:text-[13px] font-bold text-white/80 ml-1 uppercase tracking-wider">
                        Please describe what happened <span class="text-red-400 text-sm">*</span>
                    </label>
                    <textarea name="incident_details" required rows="5" class="w-full px-4 sm:px-6 py-4 sm:py-5 input-dark rounded-xl sm:rounded-[1.5rem] text-[14px] sm:text-[15px] font-medium leading-relaxed resize-none" placeholder="Where did it happen? When? Describe exactly what was said or done. Take your time, there is no rush."></textarea>
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit" id="submitBtn" class="submit-btn w-full font-black py-3.5 sm:py-4 px-4 sm:px-6 rounded-xl sm:rounded-[1.25rem] focus:outline-none focus:ring-4 focus:ring-gold/30 flex items-center justify-center gap-2 sm:gap-3 h-[54px] sm:h-[64px] mt-6 sm:mt-8 group/btn bg-gold">
                    <span id="btnText" class="font-heading text-[16px] sm:text-[18px] tracking-wide text-[#042E15] drop-shadow-sm mix-blend-multiply pt-0.5">Send Secure Report</span>
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
        
        <!-- Universal Emergency Hotline -->
        <div class="mt-6 sm:mt-8 mb-12 sm:mb-16 px-5 sm:px-6 py-5 sm:py-6 font-medium text-center bg-white/5 backdrop-blur-lg rounded-[1.5rem] sm:rounded-[2rem] border border-white/10 shadow-[0_15px_30px_-10px_rgba(0,0,0,0.3)] anim-fade-up hover:shadow-[0_20px_40px_-10px_rgba(0,0,0,0.5)] transition-shadow duration-500" style="animation-delay: 0.3s;">
            <p class="text-[14px] sm:text-[16px] font-black text-red-400 mb-1.5 sm:mb-2 font-heading tracking-wide flex items-center justify-center gap-2">
                <i class="fas fa-exclamation-circle animate-pulse"></i> Is this a physical emergency?
            </p>
            <p class="text-[12px] sm:text-[14px] text-white/60 leading-relaxed max-w-lg mx-auto font-medium">
                Please do not wait. Call emergency services immediately at <a href="tel:911" class="font-black text-white hover:text-red-400 transition-colors bg-white/10 px-2 py-0.5 rounded-lg border border-white/20">911</a> or notify campus security at <a href="tel:09123456789" class="font-black text-white hover:text-red-400 transition-colors bg-white/10 px-2 py-0.5 rounded-lg border border-white/20">0912-345-6789</a>.
            </p>
        </div>
        
    </div>

    <script>
        function toggleVictim() {
            const bystander = document.querySelector('input[value="bystander"]').checked;
            const victimDiv = document.getElementById('victimDiv');
            const victimInput = document.getElementById('victimName');
            
            if(bystander) {
                victimDiv.classList.remove('hidden');
                void victimDiv.offsetWidth; 
                victimDiv.style.opacity = '1';
                victimDiv.style.height = victimDiv.scrollHeight + 'px';
                victimDiv.style.transform = 'translateY(0)';
                victimInput.setAttribute('required', 'true');
                setTimeout(() => { victimDiv.style.height = 'auto'; }, 500);
            } else {
                victimDiv.style.height = victimDiv.scrollHeight + 'px';
                void victimDiv.offsetWidth;
                victimDiv.style.opacity = '0';
                victimDiv.style.transform = 'translateY(-10px)';
                victimDiv.style.height = '0';
                setTimeout(() => { victimDiv.classList.add('hidden'); }, 500);
                victimInput.removeAttribute('required');
                victimInput.value = '';
            }
        }
        document.addEventListener('DOMContentLoaded', toggleVictim);

        document.getElementById('bullyingForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const icon = document.getElementById('btnIcon');
            const spinner = document.getElementById('btnSpinner');
            btn.classList.add('opacity-80', 'cursor-wait', 'scale-[0.98]');
            text.innerText = 'Encrypting & Sending...';
            icon.classList.add('hidden');
            spinner.classList.remove('hidden');
        });
    </script>
</body>
</html>
