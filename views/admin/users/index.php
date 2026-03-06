<?php
// views/admin/users/index.php
$pageTitle = 'Gestion des utilisateurs — Admin';
?>

<div class="space-y-6">

    <div class="flex items-center justify-between border-b-2 border-ink pb-4">
        <h2 class="font-display text-2xl font-black text-ink">Utilisateurs</h2>
        <a href="<?= BASE_URL ?>/admin" class="text-sm font-body text-muted hover:text-accent transition-colors">← Dashboard</a>
    </div>

    <?php if (empty($users)): ?>
    <div class="text-center py-16 text-muted font-body">
        <p class="text-4xl mb-3">👤</p>
        <p>Aucun utilisateur.</p>
    </div>
    <?php else: ?>

    <div class="bg-white border border-border overflow-hidden">
        <table class="w-full text-sm font-body">
            <thead class="bg-ink text-paper">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider">Utilisateur</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell">Email</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden lg:table-cell">Entreprise</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden lg:table-cell">Inscrit le</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider">Rôle</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <?php foreach ($users as $u): ?>
                <tr class="hover:bg-paper/50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="font-semibold text-ink"><?= htmlspecialchars($u['username']) ?></div>
                        <div class="text-xs text-muted">
                            <?= htmlspecialchars(trim(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? ''))) ?>
                        </div>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell text-muted">
                        <?= htmlspecialchars($u['email']) ?>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell text-muted">
                        <?= htmlspecialchars($u['company'] ?? '—') ?>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell text-muted text-xs">
                        <?= date('d/m/Y', strtotime($u['created_at'])) ?>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            <?= $u['role'] === 'admin' ? 'bg-ink text-paper' : 'bg-paper border border-border text-ink' ?>">
                            <?= ucfirst($u['role'] ?? 'member') ?>
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2 flex-wrap">

                            <!-- Éditer -->
                            <a href="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/edit"
                               class="px-3 py-1.5 text-xs font-semibold border border-border hover:border-ink transition-colors">
                                Éditer
                            </a>

                            <!-- Changer rôle -->
                            <?php if ($u['id'] !== \App\Core\Auth::id()): ?>
                            <form method="POST" action="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/role" class="inline">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <input type="hidden" name="role"
                                       value="<?= $u['role'] === 'admin' ? 'member' : 'admin' ?>">
                                <button class="px-3 py-1.5 text-xs font-semibold border transition-colors
                                    <?= $u['role'] === 'admin'
                                        ? 'border-yellow-300 text-yellow-700 hover:bg-yellow-50'
                                        : 'border-blue-300 text-blue-700 hover:bg-blue-50' ?>">
                                    <?= $u['role'] === 'admin' ? '↓ Rétrograder' : '↑ Promouvoir' ?>
                                </button>
                            </form>

                            <!-- Supprimer -->
                            <form method="POST" action="<?= BASE_URL ?>/admin/users/<?= $u['id'] ?>/delete" class="inline"
                                  onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <button class="px-3 py-1.5 text-xs font-semibold border border-red-200 text-accent hover:bg-red-50 transition-colors">
                                    Supprimer
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="text-xs text-muted font-body italic">Vous</span>
                            <?php endif; ?>

                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php endif; ?>
</div>