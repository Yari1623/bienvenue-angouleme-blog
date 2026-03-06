<?php
// views/admin/comments/index.php
$pageTitle = 'Gestion des commentaires — Admin';
$currentFilter = $filter ?? 'all';
?>

<div class="space-y-6">

    <div class="flex items-center justify-between border-b-2 border-ink pb-4">
        <div>
            <h2 class="font-display text-2xl font-black text-ink">Commentaires</h2>
            <?php if (($pending ?? 0) > 0): ?>
            <p class="font-body text-sm text-accent font-semibold mt-1">
                ⚠ <?= $pending ?> commentaire<?= $pending > 1 ? 's' : '' ?> en attente de modération
            </p>
            <?php endif; ?>
        </div>
        <a href="<?= BASE_URL ?>/admin"
           class="text-sm font-body text-muted hover:text-accent transition-colors">
            ← Dashboard
        </a>
    </div>

    <!-- Filtres -->
    <div class="flex gap-2 font-body text-sm">
        <a href="<?= BASE_URL ?>/admin/comments"
           class="px-4 py-2 border transition-colors <?= $currentFilter === 'all' ? 'bg-ink text-paper border-ink' : 'border-border hover:border-ink' ?>">
            Tous
        </a>
        <a href="<?= BASE_URL ?>/admin/comments?filter=pending"
           class="px-4 py-2 border transition-colors <?= $currentFilter === 'pending' ? 'bg-ink text-paper border-ink' : 'border-border hover:border-ink' ?>">
            En attente
            <?php if (($pending ?? 0) > 0): ?>
            <span class="ml-1 px-1.5 py-0.5 bg-accent text-paper text-xs rounded-full"><?= $pending ?></span>
            <?php endif; ?>
        </a>
    </div>

    <?php if (empty($comments)): ?>
    <div class="text-center py-16 text-muted font-body">
        <p class="text-4xl mb-3">💬</p>
        <p>Aucun commentaire<?= $currentFilter === 'pending' ? ' en attente' : '' ?>.</p>
    </div>
    <?php else: ?>

    <div class="bg-white border border-border overflow-hidden">
        <table class="w-full text-sm font-body">
            <thead class="bg-ink text-paper">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider">Auteur</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider">Commentaire</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell">Article</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden lg:table-cell">Date</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider">Statut</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <?php foreach ($comments as $comment): ?>
                <tr class="hover:bg-paper/50 transition-colors <?= $comment['status'] === 'pending' ? 'bg-yellow-50/30' : '' ?>">
                    <td class="px-4 py-3 font-semibold text-ink whitespace-nowrap">
                        <?= htmlspecialchars($comment['username']) ?>
                    </td>
                    <td class="px-4 py-3 text-muted max-w-xs">
                        <p class="truncate"><?= htmlspecialchars(mb_substr($comment['content'], 0, 80)) ?><?= mb_strlen($comment['content']) > 80 ? '…' : '' ?></p>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <a href="<?= BASE_URL ?>/article/<?= htmlspecialchars($comment['post_slug'] ?? '') ?>"
                           target="_blank"
                           class="text-muted hover:text-accent transition-colors truncate block max-w-xs">
                            <?= htmlspecialchars($comment['post_title'] ?? '—') ?>
                        </a>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell text-muted text-xs whitespace-nowrap">
                        <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            <?php echo match($comment['status']) {
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                default    => 'bg-yellow-100 text-yellow-800',
                            } ?>">
                            <?php echo match($comment['status']) {
                                'approved' => 'Approuvé',
                                'rejected' => 'Refusé',
                                default    => 'En attente',
                            } ?>
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">

                            <?php if ($comment['status'] !== 'approved'): ?>
                            <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/approve" class="inline">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <button class="px-3 py-1.5 text-xs font-semibold border border-green-300 text-green-700 hover:bg-green-50 transition-colors">
                                    ✓ Approuver
                                </button>
                            </form>
                            <?php endif; ?>

                            <?php if ($comment['status'] !== 'rejected'): ?>
                            <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/reject" class="inline">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <button class="px-3 py-1.5 text-xs font-semibold border border-yellow-300 text-yellow-700 hover:bg-yellow-50 transition-colors">
                                    ✗ Refuser
                                </button>
                            </form>
                            <?php endif; ?>

                            <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/delete" class="inline"
                                  onsubmit="return confirm('Supprimer ce commentaire ?')">
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