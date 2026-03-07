<?php
// views/categories/index.php
$pageTitle = 'Catégories & Lieux — Bienvenue à Angoulême';

$categoriesData = [
    'actualites'              => ['img'=>'Actualités.png',               'desc'=>'Toute l\'actualité locale d\'Angoulême et de la Charente : politique, économie, société.'],
    'animaux'                 => ['img'=>'Animaux.png',                  'desc'=>'Actualités, conseils et bons plans autour de la vie animale dans notre région.'],
    'architecture-et-patrimoine'=>['img'=>'Architecture & Patrimoine.png','desc'=>'Découvrez l\'architecture remarquable et le riche patrimoine historique d\'Angoulême.'],
    'commerce'                => ['img'=>'Commerce.png',                 'desc'=>'Commerces locaux, ouvertures, fermetures et bons plans shopping en Charente.'],
    'culture'                 => ['img'=>'Culture.png',                  'desc'=>'Expositions, théâtre, cinéma, BD et toute la vie culturelle angoumoisine.'],
    'divers'                  => ['img'=>'Divers.png',                   'desc'=>'Tout ce qui ne rentre pas dans les autres catégories mais mérite d\'être partagé.'],
    'evenement'               => ['img'=>'Evénement.png',                'desc'=>'Fêtes, festivals, marchés, concerts — tous les événements à ne pas manquer.'],
    'high-tech'               => ['img'=>'High tech.png',                'desc'=>'Innovation, numérique, nouvelles technologies et startups locales.'],
    'musique'                 => ['img'=>'Musique.png',                  'desc'=>'Concerts, artistes locaux, festivals de musique et actualités musicales.'],
    'nature-et-environnement' => ['img'=>'Nature & environnement.png',   'desc'=>'Environnement, biodiversité, randonnées et nature en Charente.'],
    'social'                  => ['img'=>'Social.png',                   'desc'=>'Associations, solidarité, vie communautaire et initiatives sociales locales.'],
    'sport'                   => ['img'=>'Sports.png',                   'desc'=>'Clubs sportifs, résultats, événements et actualités du sport en Charente.'],
];

$placesData = [
    'angouleme'          => ['img'=>'angouleme.png',          'nom'=>'Angoulême',           'desc'=>'Le cœur de la Charente, perché sur son éperon rocheux.'],
    'grand-angouleme'    => ['img'=>'grand-angouleme.png',    'nom'=>'Grand Angoulême',      'desc'=>'La communauté d\'agglomération regroupant 15 communes.'],
    'charente'           => ['img'=>'charente.png',           'nom'=>'Charente',             'desc'=>'Le département de la Charente et toutes ses communes.'],
    'poitou-charentes'   => ['img'=>'poitou-charentes.png',   'nom'=>'Poitou-Charentes',     'desc'=>'L\'ancienne région historique du grand ouest français.'],
    'nouvelle-aquitaine' => ['img'=>'nouvelle-aquitaine.png', 'nom'=>'Nouvelle-Aquitaine',   'desc'=>'La grande région administrative dont fait partie la Charente.'],
    'france'             => ['img'=>'france.png',             'nom'=>'France',               'desc'=>'Actualités et événements à l\'échelle nationale.'],
    'europe'             => ['img'=>'europe.png',             'nom'=>'Europe',               'desc'=>'Ce qui se passe en Europe et impacte notre territoire.'],
    'monde'              => ['img'=>'monde.png',              'nom'=>'Monde',                'desc'=>'Les grands événements mondiaux vus depuis Angoulême.'],
];
?>

<div class="space-y-12">

    <!-- Hero -->
    <div class="text-center">
        <h2 class="font-display text-3xl font-black mb-2" style="color:var(--text)">Explorer le blog</h2>
        <div class="h-1 w-24 mx-auto rounded-full mb-4" style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)"></div>
        <p class="text-sm max-w-xl mx-auto" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Naviguez par thème ou par zone géographique pour trouver les articles qui vous intéressent.
        </p>
    </div>

    <!-- ═══ CATÉGORIES -->
    <section>
        <div class="flex items-center gap-4 mb-6">
            <h2 class="font-display text-2xl font-bold" style="color:var(--text)">Thèmes</h2>
            <div class="flex-1 h-px" style="background:linear-gradient(to right,#1d8fd8,transparent)"></div>
            <span class="text-sm" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                <?= count($categories ?? []) ?> catégories
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            <?php foreach($categories ?? [] as $cat):
                $meta  = $categoriesData[$cat['slug']] ?? ['img'=>'visuel_à_venir.jpg','desc'=>''];
                $count = $cat['post_count'] ?? 0;
                $imgSrc = BASE_URL . '/assets/images/' . $meta['img'];
            ?>
            <a href="<?= BASE_URL ?>/categorie/<?= htmlspecialchars($cat['slug']) ?>"
               class="group surface rounded-xl overflow-hidden flex flex-col transition-all hover:-translate-y-1"
               style="transition:transform .2s,box-shadow .2s,border-color .2s;"
               onmouseover="this.style.borderColor='#1d8fd8';this.style.boxShadow='0 8px 24px rgba(29,143,216,.2)'"
               onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow='none'">

                <!-- Image -->
                <div class="relative" style="aspect-ratio:4/3;background:var(--bg2);overflow:hidden;">
                    <img src="<?= $imgSrc ?>"
                         alt="<?= htmlspecialchars($cat['name']) ?>"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         onerror="this.src='<?= BASE_URL ?>/assets/images/visuel_à_venir.jpg'">
                    <!-- Badge nb articles -->
                    <span class="absolute top-2 right-2 px-2 py-0.5 rounded-full text-xs font-bold text-white"
                          style="background:rgba(0,0,0,.5);backdrop-filter:blur(4px);font-family:'Source Sans 3',sans-serif;">
                        <?= $count ?> article<?= $count>1?'s':'' ?>
                    </span>
                </div>

                <!-- Texte -->
                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="font-display text-base font-bold mb-1" style="color:var(--text)">
                        <?= htmlspecialchars($cat['name']) ?>
                    </h3>
                    <p class="text-xs leading-relaxed flex-1" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                        <?= $meta['desc'] ?>
                    </p>
                    <span class="text-xs font-bold mt-3 opacity-0 group-hover:opacity-100 transition-opacity"
                          style="color:#1d8fd8;font-family:'Source Sans 3',sans-serif;">
                        Voir les articles →
                    </span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ═══ LIEUX -->
    <section>
        <div class="flex items-center gap-4 mb-6">
            <h2 class="font-display text-2xl font-bold" style="color:var(--text)">Lieux</h2>
            <div class="flex-1 h-px" style="background:linear-gradient(to right,#1d8fd8,transparent)"></div>
            <span class="text-sm" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                <?= count($places ?? []) ?> zones géographiques
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            <?php foreach($places ?? [] as $place):
                $meta   = $placesData[$place['slug']] ?? ['img'=>'visuel_à_venir.jpg','nom'=>$place['name'],'desc'=>''];
                $count  = $place['post_count'] ?? 0;
                $imgSrc = BASE_URL . '/assets/images/' . $meta['img'];
            ?>
            <div class="surface rounded-xl overflow-hidden flex flex-col">
                <!-- Carte géographique -->
                <div class="relative flex items-center justify-center p-4" style="background:var(--bg2);min-height:120px;">
                    <img src="<?= $imgSrc ?>"
                         alt="<?= htmlspecialchars($meta['nom']) ?>"
                         class="h-24 object-contain"
                         onerror="this.src='<?= BASE_URL ?>/assets/images/visuel_à_venir.jpg'">
                </div>
                <!-- Infos -->
                <div class="p-4 flex-1 flex flex-col" style="border-top:3px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
                    <h3 class="font-display text-sm font-bold mb-1" style="color:var(--text)">
                        <?= htmlspecialchars($meta['nom']) ?>
                    </h3>
                    <p class="text-xs leading-relaxed flex-1" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                        <?= $meta['desc'] ?>
                    </p>
                    <span class="text-xs mt-2 font-semibold" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                        <?= $count ?> article<?= $count>1?'s':'' ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <p class="text-xs mt-3" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            💡 Le filtre par lieu sera disponible prochainement depuis la page Blog.
        </p>
    </section>

    <!-- CTA -->
    <section class="surface rounded-xl p-6 text-center">
        <h3 class="font-display text-xl font-bold mb-2" style="color:var(--text)">Prêt à explorer ?</h3>
        <p class="text-sm mb-4" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Parcourez tous les articles ou utilisez les filtres de la page Blog.
        </p>
        <a href="<?= BASE_URL ?>/blog" class="btn-primary inline-block">Voir tous les articles →</a>
    </section>

</div>s