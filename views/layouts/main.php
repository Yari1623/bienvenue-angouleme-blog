<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Bienvenue à Angoulême' ?></title>
 
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
 
    <!-- Google Fonts : Playfair Display (titres) + Source Sans 3 (texte) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Source+Sans+3:wght@300;400;600;700&display=swap" rel="stylesheet">
 
    <script src="<?= BASE_URL ?>/assets/js/tailwind.config.js"></script>
 
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/app.css">
</head>
<body>
 
<!-- Skip link accessibilité — navigation clavier (WCAG 2.4.1) -->
<a href="#main-content" class="skip-link">Aller au contenu principal</a>
 
<?php
use App\Core\Auth;
use App\Core\Flash;
 
// Données utilisateur — utilisées dans header et footer
$authUser   = Auth::user();
$isAdmin    = Auth::isAdmin();
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
 
<!-- ══════════════════════════════════════════════
     BANNIÈRE COOKIES
══════════════════════════════════════════════ -->
<div id="cookie-banner" style="display:none;">
    <div class="flex items-start gap-4 p-5">
        <div class="flex-1 min-w-0">
            <p class="text-sm mb-1" style="color:var(--text2)">Salut c'est nous…</p>
            <h3 class="font-display text-xl font-bold mb-2" style="color:var(--text)">les Cookies ! 🍪</h3>
            <p class="text-sm leading-relaxed" style="color:var(--text2)">
                On a attendu d'être sûrs que le contenu de ce site vous intéresse avant de vous déranger,
                mais on aimerait bien vous accompagner pendant votre visite… C'est OK pour vous ?
            </p>
        </div>
        <div class="text-4xl shrink-0">🍪</div>
    </div>
    <div class="cookie-banner-actions" style="border-top:1px solid var(--border)">
        <button onclick="cookieDeny()"    class="btn-ghost text-sm">Refuser</button>
        <button onclick="cookieDetails()" class="btn-outline text-sm">Je choisis</button>
        <button onclick="cookieAccept()"  class="btn-primary text-sm">OK pour moi ✓</button>
    </div>
</div>
 
<!-- ══════════════════════════════════════════════
     MODAL GESTION COOKIES
══════════════════════════════════════════════ -->
<div id="cookie-modal" style="display:none;">
    <div id="cookie-modal-inner">
        <div class="p-5">
            <h2 class="font-display text-lg font-bold mb-2" style="color:var(--text)">Gestion des cookies</h2>
            <p class="text-sm mb-4" style="color:var(--text2)">
                En autorisant ces services, vous acceptez le dépôt de cookies nécessaires à leur bon fonctionnement.
            </p>
 
            <div class="cookie-row-global mb-4 p-3 rounded-lg" style="background:var(--bg2)">
                <span class="font-semibold text-sm" style="color:var(--text)">Préférences globales</span>
                <div class="flex gap-2 flex-wrap">
                    <button onclick="cookieAcceptAll()" class="btn-allow">✓ Tout accepter</button>
                    <button onclick="cookieDenyAll()"   class="btn-deny">✗ Tout refuser</button>
                </div>
            </div>
 
            <div class="cookie-section">
                <div class="cookie-section-header">🔒 Cookies obligatoires</div>
                <div class="cookie-service-row p-4">
                    <div>
                        <p class="text-sm" style="color:var(--text)">Nécessaires au fonctionnement du site.</p>
                        <p class="text-xs mt-1" style="color:var(--muted)">Ne peuvent pas être désactivés.</p>
                    </div>
                    <button class="btn-allow" disabled style="opacity:.6;cursor:not-allowed;">✓ Autorisé</button>
                </div>
            </div>
 
            <div class="cookie-section">
                <div class="cookie-section-header">🎬 Vidéos</div>
                <?php
                $videoServices = [
                    ['id'=>'youtube',    'name'=>'YouTube',    'desc'=>'Lecteur vidéo YouTube intégré'],
                    ['id'=>'vimeo',      'name'=>'Vimeo',      'desc'=>'Lecteur vidéo Vimeo intégré'],
                    ['id'=>'dailymotion','name'=>'Dailymotion','desc'=>'Lecteur Dailymotion intégré'],
                ];
                foreach ($videoServices as $s): ?>
                <div class="cookie-service-row p-4" style="border-top:1px solid var(--border)">
                    <div>
                        <p class="font-semibold text-sm" style="color:var(--text)"><?= $s['name'] ?></p>
                        <p class="text-xs mt-0.5" style="color:var(--muted)"><?= $s['desc'] ?></p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="toggleCookie('<?= $s['id'] ?>',true,this)"  class="btn-allow cookie-btn-allow-<?= $s['id'] ?>">✓</button>
                        <button onclick="toggleCookie('<?= $s['id'] ?>',false,this)" class="btn-deny  cookie-btn-deny-<?= $s['id'] ?>">✗</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
 
            <div class="flex justify-end gap-3 mt-4 flex-wrap">
                <button onclick="closeCookieModal()"  class="btn-ghost">Fermer</button>
                <button onclick="saveCookieChoices()" class="btn-primary">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
 
<!-- ══════════════════════════════════════════════
     HEADER — Barre supérieure + Logo + Navigation
══════════════════════════════════════════════ -->
<header class="navbar shadow-sm">
 
    <div style="border-bottom:1px solid var(--border)">
 
        <!-- Date : sur sa propre ligne pour libérer l'espace sur mobile -->
        <div class="max-w-7xl mx-auto px-4 pt-1.5 text-xs"
             style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            <?php
            $jours = ["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
            $mois  = ["","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août",
                       "Septembre","Octobre","Novembre","Décembre"];
            echo $jours[date("N")-1] . " " . date("d") . " " . $mois[(int)date("n")] . " " . date("Y");
            ?>
        </div>
 
        <!--
            Liens utilisateur — CORRECTION passe 6 :
            overflow:hidden sur le wrapper + overflow-x:auto + min-width:max-content
            sur le flex intérieur UNIQUEMENT.
            Cela évite le bug de re-rendu en boucle qui survenait quand
            overflow-x:auto était posé directement sur un enfant de position:sticky.
        -->
        <div class="max-w-7xl mx-auto px-4 pb-1.5">
            <div class="flex items-center justify-between flex-wrap gap-y-1 text-xs py-1"
                 style="font-family:'Source Sans 3',sans-serif;color:var(--muted);">
 
                <!-- Liens utilisateur — wrappent naturellement sur mobile -->
                <div class="flex items-center flex-wrap gap-x-2 gap-y-1">
                <?php if ($authUser): ?>
                    <span style="color:var(--text2);">
                        Bonjour&nbsp;<strong style="color:var(--text)"><?= htmlspecialchars($authUser['username']) ?></strong>
                    </span>
                    <span class="topbar-sep hidden sm:inline">|</span>
 
                    <?php if ($isAdmin): ?>
                    <a href="<?= BASE_URL ?>/admin"
                       class="font-semibold whitespace-nowrap"
                       style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);
                              -webkit-background-clip:text;-webkit-text-fill-color:transparent;
                              background-clip:text;">
                        ⚙ Dashboard
                    </a>
                    <span class="topbar-sep hidden sm:inline">|</span>
                    <?php endif; ?>
 
                    <a href="<?= BASE_URL ?>/profil" class="whitespace-nowrap"
                       style="color:<?= str_ends_with($currentUri,'/profil') ? '#1d8fd8' : 'var(--text2)' ?>">
                        👤 Profil
                    </a>
                    <span class="topbar-sep hidden sm:inline">|</span>
 
                    <a href="<?= BASE_URL ?>/compte" class="whitespace-nowrap"
                       style="color:<?= str_ends_with($currentUri,'/compte') ? '#1d8fd8' : 'var(--text2)' ?>">
                        ✏️ Compte
                    </a>
                    <span class="topbar-sep hidden sm:inline">|</span>
 
                    <a href="<?= BASE_URL ?>/logout" class="whitespace-nowrap" style="color:var(--text2)">
                        Déconnexion
                    </a>
 
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login"    class="whitespace-nowrap" style="color:var(--text2)">Connexion</a>
                    <span class="topbar-sep hidden sm:inline">|</span>
                    <a href="<?= BASE_URL ?>/register" class="whitespace-nowrap" style="color:var(--text2)">Inscription</a>
                <?php endif; ?>
                </div>
 
                <!-- Toggle thème dark/light — toujours visible -->
                <button class="theme-toggle flex-shrink-0" onclick="toggleTheme()"
                        title="Changer le thème" aria-label="Changer le thème">
                    <div class="toggle-track">
                        <div class="toggle-cloud" style="width:18px;height:7px;bottom:8px;left:8px;"></div>
                        <div class="toggle-cloud" style="width:12px;height:5px;bottom:14px;left:14px;"></div>
                        <div class="toggle-star"  style="width:3px;height:3px;top:8px;right:10px;"></div>
                        <div class="toggle-star"  style="width:2px;height:2px;top:14px;right:18px;"></div>
                        <div class="toggle-star"  style="width:2px;height:2px;top:20px;right:12px;"></div>
                    </div>
                    <div class="toggle-thumb">
                        <span class="sun-icon">☀️</span>
                        <span class="moon-icon" style="display:none">🌙</span>
                    </div>
                </button>
 
            </div>
        </div>
    </div>
 
    <!-- Logo -->
    <div class="max-w-7xl mx-auto px-4 py-4">
        <a href="<?= BASE_URL ?>/" class="group flex items-center justify-center gap-4">
            <img src="<?= BASE_URL ?>/assets/images/Logo_Blog_couleur_avec_fond_blanc.png"
                 alt="Logo Bienvenue à Angoulême"
                 class="rounded-full shadow-md group-hover:scale-105 transition-transform duration-300"
                 style="width:80px;height:80px;object-fit:cover;flex-shrink:0;">
            <div class="text-left min-w-0">
                <h1 class="font-display text-2xl md:text-4xl font-black leading-tight brand-gradient-text">
                    Bienvenue à Angoulême
                </h1>
                <div class="flex items-center gap-3 mt-1">
                    <div class="h-px w-8 shrink-0"
                         style="background:linear-gradient(to right,#1d8fd8,#22d3ee)"></div>
                    <span class="text-xs tracking-widest uppercase truncate"
                          style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                        Actualités · Culture · Vie locale
                    </span>
                </div>
            </div>
        </a>
    </div>
 
    <!-- Navigation principale — hamburger sur mobile, liens sur desktop -->
    <nav class="max-w-7xl mx-auto px-4" style="border-top:1px solid var(--border)">
        <?php
        $navItems = [
            ['url' => '/',          'label' => 'Accueil'],
            ['url' => '/blog',       'label' => 'Blog'],
            ['url' => '/categories', 'label' => 'Catégories'],
            ['url' => '/agenda',     'label' => 'Agenda'],
            ['url' => '/a-propos',   'label' => 'À propos'],
            ['url' => '/contact',    'label' => 'Contact'],
        ];
        ?>
 
        <!-- Ligne nav : liens desktop + bouton hamburger mobile -->
        <div class="flex items-center justify-between py-2">
 
            <!-- Liens desktop (cachés sur mobile) -->
            <ul class="hidden md:flex items-center gap-6"
                style="font-family:'Source Sans 3',sans-serif;">
                <?php foreach ($navItems as $item):
                    $isActive = rtrim($currentUri, '/') === rtrim(BASE_URL . $item['url'], '/');
                ?>
                <li>
                    <a href="<?= BASE_URL . $item['url'] ?>"
                       class="nav-link whitespace-nowrap <?= $isActive ? 'active' : '' ?>">
                        <?= $item['label'] ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
 
            <!-- Bouton hamburger (visible uniquement sur mobile) -->
            <button id="nav-toggle"
                    class="md:hidden flex flex-col justify-center items-center gap-1.5 p-2 rounded-lg"
                    style="background:transparent;border:1.5px solid var(--border);cursor:pointer;width:40px;height:40px;"
                    aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="nav-menu">
                <span class="hamburger-line" style="display:block;width:20px;height:2px;background:var(--text2);border-radius:2px;transition:all .3s;"></span>
                <span class="hamburger-line" style="display:block;width:20px;height:2px;background:var(--text2);border-radius:2px;transition:all .3s;"></span>
                <span class="hamburger-line" style="display:block;width:20px;height:2px;background:var(--text2);border-radius:2px;transition:all .3s;"></span>
            </button>
 
            <!-- Placeholder invisible sur desktop pour centrer les liens si besoin -->
            <div class="hidden md:block w-10"></div>
        </div>
 
        <!-- Menu mobile déroulant (caché par défaut) -->
        <div id="nav-menu"
             style="display:none;border-top:1px solid var(--border);padding-bottom:.5rem;">
            <ul style="font-family:'Source Sans 3',sans-serif;">
                <?php foreach ($navItems as $item):
                    $isActive = rtrim($currentUri, '/') === rtrim(BASE_URL . $item['url'], '/');
                ?>
                <li>
                    <a href="<?= BASE_URL . $item['url'] ?>"
                       style="display:block;padding:.6rem .25rem;font-size:.95rem;font-weight:600;
                              color:<?= $isActive ? '#1d8fd8' : 'var(--text2)' ?>;
                              border-bottom:1px solid var(--border);"
                       onclick="closeNav()">
                        <?= $item['label'] ?>
                        <?php if ($isActive): ?>
                        <span style="float:right;color:#1d8fd8;">›</span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>
 
    <script>
    (function(){
        const btn  = document.getElementById('nav-toggle');
        const menu = document.getElementById('nav-menu');
        if (!btn || !menu) return;
 
        function openNav(){
            menu.style.display = 'block';
            btn.setAttribute('aria-expanded','true');
            // Transformer hamburger en ✕
            const lines = btn.querySelectorAll('.hamburger-line');
            if(lines[0]) lines[0].style.transform='translateY(7px) rotate(45deg)';
            if(lines[1]) lines[1].style.opacity='0';
            if(lines[2]) lines[2].style.transform='translateY(-7px) rotate(-45deg)';
        }
        function closeNav(){
            menu.style.display = 'none';
            btn.setAttribute('aria-expanded','false');
            const lines = btn.querySelectorAll('.hamburger-line');
            if(lines[0]) lines[0].style.transform='';
            if(lines[1]) lines[1].style.opacity='1';
            if(lines[2]) lines[2].style.transform='';
        }
        window.closeNav = closeNav;
 
        btn.addEventListener('click', function(){
            menu.style.display === 'none' ? openNav() : closeNav();
        });
 
        // Fermer le menu si on clique en dehors
        document.addEventListener('click', function(e){
            if (menu.style.display !== 'none' && !btn.contains(e.target) && !menu.contains(e.target)) {
                closeNav();
            }
        });
 
        // Fermer le menu sur Escape
        document.addEventListener('keydown', function(e){
            if(e.key === 'Escape') closeNav();
        });
    })();
    </script>
 
</header>
 
<!-- ══════════════════════════════════════════════
     FLASH MESSAGES
══════════════════════════════════════════════ -->
<?php $flashes = Flash::get(); ?>
<?php if (!empty($flashes)): ?>
<div class="max-w-7xl mx-auto px-4 pt-4 space-y-2">
    <?php foreach ($flashes as $type => $messages): ?>
        <?php foreach ($messages as $msg): ?>
        <div class="flash-<?= $type ?> px-4 py-3 rounded text-sm"
             style="font-family:'Source Sans 3',sans-serif;">
            <?= htmlspecialchars($msg) ?>
        </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>
 
<!-- ══════════════════════════════════════════════
     CONTENU PRINCIPAL
══════════════════════════════════════════════ -->
<main id="main-content" role="main" class="flex-1 max-w-7xl mx-auto w-full px-4 py-8">
    <?php require $viewPath; ?>
</main>
 
<!-- ══════════════════════════════════════════════
     FOOTER
══════════════════════════════════════════════ -->
<footer style="background:var(--bg2);border-top:2px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;margin-top:auto;">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
 
            <!-- Logo + description -->
            <div class="flex flex-col items-start gap-3">
                <img src="<?= BASE_URL ?>/assets/images/Logo_Blog_couleur_avec_fond_blanc.png"
                     alt="Logo" class="rounded-full shadow"
                     style="width:64px;height:64px;object-fit:cover;">
                <div>
                    <h3 class="font-display text-base font-bold brand-gradient-text">Bienvenue à Angoulême</h3>
                    <p class="text-sm leading-relaxed mt-1"
                       style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                        Le blog indépendant dédié à la vie locale d'Angoulême et de la Charente.
                    </p>
                </div>
            </div>
 
            <!-- Navigation -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-3 brand-gradient-text"
                    style="font-family:'Source Sans 3',sans-serif;">Navigation</h4>
                <ul class="space-y-2 text-sm" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                    <?php foreach ($navItems as $item): ?>
                    <li>
                        <a href="<?= BASE_URL . $item['url'] ?>" class="hover:text-brand1 transition-colors">
                            <?= $item['label'] ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
 
            <!-- Mentions légales -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-3 brand-gradient-text"
                    style="font-family:'Source Sans 3',sans-serif;">Informations légales</h4>
                <ul class="space-y-2 text-sm" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                    <li><a href="<?= BASE_URL ?>/mentions-legales"          class="hover:text-brand1 transition-colors">Mentions légales</a></li>
                    <li><a href="<?= BASE_URL ?>/politique-confidentialite" class="hover:text-brand1 transition-colors">Politique de confidentialité</a></li>
                    <li><a href="<?= BASE_URL ?>/politique-cookies"         class="hover:text-brand1 transition-colors">Politique des cookies</a></li>
                    <li><a href="<?= BASE_URL ?>/rgpd"                      class="hover:text-brand1 transition-colors">RGPD</a></li>
                    <li>
                        <button onclick="document.getElementById('cookie-banner').style.display='block'"
                                class="hover:text-brand1 transition-colors text-left">
                            Gérer mes cookies
                        </button>
                    </li>
                </ul>
            </div>
 
            <!-- Réseaux sociaux -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-3 brand-gradient-text"
                    style="font-family:'Source Sans 3',sans-serif;">Suivez-nous</h4>
                <div class="flex flex-wrap gap-3">
                    <?php foreach (['Facebook','Instagram','TikTok','YouTube'] as $rs): ?>
                    <a href="https://www.<?= strtolower($rs) ?>.com"
                       target="_blank" rel="noopener noreferrer"
                       class="transition-all hover:scale-110 hover:shadow-lg"
                       style="width:56px;height:56px;border-radius:14px;overflow:hidden;display:block;">
                        <img src="<?= BASE_URL ?>/assets/icones/<?= $rs ?>.png"
                             alt="<?= $rs ?>" style="width:100%;height:100%;object-fit:cover;">
                    </a>
                    <?php endforeach; ?>
                </div>
                <p class="text-xs mt-4" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                    Rejoignez notre communauté locale !
                </p>
            </div>
        </div>
 
        <div class="mt-8 pt-6 text-center text-xs"
             style="border-top:1px solid var(--border);color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            © <?= date('Y') ?> Bienvenue à Angoulême — Tous droits réservés —
            <a href="<?= BASE_URL ?>/mentions-legales" class="hover:text-brand1 transition-colors ml-1">
                Mentions légales
            </a>
        </div>
    </div>
</footer>
 
<!-- ══════════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════════ -->
    <script src="<?= BASE_URL ?>/assets/js/app.js"></script>
 
</body>
</html>