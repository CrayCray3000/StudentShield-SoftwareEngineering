(() => {
    const sidebar = document.getElementById('staffSidebar');
    const trigger = document.getElementById('staffMenuButton');
    const closeButton = document.getElementById('staffSidebarClose');
    const backdrop = document.getElementById('staffBackdrop');
    if (!sidebar || !trigger) return;
    const desktop = window.matchMedia('(min-width: 1100px)');
    let previousFocus;

    window.closeStaffNavigation = () => {
        const wasOpen = document.body.classList.contains('staff-navigation-open');
        document.body.classList.remove('staff-navigation-open');
        trigger.setAttribute('aria-expanded', 'false');
        backdrop.hidden = true;
        sidebar.inert = !desktop.matches;
        sidebar.removeAttribute('role');
        sidebar.removeAttribute('aria-modal');
        if (wasOpen && previousFocus?.isConnected) previousFocus.focus();
    };
    trigger.addEventListener('click', () => {
        previousFocus = document.activeElement;
        window.closeMeetingDropdown?.();
        sidebar.inert = false;
        sidebar.setAttribute('role', 'dialog');
        sidebar.setAttribute('aria-modal', 'true');
        document.body.classList.add('staff-navigation-open');
        trigger.setAttribute('aria-expanded', 'true');
        backdrop.hidden = false;
        closeButton.focus();
    });
    closeButton.addEventListener('click', window.closeStaffNavigation);
    backdrop.addEventListener('click', window.closeStaffNavigation);
    desktop.addEventListener('change', window.closeStaffNavigation);
    sidebar.addEventListener('click', event => {
        if (event.target.closest('a')) window.closeStaffNavigation();
    });
    document.addEventListener('keydown', event => {
        if (!document.body.classList.contains('staff-navigation-open')) return;
        if (event.key === 'Escape') { event.preventDefault(); window.closeStaffNavigation(); }
        if (event.key !== 'Tab') return;
        const focusable = [...sidebar.querySelectorAll('a[href], button')].filter(el => el.getClientRects().length);
        const first = focusable[0], last = focusable[focusable.length - 1];
        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
    });
    window.closeStaffNavigation();

    document.addEventListener('toggle', event => {
        if (!event.target.matches('.staff-entry-actions[open]')) return;
        document.querySelectorAll('.staff-entry-actions[open]').forEach(details => {
            if (details !== event.target) details.open = false;
        });
    }, true);
    document.addEventListener('click', event => {
        document.querySelectorAll('.staff-entry-actions[open]').forEach(details => {
            if (!details.contains(event.target)) details.open = false;
        });
    });
    document.addEventListener('keydown', event => {
        if (event.key !== 'Escape') return;
        document.querySelectorAll('.staff-entry-actions[open]').forEach(details => {
            if (details.contains(document.activeElement)) details.querySelector('summary').focus();
            details.open = false;
        });
    });

    const mobile = window.matchMedia('(max-width: 768px)');
    document.querySelectorAll('.staff-filter-bar').forEach((bar, index) => {
        const controls = bar.querySelector('.staff-filter-controls');
        const search = bar.querySelector('input[type="text"]');
        if (!controls || !search) return;
        const originalPlaceholder = search.placeholder;
        controls.id ||= 'staffFilterControls' + index;
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'staff-filter-toggle';
        button.setAttribute('aria-controls', controls.id);
        button.setAttribute('aria-expanded', 'false');
        button.textContent = 'Filters';
        bar.insertBefore(button, controls);
        const updateState = () => {
            const active = [...controls.querySelectorAll('input, select')].some(input => input.value && input.value !== 'all');
            button.classList.toggle('has-active-filters', active);
            button.setAttribute('aria-label', active ? 'Filters, currently applied' : 'Filters');
        };
        button.addEventListener('click', () => {
            const expanded = bar.classList.toggle('staff-filters-open');
            button.setAttribute('aria-expanded', String(expanded));
        });
        controls.addEventListener('change', updateState);
        controls.addEventListener('click', () => requestAnimationFrame(updateState));
        const syncMobile = () => {
            search.placeholder = mobile.matches
                ? (search.id === 'searchLogs' ? 'Search audit logs' : search.id === 'searchIncidents' ? 'Search incidents' : 'Search entries')
                : originalPlaceholder;
            updateState();
        };
        mobile.addEventListener('change', syncMobile);
        syncMobile();
        requestAnimationFrame(syncMobile);
    });
})();
