(() => {
    const panel = document.getElementById('analyticsFilterPanel');
    if (!panel) return;
    const period = document.getElementById('analyticsPeriod');
    const dates = [...panel.querySelectorAll('input[type="date"]')];
    const trigger = panel.querySelector('summary');
    const close = document.getElementById('closeReportFilters');
    const syncDates = () => dates.forEach(input => {
        input.closest('label').hidden = period.value !== 'custom';
        input.disabled = period.value !== 'custom';
    });
    close.hidden = false;
    function dismiss(restoreFocus = false) {
        panel.open = false;
        if (restoreFocus) trigger.focus();
    }
    period.addEventListener('change', syncDates);
    close.addEventListener('click', () => dismiss(true));
    panel.addEventListener('keydown', event => { if (event.key === 'Escape') dismiss(true); });
    document.addEventListener('click', event => { if (panel.open && !panel.contains(event.target)) dismiss(); });
    function fitPanel() {
        if (panel.open) {
            const form = panel.querySelector('form');
            if (window.matchMedia('(max-width:700px)').matches) {
                form.style.maxHeight = '';
                return;
            }
            const viewport = window.visualViewport;
            form.style.maxHeight = Math.max(180, (viewport ? viewport.height + viewport.offsetTop : window.innerHeight) - form.getBoundingClientRect().top - 16) + 'px';
        }
    }
    panel.addEventListener('toggle', fitPanel);
    window.addEventListener('resize', fitPanel);
    if (window.visualViewport) window.visualViewport.addEventListener('resize', fitPanel);
    syncDates();
})();
