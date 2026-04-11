<?php
// views/admin/comments/index.php
$pageTitle     = 'Gestion des commentaires — Admin';
$currentFilter = $filter ?? 'all';
$perPage       = 15;
$currentPage   = max(1, (int)($_GET['page'] ?? 1));
$totalItems    = count($comments ?? []);
$totalPages    = max(1, (int)ceil($totalItems / $perPage));
$currentPage   = min($currentPage, $totalPages);
$pageItems     = array_slice($comments ?? [], ($currentPage - 1) * $perPage, $perPage);
?>
 
<div class="space-y-6">
 
    <div class="flex flex-wrap items-start justify-between gap-3 pb-4"
         style="border-bottom:2px solid var(--border)">
        <div>
            <h2 class="font-display text-2xl font-black" style="color:var(--text)">Commentaires</h2>
            <?php if (($pending ?? 0) > 0): ?>
            <p class="text-sm font-semibold mt-1" style="color:#f97316;font-family:'Source Sans 3',sans-serif;">
                ⚠ <?= $pending ?> en attente
            </p>
            <?php endif; ?>
        </div>
        <a href="<?= BASE_URL ?>/admin" class="text-sm"
           style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">← Dashboard</a>
    </div>
 
    <!-- Filtres -->
    <div class="flex gap-2 flex-wrap" style="font-family:'Source Sans 3',sans-serif;font-size:.875rem;">
        <a href="<?= BASE_URL ?>/admin/comments"
           style="padding:.4rem 1rem;border-radius:9999px;border:1px solid;font-weight:600;text-decoration:none;
           <?= $currentFilter === 'all' ? 'background:linear-gradient(135deg,#1d8fd8,#22d3ee);color:white;border-color:transparent;' : 'background:var(--bg2);color:var(--text2);border-color:var(--border);' ?>">
            Tous (<?= $totalItems ?>)
        </a>
        <a href="<?= BASE_URL ?>/admin/comments?filter=pending"
           style="padding:.4rem 1rem;border-radius:9999px;border:1px solid;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:.4rem;
           <?= $currentFilter === 'pending' ? 'background:linear-gradient(135deg,#f97316,#fbbf24);color:white;border-color:transparent;' : 'background:var(--bg2);color:var(--text2);border-color:var(--border);' ?>">
            En attente
            <?php if (($pending ?? 0) > 0): ?>
            <span style="background:#f97316;color:white;padding:.1rem .4rem;border-radius:9999px;font-size:.7rem;font-weight:700;"><?= $pending ?></span>
            <?php endif; ?>
        </a>
    </div>
 
    <?php if (empty($comments)): ?>
    <div class="text-center py-16" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
        <p class="text-4xl mb-3">💬</p>
        <p>Aucun commentaire<?= $currentFilter === 'pending' ? ' en attente' : '' ?>.</p>
    </div>
    <?php else: ?>
 
    <!-- ═══ VUE MOBILE : cartes ═══ -->
    <div class="md:hidden space-y-3">
        <?php foreach ($pageItems as $comment): ?>
        <div class="surface rounded-xl p-4 space-y-3"
             style="<?= $comment['status'] === 'pending' ? 'border-left:3px solid #f97316;' : '' ?>">
 
            <!-- Auteur + date + statut -->
            <div class="flex items-center justify-between gap-2 flex-wrap">
                <div>
                    <span style="font-weight:700;color:var(--text);font-family:'Source Sans 3',sans-serif;">
                        <?= htmlspecialchars($comment['username']) ?>
                    </span>
                    <span style="font-size:.7rem;color:var(--muted);margin-left:.4rem;font-family:'Source Sans 3',sans-serif;">
                        <?= date('d/m/Y', strtotime($comment['created_at'])) ?>
                    </span>
                </div>
                <span style="padding:.2rem .5rem;font-size:.7rem;font-weight:700;border-radius:9999px;white-space:nowrap;
                    <?php echo match($comment['status']) {
                        'approved' => 'background:#dcfce7;color:#166534;',
                        'rejected' => 'background:#fee2e2;color:#991b1b;',
                        default    => 'background:#fef9c3;color:#854d0e;'
                    }; ?>">
                    <?php echo match($comment['status']) {
                        'approved' => '✓ Approuvé',
                        'rejected' => '✗ Refusé',
                        default    => '⏳ Attente'
                    }; ?>
                </span>
            </div>
 
            <!-- Extrait -->
            <p style="font-size:.875rem;color:var(--text2);font-family:'Source Sans 3',sans-serif;
                      display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                <?= htmlspecialchars($comment['content']) ?>
            </p>
 
            <!-- Boutons -->
            <div class="flex flex-wrap gap-2">
                <button onclick="openCommentModal(<?= $comment['id'] ?>)"
                        aria-label="Voir le commentaire de <?= htmlspecialchars($comment['username'] ?? 'cet utilisateur') ?>"
                        style="flex:1;min-width:60px;padding:.35rem;font-size:.8rem;font-weight:700;border-radius:.5rem;
                               border:1.5px solid #1d8fd8;color:#1d8fd8;background:transparent;cursor:pointer;"
                        onmouseover="this.style.background='#1d8fd8';this.style.color='white'"
                        onmouseout="this.style.background='transparent';this.style.color='#1d8fd8'">
                    <span aria-hidden="true">👁</span> Voir
                </button>
                <?php if ($comment['status'] !== 'approved'): ?>
                <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/approve" style="flex:1;min-width:80px;">
                    <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                    <button aria-label="Approuver le commentaire de <?= htmlspecialchars($comment['username'] ?? 'cet utilisateur') ?>"
                        style="width:100%;padding:.35rem;font-size:.8rem;font-weight:700;border-radius:.5rem;
                                   border:1.5px solid #16a34a;color:#16a34a;background:transparent;cursor:pointer;"
                        onmouseover="this.style.background='#16a34a';this.style.color='white'"
                        onmouseout="this.style.background='transparent';this.style.color='#16a34a'">✓ Approuver</button>
                </form>
                <?php endif; ?>
                <?php if ($comment['status'] !== 'rejected'): ?>
                <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/reject" style="flex:1;min-width:70px;">
                    <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                    <button aria-label="Refuser le commentaire de <?= htmlspecialchars($comment['username'] ?? 'cet utilisateur') ?>"
                        style="width:100%;padding:.35rem;font-size:.8rem;font-weight:700;border-radius:.5rem;
                                   border:1.5px solid #d97706;color:#d97706;background:transparent;cursor:pointer;"
                        onmouseover="this.style.background='#d97706';this.style.color='white'"
                        onmouseout="this.style.background='transparent';this.style.color='#d97706'">✗ Refuser</button>
                </form>
                <?php endif; ?>
                <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/delete"
                      style="flex:1;min-width:60px;" onsubmit="return confirm('Supprimer ?')">
                    <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                    <button aria-label="Supprimer le commentaire de <?= htmlspecialchars($comment['username'] ?? 'cet utilisateur') ?>"
                        style="width:100%;padding:.35rem;font-size:.8rem;font-weight:700;border-radius:.5rem;
                                   border:1.5px solid #dc2626;color:#dc2626;background:transparent;cursor:pointer;"
                        onmouseover="this.style.background='#dc2626';this.style.color='white'"
                        onmouseout="this.style.background='transparent';this.style.color='#dc2626'"><span aria-hidden="true">🗑</span> Suppr.</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
 
    <!-- ═══ VUE DESKTOP : tableau ═══ -->
    <div class="hidden md:block" style="overflow-x:auto;border-radius:.75rem;border:1px solid var(--border);">
        <table style="width:100%;font-family:'Source Sans 3',sans-serif;font-size:.875rem;border-collapse:collapse;">
            <thead>
                <tr style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
                    <th style="text-align:left;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;white-space:nowrap;">Auteur</th>
                    <th style="text-align:left;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;white-space:nowrap;">Commentaire</th>
                    <th style="text-align:center;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;white-space:nowrap;">Statut</th>
                    <th style="text-align:right;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;white-space:nowrap;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pageItems as $i => $comment): ?>
                <tr style="background:<?= $comment['status'] === 'pending' ? 'rgba(249,115,22,.06)' : ($i % 2 === 0 ? 'var(--surface)' : 'var(--bg2)') ?>;border-bottom:1px solid var(--border);">
                    <td style="padding:.75rem 1rem;font-weight:600;white-space:nowrap;color:var(--text);"><?= htmlspecialchars($comment['username']) ?></td>
                    <td style="padding:.75rem 1rem;max-width:200px;">
                        <div style="display:flex;align-items:center;gap:.5rem;">
                            <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--text2);">
                                <?= htmlspecialchars(mb_substr($comment['content'], 0, 50)) ?><?= mb_strlen($comment['content']) > 50 ? '…' : '' ?>
                            </span>
                            <button onclick="openCommentModal(<?= $comment['id'] ?>)"
                                    style="flex-shrink:0;font-size:.7rem;font-weight:700;padding:.2rem .5rem;border-radius:.375rem;border:1.5px solid #1d8fd8;color:#1d8fd8;background:transparent;cursor:pointer;white-space:nowrap;"
                                    onmouseover="this.style.background='#1d8fd8';this.style.color='white'"
                                    onmouseout="this.style.background='transparent';this.style.color='#1d8fd8'">👁</button>
                        </div>
                    </td>
                    <td style="padding:.75rem 1rem;text-align:center;">
                        <span style="padding:.2rem .5rem;font-size:.7rem;font-weight:700;border-radius:9999px;white-space:nowrap;
                            <?php echo match($comment['status']) { 'approved' => 'background:#dcfce7;color:#166534;', 'rejected' => 'background:#fee2e2;color:#991b1b;', default => 'background:#fef9c3;color:#854d0e;' }; ?>">
                            <?php echo match($comment['status']) { 'approved' => 'OK', 'rejected' => 'Refusé', default => 'Attente' }; ?>
                        </span>
                    </td>
                    <td style="padding:.75rem 1rem;text-align:right;">
                        <div style="display:flex;gap:.3rem;justify-content:flex-end;flex-wrap:nowrap;">
                            <?php if ($comment['status'] !== 'approved'): ?>
                            <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/approve" style="display:inline;">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <button style="padding:.25rem .5rem;font-size:.75rem;font-weight:700;border-radius:.375rem;border:1.5px solid #16a34a;color:#16a34a;background:transparent;cursor:pointer;"
                                    onmouseover="this.style.background='#16a34a';this.style.color='white'"
                                    onmouseout="this.style.background='transparent';this.style.color='#16a34a'">✓</button>
                            </form>
                            <?php endif; ?>
                            <?php if ($comment['status'] !== 'rejected'): ?>
                            <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/reject" style="display:inline;">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <button style="padding:.25rem .5rem;font-size:.75rem;font-weight:700;border-radius:.375rem;border:1.5px solid #d97706;color:#d97706;background:transparent;cursor:pointer;"
                                    onmouseover="this.style.background='#d97706';this.style.color='white'"
                                    onmouseout="this.style.background='transparent';this.style.color='#d97706'">✗</button>
                            </form>
                            <?php endif; ?>
                            <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/delete" style="display:inline;" onsubmit="return confirm('Supprimer ?')">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <button style="padding:.25rem .5rem;font-size:.75rem;font-weight:700;border-radius:.375rem;border:1.5px solid #dc2626;color:#dc2626;background:transparent;cursor:pointer;"
                                    onmouseover="this.style.background='#dc2626';this.style.color='white'"
                                    onmouseout="this.style.background='transparent';this.style.color='#dc2626'">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
 
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="flex justify-center items-center gap-2 flex-wrap" style="font-family:'Source Sans 3',sans-serif;">
        <?php if ($currentPage > 1): ?>
        <a href="?filter=<?= $currentFilter ?>&page=<?= $currentPage - 1 ?>" class="page-btn">← Préc.</a>
        <?php endif; ?>
        <?php
        $ellipsis = false;
        for ($i = 1; $i <= $totalPages; $i++):
            if ($i === 1 || $i === $totalPages || abs($i - $currentPage) <= 2): $ellipsis = false; ?>
        <a href="?filter=<?= $currentFilter ?>&page=<?= $i ?>" class="page-btn <?= $i === $currentPage ? 'active' : '' ?>"><?= $i ?></a>
        <?php   elseif (!$ellipsis): $ellipsis = true; echo '<span style="color:var(--muted);padding:0 .25rem">…</span>';
            endif;
        endfor; ?>
        <?php if ($currentPage < $totalPages): ?>
        <a href="?filter=<?= $currentFilter ?>&page=<?= $currentPage + 1 ?>" class="page-btn">Suiv. →</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
 
    <?php endif; ?>
</div>
 
<script>
window.BASE_URL = '<?= BASE_URL ?>';
<?php
// Construire le tableau PHP proprement puis le sérialiser en JSON d'un bloc
// Évite tout problème d'apostrophe / guillemet / retour à la ligne dans le contenu
$commentsJs = [];
foreach ($comments as $c) {
    $commentsJs[$c['id']] = [
        'id'         => (int) $c['id'],
        'username'   => $c['username'],
        'content'    => $c['content'],
        'post_title' => $c['post_title'] ?? '',
        'post_slug'  => $c['post_slug']  ?? '',
        'date'       => date('d/m/Y à H:i', strtotime($c['created_at'])),
        'status'     => $c['status'],
        'csrf'       => \App\Core\Csrf::generate(),
    ];
}
?>
const COMMENTS_DATA = <?= json_encode($commentsJs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
</script>
 
<div id="comment-modal" class="fixed inset-0 z-50 items-center justify-center p-4"
     style="display:none;background:rgba(0,0,0,.6);">
    <div class="w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden"
         style="background:var(--surface);border:1px solid var(--border);">
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid var(--border);">
            <div>
                <h3 class="font-display text-lg font-bold" style="color:var(--text)">Commentaire de
                    <span id="modal-username" style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"></span>
                </h3>
                <p class="text-xs mt-1" style="color:var(--muted);">Article : <a id="modal-post-link" href="#" target="_blank" style="color:#1d8fd8;"></a> · <span id="modal-date"></span></p>
            </div>
            <button onclick="closeCommentModal()"
                    style="width:2rem;height:2rem;border-radius:50%;border:none;background:var(--bg2);color:var(--text2);cursor:pointer;font-size:1rem;"
                    onmouseover="this.style.background='#ef4444';this.style.color='white'"
                    onmouseout="this.style.background='var(--bg2)';this.style.color='var(--text2)'">✕</button>
        </div>
        <div class="px-6 pt-5 pb-3">
            <div id="modal-content" class="text-sm leading-relaxed p-4 rounded-xl whitespace-pre-wrap"
                 style="background:var(--bg2);color:var(--text);border:1px solid var(--border);max-height:280px;overflow-y:auto;font-family:'Source Sans 3',sans-serif;"></div>
        </div>
        <div class="px-6 pb-3"><span id="modal-status-badge" class="px-3 py-1 text-xs font-bold rounded-full"></span></div>
        <div id="modal-actions" class="flex items-center justify-end gap-3 px-6 py-4 flex-wrap" style="border-top:1px solid var(--border);"></div>
    </div>
</div>
 
<script src="<?= BASE_URL ?>/assets/js/admin-comments.js"></script>