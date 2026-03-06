<h2>Dashboard Admin</h2>
<p>Accès réservé aux utilisateurs connectés.</p><?php
// views/admin/index.php
$pageTitle = 'Dashboard — Administration';
?>

<!-- Layout admin deux colonnes -->
<div class="space-y-8">

    <!-- ═══════════════════════════════════ EN-TÊTE -->
    <div class="flex items-center justify-between border-b-2 border-ink pb-4">
        <div>
            <h2 class="font-display text-2xl font-black text-ink">Tableau de bord</h2>
            <p class="font-body text-sm text-muted mt-1">
                Bienvenue, <strong><?= htmlspecialchars(\App\Core\Auth::user()['username'] ?? '') ?></strong>
                — <?= date('l d F Y') ?>
            </p>
        </div>
        <a href="<?= BASE_URL ?>/admin/posts/create"
           class="px-5 py-2.5 bg-accent text-paper font-body font-semibold text-sm hover:bg-ink transition-colors">
            + Nouvel article
        </a>
    </div>

    <!-- ═══════════════════════════════════ CARTES STATS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        <!-- Articles publiés -->
        <div class="bg-white border border-border p-5 relative overflow-hidden group hover:border-accent transition-colors">
            <div class="text-3xl font-display font-black text-ink"><?= $stats['published_posts'] ?></div>
            <div class="text-xs font-body font-semibold uppercase tracking-wider text-muted mt-1">Articles publiés</div>
            <div class="text-xs font-body text-muted/60 mt-1"><?= $stats['draft_posts'] ?> brouillon<?= $stats['draft_posts'] > 1 ? 's' : '' ?></div>
            <div class="absolute bottom-0 right-0 text-6xl font-display font-black text-ink/5 leading-none pr-2 pb-0 group-hover:text-accent/10 transition-colors">✒</div>
        </div>

        <!-- Commentaires -->
        <div class="bg-white border border-border p-5 relative overflow-hidden group hover:border-accent transition-colors">
            <div class="text-3xl font-display font-black text-ink"><?= $stats['total_comments'] ?></div>
            <div class="text-xs font-body font-semibold uppercase tracking-wider text-muted mt-1">Commentaires</div>
            <?php if ($stats['pending_comments'] > 0): ?>
            <div class="text-xs font-body text-accent font-semibold mt-1">
                ⚠ <?= $stats['pending_comments'] ?> en attente
            </div>
            <?php else: ?>
            <div class="text-xs font-body text-muted/60 mt-1">Aucun en attente</div>
            <?php endif; ?>
            <div class="absolute bottom-0 right-0 text-6xl font-display font-black text-ink/5 leading-none pr-2 pb-0 group-hover:text-accent/10 transition-colors">💬</div>
        </div>

        <!-- Utilisateurs -->
        <div class="bg-white border border-border p-5 relative overflow-hidden group hover:border-accent transition-colors">
            <div class="text-3xl font-display font-black text-ink"><?= $stats['total_users'] ?></div>
            <div class="text-xs font-body font-semibold uppercase tracking-wider text-muted mt-1">Utilisateurs</div>
            <div class="text-xs font-body text-muted/60 mt-1">
                <?= $stats['total_admins'] ?> admin · <?= $stats['total_members'] ?> membre<?= $stats['total_members'] > 1 ? 's' : '' ?>
            </div>
            <div class="absolute bottom-0 right-0 text-6xl font-display font-black text-ink/5 leading-none pr-2 pb-0 group-hover:text-accent/10 transition-colors">👤</div>
        </div>

        <!-- Événements -->
        <div class="bg-white border border-border p-5 relative overflow-hidden group hover:border-accent transition-colors">
            <div class="text-3xl font-display font-black text-ink"><?= $stats['total_events'] ?></div>
            <div class="text-xs font-body font-semibold uppercase tracking-wider text-muted mt-1">Événements</div>
            <div class="text-xs font-body text-muted/60 mt-1">à venir dans l'agenda</div>
            <div class="absolute bottom-0 right-0 text-6xl font-display font-black text-ink/5 leading-none pr-2 pb-0 group-hover:text-accent/10 transition-colors">📅</div>
        </div>
    </div>

    <!-- ═══════════════════════════════════ CHART + TOP ARTICLES -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Chart.js — articles par mois -->
        <div class="bg-white border border-border p-6">
            <h3 class="font-display text-base font-bold text-ink border-b border-border pb-3 mb-4">
                Publications par mois
            </h3>
            <canvas id="postsChart" height="200"></canvas>
        </div>

        <!-- Répartition statuts — Doughnut -->
        <div class="bg-white border border-border p-6">
            <h3 class="font-display text-base font-bold text-ink border-b border-border pb-3 mb-4">
                Répartition des articles
            </h3>
            <div class="flex items-center justify-center h-48">
                <canvas id="statusChart" height="180"></canvas>
            </div>
            <div class="flex justify-center gap-6 mt-4 text-xs font-body text-muted">
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-ink inline-block"></span>
                    Publiés (<?= $stats['published_posts'] ?>)
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-border inline-block"></span>
                    Brouillons (<?= $stats['draft_posts'] ?>)
                </span>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════ TOP ARTICLES + COMMENTAIRES EN ATTENTE -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Top articles les plus vus -->
        <div class="bg-white border border-border p-6">
            <div class="flex items-center justify-between border-b border-border pb-3 mb-4">
                <h3 class="font-display text-base font-bold text-ink">Top articles</h3>
                <a href="<?= BASE_URL ?>/admin/posts"
                   class="text-xs font-body text-muted hover:text-accent transition-colors">
                    Voir tous →
                </a>
            </div>
            <?php if (!empty($popularPosts)): ?>
            <ol class="space-y-3">
                <?php foreach ($popularPosts as $i => $p): ?>
                <li class="flex items-center gap-3">
                    <span class="font-display font-black text-ink/20 text-2xl w-8 text-right shrink-0">
                        <?= $i + 1 ?>
                    </span>
                    <div class="flex-1 min-w-0">
                        <a href="<?= BASE_URL ?>/article/<?= htmlspecialchars($p['slug']) ?>"
                           target="_blank"
                           class="font-body text-sm font-semibold text-ink hover:text-accent transition-colors truncate block">
                            <?= htmlspecialchars($p['title']) ?>
                        </a>
                        <span class="text-xs font-body text-muted">
                            👁 <?= $p['view_count'] ?> vue<?= $p['view_count'] > 1 ? 's' : '' ?>
                        </span>
                    </div>
                    <a href="<?= BASE_URL ?>/admin/posts/<?= $p['id'] ?>/edit"
                       class="text-xs font-body text-muted hover:text-accent transition-colors shrink-0">
                        Éditer
                    </a>
                </li>
                <?php endforeach; ?>
            </ol>
            <?php else: ?>
            <p class="text-sm font-body text-muted text-center py-6">Aucun article publié.</p>
            <?php endif; ?>
        </div>

        <!-- Commentaires en attente -->
        <div class="bg-white border border-border p-6">
            <div class="flex items-center justify-between border-b border-border pb-3 mb-4">
                <h3 class="font-display text-base font-bold text-ink">
                    Commentaires en attente
                    <?php if ($stats['pending_comments'] > 0): ?>
                    <span class="ml-2 px-2 py-0.5 bg-accent text-paper text-xs font-body rounded-full">
                        <?= $stats['pending_comments'] ?>
                    </span>
                    <?php endif; ?>
                </h3>
                <a href="<?= BASE_URL ?>/admin/comments"
                   class="text-xs font-body text-muted hover:text-accent transition-colors">
                    Voir tous →
                </a>
            </div>
            <?php if (!empty($pendingComments)): ?>
            <div class="space-y-3">
                <?php foreach (array_slice($pendingComments, 0, 4) as $comment): ?>
                <div class="border-l-2 border-accent/30 pl-3 py-1">
                    <div class="flex items-center justify-between mb-1">
                        <strong class="font-body text-xs font-semibold text-ink">
                            <?= htmlspecialchars($comment['username']) ?>
                        </strong>
                        <span class="text-xs font-body text-muted">
                            <?= date('d/m', strtotime($comment['created_at'])) ?>
                        </span>
                    </div>
                    <p class="text-xs font-body text-muted line-clamp-1">
                        <?= htmlspecialchars(mb_substr($comment['content'], 0, 80)) ?>…
                    </p>
                    <div class="flex gap-2 mt-1.5">
                        <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/approve">
                            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                            <button class="text-xs font-body text-green-600 hover:underline">✓ Approuver</button>
                        </form>
                        <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/reject">
                            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                            <button class="text-xs font-body text-accent hover:underline">✗ Refuser</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-6">
                <p class="text-sm font-body text-muted">✓ Aucun commentaire en attente</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ═══════════════════════════════════ NAVIGATION ADMIN RAPIDE -->
    <div class="bg-ink text-paper p-6">
        <h3 class="font-display text-base font-bold border-b border-paper/20 pb-3 mb-4">Navigation rapide</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <?php
            $navItems = [
                ['url' => '/admin/posts',      'label' => 'Articles',      'icon' => '✒'],
                ['url' => '/admin/comments',   'label' => 'Commentaires',  'icon' => '💬'],
                ['url' => '/admin/users',      'label' => 'Utilisateurs',  'icon' => '👤'],
                ['url' => '/admin/categories', 'label' => 'Catégories',    'icon' => '🏷'],
                ['url' => '/admin/events',     'label' => 'Événements',    'icon' => '📅'],
            ];
            foreach ($navItems as $item):
            ?>
            <a href="<?= BASE_URL . $item['url'] ?>"
               class="flex items-center gap-2 px-4 py-3 border border-paper/20 hover:border-accent hover:text-accent transition-all font-body text-sm font-semibold">
                <span><?= $item['icon'] ?></span>
                <span><?= $item['label'] ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Palette commune
const inkColor    = '#1a1a2e';
const accentColor = '#c8392b';
const mutedColor  = '#d4c9b8';

// ── Graphique publications par mois
const ctxBar = document.getElementById('postsChart').getContext('2d');
new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: <?= $chartLabels ?>,
        datasets: [{
            label: 'Articles publiés',
            data: <?= $chartData ?>,
            backgroundColor: inkColor,
            hoverBackgroundColor: accentColor,
            borderRadius: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: inkColor,
                titleFont: { family: 'Source Sans 3', size: 11 },
                bodyFont:  { family: 'Source Sans 3', size: 12 },
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    font: { family: 'Source Sans 3', size: 11 },
                    color: '#8a8070',
                },
                grid: { color: '#d4c9b8' }
            },
            x: {
                ticks: {
                    font: { family: 'Source Sans 3', size: 11 },
                    color: '#8a8070',
                },
                grid: { display: false }
            }
        }
    }
});

// ── Graphique répartition statuts (Doughnut)
const ctxDonut = document.getElementById('statusChart').getContext('2d');
new Chart(ctxDonut, {
    type: 'doughnut',
    data: {
        labels: ['Publiés', 'Brouillons'],
        datasets: [{
            data: [<?= $stats['published_posts'] ?>, <?= $stats['draft_posts'] ?>],
            backgroundColor: [inkColor, mutedColor],
            hoverBackgroundColor: [accentColor, '#b0a595'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: inkColor,
                titleFont: { family: 'Source Sans 3', size: 11 },
                bodyFont:  { family: 'Source Sans 3', size: 12 },
            }
        }
    }
});
</script>