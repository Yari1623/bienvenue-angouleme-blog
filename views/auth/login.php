<?php
// views/auth/login.php
$pageTitle = 'Connexion — Bienvenue à Angoulême';
?>

<div class="max-w-md mx-auto">
    <div class="text-center mb-8">
        <h2 class="font-display text-3xl font-black text-ink mb-2">Connexion</h2>
        <p class="font-body text-sm text-muted">Accédez à votre espace membre</p>
    </div>

    <div class="bg-white border border-border p-8">
        <form method="POST" action="<?= BASE_URL ?>/login" class="space-y-5">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">

            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                    Email ou nom d'utilisateur
                </label>
                <input type="text" name="login" required autofocus
                       class="w-full border border-border px-4 py-3 text-sm font-body text-ink bg-paper focus:outline-none focus:border-ink transition-colors">
            </div>

            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                    Mot de passe
                </label>
                <input type="password" name="password" required
                       class="w-full border border-border px-4 py-3 text-sm font-body text-ink bg-paper focus:outline-none focus:border-ink transition-colors">
            </div>

            <button type="submit"
                    class="w-full bg-ink text-paper font-body font-semibold py-3 text-sm hover:bg-accent transition-colors">
                Se connecter
            </button>
        </form>

        <p class="mt-6 text-center text-sm font-body text-muted">
            Pas encore de compte ?
            <a href="<?= BASE_URL ?>/register" class="text-accent hover:underline font-semibold">S'inscrire</a>
        </p>
    </div>
</div>
