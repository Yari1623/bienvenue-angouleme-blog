<?php
// views/auth/register.php
$pageTitle = 'Inscription — Bienvenue à Angoulême';
?>

<div class="max-w-lg mx-auto">
    <div class="text-center mb-8">
        <h2 class="font-display text-3xl font-black text-ink mb-2">Créer un compte</h2>
        <p class="font-body text-sm text-muted">Rejoignez la communauté Bienvenue à Angoulême</p>
    </div>

    <div class="bg-white border border-border p-8">
        <form method="POST" action="<?= BASE_URL ?>/register" class="space-y-5">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                        Prénom <span class="text-accent">*</span>
                    </label>
                    <input type="text" name="first_name" required
                           class="w-full border border-border px-4 py-3 text-sm font-body text-ink bg-paper focus:outline-none focus:border-ink">
                </div>
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                        Nom <span class="text-accent">*</span>
                    </label>
                    <input type="text" name="last_name" required
                           class="w-full border border-border px-4 py-3 text-sm font-body text-ink bg-paper focus:outline-none focus:border-ink">
                </div>
            </div>

            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                    Nom d'utilisateur <span class="text-accent">*</span>
                </label>
                <input type="text" name="username" required
                       class="w-full border border-border px-4 py-3 text-sm font-body text-ink bg-paper focus:outline-none focus:border-ink">
            </div>

            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                    Email <span class="text-accent">*</span>
                </label>
                <input type="email" name="email" required
                       class="w-full border border-border px-4 py-3 text-sm font-body text-ink bg-paper focus:outline-none focus:border-ink">
            </div>

            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                    Mot de passe <span class="text-accent">*</span>
                    <span class="text-muted font-normal normal-case tracking-normal">(min. 8 caractères)</span>
                </label>
                <input type="password" name="password" required minlength="8"
                       class="w-full border border-border px-4 py-3 text-sm font-body text-ink bg-paper focus:outline-none focus:border-ink">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                        Entreprise
                    </label>
                    <input type="text" name="company"
                           class="w-full border border-border px-4 py-3 text-sm font-body text-ink bg-paper focus:outline-none focus:border-ink">
                </div>
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                        Téléphone
                    </label>
                    <input type="text" name="phone"
                           class="w-full border border-border px-4 py-3 text-sm font-body text-ink bg-paper focus:outline-none focus:border-ink">
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-ink text-paper font-body font-semibold py-3 text-sm hover:bg-accent transition-colors mt-2">
                Créer mon compte
            </button>
        </form>

        <p class="mt-6 text-center text-sm font-body text-muted">
            Déjà un compte ?
            <a href="<?= BASE_URL ?>/login" class="text-accent hover:underline font-semibold">Se connecter</a>
        </p>
    </div>
</div>