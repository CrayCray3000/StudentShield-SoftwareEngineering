<?php
// School banner green, shared by Tailwind and the CSS used on every portal.
$schoolPrimary = [
    'DEFAULT' => '#0F8A3A',
    'light' => '#249C4F',
    'dark' => '#0B7533',
    'darker' => '#09662C',
    'darkest' => '#075223',
];
$studentPalette = $studentPalette ?? false;
if ($studentPalette) {
    // Match the student login's Tailwind palette and background exactly.
    $schoolPrimary = [
        'DEFAULT' => '#235D3A', 'light' => '#2F754A', 'dark' => '#19452B',
        'darker' => '#12331F', 'darkest' => '#0A1C11',
    ];
}
?>
<style>
    :root {
        --school-green: <?= $schoolPrimary['DEFAULT'] ?>;
        --school-green-hover: <?= $schoolPrimary['darker'] ?>;
        --school-green-input: <?= $schoolPrimary['darkest'] ?>;
        --school-cream: #FFF4C7;
        --school-header-background: <?= $studentPalette
            ? 'radial-gradient(ellipse at 85% 85%, rgba(0,0,0,0.15) 0%, transparent 60%), radial-gradient(ellipse at 70% 10%, rgba(217,170,46,0.08) 0%, transparent 45%), linear-gradient(160deg, #128F42 0%, #0E7C38 55%, #09682D 100%)'
            : $schoolPrimary['DEFAULT'] ?>;
    }
</style>
<script>
    window.StudentShieldBrand = Object.freeze({
        primary: Object.freeze(<?= json_encode($schoolPrimary, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>)
    });
</script>
<link rel="stylesheet" href="<?= base_url('css/school-brand.css?v=' . (file_exists(FCPATH . 'css/school-brand.css') ? filemtime(FCPATH . 'css/school-brand.css') : '2')) ?>">

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Nunito:wght@400;500;600;700;800;900&display=swap">
