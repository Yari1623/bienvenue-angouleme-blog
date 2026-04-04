<?php
// views/admin/categories/index.php
$pageTitle = 'Gestion des catégories — Admin';
?>
<div class="space-y-6">
    <div class="flex flex-wrap items-start justify-between gap-3 pb-4" style="border-bottom:2px solid var(--border)">
        <h2 class="font-display text-2xl font-black" style="color:var(--text)">Catégories</h2>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="<?= BASE_URL ?>/admin/categories/create" class="btn-primary px-4 py-2 text-sm">+ Nouvelle catégorie</a>
            <a href="<?= BASE_URL ?>/admin" class="text-sm" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">← Dashboard</a>
        </div>
    </div>

    <?php if (empty($categories)): ?>
    <div class="text-center py-16" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
        <p class="text-4xl mb-3">🏷</p><p>Aucune catégorie.</p>
    </div>
    <?php else: ?>

    <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;border-radius:.75rem;border:1px solid var(--border);">
        <table style="width:100%;min-width:320px;font-family:'Source Sans 3',sans-serif;font-size:.875rem;border-collapse:collapse;">
            <thead>
                <tr style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
                    <th style="text-align:left;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;white-space:nowrap;">Nom</th>
                    <th style="text-align:center;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;white-space:nowrap;">Articles</th>
                    <!-- Slug masqué sur mobile -->
                    <th class="hidden md:table-cell" style="text-align:left;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;white-space:nowrap;">Slug</th>
                    <th style="text-align:right;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;white-space:nowrap;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $i => $cat): ?>
                <tr style="background:<?= $i%2===0?'var(--surface)':'var(--bg2)' ?>;border-bottom:1px solid var(--border);">
                    <td style="padding:.75rem 1rem;font-weight:600;color:var(--text);"><?= htmlspecialchars($cat['name']) ?></td>
                    <td style="padding:.75rem 1rem;text-align:center;">
                        <span style="padding:.2rem .6rem;font-size:.7rem;font-weight:700;border-radius:9999px;background:var(--bg2);color:var(--text2);border:1px solid var(--border);">
                            <?= $cat['post_count'] ?? 0 ?>
                        </span>
                    </td>
                    <!-- Slug masqué sur mobile -->
                    <td class="hidden md:table-cell" style="padding:.75rem 1rem;font-size:.75rem;color:var(--muted);"><?= htmlspecialchars($cat['slug']) ?></td>
                    <td style="padding:.75rem 1rem;text-align:right;">
                        <div style="display:flex;gap:.3rem;justify-content:flex-end;align-items:center;">
                            <a href="<?= BASE_URL ?>/admin/categories/<?= $cat['id'] ?>/edit"
                               style="padding:.25rem .6rem;font-size:.7rem;font-weight:700;border-radius:9999px;border:1.5px solid #1d8fd8;color:#1d8fd8;text-decoration:none;white-space:nowrap;"
                               onmouseover="this.style.background='#1d8fd8';this.style.color='white'"
                               onmouseout="this.style.background='';this.style.color='#1d8fd8'">Éditer</a>
                            <form method="POST" action="<?= BASE_URL ?>/admin/categories/<?= $cat['id'] ?>/delete" style="display:inline;" onsubmit="return confirm('Supprimer ?')">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <!-- Icône seule sur mobile, texte sur desktop -->
                                <button aria-label="Supprimer la catégorie <?= htmlspecialchars($cat['name']) ?>"
                                    style="padding:.25rem .6rem;font-size:.7rem;font-weight:700;border-radius:9999px;border:1.5px solid #dc2626;color:#dc2626;background:transparent;cursor:pointer;white-space:nowrap;"
                                    onmouseover="this.style.background='#dc2626';this.style.color='white'"
                                    onmouseout="this.style.background='transparent';this.style.color='#dc2626'">
                                    <span class="hidden md:inline">Supprimer</span>
                                    <span class="md:hidden" aria-hidden="true">🗑</span>
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