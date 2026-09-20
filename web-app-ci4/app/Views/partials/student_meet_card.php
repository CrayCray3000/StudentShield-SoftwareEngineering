<section class="student-meet-card" aria-label="Counseling meeting"
    data-status-url="<?= esc(base_url('student/api/meeting/status'), 'attr') ?>"
    data-request-url="<?= esc(base_url('student/meeting/request'), 'attr') ?>"
    data-cancel-url="<?= esc(base_url('student/meeting/cancel-request'), 'attr') ?>">
    <h3><span aria-hidden="true">▣</span> Meet</h3>
    <p data-meet-status role="status">Checking session…</p>
    <a data-meet-join hidden target="_blank" rel="noopener noreferrer">Join</a>
    <button data-meet-request type="button" hidden>Request session</button>
    <button data-meet-cancel type="button" hidden>Cancel request</button>
    <p data-meet-error role="alert" hidden></p>
</section>
<style>
.student-meet-card{box-sizing:border-box;width:260px;max-width:100%;margin:0 0 24px;padding:16px;border:1px solid #c4c7c5;border-radius:12px;background:#fff;color:#202124;text-align:left;font:14px/1.5 Arial,sans-serif}
.student-meet-card h3{display:flex;gap:12px;align-items:center;margin:0 0 12px;font-size:18px;font-weight:600}
.student-meet-card h3 span{color:#16803c}.student-meet-card p{margin:8px 0 12px;font-size:13px;overflow-wrap:anywhere}
.student-meet-card a,.student-meet-card button{box-sizing:border-box;display:block;width:100%;border:1px solid #747775;border-radius:24px;padding:8px 12px;color:#1967d2;background:#fff;text-align:center;text-decoration:none;cursor:pointer;font:500 14px/1.5 Arial,sans-serif}
.student-meet-card [hidden]{display:none!important}.student-meet-card [data-meet-error]{color:#b3261e}.student-meet-card :focus-visible{outline:3px solid #1967d2;outline-offset:3px}.student-meet-card button:disabled{opacity:.6;cursor:wait}
</style>
<script src="<?= base_url('js/student-meet.js') ?>" defer></script>
