<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>StudentShield - Safe Space Portal</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Responsive scaling */
        @media (max-width: 1440px) { html { font-size: 15px; } }
        @media (max-width: 1280px) { html { font-size: 14px; } }
        
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
        }
        .input-dark::placeholder { color: rgba(255,255,255,0.6); }

        /* Mood Chips - Dark versions */
        .mood-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .mood-btn:hover { background: rgba(255, 255, 255, 0.1); border-color: rgba(255, 255, 255, 0.2); color: white; }
        .mood-btn.active {
            background: rgba(242, 195, 0, 0.15) !important; 
            border-color: #F2C300 !important;
            color: #F2C300 !important; 
            font-weight: 600 !important;
            box-shadow: 0 0 15px rgba(242, 195, 0, 0.2);
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
        
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-4px); } 50% { transform: translateX(4px); } 75% { transform: translateX(-4px); } }
    </style>
</head>
<body class="text-white antialiased min-h-screen relative flex flex-col justify-center py-16 sm:py-20 lg:py-24">

    <!-- Dark Ambient Elements -->
    <div class="bg-dark-mesh"></div>
    <div class="bg-grid"></div>
    <div class="blob bg-gold w-[300px] h-[300px] sm:w-[500px] sm:h-[500px] rounded-full top-[-5%] right-[-5%] mix-blend-screen"></div>
    <div class="blob bg-white w-[250px] h-[250px] sm:w-[400px] sm:h-[400px] rounded-full bottom-[-5%] left-[-5%] mix-blend-screen" style="animation-delay: -5s; animation-direction: alternate-reverse;"></div>

    <!-- Main Container -->
    <div class="w-full max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 mt-8 min-h-[80vh] flex flex-col justify-center">
        <div class="w-full relative z-20">
        
        <!-- Header Section -->
        <div class="text-center mb-8 sm:mb-12 anim-fade-up">
            <div class="flex items-center justify-center gap-2 mb-3">
                <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                <span class="text-[11px] font-semibold text-emerald-300 tracking-wider uppercase">Safe Space Active</span>
            </div>
            <h1 class="text-[1.75rem] sm:text-[3rem] lg:text-[3.5rem] font-heading font-black text-white tracking-tight leading-[1.1] mb-3 sm:mb-4">
                How are you <span class="text-gold">feeling?</span>
            </h1>
            <p class="text-white/60 font-medium text-[14px] sm:text-[17px] max-w-[500px] mx-auto leading-relaxed px-4 sm:px-0">
                Take your time. There are no wrong answers here. You are completely safe.
            </p>
        </div>

        <div class="flex items-center justify-center gap-3 sm:gap-4 mb-8 anim-fade-up">
            <!-- Daily Affirmation Banner -->
            <div class="bg-white/5 backdrop-blur-xl rounded-[1.25rem] px-5 sm:px-8 py-3 border border-white/10 shadow-lg text-center max-w-[90%]">
                <p id="dailyAffirmation" class="text-[13px] sm:text-[14.5px] font-medium text-gold/90 italic font-serif">
                    "Your current situation is not your final destination. Take it one day at a time."
                </p>
            </div>
        </div>

        <!-- Flash Messages -->
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
        <div class="glass-panel p-5 sm:p-8 lg:p-10 anim-fade-up" style="animation-delay: 0.2s;">
            <form action="<?= base_url('student/vent/submit') ?>" method="POST" id="ventForm" class="space-y-6 sm:space-y-8">
                
                <!-- Textarea -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between mb-2">
                        <label for="vent_text" class="block text-[12px] sm:text-[13px] font-bold text-white/80 ml-1 uppercase tracking-wider">
                            I want to express... <span class="text-red-400">*</span>
                        </label>
                        <span id="charCount" class="text-[11px] font-mono font-bold text-white/40 bg-white/5 px-2 py-1 rounded-[6px] border border-white/10">0 / 2000</span>
                    </div>
                    <textarea id="vent_text" name="vent_text" maxlength="2000" required
                              class="w-full px-4 sm:px-6 py-4 sm:py-5 input-dark rounded-xl sm:rounded-[1.5rem] text-[15px] sm:text-[16px] font-medium leading-relaxed resize-none min-h-[160px] md:min-h-[200px]" 
                              placeholder="Start typing whenever you feel ready..."></textarea>
                </div>

                <!-- Mood Quick-Select -->
                <div>
                    <p class="text-[12px] font-bold text-white/70 mb-3 ml-1 uppercase tracking-wider">
                        My Current Emotion (Optional)
                    </p>
                    <input type="hidden" name="emotions" id="emotionsInput" value="">
                    <div class="flex flex-wrap gap-2.5" id="moodSelect">
                        <button type="button" data-mood="Sad" class="mood-btn px-4 py-2.5 rounded-full text-[13px] font-semibold">😔 Sad</button>
                        <button type="button" data-mood="Angry" class="mood-btn px-4 py-2.5 rounded-full text-[13px] font-semibold">😠 Angry</button>
                        <button type="button" data-mood="Anxious" class="mood-btn px-4 py-2.5 rounded-full text-[13px] font-semibold">😰 Anxious</button>
                        <button type="button" data-mood="Lonely" class="mood-btn px-4 py-2.5 rounded-full text-[13px] font-semibold">😞 Lonely</button>
                        <button type="button" data-mood="Overwhelmed" class="mood-btn px-4 py-2.5 rounded-full text-[13px] font-semibold">😵 Overwhelmed</button>
                        <button type="button" data-mood="Hurt" class="mood-btn px-4 py-2.5 rounded-full text-[13px] font-semibold">🤕 Hurt</button>
                        <button type="button" data-mood="Numb" class="mood-btn px-4 py-2.5 rounded-full text-[13px] font-semibold">😶 Numb</button>
                        <button type="button" data-mood="Scared" class="mood-btn px-4 py-2.5 rounded-full text-[13px] font-semibold">😨 Scared</button>
                    </div>
                </div>

                <!-- Confidentiality Toggle Card -->
                <label for="hide_from_parents" class="group relative flex items-start gap-4 p-5 rounded-[1.25rem] bg-white/5 border border-white/10 hover:bg-white/10 hover:border-gold/30 transition-all cursor-pointer overflow-hidden mt-4">
                    <div class="relative flex items-center justify-center mt-0.5 z-10 flex-shrink-0">
                        <input type="checkbox" id="hide_from_parents" name="hide_from_parents" value="1" 
                               class="peer appearance-none w-5 h-5 rounded-[6px] border-2 border-white/30 bg-transparent checked:bg-gold checked:border-gold focus:outline-none focus:ring-4 focus:ring-gold/20 transition-all cursor-pointer">
                        <svg class="absolute w-3.5 h-3.5 text-[#042E15] opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100 pointer-events-none stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <div class="flex-1 z-10">
                        <h4 class="text-white font-heading font-bold text-[14px] sm:text-[15px] flex items-center gap-2 mb-1 group-hover:text-gold transition-colors">
                            <i class="fas fa-user-lock text-gold/70 text-sm"></i> Let's keep this just between us.
                        </h4>
                        <p class="text-white/50 text-[12px] sm:text-[13px] leading-relaxed pr-2 font-medium">
                            It's okay if you aren't ready for your family to know yet. By checking this box, we promise to notify our Guidance Counselors only. <strong class="text-white/80">You are safe here.</strong>
                        </p>
                    </div>
                </label>

                <!-- SUBMIT BUTTON -->
                <button type="submit" id="submitBtn" class="submit-btn w-full font-black py-4 px-6 rounded-[1.25rem] focus:outline-none focus:ring-4 focus:ring-gold/30 flex items-center justify-center gap-3 h-[54px] sm:h-[64px] mt-8 group/btn bg-gold">
                    <span id="btnText" class="font-heading text-[16px] sm:text-[18px] tracking-wide text-[#042E15] drop-shadow-sm mix-blend-multiply pt-0.5">Let It Out Securely</span>
                    <i id="btnIcon" class="fas fa-paper-plane text-[#042E15] text-[14px] sm:text-[16px] mix-blend-multiply group-hover/btn:translate-x-1 group-hover/btn:-translate-y-1 transition-transform duration-300"></i>
                    <svg id="btnSpinner" class="animate-spin h-5 w-5 sm:h-6 sm:w-6 text-[#042E15] hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-30" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
            </form>
            
            <!-- Trust Badges -->
            <div class="pt-6 sm:pt-8 mt-6 sm:mt-10 border-t border-white/10 flex flex-wrap items-center justify-center gap-x-6 gap-y-3 text-[11px] sm:text-[12px] text-white/40 font-extrabold uppercase tracking-widest">
                <span class="flex items-center gap-2"><i class="fas fa-lock text-gold/70 text-xs sm:text-sm"></i> Encrypted</span>
                <span class="flex items-center gap-2"><i class="fas fa-shield-alt text-gold/70 text-xs sm:text-sm"></i> Protected Space</span>
            </div>
        </div>

        <div class="flex items-center justify-center gap-4 mt-8 sm:mt-12 anim-fade-up" style="animation-delay: 0.3s;">
            <a href="<?= base_url('report-bullying') ?>" class="text-xs font-bold text-red-400 hover:text-red-300 px-4 py-2 rounded-xl bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 transition-colors uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-exclamation-triangle"></i> Report Incident
            </a>
            <a href="/logout" class="text-xs font-bold text-white/40 hover:text-white/80 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 transition-colors uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-sign-out-alt"></i> Sign Out
            </a>
        </div>

        </div>
    </div>

    <!-- Empathy Modal -->
    <div id="empathyModal" class="fixed inset-0 z-[100] hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-md transition-opacity duration-500 opacity-0" id="empathyBackdrop"></div>
        <div class="bg-[#0b2918] border border-white/10 p-8 sm:p-10 max-w-md w-[90%] rounded-[2rem] text-center shadow-2xl relative z-10 scale-95 opacity-0 transition-all duration-500" id="empathyContent">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gold/10 mb-5 border border-gold/20">
                <i class="fas fa-heart text-gold text-2xl"></i>
            </div>
            <h2 class="text-2xl font-heading font-black text-white mb-2">We hear you.</h2>
            <p class="text-white/60 leading-relaxed mb-6 text-[14px] font-medium">
                You've taken a brave step by sharing what you feel. Remember — you don't have to carry this alone.<br><br>
                <span class="text-gold font-bold">Our counselors are ready to help.</span>
            </p>
            <button id="closeModalBtn" class="w-full submit-btn font-black uppercase text-[13px] tracking-wider py-4 px-6 rounded-xl shadow-lg">I understand</button>
        </div>
    </div>

    <script>
        const textarea = document.getElementById('vent_text');
        const charCount = document.getElementById('charCount');
        textarea.addEventListener('input', function() {
            const len = this.value.length;
            charCount.textContent = len + ' / 2000';
            if (len > 1800) charCount.style.color = '#f87171'; // red-400
            else if (len > 1200) charCount.style.color = '#F2C300'; // gold
            else charCount.style.color = 'rgba(255,255,255,0.4)';
        });

        document.querySelectorAll('.mood-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.classList.toggle('active');
                
                // Update hidden input with active moods
                const activeMoods = [];
                document.querySelectorAll('.mood-btn.active').forEach(activeBtn => {
                    activeMoods.push(activeBtn.getAttribute('data-mood'));
                });
                document.getElementById('emotionsInput').value = activeMoods.join(', ');
            });
        });

        // Modal triggers if successfully vented
        <?php if (session()->getFlashdata('message')): ?>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('empathyModal');
                const backdrop = document.getElementById('empathyBackdrop');
                const content = document.getElementById('empathyContent');
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                
                // Trigger reflow
                void modal.offsetWidth;
                
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
                
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');

                document.getElementById('closeModalBtn').addEventListener('click', () => {
                    backdrop.classList.remove('opacity-100');
                    backdrop.classList.add('opacity-0');
                    
                    content.classList.remove('scale-100', 'opacity-100');
                    content.classList.add('scale-95', 'opacity-0');
                    
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }, 500);
                });
            });
        <?php endif; ?>

        const affirmations = [
            '"Your current situation is not your final destination."',
            '"You survive 100% of your bad days. Keep going."',
            '"Healing is not linear, and that is perfectly okay."',
            '"You are stronger than the challenges you are facing."'
        ];
        document.getElementById('dailyAffirmation').innerText = affirmations[Math.floor(Math.random() * affirmations.length)];

        // Form Submission State
        document.getElementById('ventForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const icon = document.getElementById('btnIcon');
            const spinner = document.getElementById('btnSpinner');
            
            btn.classList.add('opacity-80', 'cursor-wait');
            text.innerText = 'Securing & Sending...';
            icon.classList.add('hidden');
            spinner.classList.remove('hidden');
        });
    </script>
</body>
</html>
