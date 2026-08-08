<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('images/logo.png?v=3') ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Student Portal - Secure Login</title>
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
        
        /* Gold gradient button */
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
        
        /* Dynamic Spotlight Effect */
        .spotlight {
            background: radial-gradient(
                600px circle at var(--x) var(--y),
                rgba(217, 170, 46, 0.05),
                transparent 80%
            );
        }

        /* Splash Screen Animations */
        @keyframes scaleIn {
            0% { transform: translateY(15px) scale(0.95); opacity: 0; }
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
<body class="bg-student min-h-[100dvh] w-full flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden" onmousemove="updateSpotlight(event)">

            <!-- Premium Fast-Load Splash Screen -->
    <div id="splashScreen" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-student backdrop-blur-xl transition-all duration-700 ease-in-out">
        <div class="relative flex flex-col items-center z-10 w-full max-w-sm px-6">
            <!-- Logo -->
            <div class="relative mb-6 flex items-center justify-center w-24 h-24 sm:w-32 sm:h-32">
                <img src="<?= base_url('images/logo.png?v=6') ?>" alt="SVBSM Logo" 
                     class="relative w-full h-full object-contain z-10 drop-shadow-[0_0_20px_rgba(217,170,46,0.3)] animate-pulse"
                     id="splashLogoImg">
            </div>
            
            <!-- Branding -->
            <h1 class="text-3xl sm:text-4xl font-heading font-black tracking-[0.05em] text-white uppercase text-center drop-shadow-lg mb-2">
                Student<span class="text-gold">Shield</span>
            </h1>
            
            <p class="text-white/70 text-[10px] sm:text-[11px] font-semibold tracking-[0.15em] uppercase font-mono mb-8" id="splashStatusText">Initializing Data Vectors...</p>

            <!-- Sleek Loading Bar -->
            <div class="w-48 sm:w-64 h-1.5 bg-black/40 rounded-full overflow-hidden border border-white/5 relative">
                <div class="absolute top-0 bottom-0 left-0 bg-gradient-to-r from-[#D9AA2E] to-[#FCEEA1] shadow-[0_0_10px_#D9AA2E] rounded-full transition-all duration-200 ease-out" id="splashProgressBar" style="width: 0%;"></div>
            </div>
        </div>
    </div>


    <!-- Background Effects Container (Ensures no mobile scrolling) -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <!-- Interactive Spotlight Overlay -->
        <div id="spotlight-overlay" class="spotlight absolute inset-0 z-10"></div>

        <!-- Deep Ambient Background -->
        <div class="bg-grid absolute inset-0"></div>
        
        <!-- Adjusted blob positioning to prevent horizontal overflow constraints on mobile -->
        <div class="glow-blob bg-gold w-[300px] h-[300px] sm:w-[500px] sm:h-[500px] top-[-5%] right-[-10%] mix-blend-screen"></div>
        <div class="glow-blob bg-primary-light w-[400px] h-[400px] sm:w-[600px] sm:h-[600px] bottom-[-10%] left-[-15%] mix-blend-screen" style="animation-delay: -5s; animation-direction: alternate-reverse;"></div>
    </div>

    <div class="w-full max-w-[480px] z-20 relative animate-[fadeInUp_0.8s_cubic-bezier(0.16,1,0.3,1)] mt-4 sm:mt-0">
        
        <!-- Adjusted p-6 for tight mobile grids, p-12 for tablets/desktop -->
        <div class="student-card p-6 sm:p-8 md:p-12 rounded-[2rem] sm:rounded-[2.5rem] relative overflow-hidden group hover:shadow-[0_40px_80px_-20px_rgba(0,0,0,0.6)] transition-shadow duration-500">
            
            <div class="text-center mb-8 sm:mb-10">
                <!-- Logo: smaller on mobile, larger on larger screens -->
                <img src="<?= base_url('images/logo.png?v=6') ?>" alt="SVBSM Logo" class="w-24 h-24 sm:w-32 sm:h-32 object-contain mx-auto mb-5 sm:mb-6 drop-shadow-[0_10px_20px_rgba(0,0,0,0.5)]"
                     onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'40\' fill=\'none\' stroke=\'%23D9AA2E\' stroke-width=\'5\'/><path d=\'M35 55l10 10 25-25\' fill=\'none\' stroke=\'%23D9AA2E\' stroke-width=\'5\' stroke-linecap=\'round\' stroke-linejoin=\'round\'/></svg>';">
                
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
            <form action="<?= base_url('login/authenticate') ?>" method="post" class="space-y-5 sm:space-y-6 relative z-10 w-full">
                <?= csrf_field() ?>
                
                <div class="space-y-1.5 sm:space-y-2">
                    <label for="name" class="block text-[12px] sm:text-[13px] font-bold text-white/80 ml-1 uppercase tracking-wider">Student Name</label>
                    <div class="relative group/input">
                        <!-- pl-4 on mobile to not eat space, text-base rather than text-lg for icons on small -->
                        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center pointer-events-none transition-colors group-focus-within/input:text-gold">
                            <i class="fas fa-user text-white/40 group-focus-within/input:text-gold transition-colors text-[16px] sm:text-[18px]"></i>
                        </div>
                        <input type="text" name="name" id="name" required
                               class="w-full pl-11 sm:pl-14 pr-4 sm:pr-12 py-3.5 sm:py-4 input-dark rounded-xl sm:rounded-[1.25rem] text-[16px] font-bold outline-none"
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
                               class="w-full pl-11 sm:pl-14 pr-4 sm:pr-12 py-3.5 sm:py-4 input-dark rounded-xl sm:rounded-[1.25rem] text-[16px] font-bold outline-none tracking-[0.1em]"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex flex-col gap-3 mt-4 mb-2">
                    <div class="flex items-start group">
                        <input id="terms" name="terms" type="checkbox" required
                               class="w-4 h-4 mt-0.5 text-gold bg-white/10 border-white/20 rounded focus:ring-gold/50 focus:ring-2 cursor-pointer appearance-none checked:bg-gold checked:border-gold relative before:content-['✓'] before:absolute before:text-black before:text-xs before:font-bold before:opacity-0 checked:before:opacity-100 before:top-1/2 before:left-1/2 before:-translate-x-1/2 before:-translate-y-1/2 before:transition-opacity shrink-0">
                        <label for="terms" class="ml-2.5 text-[12px] sm:text-[13px] font-semibold text-white/70 group-hover:text-white transition-colors cursor-pointer select-none leading-snug">
                            I agree to the <button type="button" onclick="openModal('termsModal')" class="text-gold hover:underline focus:outline-none">Terms & Conditions</button> and <button type="button" onclick="openModal('privacyModal')" class="text-gold hover:underline focus:outline-none">Data Privacy Policy</button>.
                        </label>
                    </div>

                    <div class="flex items-start group">
                        <input id="guardian_consent" name="guardian_consent" type="checkbox" required
                               class="w-4 h-4 mt-0.5 text-gold bg-white/10 border-white/20 rounded focus:ring-gold/50 focus:ring-2 cursor-pointer appearance-none checked:bg-gold checked:border-gold relative before:content-['✓'] before:absolute before:text-black before:text-xs before:font-bold before:opacity-0 checked:before:opacity-100 before:top-1/2 before:left-1/2 before:-translate-x-1/2 before:-translate-y-1/2 before:transition-opacity shrink-0">
                        <label for="guardian_consent" class="ml-2.5 text-[12px] sm:text-[13px] font-semibold text-white/70 group-hover:text-white transition-colors cursor-pointer select-none leading-snug">
                            I confirm that I have my <button type="button" onclick="openModal('guardianModal')" class="text-gold hover:underline focus:outline-none">Guardian's consent</button> to use this platform.
                        </label>
                    </div>

                    <div class="flex items-center group pt-1">
                        <input id="remember" name="remember" type="checkbox"
                               class="w-4 h-4 text-gold bg-white/10 border-white/20 rounded focus:ring-gold/50 focus:ring-2 cursor-pointer appearance-none checked:bg-gold checked:border-gold relative before:content-['✓'] before:absolute before:text-black before:text-xs before:font-bold before:opacity-0 checked:before:opacity-100 before:top-1/2 before:left-1/2 before:-translate-x-1/2 before:-translate-y-1/2 before:transition-opacity shrink-0">
                        <label for="remember" class="ml-2.5 text-[13px] sm:text-[14px] font-semibold text-white/70 group-hover:text-white transition-colors cursor-pointer select-none">
                            Remember me on this device
                        </label>
                    </div>
                </div>

                <button type="submit" id="submitBtn" class="submit-btn w-full font-black py-3.5 sm:py-4 px-4 sm:px-6 rounded-xl sm:rounded-[1.25rem] focus:outline-none focus:ring-4 focus:ring-gold/30 flex items-center justify-center gap-2 sm:gap-3 h-[54px] sm:h-[60px] mt-8 sm:mt-10 group/btn">
                    <span id="btnText" class="font-heading text-[16px] sm:text-[18px] tracking-wide pt-0.5 text-primary-light mix-blend-multiply">Access Portal</span>
                    <i id="btnIcon" class="fas fa-arrow-right text-primary-light mix-blend-multiply text-[13px] sm:text-sm group-hover/btn:translate-x-1.5 group-hover/btn:scale-110 transition-all duration-300"></i>
                    <svg id="btnSpinner" class="animate-spin h-5 w-5 text-primary-light mix-blend-multiply hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-30" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>

                <p class="text-center text-white/50 text-[12px] sm:text-[13px] mt-5">
                    Don't have an account?
                    <a href="<?= base_url('register') ?>" class="text-yellow-400 hover:text-yellow-300 font-semibold transition-colors duration-200">Register here</a>
                </p>
            </form>
            
        </div>
        
    </div>

    <!-- Modals -->
    <div id="termsModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal('termsModal')"></div>
        <div class="relative bg-[#0d2114] border border-white/10 rounded-2xl w-full max-w-2xl max-h-[85vh] flex flex-col shadow-2xl animate-[scaleIn_0.3s_ease-out]">
            <div class="p-5 sm:p-6 border-b border-white/10 flex justify-between items-center">
                <h2 class="text-lg sm:text-xl font-heading font-bold text-white">Terms & Conditions</h2>
                <button type="button" onclick="closeModal('termsModal')" class="text-white/50 hover:text-white transition-colors focus:outline-none"><i class="fas fa-times text-lg"></i></button>
            </div>
            <div class="p-5 sm:p-6 overflow-y-auto text-white/80 text-[13px] sm:text-sm space-y-4 font-sans leading-relaxed">
                <p><strong class="text-gold">1. Acceptance of Terms:</strong> By accessing and using StudentShield, you agree to comply with these Terms & Conditions. This platform is designed to promote a safe and supportive school environment.</p>
                <p><strong class="text-gold">2. Eligibility & Guardian Consent:</strong> You must be an actively enrolled student. If you are under 18 years of age, you represent that you have obtained verifiable consent from your parent or legal guardian to use this service.</p>
                <p><strong class="text-gold">3. Acceptable Use:</strong> The platform must be used responsibly. Submitting false reports, impersonating others, or using the platform to harass or bully others is strictly prohibited and may result in disciplinary action from the school.</p>
                <p><strong class="text-gold">4. Confidentiality vs. Anonymity:</strong> While reports and venting logs are kept confidential, total anonymity is waived in cases where there is an imminent threat to life, self-harm, or severe danger. Authorized school administrators reserve the right to identify users to prevent harm.</p>
                <p><strong class="text-gold">5. Interventions:</strong> School counselors or designated safety officers may intervene based on the severity of the submitted reports to ensure the physical and mental well-being of the students involved.</p>
                <p><strong class="text-gold">6. Not an Emergency Service:</strong> StudentShield is not a real-time emergency response system. If you are in immediate danger, please contact local emergency authorities or proceed to the nearest hospital or police station.</p>
            </div>
            <div class="p-4 border-t border-white/10 bg-black/20 text-right rounded-b-2xl">
                <button type="button" onclick="closeModal('termsModal')" class="px-5 py-2 bg-white/10 hover:bg-white/20 text-white text-sm rounded-xl font-semibold transition-colors focus:outline-none">Close</button>
            </div>
        </div>
    </div>

    <div id="privacyModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal('privacyModal')"></div>
        <div class="relative bg-[#0d2114] border border-white/10 rounded-2xl w-full max-w-2xl max-h-[85vh] flex flex-col shadow-2xl animate-[scaleIn_0.3s_ease-out]">
            <div class="p-5 sm:p-6 border-b border-white/10 flex justify-between items-center">
                <h2 class="text-lg sm:text-xl font-heading font-bold text-white">Data Privacy Policy</h2>
                <button type="button" onclick="closeModal('privacyModal')" class="text-white/50 hover:text-white transition-colors focus:outline-none"><i class="fas fa-times text-lg"></i></button>
            </div>
            <div class="p-5 sm:p-6 overflow-y-auto text-white/80 text-[13px] sm:text-sm space-y-4 font-sans leading-relaxed">
                <p>This policy outlines how we handle your personal data in strict compliance with the Data Privacy Act of 2012 (Republic Act No. 10173) of the Philippines.</p>
                <p><strong class="text-gold">1. Data Collection & Legitimate Purpose:</strong> We collect personal information and sensitive personal information (such as your name, ID, and health/incident reports) solely for the legitimate purpose of processing incident reports, providing guidance counseling, and ensuring a safe educational environment.</p>
                <p><strong class="text-gold">2. Data Processing & Protection:</strong> Your data is protected using end-to-end encryption and strict organizational, physical, and technical security measures. Only designated, authorized school personnel (such as counselors and the Data Protection Officer) have access to identifiable information.</p>
                <p><strong class="text-gold">3. Data Retention & Disposal:</strong> We retain your personal data only for as long as necessary to fulfill the stated purposes or as required by school policy and the law. Once the retention period expires, data is securely disposed of or anonymized.</p>
                <p><strong class="text-gold">4. Third-Party Sharing:</strong> We do not sell or share your data with third parties for marketing. Information may only be disclosed to law enforcement, medical professionals, or government authorities if mandated by law or necessary for emergency intervention to protect your vital interests.</p>
                <p><strong class="text-gold">5. Data Subject Rights:</strong> As a data subject under RA 10173, you have the right to be informed, object to processing, access, rectify, erase, or block your personal data, as well as the right to data portability and the right to file a complaint with the National Privacy Commission (NPC) for any damages. To exercise these rights, you or your guardian may contact the school's Data Protection Officer (DPO).</p>
            </div>
            <div class="p-4 border-t border-white/10 bg-black/20 text-right rounded-b-2xl">
                <button type="button" onclick="closeModal('privacyModal')" class="px-5 py-2 bg-white/10 hover:bg-white/20 text-white text-sm rounded-xl font-semibold transition-colors focus:outline-none">Close</button>
            </div>
        </div>
    </div>

    <div id="guardianModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal('guardianModal')"></div>
        <div class="relative bg-[#0d2114] border border-white/10 rounded-2xl w-full max-w-2xl max-h-[85vh] flex flex-col shadow-2xl animate-[scaleIn_0.3s_ease-out]">
            <div class="p-5 sm:p-6 border-b border-white/10 flex justify-between items-center">
                <h2 class="text-lg sm:text-xl font-heading font-bold text-white">Guardian Consent</h2>
                <button type="button" onclick="closeModal('guardianModal')" class="text-white/50 hover:text-white transition-colors focus:outline-none"><i class="fas fa-times text-lg"></i></button>
            </div>
            <div class="p-5 sm:p-6 overflow-y-auto text-white/80 text-[13px] sm:text-sm space-y-4 font-sans leading-relaxed">
                <p>In compliance with the Data Privacy Act of 2012 (RA 10173), processing the personal data and sensitive personal information of minors requires the explicit, free, and informed consent of a parent or legal guardian. By checking this box, you confirm that:</p>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Your parent or legal guardian has reviewed and agreed to the Terms & Conditions and Data Privacy Policy of StudentShield.</li>
                    <li>They consent to the collection, processing, and retention of your personal and sensitive personal information for the purposes of school safety and counseling.</li>
                    <li>They understand that in cases of imminent threat, self-harm, or severe danger to your vital interests, the school may lift anonymity to provide critical and life-saving interventions.</li>
                </ul>
                <p class="mt-4 italic text-white/50 text-xs">If you have not discussed this with your parent or guardian, please do so before creating an account or logging in.</p>
            </div>
            <div class="p-4 border-t border-white/10 bg-black/20 text-right rounded-b-2xl">
                <button type="button" onclick="closeModal('guardianModal')" class="px-5 py-2 bg-white/10 hover:bg-white/20 text-white text-sm rounded-xl font-semibold transition-colors focus:outline-none">Close</button>
            </div>
        </div>
    </div>

<script>
            document.addEventListener("DOMContentLoaded", function() {
        const splash = document.getElementById('splashScreen');
        if (!sessionStorage.getItem('studentIntroPlayed')) {
            let hasLoaded = false;
            let minTimePassed = false;
            let progress = 0;
            const bar = document.getElementById('splashProgressBar');
            const status = document.getElementById('splashStatusText');
            
            const hideSplash = () => {
                splash.classList.add('opacity-0', 'backdrop-blur-none');
                splash.style.pointerEvents = 'none';
                setTimeout(() => { splash.style.display = 'none'; }, 700);
                sessionStorage.setItem('studentIntroPlayed', 'true');
            };

            const progressInterval = setInterval(() => {
                if(!hasLoaded) {
                    progress += Math.random() * 12;
                    if(progress > 85) progress = 85 + Math.random() * 5; // Hang around 85-90%
                    if(bar) bar.style.width = progress + '%';
                }
            }, 100);

            setTimeout(() => {
                minTimePassed = true;
                if(hasLoaded) finishLoading();
            }, 1000); // Wait at least 1s
            
            const finishLoading = () => {
                clearInterval(progressInterval);
                if(bar) bar.style.width = '100%';
                if(status) status.innerText = 'Connection Secured';
                setTimeout(hideSplash, 300); // Fade out slightly after hitting 100%
            };

            window.addEventListener('load', () => {
                hasLoaded = true;
                if(minTimePassed) finishLoading();
            });

            setTimeout(finishLoading, 4000); // 4s absolute max
        } else {
            splash.style.display = 'none';
        }
    });

    function updateSpotlight(e) {
        const overlay = document.getElementById('spotlight-overlay');
        overlay.style.setProperty('--x', `${e.clientX}px`);
        overlay.style.setProperty('--y', `${e.clientY}px`);
    }

    function openModal(id) {
        const modal = document.getElementById(id);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
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


