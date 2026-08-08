<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Student Portal - Registration</title>
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
                        white: '#FBFDFB', 
                        black: '#131A15', 
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
                            900: '#212825',
                        },
                        red: {
                            50: '#FDF7F7',
                            100: '#FAEDED',
                            200: '#F4D6D6',
                            500: '#D95A5A',
                            600: '#C24545',
                            700: '#A33535',
                        },
                        primary: { DEFAULT: '#235D3A', light: '#2F754A', dark: '#19452B', darker: '#12331F', darkest: '#0A1C11' },
                        gold: { DEFAULT: '#D9AA2E', light: '#EAC255', bright: '#FCEEA1', dark: '#B5881D' },
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
            border: 1.5px solid rgba(255, 255, 255, 0.08); 
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
            will-change: transform, opacity;
            transform: translateZ(0);
        }
        @keyframes pulseGlow {
            0% { opacity: 0.1; transform: scale(0.9) translate(0,0) translateZ(0); }
            100% { opacity: 0.2; transform: scale(1.1) translate(-20px, 20px) translateZ(0); }
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
        
        .submit-btn {
            background: linear-gradient(135deg, #cda434, #e6c55e);
            color: #0A1C11;
            position: relative; overflow: hidden;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 15px -3px rgba(217, 170, 46, 0.2);
        }
        .submit-btn::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.3), transparent);
            transform: skewX(-20deg) translateX(-150%);
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .submit-btn:hover {
            box-shadow: 0 8px 25px -5px rgba(217, 170, 46, 0.4);
            transform: translateY(-1px) scale(1.01);
            background: linear-gradient(135deg, #dfb745, #f5d675);
        }
        .submit-btn:hover::after { transform: skewX(-20deg) translateX(150%); }
        
        .spotlight {
            background: radial-gradient(
                600px circle at var(--x) var(--y),
                rgba(217, 170, 46, 0.05),
                transparent 80%
            );
        }

        @keyframes scaleIn {
            0% { transform: translateY(15px) scale(0.95); opacity: 0; }
            100% { transform: translateY(0) scale(1); opacity: 1; }
        }
        
        @media (prefers-reduced-motion: reduce) {
            *, ::before, ::after { animation-play-state: paused !important; transition: none !important; }
        }
    </style>
</head>
<body class="bg-student min-h-[100dvh] w-full flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden" onmousemove="updateSpotlight(event)">

    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div id="spotlight-overlay" class="spotlight absolute inset-0 z-10"></div>
        <div class="bg-grid absolute inset-0"></div>
        <div class="glow-blob bg-gold w-[300px] h-[300px] sm:w-[500px] sm:h-[500px] top-[-5%] right-[-10%] mix-blend-screen"></div>
        <div class="glow-blob bg-primary-light w-[400px] h-[400px] sm:w-[600px] sm:h-[600px] bottom-[-10%] left-[-15%] mix-blend-screen" style="animation-delay: -5s; animation-direction: alternate-reverse;"></div>
    </div>

    <!-- Increased max width to fit 2 column layout -->
    <div class="w-full max-w-[700px] z-20 relative animate-[fadeInUp_0.8s_cubic-bezier(0.16,1,0.3,1)] mt-4 sm:mt-0">
        
        <div class="student-card p-6 sm:p-8 md:p-10 rounded-[2rem] sm:rounded-[2.5rem] relative overflow-hidden group hover:shadow-[0_40px_80px_-20px_rgba(0,0,0,0.6)] transition-shadow duration-500">
            
            <div class="text-center mb-6 sm:mb-8">
                <img src="<?= base_url('images/logo.png?v=6') ?>" alt="SVBSM Logo" class="w-20 h-20 sm:w-24 sm:h-24 object-contain mx-auto mb-4 drop-shadow-[0_10px_20px_rgba(0,0,0,0.5)]">
                
                <h1 class="text-[1.75em] sm:text-[2rem] font-heading font-black tracking-tight text-white mb-2 leading-tight">Student <span class="text-gold">Registration</span></h1>
                <p class="text-white/60 font-medium text-[14px] sm:text-[15px]">Create your secure account</p>
            </div>

            <!-- Error/Success Flash -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-500/10 backdrop-blur-md border border-red-500/30 p-4 mb-6 rounded-2xl shadow-lg">
                    <p class="text-[13px] sm:text-[14px] font-bold text-red-200 leading-snug tracking-tight text-center"><?= session()->getFlashdata('error') ?></p>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-green-500/10 backdrop-blur-md border border-green-500/30 p-4 mb-6 rounded-2xl shadow-lg">
                    <p class="text-[13px] sm:text-[14px] font-bold text-green-200 leading-snug tracking-tight text-center"><?= session()->getFlashdata('success') ?></p>
                </div>
            <?php endif; ?>
            <?php if (isset($validation)): ?>
                <div class="bg-red-500/10 backdrop-blur-md border border-red-500/30 p-4 mb-6 rounded-2xl shadow-lg text-red-200 text-sm">
                    <?= $validation->listErrors() ?>
                </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form action="<?= base_url('register/process') ?>" method="post" class="space-y-4 relative z-10 w-full">
                <?= csrf_field() ?>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="name" class="block text-[12px] sm:text-[13px] font-bold text-white/80 ml-1 uppercase tracking-wider">Full Name</label>
                        <div class="relative group/input">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-user text-white/40 group-focus-within/input:text-gold transition-colors text-[16px]"></i>
                            </div>
                            <input type="text" name="name" id="name" required value="<?= set_value('name') ?>"
                                   class="w-full pl-11 pr-4 py-3 input-dark rounded-xl text-[15px] font-bold outline-none"
                                   placeholder="Juan Dela Cruz">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="student_id_number" class="block text-[12px] sm:text-[13px] font-bold text-white/80 ml-1 uppercase tracking-wider">Student ID Number</label>
                        <div class="relative group/input">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-white/40 group-focus-within/input:text-gold transition-colors text-[16px]"></i>
                            </div>
                            <input type="text" name="student_id_number" id="student_id_number" required value="<?= set_value('student_id_number') ?>"
                                   class="w-full pl-11 pr-4 py-3 input-dark rounded-xl text-[15px] font-bold outline-none"
                                   placeholder="2024-XXXXX">
                        </div>
                    </div>
                    
                    <div class="space-y-1.5">
                        <label for="grade_level" class="block text-[12px] sm:text-[13px] font-bold text-white/80 ml-1 uppercase tracking-wider">Grade Level</label>
                        <div class="relative group/input">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-layer-group text-white/40 group-focus-within/input:text-gold transition-colors text-[16px]"></i>
                            </div>
                            <!-- Using standard text input for grade level to match DB schema (string) -->
                            <input type="text" name="grade_level" id="grade_level" required value="<?= set_value('grade_level') ?>"
                                   class="w-full pl-11 pr-4 py-3 input-dark rounded-xl text-[15px] font-bold outline-none"
                                   placeholder="Grade 7, Grade 11, etc.">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="section" class="block text-[12px] sm:text-[13px] font-bold text-white/80 ml-1 uppercase tracking-wider">Section</label>
                        <div class="relative group/input">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-chalkboard-teacher text-white/40 group-focus-within/input:text-gold transition-colors text-[16px]"></i>
                            </div>
                            <input type="text" name="section" id="section" required value="<?= set_value('section') ?>"
                                   class="w-full pl-11 pr-4 py-3 input-dark rounded-xl text-[15px] font-bold outline-none"
                                   placeholder="e.g. Rizal">
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5 pt-2">
                    <label for="parent_contact" class="block text-[12px] sm:text-[13px] font-bold text-white/80 ml-1 uppercase tracking-wider">Parent/Guardian Contact (Phone/Email)</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-phone-alt text-white/40 group-focus-within/input:text-gold transition-colors text-[16px]"></i>
                        </div>
                        <input type="text" name="parent_contact" id="parent_contact" required value="<?= set_value('parent_contact') ?>"
                               class="w-full pl-11 pr-4 py-3 input-dark rounded-xl text-[15px] font-bold outline-none"
                               placeholder="09123456789 or parent@email.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <div class="space-y-1.5">
                        <label for="password" class="block text-[12px] sm:text-[13px] font-bold text-white/80 ml-1 uppercase tracking-wider">Create Password</label>
                        <div class="relative group/input">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-white/40 group-focus-within/input:text-gold transition-colors text-[16px]"></i>
                            </div>
                            <input type="password" name="password" id="password" required
                                   class="w-full pl-11 pr-4 py-3 input-dark rounded-xl text-[15px] font-bold outline-none tracking-[0.1em]"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="password_confirm" class="block text-[12px] sm:text-[13px] font-bold text-white/80 ml-1 uppercase tracking-wider">Confirm Password</label>
                        <div class="relative group/input">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-white/40 group-focus-within/input:text-gold transition-colors text-[16px]"></i>
                            </div>
                            <input type="password" name="password_confirm" id="password_confirm" required
                                   class="w-full pl-11 pr-4 py-3 input-dark rounded-xl text-[15px] font-bold outline-none tracking-[0.1em]"
                                   placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="submit-btn w-full font-black py-3 px-6 rounded-xl focus:outline-none focus:ring-4 focus:ring-gold/30 flex items-center justify-center gap-3 h-[54px] mt-8 group/btn">
                    <span id="btnText" class="font-heading text-[16px] tracking-wide pt-0.5 text-primary-light mix-blend-multiply">Submit Registration</span>
                    <i id="btnIcon" class="fas fa-paper-plane text-primary-light mix-blend-multiply text-sm group-hover/btn:translate-x-1.5 group-hover/btn:-translate-y-1.5 transition-all duration-300"></i>
                    <svg id="btnSpinner" class="animate-spin h-5 w-5 text-primary-light mix-blend-multiply hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-30" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
                
                <div class="text-center mt-6">
                    <a href="<?= base_url('login') ?>" class="text-[13px] font-semibold text-white/70 hover:text-gold transition-colors">
                        <i class="fas fa-arrow-left mr-1 text-[11px]"></i> Back to Login
                    </a>
                </div>
            </form>
            
        </div>
    </div>

<script>
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
        text.innerText = 'Processing...';
        icon.classList.add('hidden');
        spinner.classList.remove('hidden');
    });
</script>
</body>
</html>
