<?php
// views/posts/by-category.php
$pageTitle = htmlspecialchars($category['name']) . ' — Bienvenue à Angoulême';
?>

<div class="space-y-8">

    <div class="border-b-2 border-ink pb-4">
        <div class="text-xs font-body font-semibold text-accent uppercase tracking-widest mb-1">Catégorie</div>
        <h2 class="font-display text-2xl font-black text-ink"><?= htmlspecialchars($category['name']) ?></h2>
        <p class="font-body text-sm text-muted mt-1">
            <?= count($posts) ?> article<?= count($posts) > 1 ? 's' : '' ?> dans cette catégorie
        </p>
    </div>

    <?php if (empty($posts)): ?>
    <div class="text-center py-16 text-muted font-body">
        <p class="text-5xl mb-4">✒</p>
        <p>Aucun article publié dans cette catégorie.</p>
        <a href="<?= BASE_URL ?>/" class="inline-block mt-4 text-accent hover:underline text-sm font-semibold">← Retour à l'accueil</a>
    </div>
    <?php else: ?>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php foreach ($posts as $post): ?>
        <article class="post-card bg-white border border-border overflow-hidden">

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
                <?php if (!empty($post['place_name'])): ?>
                <div class="text-xs font-body text-muted mb-2">📍 <?= htmlspecialchars($post['place_name']) ?></div>
                <?php endif; ?>

                <h3 class="font-display text-lg font-bold text-ink leading-snug mb-2">
                    <a href="<?= BASE_URL ?>/article/<?= htmlspecialchars($post['slug']) ?>"
                       class="hover:text-accent transition-colors">
                        <?= htmlspecialchars($post['title']) ?>
                    </a>
                </h3>

                <?php if (!empty($post['content'])): ?>
                <p class="text-sm font-body text-muted leading-relaxed line-clamp-2 mb-3">
                    <?= htmlspecialchars(mb_substr(strip_tags($post['content']), 0, 100)) ?>…
                </p>
                <?php endif; ?>

                <div class="flex items-center justify-between text-xs font-body text-muted border-t border-border pt-3 mt-3">
                    <span><?= htmlspecialchars($post['author_name'] ?? '') ?></span>
                    <span><?= date('d/m/Y', strtotime($post['created_at'])) ?></span>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if (($totalPages ?? 1) > 1): ?>
    <div class="flex justify-center items-center gap-2 mt-8 font-body text-sm">
        <?php if ($currentPage > 1): ?>
        <a href="?page=<?= $currentPage - 1 ?>" class="px-4 py-2 border border-border hover:border-ink hover:bg-ink hover:text-paper transition-all">← Précédent</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>" class="px-4 py-2 border transition-all <?= $i === $currentPage ? 'bg-ink text-paper border-ink' : 'border-border hover:border-ink' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($currentPage < $totalPages): ?>
        <a href="?page=<?= $currentPage + 1 ?>" class="px-4 py-2 border border-border hover:border-ink hover:bg-ink hover:text-paper transition-all">Suivant →</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php endif; ?>

    <div class="text-center">
        <a href="<?= BASE_URL ?>/" class="text-sm font-body text-muted hover:text-accent transition-colors">← Retour à l'accueil</a>
    </div>
</div>