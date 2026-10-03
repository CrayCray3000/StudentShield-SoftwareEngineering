<!-- StudentShield Session Inactivity Warning Modal (Shared Computer Safety) -->
<div id="sessionIdleModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/75 backdrop-blur-md transition-opacity duration-300" role="dialog" aria-modal="true" aria-labelledby="idleModalTitle">
    <div class="bg-[#131A15] border border-amber-500/40 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl text-center transform transition-all duration-300 scale-95" id="idleModalCard">
        <div class="w-16 h-16 rounded-full bg-amber-500/20 border border-amber-500/40 flex items-center justify-center mx-auto mb-4 animate-pulse">
            <svg class="w-8 h-8 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h3 id="idleModalTitle" class="text-xl sm:text-2xl font-bold font-heading text-white tracking-tight mb-2">Session Expiring Soon</h3>
        <p class="text-sm text-white/70 leading-relaxed mb-4">
            For security on shared school and public computers, your session will automatically terminate due to inactivity.
        </p>

        <div class="bg-black/40 border border-white/10 rounded-2xl py-3 px-4 mb-6">
            <span class="text-xs uppercase tracking-wider text-white/50 block font-semibold">Logging out in</span>
            <span id="idleCountdownText" class="text-2xl sm:text-3xl font-extrabold text-amber-400 font-mono">02:00</span>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <button type="button" id="stayLoggedInBtn" class="flex-1 bg-[#096328] hover:bg-[#0D7B34] text-white font-bold py-3 px-4 rounded-xl border border-emerald-500/30 transition-all duration-200 shadow-lg cursor-pointer">
                Stay Logged In
            </button>
            <form action="<?= base_url('logout') ?>" method="POST" class="inline flex-1">
                <?= csrf_field() ?>
                <button type="submit" class="w-full bg-white/10 hover:bg-white/20 text-white/80 hover:text-white font-semibold py-3 px-4 rounded-xl border border-white/15 transition-all duration-200 cursor-pointer">
                    Sign Out Now
                </button>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    // 15-minute server inactivity timeout (900 seconds)
    // Warning triggers 2 minutes prior (at 13 minutes = 780 seconds)
    const WARN_AFTER_MS = 13 * 60 * 1000;
    const COUNTDOWN_TOTAL_SEC = 2 * 60; // 120 seconds
    
    let idleTimer = null;
    let countdownInterval = null;
    let secondsRemaining = COUNTDOWN_TOTAL_SEC;
    let warningActive = false;

    const modal = document.getElementById('sessionIdleModal');
    const card = document.getElementById('idleModalCard');
    const countdownEl = document.getElementById('idleCountdownText');
    const stayBtn = document.getElementById('stayLoggedInBtn');

    function resetIdleTimer() {
        if (warningActive) return; // Do not reset via ambient mouse moves once warning is up
        clearTimeout(idleTimer);
        idleTimer = setTimeout(showWarningModal, WARN_AFTER_MS);
    }

    function showWarningModal() {
        warningActive = true;
        secondsRemaining = COUNTDOWN_TOTAL_SEC;
        updateCountdownDisplay();
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }, 10);

        countdownInterval = setInterval(() => {
            secondsRemaining--;
            updateCountdownDisplay();

            if (secondsRemaining <= 0) {
                clearInterval(countdownInterval);
                // Perform automated logout
                window.location.href = "<?= base_url('logout') ?>";
            }
        }, 1000);
    }

    function hideWarningModal() {
        warningActive = false;
        clearInterval(countdownInterval);
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
        modal.classList.add('hidden');
        resetIdleTimer();
    }

    function updateCountdownDisplay() {
        const m = Math.floor(secondsRemaining / 60);
        const s = secondsRemaining % 60;
        countdownEl.textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
    }

    // Ping server to keep session alive and update last_activity
    if (stayBtn) {
        stayBtn.addEventListener('click', function() {
            stayBtn.disabled = true;
            stayBtn.textContent = 'Refreshing...';
            
            fetch("<?= base_url('session/ping') ?>", {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                cache: 'no-store'
            })
            .then(res => {
                stayBtn.disabled = false;
                stayBtn.textContent = 'Stay Logged In';
                if (res.ok) {
                    hideWarningModal();
                } else {
                    window.location.href = "<?= base_url('logout') ?>";
                }
            })
            .catch(() => {
                stayBtn.disabled = false;
                stayBtn.textContent = 'Stay Logged In';
                hideWarningModal();
            });
        });
    }

    // Passive activity tracking
    ['mousedown', 'keydown', 'scroll', 'touchstart'].forEach(evt => {
        window.addEventListener(evt, resetIdleTimer, { passive: true });
    });

    // Start initial timer
    resetIdleTimer();
})();
</script>
