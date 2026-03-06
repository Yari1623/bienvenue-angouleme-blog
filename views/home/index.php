<?php
// Vue : home/index.php
$pageTitle = 'Bienvenue à Angoulême – Le blog local';
?>

<div class="flex flex-col lg:flex-row gap-10">

    <!-- ═══════════════ GRILLE ARTICLES -->
    <section class="flex-1 min-w-0">

        <div class="flex items-baseline justify-between mb-6 border-b-2 border-ink pb-2">
            <h2 class="font-display text-2xl font-bold text-ink">Derniers articles</h2>
            <span class="text-sm font-body text-muted"><?= count($posts) ?> article<?= count($posts) > 1 ? 's' : '' ?></span>
        </div>

        <?php if (empty($posts)): ?>
            <div class="text-center py-16 text-muted font-body">
                <p class="text-5xl mb-4">✒️</p>
                <p class="text-lg">Aucun article publié pour le moment.</p>
            </div>
        <?php else: ?>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <?php foreach ($posts as $post): ?>
            <article class="post-card bg-white border border-border rounded-sm overflow-hidden">

                <!-- Thumbnail -->
                <?php if (!empty($post['thumbnail'])): ?>
                <div class="aspect-video overflow-hidden bg-border/30">
                    <img src="<?= htmlspecialchars($post['thumbnail']) ?>"
                         alt="<?= htmlspecialchars($post['title']) ?>"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                </div>
                <?php else: ?>
                <div class="aspect-video bg-gradient-to-br from-ink/5 to-ink/10 flex items-center justify-center">
                    <span class="font-display text-4xl text-ink/20">A</span>
                </div>
                <?php endif; ?>

                <div class="p-4">
                    <!-- Catégorie + lieu -->
                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                        <?php if (!empty($post['category_name'])): ?>
                        <a href="<?= BASE_URL ?>/categorie/<?= htmlspecialchars($post['category_slug'] ?? '') ?>"
                           class="text-xs font-body font-semibold text-accent uppercase tracking-wider hover:underline">
                            <?= htmlspecialchars($post['category_name']) ?>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($post['place_name'])): ?>
                        <span class="text-xs text-muted font-body">· <?= htmlspecialchars($post['place_name']) ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Titre -->
                    <h3 class="font-display text-lg font-bold text-ink leading-snug mb-2">
                        <a href="<?= BASE_URL ?>/article/<?= htmlspecialchars($post['slug']) ?>"
                           class="hover:text-accent transition-colors">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h3>

                    <!-- Extrait -->
                    <?php if (!empty($post['content'])): ?>
                    <p class="text-sm font-body text-muted leading-relaxed line-clamp-2 mb-3">
                        <?= htmlspecialchars(mb_substr(strip_tags($post['content']), 0, 100)) ?>…
                    </p>
                    <?php endif; ?>

                    <!-- Meta -->
                    <div class="flex items-center justify-between text-xs font-body text-muted border-t border-border pt-3 mt-3">
                        <div class="flex items-center gap-3">
                            <?php if (!empty($post['author_name'])): ?>
                            <span><?= htmlspecialchars($post['author_name']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($post['reading_time'])): ?>
                            <span>· <?= $post['reading_time'] ?> min</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center gap-3">
                            <?php if (!empty($post['like_count'])): ?>
                            <span>♥ <?= $post['like_count'] ?></span>
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

        <!-- Pagination -->
        <?php if (($totalPages ?? 1) > 1): ?>
        <div class="flex justify-center items-center gap-2 mt-10 font-body text-sm">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?= $currentPage - 1 ?>"
                   class="px-4 py-2 border border-border hover:border-ink hover:bg-ink hover:text-paper transition-all">
                    ← Précédent
                </a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>"
                   class="px-4 py-2 border transition-all <?= $i === $currentPage ? 'bg-ink text-paper border-ink' : 'border-border hover:border-ink' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?= $currentPage + 1 ?>"
                   class="px-4 py-2 border border-border hover:border-ink hover:bg-ink hover:text-paper transition-all">
                    Suivant →
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php endif; ?>
    </section>

    <!-- ═══════════════ SIDEBAR -->
    <aside class="w-full lg:w-72 shrink-0 space-y-8">

        <!-- Catégories -->
        <?php if (!empty($categories)): ?>
        <div class="bg-white border border-border p-5">
            <h3 class="font-display text-base font-bold text-ink border-b-2 border-accent pb-2 mb-4">Catégories</h3>
            <ul class="space-y-2">
                <?php foreach ($categories as $cat): ?>
                <li class="flex items-center justify-between text-sm font-body">
                    <a href="<?= BASE_URL ?>/categorie/<?= htmlspecialchars($cat['slug']) ?>"
                       class="text-ink hover:text-accent transition-colors">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                    <?php if (!empty($cat['post_count'])): ?>
                    <span class="text-xs text-muted bg-paper px-2 py-0.5 rounded-full border border-border">
                        <?= $cat['post_count'] ?>
                    </span>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Prochains événements -->
        <?php if (!empty($upcomingEvents)): ?>
        <div class="bg-ink text-paper p-5">
            <h3 class="font-display text-base font-bold border-b border-paper/20 pb-2 mb-4">Agenda</h3>
            <ul class="space-y-4">
                <?php foreach ($upcomingEvents as $event): ?>
                <li class="text-sm font-body">
                    <div class="text-accent text-xs font-semibold uppercase tracking-wider mb-1">
                        <?= date('d M Y', strtotime($event['event_date'])) ?>
                        <?php if (!empty($event['event_time'])): ?>
                        — <?= substr($event['event_time'], 0, 5) ?>
                        <?php endif; ?>
                    </div>
                    <div class="font-semibold text-paper leading-snug"><?= htmlspecialchars($event['title']) ?></div>
                    <?php if (!empty($event['location'])): ?>
                    <div class="text-paper/50 text-xs mt-0.5">📍 <?= htmlspecialchars($event['location']) ?></div>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
            <a href="<?= BASE_URL ?>/agenda"
               class="inline-block mt-4 text-xs font-body font-semibold text-accent hover:underline">
                Voir tout l'agenda →
            </a>
        </div>
        <?php endif; ?>

    </aside>
</div>