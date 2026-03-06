<?php
use App\Core\Auth;
use App\Core\Csrf;
$pageTitle = htmlspecialchars($post['title']) . ' — Bienvenue à Angoulême';
?>

<div class="flex flex-col lg:flex-row gap-10">

    <!-- ═══════════════ ARTICLE -->
    <article class="flex-1 min-w-0">

        <!-- En-tête article -->
        <header class="mb-8 pb-6 border-b-2 border-ink">

            <!-- Catégorie -->
            <?php if (!empty($post['category_name'])): ?>
            <div class="mb-3">
                <a href="<?= BASE_URL ?>/categorie/<?= htmlspecialchars($post['category_slug'] ?? '') ?>"
                   class="text-xs font-body font-semibold text-accent uppercase tracking-widest hover:underline">
                    <?= htmlspecialchars($post['category_name']) ?>
                </a>
                <?php if (!empty($post['place_name'])): ?>
                <span class="text-xs text-muted font-body ml-2">— <?= htmlspecialchars($post['place_name']) ?></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Titre -->
            <h1 class="font-display text-3xl md:text-4xl font-black text-ink leading-tight mb-4">
                <?= htmlspecialchars($post['title']) ?>
            </h1>

            <!-- Meta -->
            <div class="flex flex-wrap items-center gap-4 text-sm font-body text-muted">
                <?php if (!empty($post['author_name'])): ?>
                <span>Par <strong class="text-ink"><?= htmlspecialchars($post['author_name']) ?></strong></span>
                <?php endif; ?>
                <span><?= date('d F Y', strtotime($post['created_at'])) ?></span>
                <?php if (!empty($post['reading_time'])): ?>
                <span>· <?= $post['reading_time'] ?> min de lecture</span>
                <?php endif; ?>
                <?php if (!empty($post['view_count'])): ?>
                <span>· 👁 <?= $post['view_count'] ?> vue<?= $post['view_count'] > 1 ? 's' : '' ?></span>
                <?php endif; ?>
            </div>

            <!-- Tags -->
            <?php if (!empty($post['tags'])): ?>
            <div class="flex flex-wrap gap-2 mt-4">
                <?php foreach (explode(',', $post['tags']) as $tag): ?>
                <span class="text-xs font-body px-3 py-1 bg-ink/5 border border-border text-muted rounded-full">
                    #<?= htmlspecialchars(trim($tag)) ?>
                </span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </header>

        <!-- Thumbnail principal -->
        <?php if (!empty($post['thumbnail'])): ?>
        <div class="mb-8 aspect-video overflow-hidden bg-border/20">
            <img src="<?= htmlspecialchars($post['thumbnail']) ?>"
                 alt="<?= htmlspecialchars($post['title']) ?>"
                 class="w-full h-full object-cover">
        </div>
        <?php endif; ?>

        <!-- ═══ SECTIONS par blocs -->
        <?php if (!empty($sections)): ?>
        <div class="space-y-6 font-body text-ink leading-relaxed">
            <?php foreach ($sections as $section): ?>

                <?php if ($section['type'] === 'text'): ?>
                <div class="prose max-w-none text-base leading-relaxed text-ink/90">
                    <?= nl2br(htmlspecialchars($section['content'] ?? '')) ?>
                </div>

                <?php elseif ($section['type'] === 'title'): ?>
                <h2 class="font-display text-2xl font-bold text-ink mt-8 mb-2 border-l-4 border-accent pl-4">
                    <?= htmlspecialchars($section['content'] ?? '') ?>
                </h2>

                <?php elseif ($section['type'] === 'image'): ?>
                <figure class="my-6">
                    <img src="<?= htmlspecialchars($section['media_url'] ?? '') ?>"
                         alt="<?= htmlspecialchars($section['content'] ?? '') ?>"
                         class="w-full object-cover rounded-sm border border-border">
                    <?php if (!empty($section['content'])): ?>
                    <figcaption class="text-xs text-muted text-center mt-2 font-body italic">
                        <?= htmlspecialchars($section['content']) ?>
                    </figcaption>
                    <?php endif; ?>
                </figure>

                <?php elseif ($section['type'] === 'video'): ?>
                <div class="my-6 aspect-video bg-ink/5 border border-border flex items-center justify-center overflow-hidden">
                    <?php
                    $videoUrl = $section['media_url'] ?? '';
                    // Détection YouTube
                    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoUrl, $matches);
                    if (!empty($matches[1])):
                    ?>
                    <iframe src="https://www.youtube.com/embed/<?= $matches[1] ?>"
                            class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                    <?php else: ?>
                    <a href="<?= htmlspecialchars($videoUrl) ?>"
                       target="_blank"
                       class="flex flex-col items-center gap-2 text-muted hover:text-accent transition-colors">
                        <span class="text-5xl">▶</span>
                        <span class="text-sm font-body">Voir la vidéo</span>
                    </a>
                    <?php endif; ?>
                </div>

                <?php elseif ($section['type'] === 'quote'): ?>
                <blockquote class="my-6 border-l-4 border-accent pl-6 py-2">
                    <p class="font-display text-xl italic text-ink/80 leading-relaxed">
                        "<?= htmlspecialchars($section['content'] ?? '') ?>"
                    </p>
                </blockquote>

                <?php endif; ?>

            <?php endforeach; ?>
        </div>
        <?php elseif (!empty($post['content'])): ?>
        <!-- Fallback sur content si pas de sections -->
        <div class="font-body text-base leading-relaxed text-ink/90">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>
        <?php endif; ?>

        <!-- ═══ LIKES -->
        <div class="flex items-center gap-4 mt-10 pt-6 border-t border-border">
            <form method="POST" action="<?= BASE_URL ?>/article/<?= htmlspecialchars($post['slug']) ?>/like">
                <input type="hidden" name="_csrf" value="<?= Csrf::generate() ?>">
                <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 border-2 font-body font-semibold text-sm transition-all
                               <?= ($userHasLiked ?? false) ? 'bg-accent text-paper border-accent' : 'border-border hover:border-accent hover:text-accent' ?>">
                    ♥ <?= $post['like_count'] ?? 0 ?> J'aime
                </button>
            </form>
            <a href="<?= BASE_URL ?>/"
               class="text-sm font-body text-muted hover:text-accent transition-colors">
                ← Retour au blog
            </a>
        </div>

        <!-- ═══ COMMENTAIRES -->
        <section class="mt-12">
            <h2 class="font-display text-xl font-bold text-ink border-b-2 border-ink pb-2 mb-6">
                Commentaires (<?= count($comments) ?>)
            </h2>

            <?php if (!empty($comments)): ?>
            <div class="space-y-6 mb-8">
                <?php foreach ($comments as $comment): ?>
                <div class="bg-white border border-border p-5">
                    <div class="flex items-center justify-between mb-3">
                        <strong class="font-body font-semibold text-ink text-sm">
                            <?= htmlspecialchars($comment['username']) ?>
                        </strong>
                        <time class="text-xs text-muted font-body">
                            <?= date('d F Y à H:i', strtotime($comment['created_at'])) ?>
                        </time>
                    </div>
                    <p class="font-body text-sm text-ink/80 leading-relaxed">
                        <?= nl2br(htmlspecialchars($comment['content'])) ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-muted font-body text-sm mb-8">Soyez le premier à commenter cet article.</p>
            <?php endif; ?>

            <!-- Formulaire commentaire -->
            <?php if (Auth::check()): ?>
            <div class="bg-white border border-border p-6">
                <h3 class="font-display text-base font-bold text-ink mb-4">Laisser un commentaire</h3>
                <form method="POST" action="<?= BASE_URL ?>/article/<?= htmlspecialchars($post['slug']) ?>/comment">
                    <input type="hidden" name="_csrf" value="<?= Csrf::generate() ?>">
                    <textarea name="content" rows="4" required
                              placeholder="Votre commentaire…"
                              class="w-full border border-border px-4 py-3 text-sm font-body text-ink bg-paper focus:outline-none focus:border-ink resize-none mb-4"></textarea>
                    <button type="submit"
                            class="px-6 py-2.5 bg-ink text-paper font-body font-semibold text-sm hover:bg-accent transition-colors">
                        Publier le commentaire
                    </button>
                </form>
            </div>
            <?php else: ?>
            <div class="bg-paper border border-border p-5 text-center">
                <p class="font-body text-sm text-muted mb-3">Connectez-vous pour laisser un commentaire.</p>
                <a href="<?= BASE_URL ?>/login"
                   class="inline-block px-5 py-2 bg-ink text-paper font-body font-semibold text-sm hover:bg-accent transition-colors">
                    Se connecter
                </a>
            </div>
            <?php endif; ?>
        </section>

    </article>

    <!-- ═══════════════ SIDEBAR -->
    <aside class="w-full lg:w-64 shrink-0">
        <div class="bg-ink text-paper p-5 sticky top-4">
            <h3 class="font-display text-base font-bold border-b border-paper/20 pb-2 mb-4">À propos</h3>
            <p class="text-sm font-body text-paper/60 leading-relaxed">
                Bienvenue à Angoulême est le blog local dédié à l'actualité de la ville et de la Charente.
            </p>
            <a href="<?= BASE_URL ?>/"
               class="inline-block mt-4 text-xs font-body font-semibold text-accent hover:underline">
                ← Tous les articles
            </a>
        </div>
    </aside>

</div>