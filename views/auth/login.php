<?php
// views/auth/login.php
$pageTitle = 'Connexion — Bienvenue à Angoulême';
?>
 
<div class="max-w-md mx-auto">
 
    <div class="text-center mb-8">
        <h2 class="font-display text-3xl font-black mb-2" style="color:var(--text)">Connexion</h2>
        <p class="text-sm" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            Accédez à votre espace membre
        </p>
    </div>
 
    <div class="surface rounded-2xl p-8">
        <form method="POST" action="<?= BASE_URL ?>/login" class="space-y-5">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
 
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    Email ou nom d'utilisateur
                </label>
                <input type="text" name="login" required autofocus
                       class="w-full rounded-xl px-4 py-3 text-sm transition-colors"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                       onfocus="this.style.borderColor='#1d8fd8'"
                       onblur="this.style.borderColor='var(--border)'">
            </div>
 
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    Mot de passe
                </label>
                <input type="password" name="password" required
                       class="w-full rounded-xl px-4 py-3 text-sm transition-colors"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                       onfocus="this.style.borderColor='#1d8fd8'"
                       onblur="this.style.borderColor='var(--border)'">
            </div>
 
            <button type="submit" class="btn-primary w-full py-3 text-sm">
                Se connecter →
            </button>
        </form>
 
        <div class="mt-6 pt-5 text-center text-sm" style="border-top:1px solid var(--border);color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Pas encore de compte ?
            <a href="<?= BASE_URL ?>/register"
               class="font-semibold ml-1"
               style="color:#1d8fd8;">
                S'inscrire
            </a>
        </div>
    </div>
</div>
 