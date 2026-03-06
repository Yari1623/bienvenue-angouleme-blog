<?php $pageTitle = 'RGPD — Bienvenue à Angoulême'; ?>

<div class="max-w-3xl mx-auto space-y-8">

    <div class="text-center">
        <h2 class="font-display text-3xl font-black mb-2" style="color:var(--text)">Vos droits RGPD</h2>
        <div class="h-1 w-24 mx-auto rounded-full" style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)"></div>
        <p class="text-sm mt-3 max-w-xl mx-auto" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Le Règlement Général sur la Protection des Données (RGPD) vous garantit le contrôle de vos données personnelles.
        </p>
    </div>

    <!-- Vos droits en cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php
        $droits = [
            ['icon'=>'👁', 'titre'=>'Droit d\'accès',       'desc'=>'Obtenir une copie de toutes les données personnelles que nous détenons sur vous.'],
            ['icon'=>'✏️', 'titre'=>'Droit de rectification','desc'=>'Corriger toute information inexacte ou incomplète vous concernant.'],
            ['icon'=>'🗑', 'titre'=>'Droit à l\'effacement', 'desc'=>'Demander la suppression de vos données (droit à l\'oubli), sous réserve d\'obligations légales.'],
            ['icon'=>'📦', 'titre'=>'Droit à la portabilité','desc'=>'Recevoir vos données dans un format structuré et lisible par machine.'],
            ['icon'=>'🚫', 'titre'=>'Droit d\'opposition',   'desc'=>'Vous opposer au traitement de vos données à des fins de prospection ou de profilage.'],
            ['icon'=>'⏸', 'titre'=>'Droit de limitation',   'desc'=>'Demander la suspension temporaire du traitement de vos données dans certains cas.'],
        ];
        foreach ($droits as $d): ?>
        <div class="surface rounded-xl p-5 flex items-start gap-4">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-xl shrink-0"
                 style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
                <?= $d['icon'] ?>
            </div>
            <div>
                <h3 class="font-semibold text-sm mb-1" style="color:var(--text);font-family:'Source Sans 3',sans-serif;"><?= $d['titre'] ?></h3>
                <p class="text-xs leading-relaxed" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;"><?= $d['desc'] ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Comment exercer ses droits -->
    <div class="surface rounded-xl p-6">
        <h3 class="font-display text-lg font-bold mb-3 pb-2" style="color:var(--text);border-bottom:2px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
            Comment exercer vos droits ?
        </h3>
        <div class="text-sm leading-relaxed space-y-3" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            <p>Pour exercer l'un de vos droits, envoyez-nous une demande via notre formulaire de contact en sélectionnant le sujet <strong>"Demande RGPD"</strong>.</p>
            <p>Précisez dans votre message :</p>
            <ul class="pl-4 space-y-1" style="list-style:disc">
                <li>Le type de droit que vous souhaitez exercer</li>
                <li>Votre nom d'utilisateur ou adresse email associée au compte</li>
            </ul>
            <p>Nous nous engageons à répondre dans un délai maximum de <strong>30 jours</strong> à compter de la réception de votre demande.</p>
        </div>
        <a href="<?= BASE_URL ?>/contact" class="btn-primary inline-block mt-4">
            Envoyer une demande RGPD →
        </a>
    </div>

    <!-- Base légale des traitements -->
    <div class="surface rounded-xl overflow-hidden">
        <div class="p-5" style="border-bottom:1px solid var(--border)">
            <h3 class="font-display text-lg font-bold" style="color:var(--text)">Base légale des traitements</h3>
        </div>
        <?php
        $traitements = [
            ['traitement'=>'Gestion des comptes',       'base'=>'Exécution du contrat',      'donnees'=>'Email, pseudo, mot de passe hashé'],
            ['traitement'=>'Commentaires',               'base'=>'Consentement',              'donnees'=>'Texte du commentaire, date, utilisateur'],
            ['traitement'=>'Statistiques de vues',       'base'=>'Intérêt légitime',          'donnees'=>'Adresse IP anonymisée, article consulté'],
            ['traitement'=>'Formulaire de contact',      'base'=>'Consentement explicite',    'donnees'=>'Nom, email, message'],
            ['traitement'=>'Préférences de thème',       'base'=>'Intérêt légitime',          'donnees'=>'Cookie local (localStorage)'],
        ];
        foreach ($traitements as $i => $t): ?>
        <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-2 text-sm <?= $i > 0 ? 'border-t' : '' ?>"
             style="<?= $i > 0 ? 'border-color:var(--border)' : '' ?>;font-family:'Source Sans 3',sans-serif;">
            <div class="font-semibold" style="color:var(--text)"><?= $t['traitement'] ?></div>
            <div>
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold text-white"
                      style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
                    <?= $t['base'] ?>
                </span>
            </div>
            <div style="color:var(--text2)"><?= $t['donnees'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- CNIL -->
    <div class="surface rounded-xl p-6" style="border-left:4px solid #1d8fd8">
        <h3 class="font-display text-lg font-bold mb-2" style="color:var(--text)">Droit de réclamation</h3>
        <p class="text-sm leading-relaxed" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Si vous estimez que vos droits ne sont pas respectés, vous pouvez introduire une réclamation auprès de la
            <strong>Commission Nationale de l'Informatique et des Libertés (CNIL)</strong> :
        </p>
        <a href="https://www.cnil.fr" target="_blank" rel="noopener noreferrer"
           class="btn-outline inline-block mt-3 text-sm">
            www.cnil.fr →
        </a>
    </div>

</div>