<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>StudentShield · System accounts</title>
<script src="https://cdn.tailwindcss.com"></script>
<?= view('partials/school_brand') ?>
<link rel="stylesheet" href="<?= base_url('css/staff-layout.css?v=' . filemtime(FCPATH . 'css/staff-layout.css')) ?>">
<link rel="stylesheet" href="<?= base_url('css/role-workspaces.css?v=' . filemtime(FCPATH . 'css/role-workspaces.css')) ?>">
</head><body class="school-staff-page staff-layout role-workspace">
<?= view('partials/staff_navigation', ['staffPage' => 'users', 'staffPageLabel' => 'System accounts']) ?>
<main id="staffMain" class="staff-main role-main accounts-workspace">
<header class="accounts-heading"><div><h1>System accounts</h1><p>Manage accounts, roles and access.</p></div><button type="button" id="createAccountToggle" aria-controls="accountCreatePanel" aria-expanded="false" hidden>+ Create account</button></header>
<?php foreach (['success', 'error'] as $key): if ($message = session()->getFlashdata($key)): ?><p role="status" class="role-notice"><?= esc($message) ?></p><?php endif; endforeach; ?>
<details id="accountCreatePanel" class="role-panel account-create"><summary>Create an account</summary>
<form method="post" action="<?= base_url('admin/users/create') ?>" class="account-fields">
<?= csrf_field() ?>
<label>Account name<input name="name" required maxlength="255" autocomplete="off" placeholder="e.g. Guidance Counselor 1"></label>
<label>Role<select name="role"><?php foreach (\App\Libraries\RoleAccess::NAMES as $value => $label): ?><option value="<?= $value ?>"><?= esc($label) ?></option><?php endforeach; ?></select></label>
<label>Password<input name="passcode" type="password" minlength="8" required autocomplete="new-password" placeholder="Minimum 8 characters"></label>
<label>Staff email<input name="email" type="email" placeholder="staff@school.edu.ph (for OTP verification)"></label>
<p>New Student accounts remain pending until a Counselor approves them. Student profile and guardian details are managed by the guidance office.</p>
<button type="submit">Create account</button>
</form></details>
<section class="role-panel" aria-label="Account directory">
<div class="accounts-directory-heading"><h2>Account directory</h2><span id="accountResultCount" role="status"><?= count($users) ?> <?= count($users) === 1 ? 'account' : 'accounts' ?></span></div>
<div class="accounts-search-line"><label for="accountSearch">Search accounts<input type="search" id="accountSearch" placeholder="Search name, student ID or role"></label>
<details class="account-filter-panel"><summary>Filters</summary><div class="account-toolbar">
<label>Role<select id="accountRole"><option value="">All roles</option><?php foreach (\App\Libraries\RoleAccess::NAMES as $value => $label): ?><option value="<?= $value ?>"><?= esc($label) ?></option><?php endforeach; ?></select></label>
<label>Account access<select id="accountAccess"><option value="">All access statuses</option><option value="0">Enabled</option><option value="1">Disabled</option></select></label>
</div></details></div>
<div class="account-list">
<?php foreach ($users as $user): 
    $user['name'] = $user['name'] ?? ('Student account #' . (int) $user['id']); 
    $role = (int) $user['role'];
    $roleLabel = \App\Libraries\RoleAccess::NAMES[$role] ?? 'Unknown';
    $roleBadgeClass = $role === 2 ? 'badge-admin' : ($role === 3 ? 'badge-principal' : ($role === 1 ? 'badge-counselor' : 'badge-student'));
?>
<article class="account-row" data-role="<?= $role ?>" data-disabled="<?= (int) $user['access_disabled'] ?>" data-account="<?= esc(strtolower($user['name'] . ' ' . $roleLabel . ' ' . ($user['student_id'] ?? '')), 'attr') ?>">
<div>
    <strong><?= esc($user['name']) ?></strong>
    <p>
        <span>Account #<?= (int) $user['id'] ?></span> · 
        <span class="role-badge <?= $roleBadgeClass ?>"><?= esc($roleLabel) ?></span> · 
        <span class="status-badge <?= $user['access_disabled'] ? 'badge-disabled' : 'badge-enabled' ?>"><?= $user['access_disabled'] ? 'Disabled' : 'Enabled' ?></span>
        <?php if ($role === 0 && !empty($user['registration_status'])): ?>
            · <span class="status-badge <?= strtolower($user['registration_status']) === 'approved' ? 'badge-approved' : (strtolower($user['registration_status']) === 'pending' ? 'badge-pending' : 'badge-rejected') ?>">Registration: <?= esc(ucfirst($user['registration_status'])) ?></span>
        <?php endif; ?>
    </p>
</div>
<details><summary>Edit access</summary>
<form method="post" action="<?= base_url('admin/users/update/' . (int) $user['id']) ?>" class="account-fields">
<?= csrf_field() ?>
<?php if ($role !== 0): ?><label>Account name<input name="name" value="<?= esc($user['name'], 'attr') ?>" maxlength="255" required></label><?php endif; ?>
<label>Role<select name="role"><?php foreach (\App\Libraries\RoleAccess::NAMES as $value => $label): ?><option value="<?= $value ?>" <?= $role === $value ? 'selected' : '' ?>><?= esc($label) ?></option><?php endforeach; ?></select></label>
<label>Reset password<input name="passcode" type="password" minlength="8" autocomplete="new-password" placeholder="Leave blank to keep current"></label>
<label>Update staff email<input name="email" type="email" placeholder="Leave blank to keep current"></label>
<button type="submit">Save account</button></form>
<?php if ((int) $user['id'] !== (int) session()->get('user_id')): ?>
<form method="post" action="<?= base_url('admin/users/access/' . (int) $user['id']) ?>" class="account-access"><?= csrf_field() ?><input type="hidden" name="disabled" value="<?= $user['access_disabled'] ? '0' : '1' ?>"><button type="submit" class="secondary"><?= $user['access_disabled'] ? 'Enable account' : 'Disable account' ?></button></form>
<?php endif; ?>
</details>
<?php if ($role === 0): ?>
<details class="basic-student-profile"><summary>View basic profile</summary>
<dl class="basic-profile-fields">
<?php foreach (['student_id' => 'Student ID', 'grade_level' => 'Grade level', 'section' => 'Section', 'adviser_name' => 'Adviser', 'created_at' => 'Registered'] as $field => $label): ?>
<div><dt><?= esc($label) ?></dt><dd><?= esc(($user[$field] ?? '') ?: 'Not set') ?></dd></div>
<?php endforeach; ?>
<div><dt>LRN</dt><dd><?= !empty($user['lrn_suffix']) ? '••••••••' . esc($user['lrn_suffix']) : 'Not set' ?></dd></div>
</dl>
<p>Basic identification only. Student entries, case records and guardian contacts are restricted to the guidance office.</p>
</details>
<?php endif; ?>
</article>
<?php endforeach; ?>
<div id="noAccountsFound" class="hidden text-center py-8 text-gray-500 text-sm">No accounts match your search query.</div>
</div></section></main>
<script>
const createToggle = document.getElementById('createAccountToggle');
const createPanel = document.getElementById('accountCreatePanel');
createToggle.hidden = false;
createPanel.classList.add('account-create-enhanced');
createToggle.addEventListener('click', () => {
    createPanel.open = !createPanel.open;
    if (createPanel.open) createPanel.querySelector('input[name=name]').focus();
});
createPanel.addEventListener('toggle', () => {
    createToggle.setAttribute('aria-expanded', String(createPanel.open));
    createToggle.textContent = createPanel.open ? 'Close account form' : '+ Create account';
});
function filterAccounts() {
    const query = document.getElementById('accountSearch').value.toLowerCase().trim();
    const role = document.getElementById('accountRole').value;
    const access = document.getElementById('accountAccess').value;
    let matches = 0;
    document.querySelectorAll('[data-account]').forEach(row => {
        const isMatch = (!query || row.dataset.account.includes(query)) && (!role || row.dataset.role === role) && (!access || row.dataset.disabled === access);
        row.hidden = !isMatch;
        if (isMatch) matches++;
    });
    document.getElementById('accountResultCount').textContent = matches + (matches === 1 ? ' account' : ' accounts');
    const noResults = document.getElementById('noAccountsFound');
    if (noResults) noResults.classList.toggle('hidden', matches > 0);
}
['accountSearch', 'accountRole', 'accountAccess'].forEach(id => document.getElementById(id).addEventListener(id === 'accountSearch' ? 'input' : 'change', filterAccounts));
</script>
<?= view('partials/session_idle_modal') ?>
</body></html>
