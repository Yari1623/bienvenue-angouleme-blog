<?php
// views/admin/users/index.php
$pageTitle = 'Gestion des utilisateurs — Admin';

$perPage     = 10;
$currentPage = max(1, (int)($_GET['page'] ?? 1));
$totalItems  = count($users ?? []);
$totalPages  = max(1, (int)ceil($totalItems / $perPage));
$currentPage = min($currentPage, $totalPages);
$pageItems   = array_slice($users ?? [], ($currentPage - 1) * $perPage, $perPage);
?>

<div class="space-y-6">

    <div class="flex flex-wrap items-center justify-between gap-3 pb-4"
         style="border-bottom:2px solid var(--border)">
        <div>
            <h2 class="font-display text-2xl font-black" style="color:var(--text)">Utilisateurs</h2>
            <p class="text-xs mt-1" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                <?= $totalItems ?> utilisateur<?= $totalItems > 1 ? 's' : '' ?> au total
            </p>
        </div>
        <a href="<?= BASE_URL ?>/admin" class="text-sm"
           style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">← Dashboard</a>
    </div>

    <?php if (empty($users)): ?>
    <div class="text-center py-16" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
        <p class="text-4xl mb-3">👤</p>
        <p>Aucun utilisateur.</p>
    </div>
    <?php else: ?>

    <!-- ═══ VUE MOBILE : cartes ═══ -->
    <div class="md:hidden space-y-3">
        <?php foreach ($pageItems as $u): ?>
        <div class="surface rounded-xl p-4 space-y-3">

            <!-- Ligne 1 : avatar + nom + email -->
            <div class="flex items-center gap-3">
                <div style="width:2.5rem;height:2.5rem;border-radius:50%;flex-shrink:0;
                            background:linear-gradient(135deg,#1d8fd8,#22d3ee);
                            display:flex;align-items:center;justify-content:center;
                            color:white;font-weight:700;font-size:1rem;">
                    <?= strtoupper(mb_substr($u['username'], 0, 1)) ?>
                </div>
                <div style="min-width:0;">
                    <div style="font-weight:700;color:var(--text);font-family:'Source Sans 3',sans-serif;
                                white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        <?= htmlspecialchars($u['username']) ?>
                    </div>
                    <div style="font-size:.75rem;color:var(--muted);font-family:'Source Sans 3',sans-serif;
                                white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        <?= htmlspecialchars($u['email']) ?>
                    </div>
                </div>
            </div>

            <!-- Ligne 2 : select rôle -->
            <form method="POST" action="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/role">
                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                <div class="flex items-center gap-2">
                    <label style="font-size:.75rem;font-weight:600;color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                        Rôle :
                    </label>
                    <select name="role"
                            style="flex:1;padding:.35rem .6rem;font-size:.8rem;font-weight:600;border-radius:.5rem;
                                   border:1.5px solid <?= $u['role'] === 'admin' ? '#1d8fd8' : 'var(--border)' ?>;
                                   background:<?= $u['role'] === 'admin' ? 'rgba(29,143,216,.1)' : 'var(--bg2)' ?>;
                                   color:<?= $u['role'] === 'admin' ? '#1d8fd8' : 'var(--text2)' ?>;
                                   outline:2px solid transparent;outline-offset:2px;">
                        <option value="member" <?= $u['role'] === 'member' ? 'selected' : '' ?>>Membre</option>
                        <option value="admin"  <?= $u['role'] === 'admin'  ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <?php if ($u['id'] !== \App\Core\Auth::id()): ?>
                    <button type="submit" class="btn-primary" style="padding:.3rem .8rem;font-size:.75rem;border-radius:.5rem;">
                        ✓
                    </button>
                    <?php else: ?>
                    <span style="font-size:.7rem;color:var(--muted);font-family:'Source Sans 3',sans-serif;">(vous)</span>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Ligne 3 : boutons éditer / supprimer -->
            <div class="flex gap-2">
                <a href="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/edit"
                   style="flex:1;text-align:center;padding:.4rem;font-size:.8rem;font-weight:700;border-radius:.5rem;
                          border:1.5px solid #1d8fd8;color:#1d8fd8;text-decoration:none;"
                   onmouseover="this.style.background='#1d8fd8';this.style.color='white'"
                   onmouseout="this.style.background='';this.style.color='#1d8fd8'">
                    ✏️ Éditer
                </a>
                <?php if ($u['id'] !== \App\Core\Auth::id()): ?>
                <form method="POST" action="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/delete"
                      style="flex:1;" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                    <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                    <button type="submit"
                            style="width:100%;padding:.4rem;font-size:.8rem;font-weight:700;border-radius:.5rem;
                                   border:1.5px solid #dc2626;color:#dc2626;background:transparent;cursor:pointer;"
                        onmouseover="this.style.background='#dc2626';this.style.color='white'"
                        onmouseout="this.style.background='transparent';this.style.color='#dc2626'">
                        🗑 Supprimer
                    </button>
                </form>
                <?php else: ?>
                <div style="flex:1;"></div>
                <?php endif; ?>
            </div>

        </div>
        <?php endforeach; ?>
    </div>

    <!-- ═══ VUE DESKTOP : tableau ═══ -->
    <div class="hidden md:block" style="overflow-x:auto;border-radius:.75rem;border:1px solid var(--border);">
        <table style="width:100%;font-family:'Source Sans 3',sans-serif;font-size:.875rem;border-collapse:collapse;">
            <thead>
                <tr style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
                    <th style="text-align:left;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;">Utilisateur</th>
                    <th style="text-align:left;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;">Email</th>
                    <th style="text-align:center;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;">Rôle</th>
                    <th style="text-align:left;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;">Inscription</th>
                    <th style="text-align:right;padding:.75rem 1rem;font-size:.7rem;font-weight:700;text-transform:uppercase;color:white;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pageItems as $i => $u): ?>
                <tr style="background:<?= $i % 2 === 0 ? 'var(--surface)' : 'var(--bg2)' ?>;border-bottom:1px solid var(--border);">

                    <td style="padding:.75rem 1rem;">
                        <div style="display:flex;align-items:center;gap:.6rem;">
                            <div style="width:2rem;height:2rem;border-radius:50%;flex-shrink:0;
                                        background:linear-gradient(135deg,#1d8fd8,#22d3ee);
                                        display:flex;align-items:center;justify-content:center;
                                        color:white;font-weight:700;font-size:.8rem;">
                                <?= strtoupper(mb_substr($u['username'], 0, 1)) ?>
                            </div>
                            <div>
                                <div style="font-weight:600;color:var(--text);"><?= htmlspecialchars($u['username']) ?></div>
                                <?php if (!empty($u['first_name']) || !empty($u['last_name'])): ?>
                                <div style="font-size:.7rem;color:var(--muted);">
                                    <?= htmlspecialchars(trim(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? ''))) ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>

                    <td style="padding:.75rem 1rem;color:var(--text2);font-size:.8rem;">
                        <?= htmlspecialchars($u['email']) ?>
                    </td>

                    <td style="padding:.75rem 1rem;text-align:center;">
                        <form method="POST" action="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/role"
                              style="display:inline-flex;align-items:center;gap:.3rem;">
                            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                            <select name="role" onchange="this.form.submit()"
                                    style="padding:.2rem .4rem;font-size:.7rem;font-weight:700;border-radius:.375rem;
                                           border:1.5px solid <?= $u['role'] === 'admin' ? '#1d8fd8' : 'var(--border)' ?>;
                                           background:<?= $u['role'] === 'admin' ? 'rgba(29,143,216,.1)' : 'var(--bg2)' ?>;
                                           color:<?= $u['role'] === 'admin' ? '#1d8fd8' : 'var(--text2)' ?>;
                                           cursor:pointer;outline:2px solid transparent;outline-offset:2px;">
                                <option value="member" <?= $u['role'] === 'member' ? 'selected' : '' ?>>Membre</option>
                                <option value="admin"  <?= $u['role'] === 'admin'  ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </form>
                    </td>

                    <td style="padding:.75rem 1rem;color:var(--text2);font-size:.8rem;white-space:nowrap;">
                        <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                    </td>

                    <td style="padding:.75rem 1rem;text-align:right;">
                        <div style="display:flex;gap:.3rem;justify-content:flex-end;align-items:center;">
                            <a href="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/edit"
                               style="padding:.25rem .6rem;font-size:.7rem;font-weight:700;border-radius:.375rem;
                                      border:1.5px solid #1d8fd8;color:#1d8fd8;text-decoration:none;white-space:nowrap;"
                               onmouseover="this.style.background='#1d8fd8';this.style.color='white'"
                               onmouseout="this.style.background='';this.style.color='#1d8fd8'">Éditer</a>
                            <?php if ($u['id'] !== \App\Core\Auth::id()): ?>
                            <form method="POST" action="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/delete"
                                  style="display:inline;" onsubmit="return confirm('Supprimer ?')">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <button style="padding:.25rem .5rem;font-size:.7rem;font-weight:700;border-radius:.375rem;
                                               border:1.5px solid #dc2626;color:#dc2626;background:transparent;cursor:pointer;"
                                    onmouseover="this.style.background='#dc2626';this.style.color='white'"
                                    onmouseout="this.style.background='transparent';this.style.color='#dc2626'">🗑</button>
                            </form>
                            <?php else: ?>
                            <span style="color:var(--muted);font-size:.7rem;">—</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="flex justify-center items-center gap-2 flex-wrap"
         style="font-family:'Source Sans 3',sans-serif;">
        <?php if ($currentPage > 1): ?>
        <a href="?page=<?= $currentPage - 1 ?>" class="page-btn">← Précédent</a>
        <?php endif; ?>
        <?php
        $ellipsis = false;
        for ($i = 1; $i <= $totalPages; $i++):
            if ($i === 1 || $i === $totalPages || abs($i - $currentPage) <= 2):
                $ellipsis = false;
        ?>
        <a href="?page=<?= $i ?>" class="page-btn <?= $i === $currentPage ? 'active' : '' ?>"><?= $i ?></a>
        <?php
            elseif (!$ellipsis): $ellipsis = true;
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