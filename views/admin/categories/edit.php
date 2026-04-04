<?php
// views/admin/categories/edit.php
$pageTitle = 'Modifier la catégorie — Admin';
?>

<div class="max-w-md mx-auto space-y-6">

    <div class="flex items-center justify-between pb-4" style="border-bottom:2px solid var(--border)">
        <h2 class="font-display text-2xl font-black" style="color:var(--text)">
            Modifier : <span style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                <?= htmlspecialchars($category['name']) ?>
            </span>
        </h2>
        <a href="<?= BASE_URL ?>/admin/categories" class="text-sm transition-colors"
           style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">← Retour</a>
    </div>

    <div class="surface rounded-xl p-6">
        <form method="POST" action="<?= BASE_URL ?>/admin/categories/<?= $category['id'] ?>/update" class="space-y-5">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    Nom <span style="color:#1d8fd8">*</span>
                </label>
                <input type="text" name="name" required
                       value="<?= htmlspecialchars($category['name']) ?>"
                       class="w-full rounded-lg px-4 py-3 text-sm"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:2px solid transparent;outline-offset:2px;"
                       onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                <p class="text-xs mt-1" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                    Slug actuel : <code style="background:var(--bg2);padding:.1rem .3rem;border-radius:.25rem;"><?= htmlspecialchars($category['slug']) ?></code>
                </p>
            </div>

            <div class="flex justify-between pt-2">
                <a href="<?= BASE_URL ?>/admin/categories" class="btn-ghost text-sm">Annuler</a>
                <button type="submit" class="btn-primary text-sm">Enregistrer</button>
            </div>
        </form>
    </div>
</div>