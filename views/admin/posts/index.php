<?php
// views/admin/posts/index.php
$pageTitle = 'Gestion des articles — Admin';
?>

<div class="space-y-6">

    <div class="flex items-center justify-between border-b-2 border-ink pb-4">
        <h2 class="font-display text-2xl font-black text-ink">Articles</h2>
        <a href="<?= BASE_URL ?>/admin/posts/create"
           class="px-5 py-2.5 bg-accent text-paper font-body font-semibold text-sm hover:bg-ink transition-colors">
            + Nouvel article
        </a>
    </div>

    <?php if (empty($posts)): ?>
    <div class="text-center py-16 text-muted font-body">
        <p class="text-4xl mb-3">✒</p>
        <p>Aucun article pour le moment.</p>
    </div>
    <?php else: ?>

    <div class="bg-white border border-border overflow-hidden">
        <table class="w-full text-sm font-body">
            <thead class="bg-ink text-paper">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold text-xs uppercase tracking-wider">Titre</th>
                    <th class="text-left px-4 py-3 font-semibold text-xs uppercase tracking-wider hidden md:table-cell">Catégorie</th>
                    <th class="text-left px-4 py-3 font-semibold text-xs uppercase tracking-wider hidden lg:table-cell">Auteur</th>
                    <th class="text-left px-4 py-3 font-semibold text-xs uppercase tracking-wider hidden lg:table-cell">Date</th>
                    <th class="text-center px-4 py-3 font-semibold text-xs uppercase tracking-wider">Statut</th>
                    <th class="text-right px-4 py-3 font-semibold text-xs uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <?php foreach ($posts as $post): ?>
                <tr class="hover:bg-paper/50 transition-colors">
                    <td class="px-4 py-3">
                        <a href="<?= BASE_URL ?>/article/<?= htmlspecialchars($post['slug']) ?>"
                           target="_blank"
                           class="font-semibold text-ink hover:text-accent transition-colors">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                        <?php if (!empty($post['tags'])): ?>
                        <div class="text-xs text-muted mt-0.5 truncate max-w-xs">
                            <?= htmlspecialchars($post['tags']) ?>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell text-muted">
                        <?= htmlspecialchars($post['category_name'] ?? '—') ?>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell text-muted">
                        <?= htmlspecialchars($post['author_name'] ?? '—') ?>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell text-muted text-xs">
                        <?= date('d/m/Y', strtotime($post['created_at'])) ?>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            <?= $post['status'] === 'published'
                                ? 'bg-green-100 text-green-800'
                                : 'bg-yellow-100 text-yellow-800' ?>">
                            <?= $post['status'] === 'published' ? 'Publié' : 'Brouillon' ?>
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2 flex-wrap">

                            <!-- Éditer -->
                            <a href="<?= BASE_URL ?>/admin/posts/<?= $post['id'] ?>/edit"
                               class="px-3 py-1.5 text-xs font-semibold border border-border hover:border-ink transition-colors">
                                Éditer
                            </a>

                            <!-- Toggle statut -->
                            <form method="POST"
                                  action="<?= BASE_URL ?>/admin/posts/<?= $post['id'] ?>/status"
                                  class="inline">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <button type="submit"
                                        class="px-3 py-1.5 text-xs font-semibold border transition-colors
                                            <?= $post['status'] === 'draft'
                                                ? 'border-green-300 text-green-700 hover:bg-green-50'
                                                : 'border-yellow-300 text-yellow-700 hover:bg-yellow-50' ?>">
                                    <?= $post['status'] === 'draft' ? 'Publier' : 'Dépublier' ?>
                                </button>
                            </form>

                            <!-- Supprimer -->
                            <form method="POST"
                                  action="<?= BASE_URL ?>/admin/posts/<?= $post['id'] ?>/delete"
                                  class="inline"
                                  onsubmit="return confirm('Supprimer cet article définitivement ?')">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <button type="submit"
                                        class="px-3 py-1.5 text-xs font-semibold border border-red-200 text-accent hover:bg-red-50 transition-colors">
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

    <div class="text-center">
        <a href="<?= BASE_URL ?>/admin"
           class="text-sm font-body text-muted hover:text-accent transition-colors">
            ← Retour au dashboard
        </a>
    </div>
</div>