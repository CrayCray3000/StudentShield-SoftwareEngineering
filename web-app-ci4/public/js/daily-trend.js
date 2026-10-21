document.querySelectorAll('[data-daily-trend]').forEach(chart => {
    const readout = chart.querySelector('output');
    const picker = chart.querySelector('[data-trend-day]');
    const points = [...chart.querySelectorAll('[data-day-index]')];
    const activate = point => {
        readout.textContent = point.dataset.label;
        picker.value = point.dataset.dayIndex;
        points.forEach(candidate => candidate.setAttribute('aria-pressed', String(candidate === point)));
    };
    points.forEach(point => {
        point.addEventListener('pointerenter', () => activate(point));
        point.addEventListener('focus', () => activate(point));
        point.addEventListener('click', () => activate(point));
    });
    picker.addEventListener('change', () => {
        const point = points.find(candidate => candidate.dataset.dayIndex === picker.value);
        if (point) activate(point);
        else {
            const [date, count] = picker.selectedOptions[0].textContent.split(' — ');
            readout.textContent = date + ': ' + count + (count === '1' ? ' event' : ' events');
            points.forEach(candidate => candidate.setAttribute('aria-pressed', 'false'));
        }
    });
});
