<?php $pageTitle = 'Politique de confidentialité — Bienvenue à Angoulême'; ?>

<div class="max-w-3xl mx-auto space-y-8">

    <div class="text-center">
        <h2 class="font-display text-3xl font-black mb-2" style="color:var(--text)">Politique de confidentialité</h2>
        <div class="h-1 w-24 mx-auto rounded-full" style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)"></div>
        <p class="text-xs mt-3" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">Dernière mise à jour : <?= date('d/m/Y') ?></p>
    </div>

    <?php $sections = [
        ['title'=>'1. Qui collecte vos données ?','content'=>'
            <p>Le responsable du traitement est l\'éditeur du blog <strong>Bienvenue à Angoulême</strong>, joignable via la <a href="'.BASE_URL.'/contact" class="underline" style="color:#1d8fd8">page contact</a>.</p>'],
        ['title'=>'2. Données collectées','content'=>'
            <p>Lors de votre inscription, nous collectons :</p>
            <ul class="mt-2 space-y-1 pl-4" style="list-style:disc">
                <li>Nom, prénom, nom d\'utilisateur</li>
                <li>Adresse email</li>
                <li>Téléphone et entreprise (facultatifs)</li>
                <li>Mot de passe (hashé avec bcrypt — jamais stocké en clair)</li>
            </ul>
            <p class="mt-3">Lors de votre navigation, nous collectons :</p>
            <ul class="mt-2 space-y-1 pl-4" style="list-style:disc">
                <li>Articles consultés (via votre compte)</li>
                <li>Likes et commentaires publiés</li>
                <li>Adresse IP (pour les statistiques de vues)</li>
            </ul>'],
        ['title'=>'3. Finalités du traitement','content'=>'
            <ul class="space-y-2 pl-4" style="list-style:disc">
                <li>Gestion de votre compte et de l\'authentification</li>
                <li>Affichage de votre historique de lecture et de vos likes</li>
                <li>Modération des commentaires</li>
                <li>Statistiques de fréquentation (anonymisées)</li>
                <li>Réponse à vos demandes via le formulaire de contact</li>
            </ul>'],
        ['title'=>'4. Durée de conservation','content'=>'
            <ul class="space-y-2 pl-4" style="list-style:disc">
                <li><strong>Compte utilisateur :</strong> jusqu\'à suppression par l\'utilisateur ou inactivité de 3 ans</li>
                <li><strong>Commentaires :</strong> conservés sauf suppression manuelle</li>
                <li><strong>Logs de connexion :</strong> 12 mois maximum</li>
                <li><strong>Données de contact :</strong> 3 ans après le dernier échange</li>
            </ul>'],
        ['title'=>'5. Vos droits','content'=>'
            <p>Conformément au RGPD, vous disposez des droits suivants :</p>
            <ul class="mt-2 space-y-1 pl-4" style="list-style:disc">
                <li><strong>Droit d\'accès</strong> à vos données personnelles</li>
                <li><strong>Droit de rectification</strong> en cas d\'inexactitude</li>
                <li><strong>Droit à l\'effacement</strong> (droit à l\'oubli)</li>
                <li><strong>Droit à la portabilité</strong> de vos données</li>
                <li><strong>Droit d\'opposition</strong> au traitement</li>
            </ul>
            <p class="mt-3">Pour exercer ces droits, utilisez notre <a href="'.BASE_URL.'/contact" class="underline" style="color:#1d8fd8">formulaire de contact</a> avec le sujet "Demande RGPD".</p>'],
        ['title'=>'6. Partage des données','content'=>'
            <p>Vos données ne sont <strong>jamais vendues ni cédées</strong> à des tiers à des fins commerciales.</p>
            <p class="mt-2">Elles peuvent être transmises aux autorités compétentes sur réquisition judiciaire.</p>'],
        ['title'=>'7. Sécurité','content'=>'
            <p>Nous mettons en œuvre les mesures techniques suivantes pour protéger vos données :</p>
            <ul class="mt-2 space-y-1 pl-4" style="list-style:disc">
                <li>Mots de passe hashés (bcrypt)</li>
                <li>Tokens CSRF sur tous les formulaires</li>
                <li>Sessions sécurisées avec régénération d\'ID</li>
                <li>Échappement systématique des données affichées</li>
            </ul>'],
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