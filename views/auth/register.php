<?php
// views/auth/register.php
$pageTitle = 'Inscription — Bienvenue à Angoulême';
?>

<div class="max-w-lg mx-auto">
    <div class="text-center mb-8">
        <h2 class="font-display text-3xl font-black mb-2" style="color:var(--text)">Créer un compte</h2>
        <div class="h-1 w-20 mx-auto rounded-full" style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)"></div>
        <p class="text-sm mt-3" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Rejoignez la communauté Bienvenue à Angoulême
        </p>
    </div>

    <div class="surface rounded-xl p-6 md:p-8">
        <form method="POST" action="<?= BASE_URL ?>/register" class="space-y-5" id="registerForm">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                        Prénom <span style="color:#1d8fd8">*</span>
                    </label>
                    <input type="text" name="first_name" required
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                        Nom <span style="color:#1d8fd8">*</span>
                    </label>
                    <input type="text" name="last_name" required
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    Nom d'utilisateur <span style="color:#1d8fd8">*</span>
                </label>
                <input type="text" name="username" required
                       class="w-full rounded-lg px-4 py-3 text-sm"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                       onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    Email <span style="color:#1d8fd8">*</span>
                </label>
                <input type="email" name="email" required
                       class="w-full rounded-lg px-4 py-3 text-sm"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                       onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
            </div>

            <!-- Mot de passe + confirmation -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    Mot de passe <span style="color:#1d8fd8">*</span>
                    <span style="color:var(--muted);font-weight:400;text-transform:none;letter-spacing:0;">(min. 8 caractères)</span>
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password" required minlength="8"
                           class="w-full rounded-lg px-4 py-3 text-sm pr-12"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'"
                           oninput="checkPasswordStrength(this.value)">
                    <button type="button" onclick="togglePassword('password')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-lg" style="color:var(--muted)">👁</button>
                </div>
                <!-- Barre de force -->
                <div class="mt-2 h-1.5 rounded-full overflow-hidden" style="background:var(--border)">
                    <div id="strength-bar" class="h-full rounded-full transition-all duration-300" style="width:0%;background:#dc2626"></div>
                </div>
                <p id="strength-text" class="text-xs mt-1" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;"></p>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    Confirmer le mot de passe <span style="color:#1d8fd8">*</span>
                </label>
                <div class="relative">
                    <input type="password" name="password_confirm" id="password_confirm" required minlength="8"
                           class="w-full rounded-lg px-4 py-3 text-sm pr-12"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="checkMatch()"
                           oninput="checkMatch()">
                    <button type="button" onclick="togglePassword('password_confirm')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-lg" style="color:var(--muted)">👁</button>
                </div>
                <p id="match-msg" class="text-xs mt-1" style="font-family:'Source Sans 3',sans-serif;"></p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Entreprise</label>
                    <input type="text" name="company"
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Téléphone</label>
                    <input type="tel" name="phone"
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
            </div>

            <button type="submit" id="submitBtn"
                    class="btn-primary w-full py-3 text-sm">
                Créer mon compte
            </button>
        </form>

        <p class="mt-5 text-center text-sm" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Déjà un compte ?
            <a href="<?= BASE_URL ?>/login" class="font-semibold underline" style="color:#1d8fd8">Se connecter</a>
        </p>
    </div>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type  = input.type === 'password' ? 'text' : 'password';
}

function checkPasswordStrength(val) {
    const bar  = document.getElementById('strength-bar');
    const text = document.getElementById('strength-text');
    let score  = 0;
    if (val.length >= 8)  score++;
    if (val.length >= 12) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { w:'20%',  color:'#dc2626', label:'Très faible' },
        { w:'40%',  color:'#f97316', label:'Faible' },
        { w:'60%',  color:'#eab308', label:'Moyen' },
        { w:'80%',  color:'#22c55e', label:'Fort' },
        { w:'100%', color:'#16a34a', label:'Très fort' },
    ];
    const level  = levels[Math.max(0, score - 1)] || levels[0];
    bar.style.width      = val.length ? level.w : '0%';
    bar.style.background = level.color;
    text.textContent     = val.length ? level.label : '';
    text.style.color     = level.color;
}

function checkMatch() {
    const p1  = document.getElementById('password').value;
    const p2  = document.getElementById('password_confirm').value;
    const msg = document.getElementById('match-msg');
    if (!p2) { msg.textContent = ''; return; }
    if (p1 === p2) {
        msg.textContent  = '✓ Les mots de passe correspondent';
        msg.style.color  = '#16a34a';
        document.getElementById('password_confirm').style.borderColor = '#16a34a';
    } else {
        msg.textContent  = '✗ Les mots de passe ne correspondent pas';
        msg.style.color  = '#dc2626';
        document.getElementById('password_confirm').style.borderColor = '#dc2626';
    }
}

// Bloquer la soumission si mots de passe différents
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const p1 = document.getElementById('password').value;
    const p2 = document.getElementById('password_confirm').value;
    if (p1 !== p2) {
        e.preventDefault();
        checkMatch();
        document.getElementById('password_confirm').focus();
    }
});
</script>