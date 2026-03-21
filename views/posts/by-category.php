<?php
// views/posts/by-category.php
$pageTitle = htmlspecialchars($category['name']) . ' — Bienvenue à Angoulême';
 
// Toutes les valeurs viennent du controller
$perPage     = $perPage     ?? 6;
$currentPage = $currentPage ?? 1;
$totalPages  = $totalPages  ?? 1;
$total       = $total       ?? count($posts);
 
function catPageUrl(int $page, int $pp): string {
    $q = [];
    if ($page > 1)  $q['page']     = $page;
    if ($pp  !== 6) $q['per_page'] = $pp;
    return '?' . ($q ? http_build_query($q) : '');
}
?>
 
<div class="space-y-8">
 
    <div class="pb-4" style="border-bottom:2px solid var(--border)">
        <div class="text-xs font-semibold uppercase tracking-widest mb-1"
             style="color:#1d8fd8;font-family:'Source Sans 3',sans-serif;">Catégorie</div>
        <h2 class="font-display text-2xl font-black" style="color:var(--text)">
            <?= htmlspecialchars($category['name']) ?>
        </h2>
        <p class="text-sm mt-1" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            <?= $total ?> article<?= $total > 1 ? 's' : '' ?> dans cette catégorie
        </p>
    </div>
 
    <?php if (empty($posts)): ?>
    <div class="text-center py-16" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
        <p class="text-5xl mb-4">✒</p>
        <p>Aucun article publié dans cette catégorie.</p>
        <a href="<?= BASE_URL ?>/" class="inline-block mt-4 font-semibold text-sm"
           style="color:#1d8fd8;">← Retour à l'accueil</a>
    </div>
    <?php else: ?>
 
    <!-- Sélecteur par page + compteur -->
    <div class="flex items-center justify-between flex-wrap gap-3">
        <p class="text-sm" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            <?php
            $debut = ($currentPage - 1) * $perPage + 1;
            $fin   = min($currentPage * $perPage, $total);
            echo "Affichage {$debut} - {$fin} sur {$total}";
            ?>
        </p>
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold"
                  style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">Articles par page :</span>
            <?php foreach ([6, 12] as $n): ?>
            <a href="<?= catPageUrl(1, $n) ?>"
               class="px-3 py-1 rounded-full text-xs font-bold transition-all"
               style="<?= $perPage === $n
                   ? 'background:linear-gradient(135deg,#1d8fd8,#22d3ee);color:white;'
                   : 'background:var(--bg2);color:var(--text2);border:1px solid var(--border);' ?>">
                <?= $n ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
 
    <!-- Grille -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php foreach ($posts as $post): ?>
        <article class="post-card surface rounded-xl overflow-hidden">
            <?php if (!empty($post['thumbnail'])): ?>
            <div class="overflow-hidden" style="aspect-ratio:16/9;background:var(--bg2);">
                <img src="<?= htmlspecialchars($post['thumbnail']) ?>"
                     alt="<?= htmlspecialchars($post['title']) ?>"
                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
            </div>
            <?php else: ?>
            <div class="flex items-center justify-center" style="aspect-ratio:16/9;background:var(--bg2);">
                <span class="font-display text-4xl" style="color:var(--border)">✒</span>
            </div>
            <?php endif; ?>
            <div class="p-4">
                <?php if (!empty($post['place_name'])): ?>
                <div class="text-xs mb-2" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                    📍 <?= htmlspecialchars($post['place_name']) ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($post['tags'])): ?>
                <div class="flex flex-wrap gap-1 mb-2">
                    <?php foreach (array_slice(array_filter(array_map('trim', explode(',', $post['tags']))), 0, 3) as $tag): ?>
                    <span class="px-2 py-0.5 rounded-full text-xs"
                          style="background:var(--bg2);color:var(--text2);border:1px solid var(--border);font-family:'Source Sans 3',sans-serif;">
                        #<?= htmlspecialchars($tag) ?>
                    </span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <h3 class="font-display text-lg font-bold leading-snug mb-2" style="color:var(--text)">
                    <a href="<?= BASE_URL ?>/article/<?= htmlspecialchars($post['slug']) ?>"
                       class="transition-colors hover:opacity-75">
                        <?= htmlspecialchars($post['title']) ?>
                    </a>
                </h3>
                <?php if (!empty($post['content'])): ?>
                <p class="text-sm leading-relaxed mb-3"
                   style="color:var(--text2);font-family:'Source Sans 3',sans-serif;
                          display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-clamp:2;">
                    <?= htmlspecialchars(mb_substr(strip_tags($post['content']), 0, 120)) ?>…
                </p>
                <?php endif; ?>
                <div class="flex items-center justify-between pt-3 mt-3"
                     style="border-top:1px solid var(--border);font-family:'Source Sans 3',sans-serif;">
                    <span class="text-xs" style="color:var(--text2)"><?= htmlspecialchars($post['author_name'] ?? '') ?></span>
                    <span class="text-xs" style="color:var(--muted)"><?= date('d/m/Y', strtotime($post['created_at'])) ?></span>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
 
    <!-- Pagination uniquement si plus d'une page -->
    <?php if ($totalPages > 1): ?>
    <div class="flex justify-center items-center gap-2 mt-8 flex-wrap"
         style="font-family:'Source Sans 3',sans-serif;">
        <?php if ($currentPage > 1): ?>
        <a href="<?= catPageUrl($currentPage - 1, $perPage) ?>" class="page-btn">← Précédent</a>
        <?php endif; ?>
        <?php
        $range = 2;
        $ellipsis = false;
        for ($i = 1; $i <= $totalPages; $i++):
            if ($i === 1 || $i === $totalPages || abs($i - $currentPage) <= $range):
                $ellipsis = false;
        ?>
        <a href="<?= catPageUrl($i, $perPage) ?>"
           class="page-btn <?= $i === $currentPage ? 'active' : '' ?>"><?= $i ?></a>
        <?php
            elseif (!$ellipsis):
                $ellipsis = true;
                echo '<span style="color:var(--muted);padding:0 .25rem">…</span>';
            endif;
        endfor;
        ?>
        <?php if ($currentPage < $totalPages): ?>
        <a href="<?= catPageUrl($currentPage + 1, $perPage) ?>" class="page-btn">Suivant →</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
 
    <?php endif; ?>
 
    <div class="text-center mt-4">
        <a href="<?= BASE_URL ?>/" class="text-sm transition-colors"
           style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">← Retour à l'accueil</a>
    </div>
</div>