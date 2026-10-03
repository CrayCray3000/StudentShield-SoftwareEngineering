<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions - StudentShield</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?= view('partials/school_brand') ?>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: var(--school-green); }
        h1, h2, h3 { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="min-h-screen p-4 sm:p-8 text-gray-800">
    <div class="max-w-3xl mx-auto bg-white p-6 sm:p-10 rounded-2xl shadow-2xl mt-10">
        <h1 class="text-3xl font-bold mb-6 text-emerald-900">Terms and Conditions</h1>
        <div class="space-y-4 text-sm sm:text-base leading-relaxed">
            <p>Welcome to StudentShield. By accessing and using this platform, you agree to be bound by the following terms and conditions.</p>
            <h2 class="text-xl font-semibold text-emerald-800 mt-6">1. Acceptance of Terms</h2>
            <p>By registering or logging into StudentShield, you acknowledge that you have read, understood, and agreed to these terms.</p>
            <h2 class="text-xl font-semibold text-emerald-800 mt-6">2. Acceptable Use</h2>
            <p>The platform is provided for the purpose of reporting incidents, seeking guidance, and venting in a safe space. Users must not use the platform to submit false reports, harass others, or distribute malicious content.</p>
            <h2 class="text-xl font-semibold text-emerald-800 mt-6">3. Confidentiality</h2>
            <p>All reports and vents are treated with strict confidentiality. However, authorized counselors and administrators may access this information to ensure student safety and to provide appropriate interventions.</p>
            <h2 class="text-xl font-semibold text-emerald-800 mt-6">4. Modifications</h2>
            <p>We reserve the right to modify these terms at any time. Continued use of the platform constitutes acceptance of any changes.</p>
        </div>
        <div class="mt-8 pt-6 border-t border-gray-200">
            <a href="<?= base_url('login') ?>" class="text-emerald-700 hover:text-emerald-900 font-semibold flex items-center gap-2">
                &larr; Back to Login
            </a>
        </div>
    </div>
</body>
</html>
