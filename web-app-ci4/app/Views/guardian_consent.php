<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardian Consent - StudentShield</title>
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
        <h1 class="text-3xl font-bold mb-6 text-emerald-900">Guardian Consent</h1>
        <div class="space-y-4 text-sm sm:text-base leading-relaxed">
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6">
                <p class="text-amber-800 font-medium">As a minor using the StudentShield platform, your participation requires the explicit knowledge and consent of your parent or legal guardian.</p>
            </div>
            
            <h2 class="text-xl font-semibold text-emerald-800 mt-6">1. Parental Involvement</h2>
            <p>The StudentShield system is designed to notify your parent or guardian via SMS and Email in the event of high-risk situations (such as severe emotional distress or confirmed bullying incidents) as determined by the school counselors and AI monitoring.</p>
            
            <h2 class="text-xl font-semibold text-emerald-800 mt-6">2. Acknowledgement</h2>
            <p>By checking the "Guardian Consent" box on the login or registration page, you declare that:</p>
            <ul class="list-disc pl-5 space-y-2 mt-2">
                <li>You have informed your parent or legal guardian about your use of the StudentShield platform.</li>
                <li>Your parent or legal guardian understands that their contact number may receive automated alerts regarding your well-being.</li>
                <li>Your parent or legal guardian authorizes the school to process your data in accordance with the Data Privacy Policy (RA 10173).</li>
            </ul>
        </div>
        <div class="mt-8 pt-6 border-t border-gray-200">
            <a href="<?= base_url('login') ?>" class="text-emerald-700 hover:text-emerald-900 font-semibold flex items-center gap-2">
                &larr; Back to Login
            </a>
        </div>
    </div>
</body>
</html>
