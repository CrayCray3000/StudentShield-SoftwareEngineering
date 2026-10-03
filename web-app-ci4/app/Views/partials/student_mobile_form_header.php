<?php $isIncidentPage = ($page ?? 'vent') === 'report'; ?>
<nav class="student-mobile-header" aria-label="Student account navigation">
    <div class="student-mobile-header-row">
        <a href="<?= base_url('student/dashboard') ?>" class="student-mobile-brand"
           aria-label="Go to StudentShield action selection">
            <img src="<?= base_url('images/logo.png?v=6') ?>" alt="" width="36" height="36">
            <span class="student-mobile-brand-text">
                <span class="student-mobile-brand-name">StudentShield</span>
                <span class="mobile-section-name"><?= $isIncidentPage ? 'Incident Report' : 'Safe Space' ?></span>
            </span>
        </a>
        <form action="<?= base_url('logout') ?>" method="POST">
            <?= csrf_field() ?>
            <button type="submit" class="student-mobile-signout">
                <svg aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1" /></svg>
                Sign Out
            </button>
        </form>
    </div>
</nav>
