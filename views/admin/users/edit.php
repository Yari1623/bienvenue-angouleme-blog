<?php
// views/admin/users/edit.php
$pageTitle = 'Modifier l\'utilisateur — Admin';
?>

<div class="max-w-lg mx-auto space-y-6">

    <div class="flex items-center justify-between border-b-2 border-ink pb-4">
        <h2 class="font-display text-2xl font-black text-ink">
            Modifier : <span class="text-accent"><?= htmlspecialchars($user['username']) ?></span>
        </h2>
        <a href="<?= BASE_URL ?>/admin/users" class="text-sm font-body text-muted hover:text-accent transition-colors">← Retour</a>
    </div>

    <div class="bg-white border border-border p-6">
        <form method="POST" action="<?= BASE_URL ?>/admin/users/<?= $user['id'] ?>/update" class="space-y-5">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">Prénom</label>
                    <input type="text" name="first_name"
                           value="<?= htmlspecialchars($user['first_name'] ?? '') ?>"
                           class="w-full border border-border px-4 py-3 text-sm font-body bg-paper focus:outline-none focus:border-ink">
                </div>
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">Nom</label>
                    <input type="text" name="last_name"
                           value="<?= htmlspecialchars($user['last_name'] ?? '') ?>"
                           class="w-full border border-border px-4 py-3 text-sm font-body bg-paper focus:outline-none focus:border-ink">
                </div>
            </div>

            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                    Nom d'utilisateur <span class="text-accent">*</span>
                </label>
                <input type="text" name="username" required
                       value="<?= htmlspecialchars($user['username']) ?>"
                       class="w-full border border-border px-4 py-3 text-sm font-body bg-paper focus:outline-none focus:border-ink">
            </div>

            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                    Email <span class="text-accent">*</span>
                </label>
                <input type="email" name="email" required
                       value="<?= htmlspecialchars($user['email']) ?>"
                       class="w-full border border-border px-4 py-3 text-sm font-body bg-paper focus:outline-none focus:border-ink">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">Entreprise</label>
                    <input type="text" name="company"
                           value="<?= htmlspecialchars($user['company'] ?? '') ?>"
                           class="w-full border border-border px-4 py-3 text-sm font-body bg-paper focus:outline-none focus:border-ink">
                </div>
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">Téléphone</label>
                    <input type="text" name="phone"
                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                           class="w-full border border-border px-4 py-3 text-sm font-body bg-paper focus:outline-none focus:border-ink">
                </div>
            </div>

            <div class="flex justify-between pt-2">
                <a href="<?= BASE_URL ?>/admin/users"
                   class="px-5 py-3 border border-border font-body font-semibold text-sm text-muted hover:border-ink hover:text-ink transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-ink text-paper font-body font-semibold text-sm hover:bg-accent transition-colors">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>