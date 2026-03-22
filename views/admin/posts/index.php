<?php
// views/admin/posts/index.php
$pageTitle = 'Gestion des articles — Admin';
 
$perPage     = 15;
$currentPage = max(1, (int)($_GET['page'] ?? 1));
$totalItems  = count($posts ?? []);
$totalPages  = max(1, (int)ceil($totalItems / $perPage));
$currentPage = min($currentPage, $totalPages);
$pageItems   = array_slice($posts ?? [], ($currentPage - 1) * $perPage, $perPage);
?>
 
<div class="space-y-6">
 
    <div class="flex flex-wrap items-center justify-between gap-3 pb-4"
         style="border-bottom:2px solid var(--border)">
        <div>
            <h2 class="font-display text-2xl font-black" style="color:var(--text)">Articles</h2>
            <p class="text-xs mt-1" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                <?= $totalItems ?> article<?= $totalItems > 1 ? 's' : '' ?> au total
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/admin" class="text-sm"
               style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">← Dashboard</a>
            <a href="<?= BASE_URL ?>/admin/posts/create" class="btn-primary px-4 py-2 text-sm">
                + Nouvel article
            </a>
        </div>
    </div>
 
    <?php if (empty($posts)): ?>
    <div class="text-center py-16" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
        <p class="text-4xl mb-3">✒</p>
        <p>Aucun article pour le moment.</p>
        <a href="<?= BASE_URL ?>/admin/posts/create" class="btn-primary inline-block mt-4 px-6 py-2.5 text-sm">
            Créer le premier article
        </a>
    </div>
    <?php else: ?>
 
    <!-- ═══ VUE MOBILE : cartes ═══ -->
    <div class="md:hidden space-y-3">
        <?php foreach ($pageItems as $post): ?>
        <div class="surface rounded-xl p-4 space-y-3">
 
            <!-- Titre + auteur + badges -->
            <div class="flex items-start justify-between gap-2">
                <div style="min-width:0;">
                    <div style="font-weight:700;color:var(--text);font-family:'Source Sans 3',sans-serif;
                                overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        <?= htmlspecialchars($post['title']) ?>
                    </div>
                    <?php if (!empty($post['author_name'])): ?>
                    <div style="font-size:.75rem;color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                        par <?= htmlspecialchars($post['author_name']) ?>
                    </div>
                    <?php endif; ?>
                </div>
                <!-- Statut -->
                <span style="flex-shrink:0;padding:.2rem .5rem;font-size:.7rem;font-weight:700;border-radius:9999px;white-space:nowrap;
                    <?= $post['status'] === 'published' ? 'background:#dcfce7;color:#166534;' : 'background:#fef9c3;color:#854d0e;' ?>">
                    <?= $post['status'] === 'published' ? 'Publié' : 'Brouillon' ?>
                </span>
            </div>
 
            <!-- Catégorie + vues + date -->
            <div class="flex flex-wrap items-center gap-2">
                <?php if (!empty($post['category_name'])): ?>
                <span style="padding:.15rem .5rem;font-size:.7rem;font-weight:700;border-radius:9999px;
                             background:linear-gradient(135deg,#1d8fd8,#22d3ee);color:white;font-family:'Source Sans 3',sans-serif;">
                    <?= htmlspecialchars($post['category_name']) ?>
                </span>
                <?php endif; ?>
                <span style="font-size:.75rem;color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                    👁 <?= $post['view_count'] ?? 0 ?>
                </span>
                <span style="font-size:.75rem;color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                    <?= date('d/m/Y', strtotime($post['created_at'])) ?>
                </span>
            </div>
 
            <!-- Boutons -->
            <div class="flex flex-wrap gap-2">
                <!-- Voir -->
                <a href="<?= BASE_URL ?>/article/<?= htmlspecialchars($post['slug']) ?>" target="_blank"
                   style="flex:1;min-width:50px;text-align:center;padding:.35rem;font-size:.8rem;font-weight:700;
                          border-radius:.5rem;border:1.5px solid var(--border);color:var(--text2);text-decoration:none;"
                   onmouseover="this.style.borderColor='#1d8fd8';this.style.color='#1d8fd8'"
                   onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text2)'">
                    👁 Voir
                </a>
                <!-- Éditer -->
                <a href="<?= BASE_URL ?>/admin/posts/<?= $post['id'] ?>/edit"
                   style="flex:1;min-width:60px;text-align:center;padding:.35rem;font-size:.8rem;font-weight:700;
                          border-radius:.5rem;border:1.5px solid #1d8fd8;color:#1d8fd8;text-decoration:none;"
                   onmouseover="this.style.background='#1d8fd8';this.style.color='white'"
                   onmouseout="this.style.background='';this.style.color='#1d8fd8'">
                    ✏️ Éditer
                </a>
                <!-- Publier / Dépublier -->
                <form method="POST" action="<?= BASE_URL ?>/admin/posts/<?= $post['id'] ?>/status"
                      style="flex:1;min-width:80px;">
                    <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                    <button style="width:100%;padding:.35rem;font-size:.8rem;font-weight:700;border-radius:.5rem;cursor:pointer;
                        <?= $post['status'] === 'published'
                            ? 'border:1.5px solid #d97706;color:#d97706;background:transparent;'
                            : 'border:1.5px solid #16a34a;color:#16a34a;background:transparent;' ?>"
                        onmouseover="this.style.background='<?= $post['status'] === 'published' ? '#d97706' : '#16a34a' ?>';this.style.color='white'"
                        onmouseout="this.style.background='transparent';this.style.color='<?= $post['status'] === 'published' ? '#d97706' : '#16a34a' ?>'">
                        <?= $post['status'] === 'published' ? '↓ Brouillon' : '↑ Publier' ?>
                    </button>
                </form>
                <!-- Supprimer -->
                <form method="POST" action="<?= BASE_URL ?>/admin/posts/<?= $post['id'] ?>/delete"
                      style="flex:1;min-width:60px;" onsubmit="return confirm('Supprimer cet article ?')">
                    <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                    <button style="width:100%;padding:.35rem;font-size:.8rem;font-weight:700;border-radius:.5rem;
                                   border:1.5px solid #dc2626;color:#dc2626;background:transparent;cursor:pointer;"
                        onmouseover="this.style.background='#dc2626';this.style.color='white'"
                        onmouseout="this.style.background='transparent';this.style.color='#dc2626'">
                        🗑 Suppr.
                    </button>
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
                    <th style="text-align:left;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;white-space:nowrap;">Titre</th>
                    <th style="text-align:left;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;white-space:nowrap;">Catégorie</th>
                    <th style="text-align:center;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;white-space:nowrap;">Statut</th>
                    <th style="text-align:center;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;white-space:nowrap;">Vues</th>
                    <th style="text-align:left;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;white-space:nowrap;">Date</th>
                    <th style="text-align:right;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;white-space:nowrap;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pageItems as $i => $post): ?>
                <tr style="background:<?= $i % 2 === 0 ? 'var(--surface)' : 'var(--bg2)' ?>;border-bottom:1px solid var(--border);"
                    onmouseover="this.style.background='var(--bg2)'"
                    onmouseout="this.style.background='<?= $i % 2 === 0 ? 'var(--surface)' : 'var(--bg2)' ?>'">
 
                    <td style="padding:.75rem 1rem;max-width:220px;">
                        <div style="font-weight:600;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            <?= htmlspecialchars($post['title']) ?>
                        </div>
                        <?php if (!empty($post['author_name'])): ?>
                        <div style="font-size:.7rem;color:var(--muted);">
                            par <?= htmlspecialchars($post['author_name']) ?>
                        </div>
                        <?php endif; ?>
                    </td>
 
                    <td style="padding:.75rem 1rem;white-space:nowrap;">
                        <?php if (!empty($post['category_name'])): ?>
                        <span style="padding:.2rem .6rem;font-size:.7rem;font-weight:700;border-radius:9999px;
                                     background:linear-gradient(135deg,#1d8fd8,#22d3ee);color:white;">
                            <?= htmlspecialchars($post['category_name']) ?>
                        </span>
                        <?php else: ?>
                        <span style="color:var(--muted);font-size:.75rem;">—</span>
                        <?php endif; ?>
                    </td>
 
                    <td style="padding:.75rem 1rem;text-align:center;">
                        <span style="padding:.2rem .6rem;font-size:.7rem;font-weight:700;border-radius:9999px;white-space:nowrap;
                            <?= $post['status'] === 'published' ? 'background:#dcfce7;color:#166534;' : 'background:#fef9c3;color:#854d0e;' ?>">
                            <?= $post['status'] === 'published' ? 'Publié' : 'Brouillon' ?>
                        </span>
                    </td>
 
                    <td style="padding:.75rem 1rem;text-align:center;color:var(--text2);">
                        <?= $post['view_count'] ?? 0 ?>
                    </td>
 
                    <td style="padding:.75rem 1rem;white-space:nowrap;color:var(--text2);font-size:.8rem;">
                        <?= date('d/m/Y', strtotime($post['created_at'])) ?>
                    </td>
 
                    <td style="padding:.75rem 1rem;text-align:right;">
                        <div style="display:flex;gap:.3rem;justify-content:flex-end;align-items:center;">
                            <a href="<?= BASE_URL ?>/article/<?= htmlspecialchars($post['slug']) ?>" target="_blank"
                               style="padding:.25rem .5rem;font-size:.7rem;font-weight:700;border-radius:.375rem;
                                      border:1.5px solid var(--border);color:var(--text2);text-decoration:none;"
                               onmouseover="this.style.borderColor='#1d8fd8';this.style.color='#1d8fd8'"
                               onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text2)'">👁</a>
                            <a href="<?= BASE_URL ?>/admin/posts/<?= $post['id'] ?>/edit"
                               style="padding:.25rem .5rem;font-size:.7rem;font-weight:700;border-radius:.375rem;
                                      border:1.5px solid #1d8fd8;color:#1d8fd8;text-decoration:none;white-space:nowrap;"
                               onmouseover="this.style.background='#1d8fd8';this.style.color='white'"
                               onmouseout="this.style.background='';this.style.color='#1d8fd8'">Éditer</a>
                            <form method="POST" action="<?= BASE_URL ?>/admin/posts/<?= $post['id'] ?>/toggle-status" style="display:inline;">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <button style="padding:.25rem .5rem;font-size:.7rem;font-weight:700;border-radius:.375rem;cursor:pointer;white-space:nowrap;
                                    <?= $post['status'] === 'published'
                                        ? 'border:1.5px solid #d97706;color:#d97706;background:transparent;'
                                        : 'border:1.5px solid #16a34a;color:#16a34a;background:transparent;' ?>"
                                    onmouseover="this.style.background='<?= $post['status'] === 'published' ? '#d97706' : '#16a34a' ?>';this.style.color='white'"
                                    onmouseout="this.style.background='transparent';this.style.color='<?= $post['status'] === 'published' ? '#d97706' : '#16a34a' ?>'">
                                    <?= $post['status'] === 'published' ? '↓ Brouillon' : '↑ Publier' ?>
                                </button>
                            </form>
                            <form method="POST" action="<?= BASE_URL ?>/admin/posts/<?= $post['id'] ?>/delete"
                                  style="display:inline;" onsubmit="return confirm('Supprimer cet article ?')">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <button style="padding:.25rem .5rem;font-size:.7rem;font-weight:700;border-radius:.375rem;
                                               border:1.5px solid #dc2626;color:#dc2626;background:transparent;cursor:pointer;"
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
        <a href="?page=<?= $currentPage - 1 ?>" class="page-btn">← Précédent</a>
        <?php endif; ?>
        <?php
        $ellipsis = false;
        for ($i = 1; $i <= $totalPages; $i++):
            if ($i === 1 || $i === $totalPages || abs($i - $currentPage) <= 2): $ellipsis = false; ?>
        <a href="?page=<?= $i ?>" class="page-btn <?= $i === $currentPage ? 'active' : '' ?>"><?= $i ?></a>
        <?php   elseif (!$ellipsis): $ellipsis = true;
                echo '<span style="color:var(--muted);padding:0 .25rem">…</span>';
            endif;
        endfor; ?>
        <?php if ($currentPage < $totalPages): ?>
        <a href="?page=<?= $currentPage + 1 ?>" class="page-btn">Suivant →</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
 
    <?php endif; ?>
</div>