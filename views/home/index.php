<?php
// views/home/index.php
$pageTitle = 'Bienvenue à Angoulême – Le blog local';
?>

<div class="flex flex-col lg:flex-row gap-10">

    <!-- ═══════════════ GRILLE ARTICLES -->
    <section class="flex-1 min-w-0">

        <!-- En-tête + sélecteur -->
        <div class="flex items-center justify-between mb-6 pb-3" style="border-bottom:3px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
            <div>
                <h2 class="font-display text-2xl font-bold" style="color:var(--text)">Derniers articles</h2>
                <p class="text-xs mt-0.5" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                    <?= $total ?? 0 ?> article<?= ($total ?? 0) > 1 ? 's' : '' ?> au total
                </p>
            </div>
            <!-- Sélecteur 6 / 12 -->
            <div class="flex items-center gap-2" style="font-family:'Source Sans 3',sans-serif;">
                <span class="text-xs" style="color:var(--muted)">Afficher :</span>
                <?php foreach([6, 12] as $n): ?>
                <a href="?per_page=<?= $n ?>&page=1"
                   class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all"
                   style="<?= ($perPage ?? 6) == $n
                       ? 'background:linear-gradient(135deg,#1d8fd8,#22d3ee);color:white;'
                       : 'background:var(--bg2);color:var(--text2);' ?>">
                    <?= $n ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (empty($posts)): ?>
        <div class="text-center py-16" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            <p class="text-5xl mb-4">✒️</p>
            <p class="text-lg">Aucun article publié pour le moment.</p>
        </div>
        <?php else: ?>

        <!-- Grille articles -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            <?php foreach ($posts as $post): ?>
            <article class="post-card flex flex-col">

                <!-- Thumbnail -->
                <div class="aspect-video overflow-hidden relative" style="background:var(--bg2)">
                    <?php if (!empty($post['thumbnail'])): ?>
                    <?php
                        // Calcul taille fichier pour alt/tooltip
                        $imgPath = $_SERVER['DOCUMENT_ROOT'] . parse_url($post['thumbnail'], PHP_URL_PATH);
                        $imgSize = file_exists($imgPath) ? round(filesize($imgPath) / 1024) . ' Ko' : '';
                        $imgExt  = strtoupper(pathinfo($post['thumbnail'], PATHINFO_EXTENSION));
                        $imgName = basename($post['thumbnail']);
                        $altText = htmlspecialchars($post['title']);
                        $tooltip = $imgName . ($imgExt ? ' · ' . $imgExt : '') . ($imgSize ? ' · ' . $imgSize : '');
                    ?>
                    <img src="<?= htmlspecialchars($post['thumbnail']) ?>"
                         alt="<?= $altText ?>"
                         title="<?= htmlspecialchars($tooltip) ?>"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-4xl"
                         style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);opacity:.15">✒</div>
                    <?php endif; ?>

                    <!-- Badge catégorie -->
                    <?php if (!empty($post['category_name'])): ?>
                    <a href="<?= BASE_URL ?>/categorie/<?= htmlspecialchars($post['category_slug'] ?? '') ?>"
                       class="absolute top-2 left-2 px-2 py-0.5 rounded-full text-xs font-bold text-white"
                       style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);font-family:'Source Sans 3',sans-serif;">
                        <?= htmlspecialchars($post['category_name']) ?>
                    </a>
                    <?php endif; ?>
                </div>

                <div class="p-4 flex flex-col flex-1">
                    <!-- Lieu -->
                    <?php if (!empty($post['place_name'])): ?>
                    <p class="text-xs mb-1" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                        📍 <?= htmlspecialchars($post['place_name']) ?>
                    </p>
                    <?php endif; ?>

                    <!-- Titre -->
                    <h3 class="font-display text-base font-bold leading-snug mb-2 flex-1" style="color:var(--text)">
                        <a href="<?= BASE_URL ?>/article/<?= htmlspecialchars($post['slug']) ?>"
                           class="hover:underline transition-colors"
                           style="color:var(--text)">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h3>

                    <!-- Meta -->
                    <div class="flex items-center justify-between text-xs pt-3 mt-auto" style="border-top:1px solid var(--border);color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                        <div class="flex items-center gap-2">
                            <?php if (!empty($post['author_name'])): ?>
                            <span><?= htmlspecialchars($post['author_name']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($post['reading_time'])): ?>
                            <span>· <?= $post['reading_time'] ?> min</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center gap-2">
                            <?php if (!empty($post['like_count'])): ?>
                            <span>❤ <?= $post['like_count'] ?></span>
                            <?php endif; ?>
                            <?php if (!empty($post['view_count'])): ?>
                            <span>👁 <?= $post['view_count'] ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <!-- ── PAGINATION COMPLÈTE -->
        <?php if (($totalPages ?? 1) > 1): ?>
        <div class="mt-10">
            <!-- Infos -->
            <p class="text-xs text-center mb-4" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                Page <?= $currentPage ?> sur <?= $totalPages ?>
                · <?= $total ?> article<?= $total > 1 ? 's' : '' ?> au total
            </p>
            <div class="flex flex-wrap justify-center items-center gap-2">

                <!-- Première page -->
                <?php if ($currentPage > 2): ?>
                <a href="?page=1&per_page=<?= $perPage ?>" class="page-btn" title="Première page">«</a>
                <?php endif; ?>

                <!-- Précédent -->
                <?php if ($currentPage > 1): ?>
                <a href="?page=<?= $currentPage - 1 ?>&per_page=<?= $perPage ?>" class="page-btn">‹ Préc.</a>
                <?php endif; ?>

                <!-- Pages numérotées avec ellipses -->
                <?php
                $range = 2; // pages autour de la page courante
                for ($i = 1; $i <= $totalPages; $i++):
                    if ($i === 1 || $i === $totalPages || abs($i - $currentPage) <= $range):
                ?>
                <a href="?page=<?= $i ?>&per_page=<?= $perPage ?>"
                   class="page-btn <?= $i === $currentPage ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
                <?php
                    elseif (abs($i - $currentPage) === $range + 1):
                ?>
                <span class="page-btn" style="cursor:default;pointer-events:none">…</span>
                <?php
                    endif;
                endfor;
                ?>

                <!-- Suivant -->
                <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?= $currentPage + 1 ?>&per_page=<?= $perPage ?>" class="page-btn">Suiv. ›</a>
                <?php endif; ?>

                <!-- Dernière page -->
                <?php if ($currentPage < $totalPages - 1): ?>
                <a href="?page=<?= $totalPages ?>&per_page=<?= $perPage ?>" class="page-btn" title="Dernière page">»</a>
                <?php endif; ?>

            </div>
        </div>
        <?php endif; ?>

        <?php endif; ?>
    </section>

    <!-- ═══════════════ SIDEBAR -->
    <aside class="w-full lg:w-72 shrink-0 space-y-6">

        <!-- Catégories -->
        <?php if (!empty($categories)): ?>
        <div class="surface rounded-xl p-5">
            <h3 class="font-display text-base font-bold mb-4 pb-2" style="color:var(--text);border-bottom:2px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
                Catégories
            </h3>
            <ul class="space-y-2">
                <?php foreach ($categories as $cat): ?>
                <li class="flex items-center justify-between text-sm" style="font-family:'Source Sans 3',sans-serif;">
                    <a href="<?= BASE_URL ?>/categorie/<?= htmlspecialchars($cat['slug']) ?>"
                       class="hover:underline transition-colors"
                       style="color:var(--text2)">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                    <?php if (!empty($cat['post_count'])): ?>
                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                          style="background:var(--bg2);color:var(--muted);">
                        <?= $cat['post_count'] ?>
                    </span>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
            <a href="<?= BASE_URL ?>/categories"
               class="block text-center mt-4 text-xs font-semibold py-2 rounded-lg transition-all"
               style="background:var(--bg2);color:#1d8fd8;font-family:'Source Sans 3',sans-serif;">
                Voir toutes les catégories →
            </a>
        </div>
        <?php endif; ?>

        <!-- Prochains événements -->
        <?php if (!empty($upcomingEvents)): ?>
        <div class="surface rounded-xl p-5" style="border-top:3px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
            <h3 class="font-display text-base font-bold mb-4" style="color:var(--text)">
                Agenda
            </h3>
            <ul class="space-y-4">
                <?php foreach ($upcomingEvents as $event): ?>
                <li class="flex items-start gap-3">
                    <!-- Mini calendrier -->
                    <div class="shrink-0 w-10 h-10 rounded-lg flex flex-col items-center justify-center text-white"
                         style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
                        <span class="font-black text-sm leading-none"><?= date('d', strtotime($event['event_date'])) ?></span>
                        <span class="text-xs uppercase leading-none"><?= date('M', strtotime($event['event_date'])) ?></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm leading-snug" style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                            <?= htmlspecialchars($event['title']) ?>
                        </p>
                        <?php if (!empty($event['location'])): ?>
                        <p class="text-xs mt-0.5" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                            📍 <?= htmlspecialchars($event['location']) ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <a href="<?= BASE_URL ?>/agenda"
               class="block text-center mt-4 text-xs font-semibold py-2 rounded-lg transition-all"
               style="background:var(--bg2);color:#1d8fd8;font-family:'Source Sans 3',sans-serif;">
                Voir tout l'agenda →
            </a>
        </div>
        <?php endif; ?>

    </aside>
</div>