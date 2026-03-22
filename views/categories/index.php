<?php
// views/categories/index.php
// PAGE PUBLIQUE /categories — catégories cliquables + lieux cliquables
// NE PAS CONFONDRE avec views/admin/categories/index.php (tableau de gestion admin)
$pageTitle = 'Catégories & Lieux — Bienvenue à Angoulême';
 
$catImages = [
    'actualites'               => 'Actualites.png',
    'animaux'                  => 'Animaux.png',
    'architecture-et-patrimoine'=> 'Architecture_Patrimoine.png',
    'commerce'                 => 'Commerce.png',
    'culture'                  => 'Culture.png',
    'divers'                   => 'Divers.png',
    'evenement'                => 'Evenement.png',
    'high-tech'                => 'High_tech.png',
    'musique'                  => 'Musique.png',
    'nature-et-environnement'  => 'Nature_Environnement.png',
    'social'                   => 'Social.png',
    'sports'                   => 'Sports.png',
];
 
$lieuImages = [
    'angouleme'          => 'angouleme.png',
    'grand-angouleme'    => 'grand-angouleme.png',
    'charente'           => 'charente.png',
    'poitou-charentes'   => 'poitou-charentes.png',
    'nouvelle-aquitaine' => 'nouvelle-aquitaine.png',
    'france'             => 'france.png',
    'europe'             => 'europe.png',
    'monde'              => 'monde.png',
];
?>
 
<div class="space-y-12">
 
    <div class="pb-4" style="border-bottom:2px solid var(--border)">
        <h2 class="font-display text-2xl font-black" style="color:var(--text)">Catégories & Lieux</h2>
        <p class="text-sm mt-1" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            Explorez les articles par thème ou par zone géographique
        </p>
    </div>
 
    <!-- CATÉGORIES -->
    <section>
        <div class="flex items-center gap-4 mb-6">
            <h3 class="font-display text-xl font-bold" style="color:var(--text)">Thèmes</h3>
            <div class="flex-1 h-px" style="background:linear-gradient(to right,#1d8fd8,transparent)"></div>
        </div>
 
        <?php if (empty($categories)): ?>
        <p class="text-sm" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">Aucune catégorie.</p>
        <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <?php foreach ($categories as $cat):
                $img = $catImages[$cat['slug']] ?? null;
            ?>
            <a href="<?= BASE_URL ?>/categorie/<?= htmlspecialchars($cat['slug']) ?>"
               class="group flex flex-col items-center text-center rounded-xl p-4 transition-all"
               style="background:var(--surface);border:1px solid var(--border);text-decoration:none;"
               onmouseover="this.style.borderColor='#1d8fd8';this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 24px rgba(29,143,216,.15)'"
               onmouseout="this.style.borderColor='var(--border)';this.style.transform='';this.style.boxShadow=''">
 
                <div class="w-16 h-16 rounded-full overflow-hidden mb-3 flex items-center justify-center"
                     style="background:var(--bg2);">
                    <?php if ($img): ?>
                    <img src="<?= BASE_URL ?>/assets/categories/<?= $img ?>"
                         alt="<?= htmlspecialchars($cat['name']) ?>"
                         class="w-full h-full object-cover">
                    <?php else: ?>
                    <span class="text-2xl">🏷</span>
                    <?php endif; ?>
                </div>
 
                <span class="text-sm font-semibold leading-tight mb-1"
                      style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    <?= htmlspecialchars($cat['name']) ?>
                </span>
 
                <span class="text-xs px-2 py-0.5 rounded-full"
                      style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);color:white;font-family:'Source Sans 3',sans-serif;">
                    <?= $cat['post_count'] ?? 0 ?> article<?= ($cat['post_count'] ?? 0) > 1 ? 's' : '' ?>
                </span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>
 
    <!-- LIEUX -->
    <section>
        <div class="flex items-center gap-4 mb-6">
            <h3 class="font-display text-xl font-bold" style="color:var(--text)">Zones géographiques</h3>
            <div class="flex-1 h-px" style="background:linear-gradient(to right,#1d8fd8,transparent)"></div>
        </div>
 
        <?php if (empty($places)): ?>
        <p class="text-sm" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">Aucun lieu.</p>
        <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-4 lg:grid-cols-8 gap-4">
            <?php foreach ($places as $place):
                $img = $lieuImages[$place['slug']] ?? null;
            ?>
            <a href="<?= BASE_URL ?>/blog?lieu=<?= htmlspecialchars($place['slug']) ?>"
               class="group flex flex-col items-center text-center rounded-xl p-4 transition-all"
               style="background:var(--surface);border:1px solid var(--border);text-decoration:none;"
               onmouseover="this.style.borderColor='#22d3ee';this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 24px rgba(34,211,238,.15)'"
               onmouseout="this.style.borderColor='var(--border)';this.style.transform='';this.style.boxShadow=''">
 
                <div class="w-14 h-14 rounded-full overflow-hidden mb-2 flex items-center justify-center"
                     style="background:var(--bg2);">
                    <?php if ($img): ?>
                    <img src="<?= BASE_URL ?>/assets/lieux/<?= $img ?>"
                         alt="<?= htmlspecialchars($place['name']) ?>"
                         class="w-full h-full object-cover">
                    <?php else: ?>
                    <span class="text-xl">📍</span>
                    <?php endif; ?>
                </div>
 
                <span class="text-xs font-semibold leading-tight"
                      style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    <?= htmlspecialchars($place['name']) ?>
                </span>
 
                <span class="text-xs mt-1" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                    <?= $place['post_count'] ?? 0 ?> article<?= ($place['post_count'] ?? 0) > 1 ? 's' : '' ?>
                </span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>
 
</div>