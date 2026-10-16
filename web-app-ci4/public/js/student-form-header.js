(() => {
    const accountMenu = document.getElementById('ventAccountMenu');
    if (!accountMenu) return;
    const mobileAccount = window.matchMedia('(max-width: 767px)');
    const syncAccount = () => { accountMenu.open = !mobileAccount.matches; };
    mobileAccount.addEventListener('change', syncAccount);
    syncAccount();
    accountMenu.addEventListener('keydown', event => {
        if (event.key === 'Escape' && mobileAccount.matches) {
            accountMenu.open = false;
            accountMenu.querySelector('summary').focus();
        }
    });
    document.addEventListener('click', event => {
        if (mobileAccount.matches && !accountMenu.contains(event.target)) accountMenu.open = false;
    });
})();
