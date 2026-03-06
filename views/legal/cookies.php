<?php $pageTitle = 'Politique des cookies — Bienvenue à Angoulême'; ?>

<div class="max-w-3xl mx-auto space-y-8">

    <div class="text-center">
        <h2 class="font-display text-3xl font-black mb-2" style="color:var(--text)">Politique des cookies 🍪</h2>
        <div class="h-1 w-24 mx-auto rounded-full" style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)"></div>
        <p class="text-xs mt-3" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">Dernière mise à jour : <?= date('d/m/Y') ?></p>
    </div>

    <!-- Qu'est-ce qu'un cookie -->
    <div class="surface rounded-xl p-6">
        <h3 class="font-display text-lg font-bold mb-3 pb-2" style="color:var(--text);border-bottom:2px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
            Qu'est-ce qu'un cookie ?
        </h3>
        <p class="text-sm leading-relaxed" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Un cookie est un petit fichier texte déposé sur votre appareil lors de la visite d'un site web.
            Il permet au site de mémoriser vos actions et préférences (connexion, thème, langue…) pendant une durée déterminée,
            afin que vous n'ayez pas à les reconfigurer à chaque visite.
        </p>
    </div>

    <!-- Tableau des cookies -->
    <div class="surface rounded-xl overflow-hidden">
        <div class="p-5" style="border-bottom:1px solid var(--border)">
            <h3 class="font-display text-lg font-bold" style="color:var(--text)">Cookies utilisés sur ce site</h3>
        </div>
        <?php
        $cookies = [
            ['nom'=>'PHPSESSID',      'type'=>'Obligatoire', 'duree'=>'Session',  'finalite'=>'Session PHP — maintien de la connexion et des messages flash',       'color'=>'#16a34a'],
            ['nom'=>'bwa_theme',      'type'=>'Fonctionnel', 'duree'=>'1 an',     'finalite'=>'Mémorise votre préférence de thème (clair / sombre)',                 'color'=>'#1d8fd8'],
            ['nom'=>'bwa_cookies_choice','type'=>'Obligatoire','duree'=>'1 an',   'finalite'=>'Enregistre votre choix concernant les cookies',                       'color'=>'#16a34a'],
            ['nom'=>'YouTube',        'type'=>'Optionnel',   'duree'=>'Variable', 'finalite'=>'Déposé si vous autorisez les vidéos YouTube intégrées',               'color'=>'#dc2626'],
            ['nom'=>'Vimeo',          'type'=>'Optionnel',   'duree'=>'Variable', 'finalite'=>'Déposé si vous autorisez les vidéos Vimeo intégrées',                 'color'=>'#dc2626'],
            ['nom'=>'Dailymotion',    'type'=>'Optionnel',   'duree'=>'Variable', 'finalite'=>'Déposé si vous autorisez les vidéos Dailymotion intégrées',           'color'=>'#dc2626'],
        ];
        foreach ($cookies as $i => $c): ?>
        <div class="p-4 flex flex-col md:flex-row md:items-center gap-3 <?= $i > 0 ? 'border-t' : '' ?>"
             style="<?= $i > 0 ? 'border-color:var(--border)' : '' ?>">
            <div class="w-32 shrink-0">
                <span class="px-2 py-0.5 rounded-full text-xs font-bold text-white"
                      style="background:<?= $c['color'] ?>;font-family:'Source Sans 3',sans-serif;">
                    <?= $c['type'] ?>
                </span>
            </div>
            <div class="flex-1">
                <p class="font-bold text-sm" style="color:var(--text);font-family:'Source Sans 3',sans-serif;"><?= $c['nom'] ?></p>
                <p class="text-xs mt-0.5" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;"><?= $c['finalite'] ?></p>
            </div>
            <div class="shrink-0 text-xs font-semibold" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                ⏱ <?= $c['duree'] ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Gérer les cookies -->
    <div class="surface rounded-xl p-6">
        <h3 class="font-display text-lg font-bold mb-3 pb-2" style="color:var(--text);border-bottom:2px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
            Gérer vos préférences
        </h3>
        <p class="text-sm leading-relaxed mb-4" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Vous pouvez modifier vos préférences à tout moment en cliquant sur le bouton ci-dessous.
            Les cookies obligatoires ne peuvent pas être désactivés car ils sont nécessaires au fonctionnement du site.
        </p>
        <button onclick="document.getElementById('cookie-banner').style.display='block'; window.scrollTo({top:0,behavior:'smooth'})"
                class="btn-primary">
            🍪 Gérer mes cookies
        </button>
    </div>

    <!-- Navigateurs -->
    <div class="surface rounded-xl p-6">
        <h3 class="font-display text-lg font-bold mb-3 pb-2" style="color:var(--text);border-bottom:2px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
            Refuser les cookies via votre navigateur
        </h3>
        <p class="text-sm leading-relaxed mb-3" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Vous pouvez également configurer votre navigateur pour refuser ou supprimer les cookies :
        </p>
        <ul class="text-sm space-y-2" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            <li>🌐 <strong>Chrome :</strong> Paramètres → Confidentialité → Cookies</li>
            <li>🦊 <strong>Firefox :</strong> Options → Vie privée → Cookies</li>
            <li>🧭 <strong>Safari :</strong> Préférences → Confidentialité</li>
            <li>🔵 <strong>Edge :</strong> Paramètres → Confidentialité → Cookies</li>
        </ul>
        <p class="text-xs mt-3" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            ⚠️ La désactivation de certains cookies peut altérer le fonctionnement du site (déconnexion automatique, perte du thème…).
        </p>
    </div>

</div>