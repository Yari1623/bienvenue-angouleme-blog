/* =============================================================
   app.js — JavaScript global — Bienvenue à Angoulême
   Thème dark/light + Gestion cookies RGPD
   Inclus via <script src> en bas de views/layouts/main.php
============================================================= */
 
// ── Thème dark/light ──────────────────────────────────────
function applyTheme(dark) {
    document.documentElement.classList.toggle('dark', dark);
    const sun  = document.querySelector('.sun-icon');
    const moon = document.querySelector('.moon-icon');
    if (sun && moon) {
        sun.style.display  = dark ? 'none'   : 'inline';
        moon.style.display = dark ? 'inline' : 'none';
    }
}
function toggleTheme() {
    const isDark = !document.documentElement.classList.contains('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    applyTheme(isDark);
}
// Applique le thème sauvegardé ou la préférence système au chargement
(function () {
    const saved      = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyTheme(saved ? saved === 'dark' : prefersDark);
})();
 
// ── Gestion des cookies ───────────────────────────────────
const COOKIE_KEY = 'bwa_cookies_choice';
 
function cookieAccept() {
    localStorage.setItem(COOKIE_KEY, JSON.stringify({
        choice: 'accepted', youtube: true, vimeo: true, dailymotion: true
    }));
    hideCookieBanner();
}
function cookieDeny() {
    localStorage.setItem(COOKIE_KEY, JSON.stringify({
        choice: 'denied', youtube: false, vimeo: false, dailymotion: false
    }));
    hideCookieBanner();
}
function cookieDetails() {
    hideCookieBanner();
    document.getElementById('cookie-modal').style.display = 'flex';
}
function closeCookieModal() {
    document.getElementById('cookie-modal').style.display = 'none';
}
function cookieAcceptAll() { saveCookieChoices(true); }
function cookieDenyAll()   { saveCookieChoices(false); }
 
function toggleCookie(service, allow) {
    const a = document.querySelector('.cookie-btn-allow-' + service);
    const d = document.querySelector('.cookie-btn-deny-'  + service);
    if (allow) { a && (a.style.outline = '2px solid white'); d && (d.style.opacity = '.6'); }
    else        { d && (d.style.outline = '2px solid white'); a && (a.style.opacity = '.6'); }
}
function saveCookieChoices(all = null) {
    const c = { choice: 'custom' };
    ['youtube', 'vimeo', 'dailymotion'].forEach(s => {
        c[s] = all === null
            ? (document.querySelector('.cookie-btn-allow-' + s)?.style.outline !== '')
            : all;
    });
    localStorage.setItem(COOKIE_KEY, JSON.stringify(c));
    closeCookieModal();
}
function hideCookieBanner() {
    document.getElementById('cookie-banner').style.display = 'none';
}
 
// Affiche la bannière cookies si pas de choix enregistré (délai 1.2s)
(function () {
    if (!localStorage.getItem(COOKIE_KEY)) {
        setTimeout(() => {
            document.getElementById('cookie-banner').style.display = 'block';
        }, 1200);
    }
})();