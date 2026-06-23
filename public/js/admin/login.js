lucide.createIcons();

// Live clock
function updateClock() {
    const now = new Date();
    document.getElementById('auth-clock').textContent =
        now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
}
setInterval(updateClock, 1000);
updateClock();

function togglePwd() {
    const input = document.getElementById('password');
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    document.getElementById('eye-icon').innerHTML = isText ?
        '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>' :
        '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
}

function handleAdminLogin(event) {
    event.preventDefault();
    const user = document.getElementById('username').value;
    const pass = document.getElementById('password').value;
    const btn = document.getElementById('btn-login');
    const btnText = document.getElementById('btn-text');
    const errorAlert = document.getElementById('error-alert');
    const card = document.getElementById('login-card');

    errorAlert.classList.remove('show');

    // Loading state
    btn.disabled = true;
    btn.style.opacity = '0.8';
    btnText.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="inline animate-spin" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Verifikasi...`;

    setTimeout(() => {
        if (user === 'admin' && pass === 'admin123') {
            btnText.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="inline" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Masuk...`;
            btn.style.background = 'linear-gradient(135deg,#22c55e,#16a34a)';
            setTimeout(() => { window.location.href = (window.APP_CONFIG && window.APP_CONFIG.dashboardUrl) ? window.APP_CONFIG.dashboardUrl : '/admin/dashboard'; }, 600);
        } else {
            errorAlert.classList.add('show');
            card.classList.add('shake');
            setTimeout(() => card.classList.remove('shake'), 500);
            btn.disabled = false;
            btn.style.opacity = '1';
            btnText.textContent = 'Masuk Dashboard';
        }
    }, 1400);
}