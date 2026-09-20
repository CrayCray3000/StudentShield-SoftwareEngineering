(() => {
    const card = document.querySelector('.student-meet-card');
    if (!card) return;
    const status = card.querySelector('[data-meet-status]');
    const join = card.querySelector('[data-meet-join]');
    const request = card.querySelector('[data-meet-request]');
    const cancel = card.querySelector('[data-meet-cancel]');
    const error = card.querySelector('[data-meet-error]');
    let busy = false;
    function render(data) {
        const active = !!(data.has_meeting && data.join_url);
        join.hidden = !active;
        join.removeAttribute('href');
        if (active) join.href = data.join_url;
        request.hidden = active || !!data.has_request;
        cancel.hidden = active || !data.has_request;
        status.hidden = active || !data.has_request;
        status.textContent = data.has_request ? 'Waiting for counselor' : '';
    }
    async function call(url, method = 'GET') {
        const response = await fetch(url, {method, cache: 'no-store', headers: {
            'X-Requested-With': 'XMLHttpRequest',
            ...(method === 'POST' ? {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''} : {})
        }});
        const data = await response.json();
        if (!response.ok || data.status !== 'success') throw new Error(data.message || 'Unable to check your meeting. Please try again.');
        return data;
    }
    async function refresh(action) {
        if (busy) return;
        busy = true;
        request.disabled = cancel.disabled = true;
        try {
            if (action) await call(action, 'POST');
            render(await call(card.dataset.statusUrl));
            error.hidden = true;
        } catch (e) {
            // Do not leave a previously published link available after an uncertain status check.
            join.hidden = true;
            join.removeAttribute('href');
            error.textContent = e.message;
            error.hidden = false;
        } finally {
            busy = false;
            request.disabled = cancel.disabled = false;
        }
    }
    request.addEventListener('click', () => refresh(card.dataset.requestUrl));
    cancel.addEventListener('click', () => refresh(card.dataset.cancelUrl));
    refresh();
    setInterval(() => refresh(), 8000);
})();
