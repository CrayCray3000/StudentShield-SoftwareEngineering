<section class="student-meet-card" aria-label="Counseling meeting"
    data-status-url="<?= esc(base_url('student/api/meeting/status'), 'attr') ?>"
    data-request-url="<?= esc(base_url('student/meeting/request'), 'attr') ?>"
    data-cancel-url="<?= esc(base_url('student/meeting/cancel-request'), 'attr') ?>">
    <h3><svg aria-hidden="true" width="24" height="24" viewBox="0 0 24 24" fill="none"><rect x="2" y="5" width="14" height="14" rx="4" fill="#FBBC04"/><path d="M17 9 22 6v12l-5-3V9Z" fill="#34A853"/><path d="M2 14h5v5H6a4 4 0 0 1-4-4v-1Z" fill="#4285F4"/></svg> Meet</h3>
    <p data-meet-status role="status">Checking session…</p>
    <a data-meet-join hidden target="_blank" rel="noopener noreferrer" aria-label="Join Google Meet (opens in a new tab)">Join</a>
    <button data-meet-request type="button" hidden>Request session</button>
    <button data-meet-cancel type="button" hidden>Cancel request</button>
    <p data-meet-error role="alert" hidden></p>
</section>
<style>
.student-meet-card{box-sizing:border-box;width:208px;max-width:100%;margin:0 0 24px;padding:16px;border:1px solid #c4c7c5;border-radius:12px;background:#fff;color:#202124;text-align:left;font:14px/1.5 Arial,sans-serif}
.student-meet-card h3{display:flex;gap:12px;align-items:center;margin:0 0 16px;font-size:16px;line-height:24px;font-weight:600}
.student-meet-card h3 svg{flex-shrink:0}.student-meet-card p{margin:0 0 12px;font-size:12px;color:#5f6368;overflow-wrap:anywhere}
.student-meet-card a,.student-meet-card button{box-sizing:border-box;display:block;width:100%;border:1px solid #747775;border-radius:24px;padding:8px 12px;color:#1967d2;background:#fff;text-align:center;text-decoration:none;cursor:pointer;font:500 14px/1.5 Arial,sans-serif}
.student-meet-card [hidden]{display:none!important}.student-meet-card [data-meet-error]{color:#b3261e}.student-meet-card :focus-visible{outline:3px solid #1967d2;outline-offset:3px}.student-meet-card button:disabled{opacity:.6;cursor:wait}
</style>
<script src="<?= base_url('js/student-meet.js') ?>" defer></script>
