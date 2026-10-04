<?php
// School banner green, shared by Tailwind and the CSS used on every portal.
$schoolPrimary = [
    'DEFAULT' => '#0F8A3A',
    'light' => '#249C4F',
    'dark' => '#0B7533',
    'darker' => '#09662C',
    'darkest' => '#075223',
];
?>
<style>
    :root {
        --school-green: <?= $schoolPrimary['DEFAULT'] ?>;
        --school-green-hover: <?= $schoolPrimary['darker'] ?>;
        --school-green-input: <?= $schoolPrimary['darkest'] ?>;
        --school-cream: #FFF4C7;
    }
</style>
<script>
    window.StudentShieldBrand = Object.freeze({
        primary: Object.freeze(<?= json_encode($schoolPrimary, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>)
    });
</script>
<link rel="stylesheet" href="<?= base_url('css/school-brand.css?v=' . (file_exists(FCPATH . 'css/school-brand.css') ? filemtime(FCPATH . 'css/school-brand.css') : '2')) ?>">
