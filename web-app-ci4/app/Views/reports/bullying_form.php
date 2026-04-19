<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>StudentShield - Incident Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
            <script>
        let currentStep = 1;

        function toggleVictim() {
            const bystander = document.querySelector('input[value="bystander"]').checked;
            const victimDiv = document.getElementById('victimDiv');
            const victimInput = document.getElementById('victimName');
            
            if(bystander) {
                victimDiv.classList.remove('hidden');
                void victimDiv.offsetWidth; 
                victimDiv.style.opacity = '1';
                victimInput.setAttribute('required', 'required');
            } else {
                victimDiv.style.opacity = '0';
                setTimeout(() => {
                    victimDiv.classList.add('hidden');
                }, 300);
                victimInput.removeAttribute('required');
                victimInput.value = '';
            }
        }

        function validateStep(step) {
            if (step === 1) {
                const bystander = document.querySelector('input[value="bystander"]').checked;
                const victimName = document.getElementById('victimName').value.trim();
                if (bystander && victimName === '') {
                    alert('Please enter the victim\'s name.');
                    document.getElementById('victimName').focus();
                    return false;
                }
            } else if (step === 2) {
                const bullyName = document.getElementById('bullyName').value.trim();
                if (bullyName === '') {
                    alert('Please enter the name of the aggressor.');
                    document.getElementById('bullyName').focus();
                    return false;
                }
            } else if (step === 3) {
                const details = document.getElementById('incidentDetails').value.trim();
                if (details === '') {
                    alert('Please describe what happened.');
                    document.getElementById('incidentDetails').focus();
                    return false;
                }
            }
            return true;
        }

        function nextStep(current) {
            if (!validateStep(current)) return;

            const currentPane = document.getElementById('step' + current);
            const nextPane = document.getElementById('step' + (current + 1));
            
            currentPane.style.opacity = '0';
            setTimeout(() => {
                currentPane.classList.add('hidden');
                currentPane.classList.remove('active');
                
                nextPane.classList.remove('hidden');
                void nextPane.offsetWidth; // trigger reflow
                nextPane.classList.add('active');
                nextPane.style.opacity = '1';
                
                updateProgress(current + 1);
            }, 300);
        }

        function prevStep(current) {
            const currentPane = document.getElementById('step' + current);
            const prevPane = document.getElementById('step' + (current - 1));
            
            currentPane.style.opacity = '0';
            setTimeout(() => {
                currentPane.classList.add('hidden');
                currentPane.classList.remove('active');
                
                prevPane.classList.remove('hidden');
                void prevPane.offsetWidth;
                prevPane.classList.add('active');
                prevPane.style.opacity = '1';
                
                updateProgress(current - 1);
            }, 300);
        }

        function updateProgress(step) {
            currentStep = step;
            const percentages = { 1: '33.33%', 2: '66.66%', 3: '100%' };
            document.getElementById('progressBar').style.width = percentages[step];
            document.getElementById('stepCounter').innerText = `Step ${step} of 3`;
        }

        function submitWizard() {
            if (!validateStep(3)) return;
            
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');
            const btnSpinner = document.getElementById('btnSpinner');

            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            btnText.innerText = 'Encrypting & Sending...';
            btnIcon.classList.add('hidden');
            btnSpinner.classList.remove('hidden');

            document.getElementById('bullyingForm').submit();
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
<body class="text-white antialiased min-h-screen relative flex flex-col justify-center py-4 lg:py-6 overflow-x-hidden">

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

    <!-- Main Container: Centered Stack Layout -->
    <div class="w-[95%] md:w-[85%] lg:w-[65%] xl:w-[50%] max-w-[800px] mx-auto relative z-20 mt-4 sm:mt-6 flex flex-col items-center justify-center">
        
        <!-- Header & Emergency Container -->
        <div class="w-full flex flex-col justify-center items-center">
        
        <!-- Header Section -->
        <div class="text-center mb-6 sm:mb-8 anim-fade-up">
            <img src="<?= base_url('images/logo.png?v=6') ?>" alt="SVBSM Logo" class="w-16 h-16 sm:w-20 sm:h-20 object-contain mx-auto mb-3 sm:mb-4 drop-shadow-[0_10px_20px_rgba(0,0,0,0.5)]"
                 onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'40\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'5\'/><path d=\'M35 55l10 10 25-25\' fill=\'none\' stroke=\'%23F2C300\' stroke-width=\'5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'/></svg>';">
            
            <h1 class="text-[1.75rem] sm:text-[3rem] lg:text-[3.5rem] font-heading font-black text-white tracking-tight leading-[1.1] mb-3 sm:mb-4">
                You are <span class="text-gold">safe</span> here.
            </h1>
            <p class="text-white/60 font-medium text-[14px] sm:text-[16px] xl:text-[17px] max-w-[500px] mx-auto leading-relaxed px-4 sm:px-0">
                Your report is securely encrypted and sent directly to the school counselor. After verification, automated safety alerts will be sent to the relevant parents.
            </p>
        </div>

        <!-- Universal Emergency Hotline -->
        <div class="mt-2 w-full mb-6 sm:mb-8 px-5 py-5 font-medium text-center bg-white/5 backdrop-blur-lg rounded-[2rem] border border-white/10 shadow-[0_15px_30px_-10px_rgba(0,0,0,0.3)] anim-fade-up hover:shadow-[0_20px_40px_-10px_rgba(0,0,0,0.5)] transition-shadow duration-500" style="animation-delay: 0.3s;">
            <p class="text-[14px] sm:text-[16px] font-black text-red-400 mb-1 sm:mb-2 font-heading tracking-wide flex items-center justify-center gap-2">
                <i class="fas fa-exclamation-circle animate-pulse"></i> Is this a physical emergency?
            </p>
            <p class="text-[12px] sm:text-[14px] text-white/60 leading-relaxed max-w-lg mx-auto font-medium">
                Please do not wait. Call emergency services immediately at <a href="tel:911" class="font-black text-white hover:text-red-400 transition-colors bg-white/10 px-2 py-0.5 rounded-lg border border-white/20">911</a> or notify campus security at <a href="tel:09123456789" class="font-black text-white hover:text-red-400 transition-colors bg-white/10 px-2 py-0.5 rounded-lg border border-white/20">0912-345-6789</a>.
            </p>
        </div>
        
        </div> <!-- End Header Container -->

        <!-- Wizard Form Container -->
        <div class="w-full">

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
        <div class="glass-panel p-5 sm:p-8 anim-fade-up" style="animation-delay: 0.2s;">
                        <form action="<?= base_url('report-bullying/submit') ?>" method="POST" id="bullyingForm" class="relative min-h-[320px] pb-4">
                <div class="hidden"><?= csrf_field() ?></div>
                
                <!-- Progress Indicator -->
                <div class="mb-8 relative">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[11px] sm:text-[13px] font-bold text-white/50 uppercase tracking-widest" id="stepCounter">Step 1 of 3</span>
                    </div>
                    <div class="w-full bg-white/10 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-gold h-full w-1/3 transition-all duration-500 ease-out" id="progressBar"></div>
                    </div>
                </div>

                <!-- Step 1: Reporter Type & Victim (if bystander) -->
                <div class="step-pane active transition-opacity duration-300" id="step1">
                    <div class="space-y-4 sm:space-y-6">
                        <label class="block text-[15px] sm:text-[18px] font-black text-white leading-tight mb-2">
                            What is your connection to the incident?
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <label class="radio-card flex items-start gap-4 cursor-pointer p-4 sm:p-5 rounded-[1.25rem]">
                                <input type="radio" name="reporter_type" value="self" checked class="mt-1 w-5 h-5 text-gold border-white/20 bg-transparent focus:ring-gold focus:ring-opacity-50 cursor-pointer" onclick="toggleVictim()">
                                <div class="flex flex-col">
                                    <span class="text-[15px] sm:text-[16px] font-bold text-white">I am the Victim</span>
                                    <span class="text-[13px] text-white/50 font-medium">I'm reporting what happened to me.</span>
                                </div>
                            </label>
                            <label class="radio-card flex items-start gap-4 cursor-pointer p-4 sm:p-5 rounded-[1.25rem]">
                                <input type="radio" name="reporter_type" value="bystander" class="mt-1 w-5 h-5 text-gold border-white/20 bg-transparent focus:ring-gold focus:ring-opacity-50 cursor-pointer" onclick="toggleVictim()">
                                <div class="flex flex-col">
                                    <span class="text-[15px] sm:text-[16px] font-bold text-white">I am a Bystander</span>
                                    <span class="text-[13px] text-white/50 font-medium">I saw it happen to someone else.</span>
                                </div>
                            </label>
                        </div>
                        
                        <div id="victimDiv" class="hidden overflow-hidden transition-all duration-500 ease-in-out mt-4 sm:mt-6">
                            <label class="block text-[12px] sm:text-[13px] font-bold text-white/80 mb-2 uppercase tracking-wider">
                                Who is the Victim? <span class="text-red-400 text-sm">*</span>
                            </label>
                            <div class="relative group/input">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <i class="fas fa-user-friends text-white/30 text-[16px]"></i>
                                </div>
                                <input type="text" name="victim_name" id="victimName" class="w-full pl-14 pr-5 py-4 input-dark rounded-[1.25rem] text-[15px] font-semibold" placeholder="Enter the victim's full name">
                            </div>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="button" onclick="nextStep(1)" class="bg-white/10 hover:bg-white/20 text-white font-bold py-3.5 px-8 rounded-xl transition-colors flex items-center gap-2">
                                Next <i class="fas fa-arrow-right text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Aggressor -->
                <div class="step-pane hidden opacity-0 transition-opacity duration-300" id="step2">
                    <div class="space-y-4 sm:space-y-6">
                        <label class="block text-[15px] sm:text-[18px] font-black text-white leading-tight mb-2">
                            Who was the Aggressor? <span class="text-red-400">*</span>
                        </label>
                        <p class="text-[13px] text-white/50 font-medium mb-4">If there were multiple people, separate their names with commas.</p>
                        
                        <div class="relative group/input">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i class="fas fa-users-slash text-white/30 text-[16px]"></i>
                            </div>
                            <input type="text" name="bully_name" id="bullyName" class="w-full pl-14 pr-5 py-4 input-dark rounded-[1.25rem] text-[15px] font-semibold focus:border-red-400" placeholder="Who did this?">
                        </div>

                        <div class="flex justify-between mt-8">
                            <button type="button" onclick="prevStep(2)" class="text-white/50 hover:text-white font-bold py-3.5 px-4 rounded-xl transition-colors flex items-center gap-2 cursor-pointer">
                                <i class="fas fa-arrow-left text-sm"></i> Back
                            </button>
                            <button type="button" onclick="nextStep(2)" class="bg-white/10 hover:bg-white/20 text-white font-bold py-3.5 px-8 rounded-xl transition-colors flex items-center gap-2">
                                Next <i class="fas fa-arrow-right text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Details -->
                <div class="step-pane hidden opacity-0 transition-opacity duration-300" id="step3">
                    <div class="space-y-4 sm:space-y-6">
                        <label class="block text-[15px] sm:text-[18px] font-black text-white leading-tight mb-2">
                            Please describe what happened. <span class="text-red-400 text-sm">*</span>
                        </label>
                        <textarea name="incident_details" id="incidentDetails" rows="4" class="w-full px-6 py-5 input-dark rounded-[1.5rem] text-[15px] font-medium leading-relaxed resize-none" placeholder="Where did it happen? When? Describe exactly what was said or done. Take your time, there is no rush."></textarea>

                        <div class="flex justify-between items-center mt-8 gap-4">
                            <button type="button" onclick="prevStep(3)" class="text-white/50 hover:text-white font-bold py-3.5 px-2 rounded-xl transition-colors flex items-center gap-2 cursor-pointer shrink-0">
                                <i class="fas fa-arrow-left text-sm"></i> Back
                            </button>
                            <button type="button" onclick="submitWizard()" id="submitBtn" class="submit-btn font-black py-3.5 px-6 rounded-[1.25rem] focus:outline-none focus:ring-4 focus:ring-gold/30 flex items-center justify-center gap-3 bg-gold text-[#042E15] group/btn w-full sm:w-auto">
                                <span id="btnText" class="tracking-wide drop-shadow-sm mix-blend-multiply">Send Secure Report</span>
                                <i id="btnIcon" class="fas fa-lock mix-blend-multiply group-hover/btn:scale-110 transition-transform hidden sm:inline"></i>
                                <svg id="btnSpinner" class="animate-spin h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-30" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
            <!-- Trust Badges -->
            <div class="pt-6 sm:pt-8 mt-6 sm:mt-10 border-t border-white/10 flex flex-wrap items-center justify-center gap-x-6 sm:gap-x-8 gap-y-3 sm:gap-y-4 text-[10px] sm:text-[12px] text-white/40 font-extrabold uppercase tracking-widest">
                <span class="flex items-center gap-1.5 sm:gap-2"><i class="fas fa-user-shield text-gold/70 text-xs sm:text-sm"></i> Confidential Reporter</span>
                <span class="flex items-center gap-1.5 sm:gap-2"><i class="fas fa-check-double text-gold/70 text-xs sm:text-sm"></i> Counselor-Verified Alerts</span>
            </div>
            
        </div>
        </div>
        
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
