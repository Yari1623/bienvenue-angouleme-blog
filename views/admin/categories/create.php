<?php
// views/admin/categories/create.php
$pageTitle = 'Nouvelle catégorie — Admin';
?>
 
<div class="max-w-md mx-auto space-y-6">
 
    <div class="flex items-center justify-between pb-4" style="border-bottom:2px solid var(--border)">
        <h2 class="font-display text-2xl font-black" style="color:var(--text)">Nouvelle catégorie</h2>
        <a href="<?= BASE_URL ?>/admin/categories" class="text-sm transition-colors"
           style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">← Retour</a>
    </div>
 
    <div class="surface rounded-xl p-6">
        <form method="POST" action="<?= BASE_URL ?>/admin/categories/store" class="space-y-5">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
 
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    Nom <span style="color:#1d8fd8">*</span>
                </label>
                <input type="text" name="name" required autofocus
                       placeholder="Ex : Sport, Culture…"
                       class="w-full rounded-lg px-4 py-3 text-sm"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                       onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                <p class="text-xs mt-1" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                    Le slug sera généré automatiquement.
                </p>
            </div>
 
            <div class="flex justify-between pt-2">
                <a href="<?= BASE_URL ?>/admin/categories" class="btn-ghost text-sm">Annuler</a>
                <button type="submit" class="btn-primary text-sm">Créer</button>
            </div>
        </form>
    </div>
</div>
 
