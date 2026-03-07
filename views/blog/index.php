<?php
// views/blog/index.php
$pageTitle = 'Blog — Bienvenue à Angoulême';

if (!function_exists('dateFr')) {
    function dateFr(string $date): string {
        $mois = ['','jan.','fév.','mar.','avr.','mai','juin','juil.','août','sep.','oct.','nov.','déc.'];
        $d = date_create($date);
        return date_format($d,'d') . ' ' . $mois[(int)date_format($d,'n')] . ' ' . date_format($d,'Y');
    }
}

// Récupérer les filtres actifs
$search    = htmlspecialchars($_GET['q']          ?? '');
$catFilter = htmlspecialchars($_GET['categorie']   ?? '');
$placeFilter= htmlspecialchars($_GET['lieu']       ?? '');
$tagFilter = htmlspecialchars($_GET['tag']         ?? '');
$sortFilter= $_GET['tri'] ?? 'date_desc';
$_perPage  = (int)($_GET['per_page'] ?? 6);
$perPage   = in_array($_perPage, [6,12]) ? $_perPage : 6;
$currentPage = max(1,(int)($_GET['page']??1));
$totalPages  = $totalPages ?? 1;
$total       = $total ?? 0;

// Construire la query string pour pagination (sans page)
$qParams = array_filter([
    'q'         => $_GET['q'] ?? '',
    'categorie' => $_GET['categorie'] ?? '',
    'lieu'      => $_GET['lieu'] ?? '',
    'tag'       => $_GET['tag'] ?? '',
    'tri'       => $_GET['tri'] ?? '',
    'per_page'  => $perPage != 6 ? $perPage : '',
]);
$qString = http_build_query(array_filter($qParams));
?>

<div class="space-y-6">

    <!-- Titre -->
    <div class="flex items-center gap-4">
        <h2 class="font-display text-2xl font-bold" style="color:var(--text)">Blog</h2>
        <div class="flex-1 h-px" style="background:linear-gradient(to right,#1d8fd8,transparent)"></div>
        <span class="text-sm" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            <?= $total ?> article<?= $total > 1 ? 's' : '' ?>
        </span>
    </div>

    <!-- ── BARRE RECHERCHE + FILTRES -->
    <div class="surface rounded-xl p-4 space-y-3">
        <form method="GET" action="<?= BASE_URL ?>/blog" class="space-y-3">

            <!-- Recherche -->
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-lg" style="color:var(--muted)">🔍</span>
                <input type="text" name="q" value="<?= $search ?>"
                       placeholder="Rechercher un article…"
                       class="w-full rounded-lg pl-10 pr-4 py-3 text-sm"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                       onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
            </div>

            <!-- Filtres ligne -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

                <!-- Tri -->
                <select name="tri"
                        class="rounded-lg px-3 py-2.5 text-sm"
                        style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;">
                    <option value="date_desc" <?= $sortFilter==='date_desc'?'selected':'' ?>>📅 Plus récents</option>
                    <option value="date_asc"  <?= $sortFilter==='date_asc' ?'selected':'' ?>>📅 Plus anciens</option>
                    <option value="vues"      <?= $sortFilter==='vues'     ?'selected':'' ?>>👁 Plus lus</option>
                    <option value="comments"  <?= $sortFilter==='comments' ?'selected':'' ?>>💬 Plus commentés</option>
                </select>

                <!-- Catégorie -->
                <select name="categorie"
                        class="rounded-lg px-3 py-2.5 text-sm"
                        style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;">
                    <option value="">Toutes catégories</option>
                    <?php foreach($categories ?? [] as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['slug']) ?>"
                            <?= $catFilter===$cat['slug']?'selected':'' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <!-- Lieu -->
                <select name="lieu"
                        class="rounded-lg px-3 py-2.5 text-sm"
                        style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;">
                    <option value="">Tous les lieux</option>
                    <?php foreach($places ?? [] as $place): ?>
                    <option value="<?= htmlspecialchars($place['slug']) ?>"
                            <?= $placeFilter===$place['slug']?'selected':'' ?>>
                        <?= htmlspecialchars($place['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <!-- Tags -->
                <input type="text" name="tag" value="<?= $tagFilter ?>"
                       placeholder="🏷 Tag…"
                       class="rounded-lg px-3 py-2.5 text-sm"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                       onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
            </div>

            <!-- Boutons + sélecteur nb articles -->
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">Afficher :</span>
                    <?php foreach([6,12] as $n): ?>
                    <button type="submit" name="per_page" value="<?= $n ?>"
                            class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all"
                            style="<?= $perPage===$n ? 'background:linear-gradient(135deg,#1d8fd8,#22d3ee);color:white;border:none;' : 'background:var(--bg2);color:var(--text2);border:1px solid var(--border);' ?>;font-family:'Source Sans 3',sans-serif;cursor:pointer;">
                        <?= $n ?>
                    </button>
                    <?php endforeach; ?>
                </div>
                <div class="flex gap-2">
                    <?php if ($search || $catFilter || $placeFilter || $tagFilter): ?>
                    <a href="<?= BASE_URL ?>/blog" class="btn-ghost text-xs py-2 px-4">✕ Réinitialiser</a>
                    <?php endif; ?>
                    <button type="submit" class="btn-primary text-xs py-2 px-5">Rechercher</button>
                </div>
            </div>

        </form>
    </div>

    <!-- ── GRILLE ARTICLES -->
    <?php if (empty($posts)): ?>
    <div class="text-center py-16 surface rounded-xl" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
        <p class="text-5xl mb-4">🔍</p>
        <p class="text-lg font-semibold" style="color:var(--text)">Aucun article trouvé</p>
        <p class="text-sm mt-2">Essayez avec d'autres critères de recherche.</p>
        <a href="<?= BASE_URL ?>/blog" class="btn-primary inline-block mt-4">Voir tous les articles</a>
    </div>
    <?php else: ?>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <?php foreach($posts as $post): ?>
        <?php include __DIR__ . '/../partials/post-card.php'; ?>
        <?php endforeach; ?>
    </div>

    <!-- ── PAGINATION -->
    <?php if ($totalPages > 1): ?>
    <div class="mt-8">
        <p class="text-xs text-center mb-4" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            Page <?= $currentPage ?> sur <?= $totalPages ?> · <?= $total ?> article<?= $total>1?'s':'' ?>
        </p>
        <div class="flex flex-wrap justify-center items-center gap-2">

            <?php if ($currentPage > 2): ?>
            <a href="?<?= $qString ?>&page=1" class="page-btn" title="Première">«</a>
            <?php endif; ?>

            <?php if ($currentPage > 1): ?>
            <a href="?<?= $qString ?>&page=<?= $currentPage-1 ?>" class="page-btn">‹ Préc.</a>
            <?php endif; ?>

            <?php for ($i=1; $i<=$totalPages; $i++):
                if ($i===1 || $i===$totalPages || abs($i-$currentPage)<=2): ?>
            <a href="?<?= $qString ?>&page=<?= $i ?>"
               class="page-btn <?= $i===$currentPage?'active':'' ?>"><?= $i ?></a>
            <?php elseif (abs($i-$currentPage)===3): ?>
            <span class="page-btn" style="cursor:default;pointer-events:none">…</span>
            <?php endif; endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
            <a href="?<?= $qString ?>&page=<?= $currentPage+1 ?>" class="page-btn">Suiv. ›</a>
            <?php endif; ?>

            <?php if ($currentPage < $totalPages-1): ?>
            <a href="?<?= $qString ?>&page=<?= $totalPages ?>" class="page-btn" title="Dernière">»</a>
            <?php endif; ?>

        </div>
    </div>
    <?php endif; ?>

    <?php endif; ?>
</div>