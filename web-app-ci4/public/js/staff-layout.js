(() => {
    const date = document.getElementById('liveDayDate');
    const time = document.getElementById('liveTime');
    const dateFormat = new Intl.DateTimeFormat('en-PH', {timeZone: 'Asia/Manila', weekday: 'short', year: 'numeric', month: 'short', day: 'numeric'});
    const timeFormat = new Intl.DateTimeFormat('en-PH', {timeZone: 'Asia/Manila', hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true});
    const updateClock = () => { const now = new Date(); if (date) date.textContent = dateFormat.format(now); if (time) time.textContent = timeFormat.format(now); };
    updateClock();
    setInterval(updateClock, 1000);
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

    const period = document.getElementById('analyticsPeriod');
    const accountRole = document.getElementById('accountRole');
    function shortcuts(select, labels, target, ariaLabel, submit) {
        if (!select || !target) return;
        const group = document.createElement('div'); group.className = 'filter-quick'; group.setAttribute('role', 'group'); group.setAttribute('aria-label', ariaLabel);
        labels.forEach(([value, label]) => {
            const button = document.createElement('button'); button.type = 'button'; button.textContent = label; button.dataset.value = value;
            button.addEventListener('click', () => { select.value = value; select.dispatchEvent(new Event('change', {bubbles:true})); if (submit) select.form.requestSubmit(); });
            group.append(button);
        });
        target.before(group);
        const sync = () => group.querySelectorAll('button').forEach(button => button.setAttribute('aria-pressed', String(button.dataset.value === select.value)));
        select.addEventListener('change', sync); sync();
    }
    shortcuts(period, [['today','Today'],['last7','7 days'],['last30','30 days'],['month','This month']], document.getElementById('analyticsFilterPanel'), 'Reporting period', true);

    document.querySelectorAll('.analytics-filter-panel > summary, .account-filter-panel > summary').forEach(summary => {
        const icon = document.createElement('span'); icon.className = 'filter-symbol'; icon.setAttribute('aria-hidden', 'true');
        icon.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M4 17h16M8 4v6M16 14v6"/></svg>';
        summary.prepend(icon);
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
        button.textContent = 'Show filters';
        controls.hidden = true;
        const icon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 7h16M4 17h16M8 4v6M16 14v6"/></svg>';
        button.innerHTML = icon + '<span>Refine results</span>';
        bar.insertBefore(button, controls);
        controls.classList.add('filter-drawer');
        controls.querySelectorAll('select, input[type="date"]').forEach(input => {
            const label = document.createElement('label');
            const caption = document.createElement('span');
            caption.textContent = input.type === 'date' ? 'On this date' : (input.getAttribute('aria-label') || 'Filter').replace(/^Filter /i, '');
            input.before(label); label.append(caption, input);
        });
        const select = controls.querySelector('select');
        if (select && select.id !== 'filterCategoryLogs') {
            const quick = document.createElement('div'); quick.className = 'filter-quick'; quick.setAttribute('role', 'group'); quick.setAttribute('aria-label', 'Quick status filters');
            [...select.options].forEach(option => {
                const chip = document.createElement('button'); chip.type = 'button'; chip.textContent = option.textContent; chip.dataset.value = option.value;
                chip.addEventListener('click', () => { select.value = option.value; select.dispatchEvent(new Event('change', {bubbles: true})); });
                quick.append(chip);
            });
            bar.after(quick);
            const sync = () => quick.querySelectorAll('button').forEach(chip => chip.setAttribute('aria-pressed', String(chip.dataset.value === select.value)));
            select.addEventListener('change', sync); sync();
        }
        const updateState = () => {
            const active = [...controls.querySelectorAll('input, select')].some(input => input.value && input.value !== 'all');
            button.classList.toggle('has-active-filters', active);
            const count = [...controls.querySelectorAll('input, select')].filter(input => input.value && input.value !== 'all').length;
            button.setAttribute('aria-label', active ? 'Refine results, ' + count + ' filters applied' : 'Refine results');
            button.dataset.count = count || '';
        };
        button.addEventListener('click', () => {
            const expanded = bar.classList.toggle('staff-filters-open');
            button.setAttribute('aria-expanded', String(expanded));
            button.innerHTML = icon + '<span>' + (expanded ? 'Close filters' : 'Refine results') + '</span>';
            controls.hidden = !expanded;
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
