<?php
use App\Core\Auth;
use App\Core\Csrf;

$pageTitle = htmlspecialchars($post['title']) . ' — Bienvenue à Angoulême';

if (!function_exists('dateFr')) {
    function dateFr(string $date): string {
        $mois = ['','janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
        $d = date_create($date);
        return date_format($d,'d') . ' ' . $mois[(int)date_format($d,'n')] . ' ' . date_format($d,'Y');
    }
}
?>

<div class="max-w-3xl mx-auto space-y-10">

<!-- ═══════════════════════════════════════════════
     EN-TÊTE DE L'ARTICLE
════════════════════════════════════════════════ -->
<header class="space-y-4">

    <!-- Catégorie + Lieu -->
    <div class="flex flex-wrap items-center gap-2">
        <?php if (!empty($post['category_name'])): ?>
        <a href="<?= BASE_URL ?>/categorie/<?= htmlspecialchars($post['category_slug'] ?? '') ?>"
           class="px-3 py-1 rounded-full text-xs font-bold text-white"
           style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);font-family:'Source Sans 3',sans-serif;">
            <?= htmlspecialchars($post['category_name']) ?>
        </a>
        <?php endif; ?>
        <?php if (!empty($post['place_name'])): ?>
        <span class="px-3 py-1 rounded-full text-xs font-semibold"
              style="background:var(--bg2);color:var(--text2);border:1px solid var(--border);font-family:'Source Sans 3',sans-serif;">
            📍 <?= htmlspecialchars($post['place_name']) ?>
        </span>
        <?php endif; ?>
        <?php if (!empty($post['reading_time'])): ?>
        <span class="px-3 py-1 rounded-full text-xs font-semibold"
              style="background:var(--bg2);color:var(--text2);border:1px solid var(--border);font-family:'Source Sans 3',sans-serif;">
            ⏱ <?= $post['reading_time'] ?> min de lecture
        </span>
        <?php endif; ?>
    </div>

    <!-- Titre -->
    <h1 class="font-display text-3xl md:text-4xl font-black leading-tight" style="color:var(--text)">
        <?= htmlspecialchars($post['title']) ?>
    </h1>

    <!-- Auteur + Date + Partage -->
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0"
                 style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
                <?= strtoupper(mb_substr($post['author_name'] ?? 'A', 0, 1)) ?>
            </div>
            <div>
                <div class="text-sm font-semibold" style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    <?= htmlspecialchars($post['author_name'] ?? 'Auteur inconnu') ?>
                </div>
                <div class="text-xs" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                    <?= dateFr($post['created_at'] ?? date('Y-m-d')) ?>
                </div>
            </div>
        </div>

        <!-- Boutons partage -->
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold mr-1" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">Partager</span>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>"
               target="_blank" rel="noopener"
               class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold transition-all hover:scale-110"
               style="background:#1877f2;" title="Partager sur Facebook">f</a>
            <a href="https://twitter.com/intent/tweet?url=<?= urlencode((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>&text=<?= urlencode($post['title']) ?>"
               target="_blank" rel="noopener"
               class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold transition-all hover:scale-110"
               style="background:#000;" title="Partager sur X">𝕏</a>
            <button onclick="navigator.clipboard.writeText(window.location.href).then(()=>showToast('Lien copié !'))"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-xs transition-all hover:scale-110"
                    style="background:var(--bg2);color:var(--text2);border:1px solid var(--border);" title="Copier le lien">🔗</button>
        </div>
    </div>
</header>

<!-- ═══════════════════════════════════════════════
     IMAGE PRINCIPALE
════════════════════════════════════════════════ -->
<?php
$thumb = !empty($post['thumbnail'])
    ? htmlspecialchars($post['thumbnail'])
    : BASE_URL . '/assets/images/visuel_à_venir.jpg';
?>
<div class="rounded-2xl overflow-hidden" style="aspect-ratio:16/9;background:var(--bg2);">
    <img src="<?= $thumb ?>"
         alt="<?= htmlspecialchars($post['title']) ?>"
         class="w-full h-full object-cover">
</div>

<!-- ═══════════════════════════════════════════════
     CONTENU DE L'ARTICLE
════════════════════════════════════════════════ -->
<div class="article-body surface rounded-2xl p-6 md:p-10 space-y-5"
     style="font-family:'Source Sans 3',sans-serif;font-size:1rem;line-height:1.8;color:var(--text);">
    <?php
    // Rendu du contenu : si le contenu contient du HTML on l'affiche tel quel,
    // sinon on gère les sauts de ligne et on crée des paragraphes
    $rawContent = $post['content'] ?? '';
    if (strip_tags($rawContent) === $rawContent) {
        // Contenu texte brut → on crée des paragraphes
        $paragraphes = array_filter(explode("\n\n", $rawContent));
        foreach ($paragraphes as $para) {
            echo '<p>' . nl2br(htmlspecialchars(trim($para))) . '</p>';
        }
    } else {
        // Contenu HTML → affichage direct
        echo $rawContent;
    }
    ?>
</div>

<!-- ═══════════════════════════════════════════════
     PIED D'ARTICLE : auteur, date, catégorie, lieu, tags, likes
════════════════════════════════════════════════ -->
<div class="surface rounded-2xl p-6 space-y-4">

    <!-- Ligne auteur / date / likes -->
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-full flex items-center justify-center text-white font-bold shrink-0"
                 style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
                <?= strtoupper(mb_substr($post['author_name'] ?? 'A', 0, 1)) ?>
            </div>
            <div>
                <div class="text-sm font-bold" style="color:var(--text)"><?= htmlspecialchars($post['author_name'] ?? '') ?></div>
                <div class="text-xs" style="color:var(--muted)"><?= dateFr($post['created_at'] ?? date('Y-m-d')) ?></div>
            </div>
        </div>

        <!-- Like -->
        <?php if (Auth::check()): ?>
        <form method="POST" action="<?= BASE_URL ?>/article/<?= $post['slug'] ?>/like" style="display:inline;">
            <input type="hidden" name="_csrf" value="<?= Csrf::generate() ?>">
            <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold transition-all hover:scale-105"
                    style="background:var(--bg2);border:1px solid var(--border);color:var(--text2);">
                <?= ($post['user_has_liked'] ?? false) ? '❤️' : '🤍' ?>
                <span><?= $post['like_count'] ?? 0 ?> j'aime</span>
            </button>
        </form>
        <?php else: ?>
        <span class="flex items-center gap-2 px-4 py-2 rounded-full text-sm"
              style="background:var(--bg2);border:1px solid var(--border);color:var(--muted);">
            🤍 <?= $post['like_count'] ?? 0 ?> j'aime
        </span>
        <?php endif; ?>
    </div>

    <!-- Catégorie + lieu -->
    <div class="flex flex-wrap gap-2" style="border-top:1px solid var(--border);padding-top:1rem;">
        <?php if (!empty($post['category_name'])): ?>
        <a href="<?= BASE_URL ?>/categorie/<?= htmlspecialchars($post['category_slug'] ?? '') ?>"
           class="px-3 py-1 rounded-full text-xs font-bold text-white"
           style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
            <?= htmlspecialchars($post['category_name']) ?>
        </a>
        <?php endif; ?>
        <?php if (!empty($post['place_name'])): ?>
        <span class="px-3 py-1 rounded-full text-xs font-semibold"
              style="background:var(--bg2);color:var(--text2);border:1px solid var(--border);">
            📍 <?= htmlspecialchars($post['place_name']) ?>
        </span>
        <?php endif; ?>
    </div>

    <!-- Tags -->
    <?php if (!empty($post['tags'])): ?>
    <div class="flex flex-wrap gap-2">
        <?php
        $tags = array_filter(array_map('trim', explode(',', $post['tags'])));
        foreach (array_slice($tags, 0, 8) as $tag):
        ?>
        <span class="px-3 py-1 rounded-full text-xs font-medium"
              style="background:var(--bg2);color:var(--text2);border:1px solid var(--border);">
            #<?= htmlspecialchars($tag) ?>
        </span>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>

<!-- ═══════════════════════════════════════════════
     COMMENTAIRES
════════════════════════════════════════════════ -->
<section class="space-y-6">

    <h2 class="font-display text-2xl font-bold" style="color:var(--text)">
        💬 Commentaires
        <?php if (!empty($comments)): ?>
        <span class="text-base font-normal ml-2" style="color:var(--muted)">(<?= count($comments) ?>)</span>
        <?php endif; ?>
    </h2>

    <?php if (!empty($comments)): ?>
    <div class="space-y-4">
        <?php foreach ($comments as $i => $comment): ?>
        <div class="flex gap-4" style="padding-left:<?= $i % 3 * 16 ?>px;">
            <!-- Avatar -->
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0 self-start mt-1"
                 style="background:linear-gradient(135deg,
                    <?= ['#1d8fd8,#22d3ee','#3fb950,#22d3ee','#bc8cff,#1d8fd8','#d29922,#f85149'][$i % 4] ?>)">
                <?= strtoupper(mb_substr($comment['username'] ?? 'A', 0, 1)) ?>
            </div>
            <!-- Bulle -->
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-sm font-bold" style="color:var(--text)"><?= htmlspecialchars($comment['username'] ?? 'Anonyme') ?></span>
                    <span class="text-xs" style="color:var(--muted)"><?= dateFr($comment['created_at']) ?></span>
                </div>
                <div class="p-4 rounded-xl rounded-tl-none text-sm leading-relaxed"
                     style="background:var(--bg2);border:1px solid var(--border);color:var(--text2);">
                    <?= nl2br(htmlspecialchars($comment['content'])) ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="surface rounded-xl p-8 text-center" style="color:var(--muted)">
        <p class="text-4xl mb-3">💬</p>
        <p class="font-semibold" style="color:var(--text)">Aucun commentaire pour le moment</p>
        <p class="text-sm mt-1">Soyez le premier à donner votre avis !</p>
    </div>
    <?php endif; ?>

    <!-- ── Formulaire commentaire (connecté uniquement) -->
    <?php if (Auth::check()): ?>
    <div class="surface rounded-2xl p-6 space-y-4">
        <h3 class="font-display text-lg font-bold" style="color:var(--text)">Laisser un commentaire</h3>
        <form method="POST" action="<?= BASE_URL ?>/article/<?= $post['slug'] ?>/comment" class="space-y-4">
            <input type="hidden" name="_csrf" value="<?= Csrf::generate() ?>">
            <textarea name="content" rows="4" required
                      placeholder="Partagez votre avis sur cet article…"
                      class="w-full rounded-xl p-4 text-sm resize-y"
                      style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;min-height:100px;transition:border-color .2s;"
                      onfocus="this.style.borderColor='#1d8fd8'"
                      onblur="this.style.borderColor='var(--border)'"></textarea>
            <div class="flex justify-end">
                <button type="submit" class="btn-primary px-6 py-2.5">
                    Publier le commentaire →
                </button>
            </div>
        </form>
        <p class="text-xs" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            ℹ️ Votre commentaire sera visible après modération par l'équipe.
        </p>
    </div>
    <?php else: ?>
    <div class="surface rounded-2xl p-6 text-center">
        <p class="text-sm mb-3" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Vous devez être connecté pour laisser un commentaire.
        </p>
        <a href="<?= BASE_URL ?>/login" class="btn-primary inline-block px-6 py-2.5">
            Se connecter →
        </a>
    </div>
    <?php endif; ?>

</section>

</div>

<!-- Toast copie lien -->
<div id="toast" class="fixed bottom-6 right-6 px-4 py-2 rounded-xl text-white text-sm font-semibold opacity-0 pointer-events-none transition-all duration-300"
     style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);z-index:9999;">
    Lien copié !
</div>

<script>
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.style.opacity = '1';
    t.style.transform = 'translateY(-8px)';
    setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateY(0)'; }, 2500);
}
</script>

<style>
/* ── Styles typographiques pour le corps de l'article ── */
.article-body h2 {
    font-family: 'Lexend', sans-serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text);
    margin-top: 2rem;
    margin-bottom: .75rem;
    padding-bottom: .4rem;
    border-bottom: 2px solid;
    border-image: linear-gradient(135deg,#1d8fd8,#22d3ee) 1;
}
.article-body h3 {
    font-family: 'Lexend', sans-serif;
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text);
    margin-top: 1.5rem;
    margin-bottom: .5rem;
}
.article-body p { margin-bottom: .75rem; }
.article-body ul, .article-body ol {
    padding-left: 1.5rem;
    margin-bottom: .75rem;
    color: var(--text2);
}
.article-body ul { list-style: disc; }
.article-body ol { list-style: decimal; }
.article-body li { margin-bottom: .3rem; }
.article-body blockquote {
    margin: 1.5rem 0;
    padding: 1.25rem 1.5rem;
    border-left: 4px solid #1d8fd8;
    border-radius: 0 .75rem .75rem 0;
    background: var(--bg2);
    font-style: italic;
    font-size: 1.05rem;
    color: var(--text2);
}
.article-body blockquote strong {
    display: block;
    margin-top: .75rem;
    font-style: normal;
    font-size: .85rem;
    color: var(--muted);
}
.article-body img {
    width: 100%;
    border-radius: .75rem;
    margin: 1rem 0;
    object-fit: cover;
}
.article-body a {
    color: #1d8fd8;
    text-decoration: underline;
    text-underline-offset: 3px;
}
.article-body a:hover { color: #22d3ee; }
.article-body strong { color: var(--text); font-weight: 700; }
.article-body em { font-style: italic; color: var(--text2); }
.article-body hr {
    border: none;
    border-top: 1px solid var(--border);
    margin: 2rem 0;
}
</style>