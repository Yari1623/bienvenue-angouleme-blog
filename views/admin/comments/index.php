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
        <a href="<?= BASE_URL ?>/admin" class="text-sm font-body text-muted hover:text-accent transition-colors">
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

                    <!-- Commentaire tronqué + bouton Lire -->
                    <td class="px-4 py-3 text-muted max-w-xs">
                        <div class="flex items-start gap-2">
                            <p class="truncate flex-1">
                                <?= htmlspecialchars(mb_substr($comment['content'], 0, 60)) ?><?= mb_strlen($comment['content']) > 60 ? '…' : '' ?>
                            </p>
                            <button onclick="openCommentModal(<?= $comment['id'] ?>)"
                                    class="shrink-0 text-xs font-semibold px-2 py-1 rounded border transition-colors"
                                    style="border-color:#1d8fd8;color:#1d8fd8;"
                                    onmouseover="this.style.background='#1d8fd8';this.style.color='white'"
                                    onmouseout="this.style.background='';this.style.color='#1d8fd8'">
                                👁 Lire
                            </button>
                        </div>
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
                                    ✓
                                </button>
                            </form>
                            <?php endif; ?>

                            <?php if ($comment['status'] !== 'rejected'): ?>
                            <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/reject" class="inline">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <button class="px-3 py-1.5 text-xs font-semibold border border-yellow-300 text-yellow-700 hover:bg-yellow-50 transition-colors">
                                    ✗
                                </button>
                            </form>
                            <?php endif; ?>

                            <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/delete" class="inline"
                                  onsubmit="return confirm('Supprimer ce commentaire ?')">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <button class="px-3 py-1.5 text-xs font-semibold border border-red-200 text-red-600 hover:bg-red-50 transition-colors">
                                    🗑
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

<!-- Données JSON pour la modale -->
<script>
const COMMENTS_DATA = {
    <?php foreach ($comments as $c): ?>
    <?= $c['id'] ?>: {
        id:         <?= $c['id'] ?>,
        username:   <?= json_encode($c['username']) ?>,
        content:    <?= json_encode($c['content']) ?>,
        post_title: <?= json_encode($c['post_title'] ?? '') ?>,
        post_slug:  <?= json_encode($c['post_slug']  ?? '') ?>,
        date:       <?= json_encode(date('d/m/Y à H:i', strtotime($c['created_at']))) ?>,
        status:     <?= json_encode($c['status']) ?>,
        csrf:       <?= json_encode(\App\Core\Csrf::generate()) ?>,
    },
    <?php endforeach; ?>
};
</script>

<!-- ════ MODALE ════ -->
<div id="comment-modal"
     class="fixed inset-0 z-50 items-center justify-center p-4"
     style="display:none;background:rgba(0,0,0,.6);">

    <div class="w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden"
         style="background:var(--surface);border:1px solid var(--border);">

        <!-- En-tête -->
        <div class="flex items-center justify-between px-6 py-4"
             style="border-bottom:1px solid var(--border);">
            <div>
                <h3 class="font-display text-lg font-bold" style="color:var(--text)">
                    Commentaire de <span id="modal-username" style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"></span>
                </h3>
                <p class="text-xs mt-1" style="color:var(--muted);">
                    Article : <a id="modal-post-link" href="#" target="_blank" style="color:#1d8fd8;" class="hover:underline"></a>
                    &nbsp;·&nbsp; <span id="modal-date"></span>
                </p>
            </div>
            <button onclick="closeCommentModal()"
                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold transition-colors"
                    style="background:var(--bg2);color:var(--text2);font-size:1rem;"
                    onmouseover="this.style.background='#ef4444';this.style.color='white'"
                    onmouseout="this.style.background='var(--bg2)';this.style.color='var(--text2)'">
                ✕
            </button>
        </div>

        <!-- Contenu complet -->
        <div class="px-6 pt-5 pb-3">
            <div id="modal-content"
                 class="text-sm leading-relaxed p-4 rounded-xl whitespace-pre-wrap"
                 style="background:var(--bg2);color:var(--text);border:1px solid var(--border);max-height:280px;overflow-y:auto;font-family:'Source Sans 3',sans-serif;">
            </div>
        </div>

        <!-- Statut -->
        <div class="px-6 pb-3">
            <span id="modal-status-badge" class="px-3 py-1 text-xs font-bold rounded-full"></span>
        </div>

        <!-- Actions -->
        <div id="modal-actions" class="flex items-center justify-end gap-3 px-6 py-4"
             style="border-top:1px solid var(--border);">
        </div>

    </div>
</div>

<script>
function openCommentModal(id) {
    const c = COMMENTS_DATA[id];
    if (!c) return;

    document.getElementById('modal-username').textContent = c.username;
    document.getElementById('modal-date').textContent     = c.date;
    document.getElementById('modal-content').textContent  = c.content;

    const link = document.getElementById('modal-post-link');
    link.textContent = c.post_title || 'Voir l\'article';
    link.href = '<?= BASE_URL ?>/article/' + c.post_slug;

    // Badge statut
    const badge  = document.getElementById('modal-status-badge');
    const labels = { approved:'Approuvé', rejected:'Refusé', pending:'En attente' };
    const styles  = {
        approved: 'background:#dcfce7;color:#166534;',
        rejected: 'background:#fee2e2;color:#991b1b;',
        pending:  'background:#fef9c3;color:#854d0e;',
    };
    badge.textContent  = labels[c.status] || c.status;
    badge.style.cssText = styles[c.status] || '';

    // Boutons d'action
    const actions = document.getElementById('modal-actions');
    let html = '';

    if (c.status !== 'approved') {
        html += `<form method="POST" action="<?= BASE_URL ?>/admin/comments/${id}/approve">
            <input type="hidden" name="_csrf" value="${c.csrf}">
            <button class="px-4 py-2 text-sm font-semibold rounded-full border border-green-300 text-green-700 hover:bg-green-50 transition-colors">
                ✓ Approuver
            </button></form>`;
    }
    if (c.status !== 'rejected') {
        html += `<form method="POST" action="<?= BASE_URL ?>/admin/comments/${id}/reject">
            <input type="hidden" name="_csrf" value="${c.csrf}">
            <button class="px-4 py-2 text-sm font-semibold rounded-full border border-yellow-300 text-yellow-700 hover:bg-yellow-50 transition-colors">
                ✗ Refuser
            </button></form>`;
    }
    html += `<form method="POST" action="<?= BASE_URL ?>/admin/comments/${id}/delete"
              onsubmit="return confirm('Supprimer ce commentaire définitivement ?')">
        <input type="hidden" name="_csrf" value="${c.csrf}">
        <button class="px-4 py-2 text-sm font-semibold rounded-full border border-red-200 text-red-600 hover:bg-red-50 transition-colors">
            🗑 Supprimer
        </button></form>`;

    actions.innerHTML = html;

    // Afficher la modale
    const modal = document.getElementById('comment-modal');
    modal.style.display = 'flex';
}

function closeCommentModal() {
    document.getElementById('comment-modal').style.display = 'none';
}

// Clic sur le fond → fermer
document.getElementById('comment-modal').addEventListener('click', function(e) {
    if (e.target === this) closeCommentModal();
});

// Touche Échap → fermer
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeCommentModal();
});
</script>