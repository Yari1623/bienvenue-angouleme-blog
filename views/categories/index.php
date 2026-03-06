<?php
// views/categories/index.php
$pageTitle = 'Catégories & Lieux — Bienvenue à Angoulême';
?>

<div class="space-y-12">

    <!-- Hero -->
    <div class="text-center">
        <h2 class="font-display text-3xl font-black mb-2" style="color:var(--text)">Explorer le blog</h2>
        <div class="h-1 w-24 mx-auto rounded-full mb-4" style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)"></div>
        <p class="text-sm max-w-xl mx-auto" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Trouvez les articles qui vous intéressent en naviguant par thème ou par lieu.
        </p>
    </div>

    <!-- ═══ CATÉGORIES -->
    <section>
        <div class="flex items-center gap-4 mb-6">
            <h2 class="font-display text-2xl font-bold" style="color:var(--text)">Par thème</h2>
            <div class="flex-1 h-px" style="background:linear-gradient(to right,#1d8fd8,transparent)"></div>
            <span class="text-sm" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                <?= count($categories ?? []) ?> catégories
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php
            $catIcons = [
                'actualites'             => ['icon'=>'📰', 'color'=>'#1d8fd8'],
                'animaux'                => ['icon'=>'🐾', 'color'=>'#16a34a'],
                'architecture-et-patrimoine' => ['icon'=>'🏛️', 'color'=>'#d97706'],
                'commerce'               => ['icon'=>'🛍️', 'color'=>'#7c3aed'],
                'culture'                => ['icon'=>'🎭', 'color'=>'#db2777'],
                'divers'                 => ['icon'=>'💡', 'color'=>'#6b7280'],
                'evenement'              => ['icon'=>'🎉', 'color'=>'#dc2626'],
                'high-tech'              => ['icon'=>'💻', 'color'=>'#0891b2'],
                'musique'                => ['icon'=>'🎵', 'color'=>'#7c3aed'],
                'nature-et-environnement'=> ['icon'=>'🌿', 'color'=>'#16a34a'],
                'social'                 => ['icon'=>'🤝', 'color'=>'#ea580c'],
                'sport'                  => ['icon'=>'⚽', 'color'=>'#1d8fd8'],
            ];
            foreach($categories ?? [] as $cat):
                $meta  = $catIcons[$cat['slug']] ?? ['icon'=>'📌', 'color'=>'#1d8fd8'];
                $count = $cat['post_count'] ?? 0;
            ?>
            <a href="<?= BASE_URL ?>/categorie/<?= htmlspecialchars($cat['slug']) ?>"
               class="group surface rounded-xl p-5 flex flex-col items-center gap-3 text-center transition-all hover:-translate-y-1"
               style="transition:transform .2s,box-shadow .2s,border-color .2s;"
               onmouseover="this.style.borderColor='<?= $meta['color'] ?>';this.style.boxShadow='0 8px 24px <?= $meta['color'] ?>30'"
               onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow='none'">

                <!-- Icône avec fond coloré -->
                <div class="w-14 h-14 rounded-full flex items-center justify-center text-2xl shadow-sm"
                     style="background:<?= $meta['color'] ?>18;border:2px solid <?= $meta['color'] ?>40">
                    <?= $meta['icon'] ?>
                </div>

                <!-- Nom -->
                <div>
                    <h3 class="font-semibold text-sm leading-tight" style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                        <?= htmlspecialchars($cat['name']) ?>
                    </h3>
                    <!-- Compteur articles -->
                    <span class="text-xs mt-1 inline-block px-2 py-0.5 rounded-full font-semibold"
                          style="background:<?= $meta['color'] ?>18;color:<?= $meta['color'] ?>;font-family:'Source Sans 3',sans-serif;">
                        <?= $count ?> article<?= $count > 1 ? 's' : '' ?>
                    </span>
                </div>

                <!-- Flèche hover -->
                <span class="text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity -mt-1"
                      style="color:<?= $meta['color'] ?>;font-family:'Source Sans 3',sans-serif;">
                    Voir les articles →
                </span>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ═══ LIEUX -->
    <section>
        <div class="flex items-center gap-4 mb-6">
            <h2 class="font-display text-2xl font-bold" style="color:var(--text)">Par lieu</h2>
            <div class="flex-1 h-px" style="background:linear-gradient(to right,#1d8fd8,transparent)"></div>
            <span class="text-sm" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                <?= count($places ?? []) ?> lieux
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php
            $placeIcons = [
                'angouleme'         => '🏰',
                'charente'          => '🌊',
                'grand-angouleme'   => '🏙️',
                'nouvelle-aquitaine'=> '🌻',
                'poitou-charentes'  => '🗺️',
                'france'            => '🇫🇷',
                'europe'            => '🇪🇺',
                'monde'             => '🌍',
            ];
            foreach($places ?? [] as $place):
                $icon  = $placeIcons[$place['slug']] ?? '📍';
                $count = $place['post_count'] ?? 0;
            ?>
            <div class="surface rounded-xl p-4 flex items-center gap-3"
                 style="border-left:3px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
                <span class="text-2xl shrink-0"><?= $icon ?></span>
                <div>
                    <h3 class="font-semibold text-sm" style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                        <?= htmlspecialchars($place['name']) ?>
                    </h3>
                    <span class="text-xs" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                        <?= $count ?> article<?= $count > 1 ? 's' : '' ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <p class="text-xs mt-3" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            💡 Les lieux permettent de situer géographiquement les articles. Le filtre par lieu sera disponible prochainement.
        </p>
    </section>

    <!-- ═══ RECHERCHE RAPIDE -->
    <section class="surface rounded-xl p-6 text-center">
        <h3 class="font-display text-xl font-bold mb-2" style="color:var(--text)">Vous cherchez quelque chose ?</h3>
        <p class="text-sm mb-4" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Parcourez tous les articles ou utilisez les catégories ci-dessus pour affiner votre recherche.
        </p>
        <a href="<?= BASE_URL ?>/blog" class="btn-primary inline-block">
            Voir tous les articles →
        </a>
    </section>

</div>