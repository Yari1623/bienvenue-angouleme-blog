<?php $pageTitle = 'Mentions légales — Bienvenue à Angoulême'; ?>

<div class="max-w-3xl mx-auto space-y-8">

    <div class="text-center">
        <h2 class="font-display text-3xl font-black mb-2" style="color:var(--text)">Mentions légales</h2>
        <div class="h-1 w-24 mx-auto rounded-full" style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)"></div>
        <p class="text-xs mt-3" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">Dernière mise à jour : <?= date('d/m/Y') ?></p>
    </div>

    <?php $sections = [
        ['title'=>'1. Éditeur du site','content'=>'
            <p>Le site <strong>Bienvenue à Angoulême — le blog</strong> est un projet personnel réalisé dans le cadre d\'une formation <strong>Développeur Web et Web Mobile (DWWM)</strong>.</p>
            <ul class="mt-3 space-y-1 list-none">
                <li>📌 <strong>Responsable de publication :</strong> L\'administrateur du blog</li>
                <li>📧 <strong>Email :</strong> contact@bienvenue-angouleme.fr</li>
                <li>📍 <strong>Localisation :</strong> Angoulême, Charente (16), France</li>
            </ul>'],
        ['title'=>'2. Hébergement','content'=>'
            <p>Ce site est hébergé localement dans le cadre d\'un projet de formation. En production, il sera hébergé par un prestataire situé sur le territoire de l\'Union Européenne.</p>'],
        ['title'=>'3. Propriété intellectuelle','content'=>'
            <p>L\'ensemble des contenus présents sur ce site (textes, images, logo, code source) sont la propriété de l\'éditeur ou sont utilisés avec autorisation.</p>
            <p class="mt-2">Toute reproduction, représentation ou exploitation non autorisée est interdite et constitue une contrefaçon sanctionnée par le Code de la propriété intellectuelle.</p>'],
        ['title'=>'4. Responsabilité','content'=>'
            <p>L\'éditeur s\'efforce d\'assurer l\'exactitude et la mise à jour des informations publiées mais ne peut garantir leur exhaustivité.</p>
            <p class="mt-2">Il ne saurait être tenu responsable des dommages directs ou indirects résultant de l\'utilisation de ce site.</p>'],
        ['title'=>'5. Données personnelles','content'=>'
            <p>Conformément au RGPD et à la loi Informatique et Libertés, vous disposez d\'un droit d\'accès, rectification, suppression et opposition aux données vous concernant.</p>
            <p class="mt-2">Pour exercer ces droits : <a href="'.BASE_URL.'/contact" class="underline" style="color:#1d8fd8">page contact</a> · <a href="'.BASE_URL.'/rgpd" class="underline" style="color:#1d8fd8">politique RGPD</a></p>'],
        ['title'=>'6. Cookies','content'=>'
            <p>Ce site utilise des cookies nécessaires à son fonctionnement et des cookies optionnels pour les vidéos intégrées.</p>
            <p class="mt-2">Consultez notre <a href="'.BASE_URL.'/politique-cookies" class="underline" style="color:#1d8fd8">politique des cookies</a> pour en savoir plus.</p>'],
        ['title'=>'7. Droit applicable','content'=>'
            <p>Le présent site est soumis au droit français. En cas de litige, les tribunaux compétents seront ceux du ressort d\'Angoulême, France.</p>'],
    ];
    foreach ($sections as $s): ?>
    <div class="surface rounded-xl p-6">
        <h3 class="font-display text-lg font-bold mb-3 pb-2" style="color:var(--text);border-bottom:2px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
            <?= $s['title'] ?>
        </h3>
        <div class="text-sm leading-relaxed" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            <?= $s['content'] ?>
        </div>
    </div>
    <?php endforeach; ?>

</div>














