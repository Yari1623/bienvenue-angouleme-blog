<?php
// views/admin/categories/create.php
$pageTitle = 'Nouvelle catégorie — Admin';
?>

<div class="max-w-md mx-auto space-y-6">

    <div class="flex items-center justify-between border-b-2 border-ink pb-4">
        <h2 class="font-display text-2xl font-black text-ink">Nouvelle catégorie</h2>
        <a href="<?= BASE_URL ?>/admin/categories" class="text-sm font-body text-muted hover:text-accent transition-colors">← Retour</a>
    </div>

    <div class="bg-white border border-border p-6">
        <form method="POST" action="<?= BASE_URL ?>/admin/categories/store" class="space-y-5">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">

            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                    Nom <span class="text-accent">*</span>
                </label>
                <input type="text" name="name" required autofocus
                       class="w-full border border-border px-4 py-3 text-sm font-body bg-paper focus:outline-none focus:border-ink"
                       placeholder="Ex : Sport, Culture…">
                <p class="text-xs text-muted font-body mt-1">Le slug sera généré automatiquement.</p>
            </div>

            <div class="flex justify-between pt-2">
                <a href="<?= BASE_URL ?>/admin/categories"
                   class="px-5 py-3 border border-border font-body font-semibold text-sm text-muted hover:border-ink hover:text-ink transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-ink text-paper font-body font-semibold text-sm hover:bg-accent transition-colors">
                    Créer
                </button>
            </div>
        </form>
    </div>
</div>
