<?php
// views/about/index.php
$pageTitle = 'À propos — Bienvenue à Angoulême';
?>

<div class="space-y-12">

    <!-- ═══ HERO -->
    <div class="relative rounded-xl overflow-hidden p-8 md:p-12 text-center"
         style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
        <div class="relative z-10">
            <h2 class="font-display text-4xl font-black text-white mb-3">À propos du blog</h2>
            <p class="text-white/80 text-lg max-w-2xl mx-auto" style="font-family:'Source Sans 3',sans-serif;">
                Découvrez qui nous sommes, comment fonctionne le blog et les règles de la communauté.
            </p>
        </div>
        <!-- Décor -->
        <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle, white 1px, transparent 1px);background-size:24px 24px;"></div>
    </div>

    <!-- ═══ FONCTIONNEMENT -->
    <section>
        <h2 class="font-display text-2xl font-bold mb-6" style="color:var(--text);border-bottom:3px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;padding-bottom:.5rem;">
            Comment fonctionne le blog ?
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php
            $steps = [
                ['icon'=>'📖', 'title'=>'Lire', 'desc'=>'Parcourez nos articles sur l\'actualité locale d\'Angoulême et de la Charente. Filtrés par catégorie, lieu ou date.'],
                ['icon'=>'💬', 'title'=>'Participer', 'desc'=>'Créez un compte gratuit pour commenter les articles, liker vos contenus préférés et marquer votre intérêt pour les événements.'],
                ['icon'=>'📅', 'title'=>'Agenda', 'desc'=>'Consultez l\'agenda des événements locaux et indiquez votre participation. Restez informé de la vie culturelle et sociale.'],
            ];
            foreach($steps as $i => $step): ?>
            <div class="surface rounded-xl p-6 text-center">
                <div class="text-5xl mb-4"><?= $step['icon'] ?></div>
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm mx-auto mb-3"
                     style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
                    <?= $i+1 ?>
                </div>
                <h3 class="font-display text-lg font-bold mb-2" style="color:var(--text)"><?= $step['title'] ?></h3>
                <p class="text-sm leading-relaxed" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;"><?= $step['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ═══ RÈGLES -->
    <section>
        <h2 class="font-display text-2xl font-bold mb-6" style="color:var(--text);border-bottom:3px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;padding-bottom:.5rem;">
            Règles de la communauté
        </h2>
        <div class="surface rounded-xl overflow-hidden">
            <?php
            $rules = [
                ['icon'=>'✅', 'color'=>'#16a34a', 'title'=>'Respectez les autres membres', 'desc'=>'La politesse et le respect sont essentiels. Toute forme de harcèlement, d\'insulte ou de discrimination sera sanctionnée.'],
                ['icon'=>'✅', 'color'=>'#16a34a', 'title'=>'Commentaires constructifs', 'desc'=>'Les commentaires doivent apporter une valeur ajoutée. Critiques positives, informations complémentaires et retours d\'expérience sont les bienvenus.'],
                ['icon'=>'✅', 'color'=>'#16a34a', 'title'=>'Informations vérifiées', 'desc'=>'Merci de ne partager que des informations vérifiées et de sourcer vos affirmations lorsque c\'est possible.'],
                ['icon'=>'❌', 'color'=>'#dc2626', 'title'=>'Pas de spam ni publicité', 'desc'=>'Aucun contenu publicitaire, lien commercial non sollicité ou spam ne sera toléré dans les commentaires.'],
                ['icon'=>'❌', 'color'=>'#dc2626', 'title'=>'Pas de contenu illégal', 'desc'=>'Tout contenu contraire à la loi française (diffamation, incitation à la haine, violation du droit d\'auteur) sera immédiatement supprimé.'],
                ['icon'=>'❌', 'color'=>'#dc2626', 'title'=>'Pas d\'informations personnelles', 'desc'=>'Ne divulguez jamais d\'informations personnelles sur vous-même ou sur des tiers (adresse, téléphone, données bancaires…).'],
            ];
            foreach($rules as $i => $rule): ?>
            <div class="flex items-start gap-4 p-5 <?= $i > 0 ? 'border-t' : '' ?>" style="<?= $i > 0 ? 'border-color:var(--border)' : '' ?>">
                <span class="text-2xl shrink-0"><?= $rule['icon'] ?></span>
                <div>
                    <h3 class="font-semibold mb-1" style="color:<?= $rule['color'] ?>;font-family:'Source Sans 3',sans-serif;">
                        <?= $rule['title'] ?>
                    </h3>
                    <p class="text-sm leading-relaxed" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                        <?= $rule['desc'] ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <p class="text-sm mt-4 p-4 rounded-lg" style="background:var(--bg2);color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            ⚠️ Tout manquement à ces règles peut entraîner la suppression du commentaire et/ou la suspension du compte.
            L'équipe de modération se réserve le droit de prendre toute décision nécessaire.
        </p>
    </section>

    <!-- ═══ FAQ -->
    <section>
        <h2 class="font-display text-2xl font-bold mb-6" style="color:var(--text);border-bottom:3px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;padding-bottom:.5rem;">
            Questions fréquentes (FAQ)
        </h2>
        <div class="space-y-3" id="faq-container">
            <?php
            $faqs = [
                ['q'=>'Comment créer un compte ?',
                 'r'=>'Cliquez sur "Inscription" en haut à droite. Renseignez votre nom, prénom, email et choisissez un mot de passe sécurisé. Votre compte est actif immédiatement.'],
                ['q'=>'Comment laisser un commentaire ?',
                 'r'=>'Vous devez être connecté. Ouvrez un article, faites défiler jusqu\'à la section "Commentaires" en bas de page et rédigez votre message. Il sera visible après validation par un modérateur.'],
                ['q'=>'Mes commentaires sont-ils publiés immédiatement ?',
                 'r'=>'Non, tous les commentaires passent par une modération avant publication. Cela garantit la qualité des échanges et le respect des règles de la communauté.'],
                ['q'=>'Comment signaler un contenu inapproprié ?',
                 'r'=>'Utilisez la page Contact pour nous signaler tout contenu qui vous semble inapproprié. Notre équipe traitera votre signalement dans les plus brefs délais.'],
                ['q'=>'Puis-je supprimer mon compte ?',
                 'r'=>'Oui, contactez-nous via la page Contact en demandant la suppression de votre compte. Conformément au RGPD, vos données seront effacées dans un délai de 30 jours.'],
                ['q'=>'Comment sont utilisées mes données personnelles ?',
                 'r'=>'Vos données sont utilisées uniquement pour le fonctionnement du blog (authentification, commentaires). Elles ne sont jamais vendues à des tiers. Consultez notre politique de confidentialité pour plus de détails.'],
                ['q'=>'Le blog est-il affilié à la mairie d\'Angoulême ?',
                 'r'=>'Non, ce blog est totalement indépendant. Il s\'agit d\'un projet personnel sans aucune affiliation officielle avec la ville d\'Angoulême ou ses institutions.'],
            ];
            foreach($faqs as $i => $faq): ?>
            <div class="surface rounded-xl overflow-hidden faq-item">
                <button class="w-full flex items-center justify-between p-5 text-left faq-trigger"
                        onclick="toggleFaq(<?= $i ?>)"
                        style="font-family:'Source Sans 3',sans-serif;">
                    <span class="font-semibold text-sm pr-4" style="color:var(--text)"><?= $faq['q'] ?></span>
                    <span class="faq-icon shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold transition-transform"
                          id="faq-icon-<?= $i ?>"
                          style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">+</span>
                </button>
                <div id="faq-answer-<?= $i ?>" class="faq-answer" style="display:none;padding:0 1.25rem 1.25rem;color:var(--text2);font-size:.875rem;line-height:1.6;font-family:'Source Sans 3',sans-serif;">
                    <?= $faq['r'] ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ═══ AUTEUR -->
    <section>
        <h2 class="font-display text-2xl font-bold mb-6" style="color:var(--text);border-bottom:3px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;padding-bottom:.5rem;">
            L'auteur du blog
        </h2>
        <div class="surface rounded-xl p-6 md:p-8 flex flex-col md:flex-row items-center gap-6">
            <!-- Avatar -->
            <div class="shrink-0">
                <div class="w-24 h-24 rounded-full flex items-center justify-center text-4xl text-white shadow-lg"
                     style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
                    👤
                </div>
            </div>
            <!-- Infos -->
            <div class="flex-1 text-center md:text-left">
                <h3 class="font-display text-2xl font-bold mb-1" style="color:var(--text)">
                    <?= htmlspecialchars(\App\Core\Auth::user()['username'] ?? 'L\'administrateur') ?>
                </h3>
                <p class="text-sm font-semibold mb-3" style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-family:'Source Sans 3',sans-serif;">
                    Fondateur & Administrateur du blog
                </p>
                <p class="text-sm leading-relaxed" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                    Passionné par la vie locale angoumoisine, j'ai créé ce blog pour partager l'actualité,
                    les événements et les bons plans de notre belle ville. Ce projet est aussi l'occasion
                    de mettre en pratique mes compétences diverses acquises au fil des années et pratiquées au cours de mes loisirs et des formations suivies.
                </p>
                <div class="flex flex-wrap gap-2 mt-4 justify-center md:justify-start">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-white"
                          style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);font-family:'Source Sans 3',sans-serif;">
                        Photographe
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-white"
                          style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);font-family:'Source Sans 3',sans-serif;">
                        Vidéaste
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-white"
                          style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);font-family:'Source Sans 3',sans-serif;">
                        Droniste
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-white"
                          style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);font-family:'Source Sans 3',sans-serif;">
                        Graphiste
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-white"
                          style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);font-family:'Source Sans 3',sans-serif;">
                        Développeur web
                    </span>
                </div>
            </div>
        </div>
    </section>

</div>

<script>
function toggleFaq(i) {
    const answer = document.getElementById('faq-answer-' + i);
    const icon   = document.getElementById('faq-icon-' + i);
    const isOpen = answer.style.display !== 'none';
    answer.style.display = isOpen ? 'none' : 'block';
    icon.textContent     = isOpen ? '+' : '×';
    icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(45deg)';
}
</script>