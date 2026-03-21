<?php
// views/admin/events/index.php
$pageTitle = 'Gestion des événements — Admin';
 
$perPage     = 15;
$currentPage = max(1, (int)($_GET['page'] ?? 1));
$totalItems  = count($events ?? []);
$totalPages  = max(1, (int)ceil($totalItems / $perPage));
$currentPage = min($currentPage, $totalPages);
$pageItems   = array_slice($events ?? [], ($currentPage - 1) * $perPage, $perPage);
?>
 
<div class="space-y-6">
 
    <div class="flex items-center justify-between pb-4" style="border-bottom:2px solid var(--border)">
        <div>
            <h2 class="font-display text-2xl font-black" style="color:var(--text)">Événements</h2>
            <p class="text-xs mt-1" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                <?= $totalItems ?> événement<?= $totalItems > 1 ? 's' : '' ?> au total
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/admin" class="text-sm transition-colors"
               style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">← Dashboard</a>
            <a href="<?= BASE_URL ?>/admin/events/create" class="btn-primary px-4 py-2 text-sm">
                + Nouvel événement
            </a>
        </div>
    </div>
 
    <?php if (empty($events)): ?>
    <div class="text-center py-16" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
        <p class="text-4xl mb-3">📅</p>
        <p>Aucun événement pour le moment.</p>
    </div>
    <?php else: ?>
 
    <div class="surface rounded-xl overflow-hidden">
        <table class="w-full text-sm" style="font-family:'Source Sans 3',sans-serif;">
            <thead>
                <tr style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white">Titre</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white hidden md:table-cell">Date</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white hidden lg:table-cell">Lieu</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white hidden lg:table-cell">Intérêts</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pageItems as $i => $event):
                    $isPast = strtotime($event['event_date']) < strtotime('today');
                ?>
                <tr style="<?= $i % 2 === 0 ? 'background:var(--surface)' : 'background:var(--bg2)' ?>;border-bottom:1px solid var(--border);<?= $isPast ? 'opacity:.65;' : '' ?>"
                    onmouseover="this.style.background='var(--bg2)'"
                    onmouseout="this.style.background='<?= $i % 2 === 0 ? 'var(--surface)' : 'var(--bg2)' ?>'">
 
                    <td class="px-4 py-3">
                        <div class="font-semibold" style="color:var(--text)"><?= htmlspecialchars($event['title']) ?></div>
                        <?php if ($isPast): ?>
                        <div class="text-xs mt-0.5" style="color:var(--muted)">Événement passé</div>
                        <?php endif; ?>
                    </td>
 
                    <td class="px-4 py-3 hidden md:table-cell" style="color:var(--text2)">
                        <?php
                        $mois = ['','jan.','fév.','mar.','avr.','mai','juin','juil.','août','sep.','oct.','nov.','déc.'];
                        $d = strtotime($event['event_date']);
                        echo date('d', $d) . ' ' . $mois[(int)date('n', $d)] . ' ' . date('Y', $d);
                        if (!empty($event['event_time'])) echo ' à ' . substr($event['event_time'], 0, 5);
                        ?>
                    </td>
 
                    <td class="px-4 py-3 hidden lg:table-cell text-sm" style="color:var(--text2)">
                        <?= htmlspecialchars($event['location'] ?? '—') ?>
                    </td>
 
                    <td class="px-4 py-3 hidden lg:table-cell text-center">
                        <span class="px-2 py-1 text-xs font-bold rounded-full"
                              style="background:var(--bg2);color:var(--text2);border:1px solid var(--border);">
                            ★ <?= $event['interest_count'] ?? 0 ?>
                        </span>
                    </td>
 
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?= BASE_URL ?>/admin/events/<?= $event['id'] ?>/edit"
                               class="px-3 py-1.5 text-xs font-semibold rounded-full border transition-all"
                               style="border-color:#1d8fd8;color:#1d8fd8;"
                               onmouseover="this.style.background='#1d8fd8';this.style.color='white'"
                               onmouseout="this.style.background='';this.style.color='#1d8fd8'">
                                Éditer
                            </a>
                            <form method="POST" action="<?= BASE_URL ?>/admin/events/<?= $event['id'] ?>/delete" class="inline"
                                  onsubmit="return confirm('Supprimer cet événement ?')">
                                <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                                <button class="px-3 py-1.5 text-xs font-semibold rounded-full border transition-all"
                                        style="border-color:#dc2626;color:#dc2626;"
                                        onmouseover="this.style.background='#dc2626';this.style.color='white'"
                                        onmouseout="this.style.background='';this.style.color='#dc2626'">
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
 
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="flex justify-center items-center gap-2 flex-wrap" style="font-family:'Source Sans 3',sans-serif;">
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
        endfor;
        ?>
        <?php if ($currentPage < $totalPages): ?>
        <a href="?page=<?= $currentPage + 1 ?>" class="page-btn">Suivant →</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
 
    <?php endif; ?>
</div>