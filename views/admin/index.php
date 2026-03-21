<?php
// views/admin/index.php
$pageTitle = 'Dashboard — Administration';
?>
 
<div class="space-y-8">
 
    <!-- EN-TÊTE -->
    <div class="flex items-center justify-between pb-4" style="border-bottom:2px solid var(--border)">
        <div>
            <h2 class="font-display text-2xl font-black" style="color:var(--text)">Tableau de bord</h2>
            <p class="text-sm mt-1" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                Bienvenue, <strong style="color:var(--text)"><?= htmlspecialchars(\App\Core\Auth::user()['username'] ?? '') ?></strong>
                —
                <?php
                $jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];
                $mois  = ['','Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
                echo $jours[date('N')-1] . ' ' . date('d') . ' ' . $mois[(int)date('n')] . ' ' . date('Y');
                ?>
            </p>
        </div>
        <a href="<?= BASE_URL ?>/admin/posts/create"
           class="btn-primary px-5 py-2.5 text-sm">
            + Nouvel article
        </a>
    </div>
 
    <!-- CARTES STATS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php
        $cards = [
            ['val'=>$stats['published_posts'], 'label'=>'Articles publiés',  'sub'=>$stats['draft_posts'].' brouillon'.($stats['draft_posts']>1?'s':''), 'icon'=>'✒', 'color'=>'#f97316'],
            ['val'=>$stats['total_comments'],  'label'=>'Commentaires',      'sub'=>$stats['pending_comments']>0 ? '⚠ '.$stats['pending_comments'].' en attente' : '✓ Aucun en attente', 'icon'=>'💬', 'color'=>'#8b5cf6'],
            ['val'=>$stats['total_users'],     'label'=>'Utilisateurs',      'sub'=>$stats['total_admins'].' admin · '.$stats['total_members'].' membre'.($stats['total_members']>1?'s':''), 'icon'=>'👤', 'color'=>'#ef4444'],
            ['val'=>$stats['total_events'],    'label'=>'Événements',        'sub'=>'dans l\'agenda', 'icon'=>'📅', 'color'=>'#06b6d4'],
        ];
        foreach ($cards as $card): ?>
        <div class="surface rounded-xl p-5 relative overflow-hidden group transition-all"
             onmouseover="this.style.borderColor='<?= $card['color'] ?>'"
             onmouseout="this.style.borderColor='var(--border)'">
            <div class="flex items-start justify-between mb-2">
                <div class="text-3xl font-display font-black" style="color:var(--text)"><?= $card['val'] ?></div>
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-base shrink-0"
                     style="background:<?= $card['color'] ?>">
                    <?= $card['icon'] ?>
                </div>
            </div>
            <div class="text-xs font-semibold uppercase tracking-wider" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;"><?= $card['label'] ?></div>
            <div class="text-xs mt-1" style="color:<?= str_contains($card['sub'],'⚠') ? '#f97316' : 'var(--muted)' ?>;font-family:'Source Sans 3',sans-serif;"><?= $card['sub'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>
 
    <!-- GRAPHIQUES ROW 1 : Publications par mois + Répartition statuts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
 
        <!-- Publications par mois -->
        <div class="surface rounded-xl p-6">
            <h3 class="font-display text-base font-bold mb-4 pb-3"
                style="color:var(--text);border-bottom:1px solid var(--border)">
                Publications par mois
            </h3>
            <canvas id="postsChart" height="200"></canvas>
        </div>
 
        <!-- Répartition statuts Doughnut -->
        <div class="surface rounded-xl p-6">
            <h3 class="font-display text-base font-bold mb-4 pb-3"
                style="color:var(--text);border-bottom:1px solid var(--border)">
                Répartition des articles
            </h3>
            <div class="flex items-center justify-center" style="height:180px;">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="flex justify-center gap-6 mt-4 text-xs" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full inline-block" style="background:#f97316"></span>
                    Publiés (<?= $stats['published_posts'] ?>)
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full inline-block" style="background:#8b5cf6"></span>
                    Brouillons (<?= $stats['draft_posts'] ?>)
                </span>
            </div>
        </div>
    </div>
 
    <!-- GRAPHIQUES ROW 2 : Répartition par catégorie + Utilisateurs par mois -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
 
        <!-- Répartition par catégorie -->
        <div class="surface rounded-xl p-6">
            <h3 class="font-display text-base font-bold mb-4 pb-3"
                style="color:var(--text);border-bottom:1px solid var(--border)">
                Répartition par catégorie
            </h3>
            <canvas id="categoryChart" height="260"></canvas>
        </div>
 
        <!-- Utilisateurs par mois -->
        <div class="surface rounded-xl p-6">
            <h3 class="font-display text-base font-bold mb-4 pb-3"
                style="color:var(--text);border-bottom:1px solid var(--border)">
                Inscriptions par mois
            </h3>
            <canvas id="usersChart" height="200"></canvas>
            <div class="flex justify-center gap-6 mt-4 text-xs" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full inline-block" style="background:#f97316"></span>
                    Admins
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full inline-block" style="background:#8b5cf6"></span>
                    Membres
                </span>
            </div>
        </div>
    </div>
 
    <!-- TOP ARTICLES + COMMENTAIRES EN ATTENTE -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
 
        <div class="surface rounded-xl p-6">
            <div class="flex items-center justify-between pb-3 mb-4" style="border-bottom:1px solid var(--border)">
                <h3 class="font-display text-base font-bold" style="color:var(--text)">Top articles</h3>
                <a href="<?= BASE_URL ?>/admin/posts" class="text-xs transition-colors"
                   style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">Voir tous →</a>
            </div>
            <?php if (!empty($popularPosts)): ?>
            <ol class="space-y-3">
                <?php foreach ($popularPosts as $i => $p): ?>
                <li class="flex items-center gap-3">
                    <span class="font-display font-black text-2xl w-8 text-right shrink-0"
                          style="color:var(--border)"><?= $i + 1 ?></span>
                    <div class="flex-1 min-w-0">
                        <a href="<?= BASE_URL ?>/article/<?= htmlspecialchars($p['slug']) ?>"
                           target="_blank"
                           class="text-sm font-semibold truncate block transition-colors"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                            <?= htmlspecialchars($p['title']) ?>
                        </a>
                        <span class="text-xs" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                            👁 <?= $p['view_count'] ?> vue<?= $p['view_count'] > 1 ? 's' : '' ?>
                        </span>
                    </div>
                    <a href="<?= BASE_URL ?>/admin/posts/<?= $p['id'] ?>/edit"
                       class="text-xs shrink-0 transition-colors px-2 py-1 rounded"
                       style="color:#1d8fd8;border:1px solid #1d8fd8;font-family:'Source Sans 3',sans-serif;">
                        Éditer
                    </a>
                </li>
                <?php endforeach; ?>
            </ol>
            <?php else: ?>
            <p class="text-sm text-center py-6" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">Aucun article publié.</p>
            <?php endif; ?>
        </div>
 
        <div class="surface rounded-xl p-6">
            <div class="flex items-center justify-between pb-3 mb-4" style="border-bottom:1px solid var(--border)">
                <h3 class="font-display text-base font-bold" style="color:var(--text)">
                    Commentaires en attente
                    <?php if ($stats['pending_comments'] > 0): ?>
                    <span class="ml-2 px-2 py-0.5 text-xs font-bold rounded-full text-white"
                          style="background:#f97316">
                        <?= $stats['pending_comments'] ?>
                    </span>
                    <?php endif; ?>
                </h3>
                <a href="<?= BASE_URL ?>/admin/comments" class="text-xs transition-colors"
                   style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">Voir tous →</a>
            </div>
            <?php if (!empty($pendingComments)): ?>
            <div class="space-y-3">
                <?php foreach (array_slice($pendingComments, 0, 4) as $comment): ?>
                <div class="pl-3 py-1" style="border-left:3px solid #f97316">
                    <div class="flex items-center justify-between mb-1">
                        <strong class="text-xs font-semibold" style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                            <?= htmlspecialchars($comment['username']) ?>
                        </strong>
                        <span class="text-xs" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                            <?= date('d/m', strtotime($comment['created_at'])) ?>
                        </span>
                    </div>
                    <p class="text-xs mb-1.5" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        <?= htmlspecialchars(mb_substr($comment['content'], 0, 80)) ?>…
                    </p>
                    <div class="flex gap-2">
                        <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/approve">
                            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                            <button class="text-xs font-semibold" style="color:#16a34a;font-family:'Source Sans 3',sans-serif;">✓ Approuver</button>
                        </form>
                        <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/reject">
                            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
                            <button class="text-xs font-semibold" style="color:#ef4444;font-family:'Source Sans 3',sans-serif;">✗ Refuser</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-6">
                <p class="text-sm" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">✓ Aucun commentaire en attente</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
 
    <!-- NAVIGATION RAPIDE -->
    <div class="rounded-xl p-6" style="background:var(--bg2);border:1px solid var(--border)">
        <h3 class="font-display text-base font-bold mb-4 pb-3"
            style="color:var(--text);border-bottom:1px solid var(--border)">
            Navigation rapide
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <?php
            $navItems = [
                ['url'=>'/admin/posts',      'label'=>'Articles',     'icon'=>'✒', 'color'=>'#f97316'],
                ['url'=>'/admin/comments',   'label'=>'Commentaires', 'icon'=>'💬','color'=>'#8b5cf6'],
                ['url'=>'/admin/users',      'label'=>'Utilisateurs', 'icon'=>'👤','color'=>'#ef4444'],
                ['url'=>'/admin/categories', 'label'=>'Catégories',   'icon'=>'🏷', 'color'=>'#06b6d4'],
                ['url'=>'/admin/events',     'label'=>'Événements',   'icon'=>'📅','color'=>'#10b981'],
            ];
            foreach ($navItems as $item): ?>
            <a href="<?= BASE_URL . $item['url'] ?>"
               class="flex items-center gap-2 px-4 py-3 rounded-xl transition-all"
               style="background:var(--surface);border:1px solid var(--border);color:var(--text2);font-family:'Source Sans 3',sans-serif;font-size:.875rem;font-weight:600;"
               onmouseover="this.style.borderColor='<?= $item['color'] ?>';this.style.color='<?= $item['color'] ?>'"
               onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text2)'">
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
// ── Palette (du plus utilisé au moins utilisé)
const COLORS = [
    '#f97316', // orange
    '#8b5cf6', // violet
    '#ef4444', // rouge
    '#06b6d4', // cyan
    '#10b981', // vert émeraude
    '#f59e0b', // ambre
    '#3b82f6', // bleu
    '#ec4899', // rose
    '#84cc16', // lime
    '#14b8a6', // teal
    '#6366f1', // indigo
    '#f43f5e', // rose-rouge
];
 
const chartDefaults = {
    font: { family: "'Source Sans 3', sans-serif", size: 11 },
    color: getComputedStyle(document.documentElement).getPropertyValue('--muted').trim() || '#6b7280',
};
 
// ── 1. Publications par mois (barres)
new Chart(document.getElementById('postsChart'), {
    type: 'bar',
    data: {
        labels: <?= $chartLabels ?? '[]' ?>,
        datasets: [{
            label: 'Articles publiés',
            data: <?= $chartData ?? '[]' ?>,
            backgroundColor: COLORS[0],
            hoverBackgroundColor: COLORS[1],
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { backgroundColor: '#1e2d3d', titleFont: chartDefaults.font, bodyFont: chartDefaults.font }
        },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, font: chartDefaults.font, color: chartDefaults.color }, grid: { color: 'rgba(0,0,0,.06)' } },
            x: { ticks: { font: chartDefaults.font, color: chartDefaults.color }, grid: { display: false } }
        }
    }
});
 
// ── 2. Répartition publiés/brouillons (doughnut)
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Publiés', 'Brouillons'],
        datasets: [{
            data: [<?= $stats['published_posts'] ?>, <?= $stats['draft_posts'] ?>],
            backgroundColor: [COLORS[0], COLORS[1]],
            hoverBackgroundColor: [COLORS[2], COLORS[3]],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        cutout: '70%',
        plugins: {
            legend: { display: false },
            tooltip: { backgroundColor: '#1e2d3d', titleFont: chartDefaults.font, bodyFont: chartDefaults.font }
        }
    }
});
 
// ── 3. Répartition par catégorie (barres horizontales)
const catLabels = <?= json_encode(array_column($categoryStats ?? [], 'name')) ?>;
const catData   = <?= json_encode(array_column($categoryStats ?? [], 'count')) ?>;
new Chart(document.getElementById('categoryChart'), {
    type: 'bar',
    data: {
        labels: catLabels,
        datasets: [{
            label: 'Articles',
            data: catData,
            backgroundColor: catLabels.map((_, i) => COLORS[i % COLORS.length]),
            borderRadius: 4,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { backgroundColor: '#1e2d3d', titleFont: chartDefaults.font, bodyFont: chartDefaults.font }
        },
        scales: {
            x: { beginAtZero: true, ticks: { stepSize: 1, font: chartDefaults.font, color: chartDefaults.color }, grid: { color: 'rgba(0,0,0,.06)' } },
            y: { ticks: { font: { family: "'Source Sans 3', sans-serif", size: 10 }, color: chartDefaults.color }, grid: { display: false } }
        }
    }
});
 
// ── 4. Inscriptions par mois (lignes) — admins vs membres
new Chart(document.getElementById('usersChart'), {
    type: 'line',
    data: {
        labels: <?= $usersChartLabels ?? '[]' ?>,
        datasets: [
            {
                label: 'Admins',
                data: <?= $usersChartAdmins ?? '[]' ?>,
                borderColor: COLORS[0],
                backgroundColor: COLORS[0] + '22',
                borderWidth: 2,
                pointBackgroundColor: COLORS[0],
                tension: 0.4,
                fill: true,
            },
            {
                label: 'Membres',
                data: <?= $usersChartMembers ?? '[]' ?>,
                borderColor: COLORS[1],
                backgroundColor: COLORS[1] + '22',
                borderWidth: 2,
                pointBackgroundColor: COLORS[1],
                tension: 0.4,
                fill: true,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { backgroundColor: '#1e2d3d', titleFont: chartDefaults.font, bodyFont: chartDefaults.font }
        },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, font: chartDefaults.font, color: chartDefaults.color }, grid: { color: 'rgba(0,0,0,.06)' } },
            x: { ticks: { font: chartDefaults.font, color: chartDefaults.color }, grid: { display: false } }
        }
    }
});
</script>