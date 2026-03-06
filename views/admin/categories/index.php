<?php
// views/admin/categories/index.php
$pageTitle = 'Gestion des catégories — Admin';
?>

<div class="space-y-6">

    <div class="flex items-center justify-between border-b-2 border-ink pb-4">
        <h2 class="font-display text-2xl font-black text-ink">Catégories</h2>
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/admin/categories/create"
               class="px-5 py-2.5 bg-accent text-paper font-body font-semibold text-sm hover:bg-ink transition-colors">
                + Nouvelle catégorie
            </a>
            <a href="<?= BASE_URL ?>/admin" class="text-sm font-body text-muted hover:text-accent transition-colors">← Dashboard</a>
        </div>
    </div>

    <?php if (empty($categories)): ?>
    <div class="text-center py-16 text-muted font-body">
        <p class="text-4xl mb-3">🏷</p>
        <p>Aucune catégorie.</p>
    </div>
    <?php else: ?>

    <div class="bg-white border border-border overflow-hidden">
        <table class="w-full text-sm font-body">
            <thead class="bg-ink text-paper">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider">Nom</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell">Slug</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider">Articles</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <?php foreach ($categories as $cat): ?>
                <tr class="hover:bg-paper/50 transition-colors">
                    <td class="px-4 py-3 font-semibold text-ink">
                        <a href="<?= BASE_URL ?>/categorie/<?= htmlspecialchars($cat['slug']) ?>"
                           target="_blank"
                           class="hover:text-accent transition-colors">
                            <?= htmlspecialchars($cat['name']) ?>
                        </a>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell text-muted text-xs font-mono">
                        <?= htmlspecialchars($cat['slug']) ?>
                    </td>
                    <td class="px-4 py-3 text-center text-muted">
                        <?= $cat['post_count'] ?? 0 ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?= BASE_URL ?>/admin/categories/<?= $cat['id'] ?>/edit"
                               class="px-3 py-1.5 text-xs font-semibold border border-border hover:border-ink transition-colors">
                                Éditer
                            </a>
                            <form method="POST" action="<?= BASE_URL ?>/admin/categories/<?= $cat['id'] ?>/delete"
                                  class="inline"
                                  onsubmit="return confirm('Supprimer cette catégorie ?')">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <button class="px-3 py-1.5 text-xs font-semibold border border-red-200 text-accent hover:bg-red-50 transition-colors">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php endif; ?>
</div>