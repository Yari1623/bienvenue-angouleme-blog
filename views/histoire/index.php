<?php $pageTitle = 'Histoire d\'Angoulême — Bienvenue à Angoulême'; ?>

<div class="max-w-4xl mx-auto space-y-10">

    <!-- Hero -->
    <div class="relative rounded-2xl overflow-hidden" style="min-height:300px;">
        <img src="<?= BASE_URL ?>/assets/images/panorama_angouleme.jpg"
             alt="Angoulême vue aérienne"
             class="w-full h-full object-cover absolute inset-0">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(13,25,45,.85),rgba(29,143,216,.5))"></div>
        <div class="relative z-10 px-8 py-14 text-center">
            <h2 class="font-display text-4xl font-black text-white mb-3">Histoire d'Angoulême</h2>
            <div class="h-1 w-24 mx-auto rounded-full mb-4" style="background:rgba(255,255,255,.5)"></div>
            <p class="text-white/80 max-w-xl mx-auto text-base" style="font-family:'Source Sans 3',sans-serif;">
                De l'oppidum gaulois à la capitale mondiale de la bande dessinée — vingt siècles d'histoire.
            </p>
        </div>
    </div>

    <!-- Frise chronologique -->
    <section>
        <h3 class="font-display text-2xl font-bold mb-6" style="color:var(--text);border-bottom:3px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;padding-bottom:.5rem;">
            Les grandes dates
        </h3>
        <div class="space-y-4">
            <?php
            $timeline = [
                ['date'=>'Ier siècle av. J.-C.','titre'=>'Origine gauloise','texte'=>'Angoulême naît sous le nom d\'<em>Iculisma</em>, oppidum gaulois des Santons, juché sur son promontoire rocheux dominant la Charente. Sa position stratégique en fait rapidement un lieu de passage et d\'échanges.'],
                ['date'=>'IVe siècle','titre'=>'Évêché chrétien','texte'=>'Angoulême devient le siège d\'un évêché. La cathédrale Saint-Pierre, dont la construction débute au XIIe siècle, témoigne encore aujourd\'hui de l\'importance religieuse de la cité.'],
                ['date'=>'XIIe siècle','titre'=>'Cathédrale Saint-Pierre','texte'=>'Construction de la célèbre cathédrale romane Saint-Pierre d\'Angoulême, chef-d\'œuvre de l\'art roman poitevin. Sa façade sculptée, comptant plus de 70 figures, est l\'une des plus remarquables de France.'],
                ['date'=>'XVIe siècle','titre'=>'Marguerite d\'Angoulême','texte'=>'Sœur de François Ier, Marguerite d\'Angoulême (1492-1549) illustre le rayonnement de la ville à la Renaissance. Poétesse et protectrice des artistes, elle incarne l\'humanisme français.'],
                ['date'=>'XIXe siècle','titre'=>'Révolution industrielle','texte'=>'Angoulême devient un centre industriel majeur grâce à la papeterie et l\'imprimerie, favorisées par la Charente. La ville s\'agrandit et se modernise, avec l\'arrivée du chemin de fer en 1852.'],
                ['date'=>'1974','titre'=>'Naissance du FIBD','texte'=>'Le premier Festival International de la Bande Dessinée d\'Angoulême est organisé. Il deviendra le plus grand festival de BD au monde, réunissant chaque année en janvier des centaines de milliers de visiteurs.'],
                ['date'=>'Aujourd\'hui','titre'=>'Ville d\'art et d\'histoire','texte'=>'Angoulême est officiellement labellisée "Ville d\'Art et d\'Histoire". Elle conjugue son riche patrimoine médiéval, sa tradition industrielle papetière et son rayonnement culturel autour de la bande dessinée.'],
            ];
            foreach($timeline as $i => $item): ?>
            <div class="flex gap-4">
                <!-- Ligne temporelle -->
                <div class="flex flex-col items-center shrink-0">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
                         style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
                        <?= $i+1 ?>
                    </div>
                    <?php if ($i < count($timeline)-1): ?>
                    <div class="w-0.5 flex-1 mt-1" style="background:linear-gradient(to bottom,#1d8fd8,transparent);min-height:2rem"></div>
                    <?php endif; ?>
                </div>
                <!-- Contenu -->
                <div class="surface rounded-xl p-5 flex-1 mb-2">
                    <div class="flex items-start justify-between gap-4 mb-2">
                        <h4 class="font-display text-base font-bold" style="color:var(--text)"><?= $item['titre'] ?></h4>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full text-white shrink-0"
                              style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);font-family:'Source Sans 3',sans-serif;">
                            <?= $item['date'] ?>
                        </span>
                    </div>
                    <p class="text-sm leading-relaxed" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                        <?= $item['texte'] ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Patrimoine -->
    <section>
        <h3 class="font-display text-2xl font-bold mb-6" style="color:var(--text);border-bottom:3px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;padding-bottom:.5rem;">
            Patrimoine remarquable
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <?php
            $patrimoine = [
                ['icon'=>'⛪','titre'=>'Cathédrale Saint-Pierre','desc'=>'Chef-d\'œuvre de l\'art roman (XIIe s.), sa façade sculptée est classée au patrimoine mondial de l\'UNESCO (chemins de Saint-Jacques).'],
                ['icon'=>'🏰','titre'=>'Hôtel de Ville','desc'=>'Construit dans les vestiges du château des Valois, il intègre le donjon de Lusignan (XIIIe s.) et la tour de Valois (XIVe s.).'],
                ['icon'=>'🎨','titre'=>'Cité Internationale de la BD','desc'=>'La CIBDI abrite un musée, une bibliothèque, un centre de conservation des œuvres de bande dessinée et un cinéma.'],
                ['icon'=>'🌊','titre'=>'Les Remparts','desc'=>'Les remparts médiévaux offrent une promenade panoramique de 2 km avec une vue exceptionnelle sur la vallée de la Charente.'],
            ];
            foreach($patrimoine as $p): ?>
            <div class="surface rounded-xl p-5 flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shrink-0"
                     style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
                    <?= $p['icon'] ?>
                </div>
                <div>
                    <h4 class="font-display text-base font-bold mb-1" style="color:var(--text)"><?= $p['titre'] ?></h4>
                    <p class="text-sm leading-relaxed" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;"><?= $p['desc'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- CTA -->
    <div class="surface rounded-xl p-6 text-center" style="border-top:3px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
        <h3 class="font-display text-xl font-bold mb-2" style="color:var(--text)">Envie d'en découvrir plus ?</h3>
        <p class="text-sm mb-4" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Explorez nos articles sur l'histoire et le patrimoine d'Angoulême.
        </p>
        <a href="<?= BASE_URL ?>/categorie/architecture-et-patrimoine" class="btn-primary inline-block mr-3">
            Articles Patrimoine →
        </a>
        <a href="<?= BASE_URL ?>/agenda" class="btn-outline inline-block">
            Voir l'agenda
        </a>
    </div>

</div>