<?php
// views/admin/events/index.php
$pageTitle = 'Gestion des événements — Admin';
?>

<div class="space-y-6">

    <div class="flex items-center justify-between border-b-2 border-ink pb-4">
        <h2 class="font-display text-2xl font-black text-ink">Événements</h2>
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/admin/events/create"
               class="px-5 py-2.5 bg-accent text-paper font-body font-semibold text-sm hover:bg-ink transition-colors">
                + Nouvel événement
            </a>
            <a href="<?= BASE_URL ?>/admin" class="text-sm font-body text-muted hover:text-accent transition-colors">← Dashboard</a>
        </div>
    </div>

    <?php if (empty($events)): ?>
    <div class="text-center py-16 text-muted font-body">
        <p class="text-4xl mb-3">📅</p>
        <p>Aucun événement.</p>
    </div>
    <?php else: ?>

    <div class="bg-white border border-border overflow-hidden">
        <table class="w-full text-sm font-body">
            <thead class="bg-ink text-paper">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider">Titre</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell">Date</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden lg:table-cell">Lieu</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden lg:table-cell">Intéressés</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                <?php foreach ($events as $event): ?>
                <?php $isPast = strtotime($event['event_date']) < strtotime('today'); ?>
                <tr class="hover:bg-paper/50 transition-colors <?= $isPast ? 'opacity-60' : '' ?>">
                    <td class="px-4 py-3">
                        <div class="font-semibold text-ink"><?= htmlspecialchars($event['title']) ?></div>
                        <?php if (!empty($event['description'])): ?>
                        <div class="text-xs text-muted truncate max-w-xs">
                            <?= htmlspecialchars(mb_substr($event['description'], 0, 60)) ?>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell text-muted">
                        <?= date('d/m/Y', strtotime($event['event_date'])) ?>
                        <?php if (!empty($event['event_time'])): ?>
                        <span class="text-xs"> à <?= substr($event['event_time'], 0, 5) ?></span>
                        <?php endif; ?>
                        <?php if ($isPast): ?>
                        <span class="ml-1 text-xs text-muted/60 italic">passé</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell text-muted">
                        <?= htmlspecialchars($event['location'] ?? '—') ?>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell text-center text-muted">
                        <?= $event['interest_count'] ?? 0 ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?= BASE_URL ?>/admin/events/<?= $event['id'] ?>/edit"
                               class="px-3 py-1.5 text-xs font-semibold border border-border hover:border-ink transition-colors">
                                Éditer
                            </a>
                            <form method="POST" action="<?= BASE_URL ?>/admin/events/<?= $event['id'] ?>/delete"
                                  class="inline"
                                  onsubmit="return confirm('Supprimer cet événement ?')">
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