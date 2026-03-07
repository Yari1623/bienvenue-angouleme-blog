<?php
// views/partials/post-card.php
// Variable attendue : $post (tableau avec les champs de l'article)
// Helper date FR (défini dans home/index.php et blog/index.php)
if (!function_exists('dateFr')) {
    function dateFr(string $date): string {
        $mois = ['','jan.','fév.','mar.','avr.','mai','juin','juil.','août','sep.','oct.','nov.','déc.'];
        $d = date_create($date);
        return date_format($d,'d') . ' ' . $mois[(int)date_format($d,'n')] . ' ' . date_format($d,'Y');
    }
}
?>
<article class="post-card flex flex-col" style="border-radius:.75rem;overflow:hidden;">

    <!-- Ligne 1 : Auteur (gauche) + Date (droite) -->
    <div class="flex items-center justify-between px-4 pt-4 pb-2">
        <span class="flex items-center gap-1.5 text-xs font-semibold" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            <span class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
                  style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
                <?= strtoupper(mb_substr($post['author_name'] ?? 'A', 0, 1)) ?>
            </span>
            <?= htmlspecialchars($post['author_name'] ?? 'Auteur') ?>
        </span>
        <span class="text-xs" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            <?= dateFr($post['created_at'] ?? date('Y-m-d')) ?>
        </span>
    </div>

    <!-- Ligne 2 : Image -->
    <div class="relative mx-4" style="aspect-ratio:16/9;border-radius:.5rem;overflow:hidden;background:var(--bg2);">
        <?php
        $imgSrc  = !empty($post['thumbnail']) ? htmlspecialchars($post['thumbnail']) : BASE_URL . '/assets/images/visuel_à_venir.jpg';
        $imgName = basename($imgSrc);
        $imgExt  = strtoupper(pathinfo($imgSrc, PATHINFO_EXTENSION));
        $imgPath = $_SERVER['DOCUMENT_ROOT'] . parse_url($imgSrc, PHP_URL_PATH);
        $imgSize = file_exists($imgPath) ? round(filesize($imgPath)/1024) . ' Ko' : '';
        $tooltip = $imgName . ($imgExt ? ' · '.$imgExt : '') . ($imgSize ? ' · '.$imgSize : '');
        ?>
        <img src="<?= $imgSrc ?>"
             alt="<?= htmlspecialchars($post['title']) ?>"
             title="<?= htmlspecialchars($tooltip) ?>"
             class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
    </div>

    <!-- Ligne 3 : Catégorie (gauche) + Lieu (droite) -->
    <div class="flex items-center justify-between px-4 pt-3">
        <?php if (!empty($post['category_name'])): ?>
        <a href="<?= BASE_URL ?>/categorie/<?= htmlspecialchars($post['category_slug'] ?? '') ?>"
           class="text-xs font-bold px-2.5 py-1 rounded-full text-white"
           style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);font-family:'Source Sans 3',sans-serif;">
            <?= htmlspecialchars($post['category_name']) ?>
        </a>
        <?php else: ?>
        <span></span>
        <?php endif; ?>
        <?php if (!empty($post['place_name'])): ?>
        <span class="text-xs px-2.5 py-1 rounded-full font-semibold"
              style="background:var(--bg2);color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            📍 <?= htmlspecialchars($post['place_name']) ?>
        </span>
        <?php else: ?>
        <span class="text-xs px-2.5 py-1 rounded-full font-semibold"
              style="background:var(--bg2);color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            📍 Lieu inconnu
        </span>
        <?php endif; ?>
    </div>

    <!-- Ligne 4 : Tags (3 min, 6 max) -->
    <?php if (!empty($post['tags'])): ?>
    <div class="flex flex-wrap gap-1.5 px-4 pt-3">
        <?php
        $allTags  = array_filter(array_map('trim', explode(',', $post['tags'])));
        $allTags  = array_values($allTags);
        // Compléter jusqu'à 3 si moins de 3 tags
        $defaults = ['Angoulême', 'Local', 'Blog', 'Charente', 'Actualité', 'Info'];
        $i = 0;
        while (count($allTags) < 3 && $i < count($defaults)) {
            if (!in_array($defaults[$i], $allTags)) $allTags[] = $defaults[$i];
            $i++;
        }
        // Limiter à 6
        $displayTags = array_slice($allTags, 0, 6);
        foreach ($displayTags as $tag): ?>
        <span class="text-xs px-2 py-0.5 rounded-full font-medium"
              style="background:var(--bg2);color:var(--text2);border:1px solid var(--border);font-family:'Source Sans 3',sans-serif;">
            #<?= htmlspecialchars(trim($tag)) ?>
        </span>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Ligne 5 : Titre -->
    <h3 class="font-display text-base font-bold px-4 pt-2 leading-snug" style="color:var(--text)">
        <a href="<?= BASE_URL ?>/article/<?= htmlspecialchars($post['slug']) ?>"
           class="hover:underline" style="color:var(--text)">
            <?= htmlspecialchars($post['title']) ?>
        </a>
    </h3>

    <!-- Ligne 5 : Début de l'article -->
    <p class="text-sm px-4 pt-2 leading-relaxed flex-1" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;display:-webkit-box;-webkit-line-clamp:2;line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
        <?= htmlspecialchars(strip_tags(mb_substr($post['content'] ?? '', 0, 120))) ?>…
    </p>

    <!-- Ligne 6 : Bouton lire la suite (gauche) -->
    <div class="px-4 pt-3">
        <a href="<?= BASE_URL ?>/article/<?= htmlspecialchars($post['slug']) ?>"
           class="inline-block px-4 py-1.5 rounded-full text-xs font-bold text-white transition-all hover:opacity-90 hover:-translate-y-px"
           style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);font-family:'Source Sans 3',sans-serif;">
            Lire l'article →
        </a>
    </div>

    <!-- Ligne 7 : Temps de lecture (gauche) + Commentaires (droite) -->
    <div class="flex items-center justify-between px-4 py-3 mt-2" style="border-top:1px solid var(--border)">
        <span class="text-xs" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            <?php if (!empty($post['reading_time'])): ?>
            ⏱ <?= $post['reading_time'] ?> min de lecture
            <?php else: ?>
            ⏱ 3 min de lecture
            <?php endif; ?>
        </span>
        <span class="text-xs" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            💬 <?= $post['comment_count'] ?? $post['comments_count'] ?? 0 ?> commentaire<?= ($post['comment_count'] ?? 0) > 1 ? 's' : '' ?>
        </span>
    </div>

</article>