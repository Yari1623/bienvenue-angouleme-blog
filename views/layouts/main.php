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

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: {
                fontFamily: {
                    display: ['"Playfair Display"', 'Georgia', 'serif'],
                    body:    ['"Source Sans 3"', 'sans-serif'],
                },
                colors: { brand1: '#1d8fd8', brand2: '#22d3ee' }
            }}
        }
    </script>

    <style>
        /* ═══════════════════════════════════════════
           VARIABLES CSS — Thème clair (défaut)
        ═══════════════════════════════════════════ */
        :root {
            --bg:      #f0f4f8;
            --bg2:     #e2eaf2;
            --surface: #ffffff;
            --border:  #c8d8e8;
            --text:    #1e2d3d;
            --text2:   #4a6275;
            --muted:   #7a95a8;
        }

        /* ═══════════════════════════════════════════
           VARIABLES CSS — Thème sombre
        ═══════════════════════════════════════════ */
        html.dark {
            --bg:      #0f1923;
            --bg2:     #162030;
            --surface: #1a2840;
            --border:  #243550;
            --text:    #e8f0f8;
            --text2:   #9ab0c8;
            --muted:   #5a7a95;
        }

        /* ═══════════════════════════════════════════
           RESET / BASE
        ═══════════════════════════════════════════ */
        * { transition: background-color .3s ease, color .3s ease, border-color .3s ease; }
        body {
            font-family: 'Source Sans 3', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        h1, h2, h3, h4 { font-family: 'Playfair Display', serif; }

        /* ═══════════════════════════════════════════
           GRADIENT BRAND
        ═══════════════════════════════════════════ */
        .brand-gradient      { background: linear-gradient(135deg, #1d8fd8, #22d3ee); }
        .brand-gradient-text {
            background: linear-gradient(135deg, #1d8fd8, #22d3ee);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ═══════════════════════════════════════════
           BOUTONS
        ═══════════════════════════════════════════ */
        .btn-primary {
            background: linear-gradient(135deg, #1d8fd8, #22d3ee);
            color: white; font-weight: 600;
            padding: .6rem 1.4rem; border-radius: 9999px;
            border: none; cursor: pointer;
            transition: opacity .2s, transform .15s;
            font-family: 'Source Sans 3', sans-serif;
        }
        .btn-primary:hover { opacity: .9; transform: translateY(-1px); }

        .btn-outline {
            background: transparent; color: #1d8fd8;
            border: 2px solid #1d8fd8; font-weight: 600;
            padding: .5rem 1.3rem; border-radius: 9999px;
            cursor: pointer; transition: all .2s;
            font-family: 'Source Sans 3', sans-serif;
        }
        .btn-outline:hover {
            background: linear-gradient(135deg, #1d8fd8, #22d3ee);
            color: white; border-color: transparent;
        }

        .btn-ghost {
            background: transparent; color: var(--text2);
            border: 2px solid var(--border); font-weight: 600;
            padding: .5rem 1.3rem; border-radius: 9999px;
            cursor: pointer; transition: all .2s;
            font-family: 'Source Sans 3', sans-serif;
        }
        .btn-ghost:hover { border-color: #1d8fd8; color: #1d8fd8; }

        /* ═══════════════════════════════════════════
           SURFACE (cards, panels)
        ═══════════════════════════════════════════ */
        .surface { background: var(--surface); border: 1px solid var(--border); }

        /* ═══════════════════════════════════════════
           NAVBAR
        ═══════════════════════════════════════════ */
        .navbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 100;
        }
        .nav-link {
            position: relative; color: var(--text2);
            font-weight: 600; font-size: .875rem;
            padding: .4rem 0; transition: color .2s;
        }
        .nav-link::after {
            content: ''; position: absolute; bottom: -2px; left: 0;
            width: 0; height: 2px;
            background: linear-gradient(135deg, #1d8fd8, #22d3ee);
            transition: width .25s; border-radius: 1px;
        }
        .nav-link:hover, .nav-link.active { color: #1d8fd8; }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }

        /* ═══════════════════════════════════════════
           BARRE SUPÉRIEURE — séparateur
        ═══════════════════════════════════════════ */
        .topbar-sep { color: var(--border); user-select: none; }

        /* ═══════════════════════════════════════════
           TOGGLE DARK/LIGHT (3D neumorphique)
        ═══════════════════════════════════════════ */
        .theme-toggle {
            width: 80px; height: 36px; border-radius: 9999px;
            position: relative; cursor: pointer;
            border: none; padding: 0; overflow: hidden; flex-shrink: 0;
            box-shadow: inset 2px 2px 5px rgba(0,0,0,.25),
                        inset -2px -2px 5px rgba(255,255,255,.15);
        }
        .theme-toggle .toggle-track {
            position: absolute; inset: 0; border-radius: 9999px;
            transition: background .4s ease;
        }
        html:not(.dark) .theme-toggle .toggle-track {
            background: linear-gradient(135deg, #87ceeb 0%, #e0f4ff 40%, #fff9c4 70%, #ffe082 100%);
        }
        html.dark .theme-toggle .toggle-track {
            background: linear-gradient(135deg, #1a1035 0%, #2d1b6b 50%, #0f2050 100%);
        }
        .toggle-cloud { position: absolute; border-radius: 9999px; background: rgba(255,255,255,.85); transition: opacity .3s; }
        html.dark .toggle-cloud { opacity: 0; }
        .toggle-star { position: absolute; border-radius: 50%; background: #fff; transition: opacity .3s; }
        html:not(.dark) .toggle-star { opacity: 0; }
        .theme-toggle .toggle-thumb {
            position: absolute; top: 4px; width: 28px; height: 28px;
            border-radius: 50%;
            transition: left .4s cubic-bezier(.34, 1.56, .64, 1);
            box-shadow: 2px 2px 6px rgba(0,0,0,.3);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; z-index: 2;
        }
        html:not(.dark) .theme-toggle .toggle-thumb {
            left: 4px;
            background: radial-gradient(circle at 35% 35%, #ffe88a, #ffb300);
        }
        html.dark .theme-toggle .toggle-thumb {
            left: 48px;
            background: radial-gradient(circle at 35% 35%, #e8e8ff, #b0b8d8);
        }

        /* ═══════════════════════════════════════════
           CARDS ARTICLES
        ═══════════════════════════════════════════ */
        .post-card {
            background: var(--surface); border: 1px solid var(--border);
            transition: transform .2s, box-shadow .2s;
            border-radius: .5rem; overflow: hidden;
        }
        .post-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(29,143,216,.15);
            border-color: #1d8fd8;
        }

        /* ═══════════════════════════════════════════
           FLASH MESSAGES
        ═══════════════════════════════════════════ */
        .flash-success { background: #f0fdf4; border-left: 4px solid #22c55e; color: #166534; }
        .flash-error   { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; }

        /* ═══════════════════════════════════════════
           PAGINATION
        ═══════════════════════════════════════════ */
        .page-btn {
            padding: .4rem .85rem; border-radius: .375rem;
            border: 1px solid var(--border); color: var(--text2);
            font-size: .875rem; transition: all .2s; background: var(--surface);
        }
        .page-btn:hover, .page-btn.active {
            background: linear-gradient(135deg, #1d8fd8, #22d3ee);
            color: white; border-color: transparent;
        }

        /* ═══════════════════════════════════════════
           ACCESSIBILITÉ — Focus visible (WCAG 2.1 AA)
           outline transparent par défaut, visible au focus clavier
        ═══════════════════════════════════════════ */
        input:focus-visible,
        textarea:focus-visible,
        select:focus-visible {
            outline: 2px solid #1d8fd8;
            outline-offset: 2px;
        }
        button:focus-visible,
        a:focus-visible {
            outline: 2px solid #1d8fd8;
            outline-offset: 3px;
            border-radius: 4px;
        }
        /* Skip link accessibilité — navigation clavier */
        .skip-link {
            position: absolute; top: -100%; left: 0;
            background: #1d8fd8; color: white;
            padding: .5rem 1rem; font-weight: 700;
            z-index: 99999; border-radius: 0 0 .5rem 0;
            transition: top .2s;
        }
        .skip-link:focus { top: 0; }

        /* ═══════════════════════════════════════════
           BANNIÈRE COOKIES
           width:calc(100vw - 2rem) + max-width évite le débordement mobile
        ═══════════════════════════════════════════ */
        #cookie-banner {
            position: fixed; bottom: 1rem; left: 50%;
            transform: translateX(-50%); z-index: 9999;
            width: calc(100vw - 2rem); max-width: 520px;
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 1rem; box-shadow: 0 20px 60px rgba(0,0,0,.25);
            overflow: hidden;
        }
        #cookie-modal {
            position: fixed; inset: 0; z-index: 9998;
            background: rgba(0,0,0,.6);
            display: flex; align-items: center; justify-content: center; padding: 1rem;
        }
        #cookie-modal-inner {
            background: var(--surface); border-radius: 1rem;
            width: 100%; max-width: 600px; max-height: 90vh;
            overflow-y: auto; border: 1px solid var(--border);
        }

        /* Cookie modal — responsive boutons en colonne sur mobile */
        .cookie-row-global {
            display: flex; flex-direction: column;
            gap: .5rem; align-items: flex-start;
        }
        @media(min-width: 480px) {
            .cookie-row-global {
                flex-direction: row; align-items: center;
                justify-content: space-between;
            }
        }
        .cookie-service-row { display: flex; flex-direction: column; gap: .5rem; align-items: flex-start; }
        @media(min-width: 480px) {
            .cookie-service-row { flex-direction: row; align-items: center; justify-content: space-between; }
        }
        .cookie-banner-actions {
            display: flex; flex-wrap: wrap; gap: .5rem;
            align-items: center; justify-content: center;
            padding: .75rem 1rem;
        }
        .cookie-section {
            border: 1px solid var(--border); border-radius: .5rem;
            overflow: hidden; margin-bottom: 1rem;
        }
        .cookie-section-header {
            background: var(--bg2); padding: .75rem 1rem;
            font-weight: 700; font-size: .875rem; color: var(--text);
        }
        .btn-allow {
            background: #16a34a; color: white; border: none;
            padding: .35rem .9rem; border-radius: 9999px;
            font-size: .8rem; font-weight: 700; cursor: pointer; white-space: nowrap;
        }
        .btn-allow:hover { opacity: .85; }
        .btn-deny {
            background: #dc2626; color: white; border: none;
            padding: .35rem .9rem; border-radius: 9999px;
            font-size: .8rem; font-weight: 700; cursor: pointer; white-space: nowrap;
        }
        .btn-deny:hover { opacity: .85; }

        /* ═══════════════════════════════════════════
           SCROLLBAR personnalisée
        ═══════════════════════════════════════════ */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: #1d8fd8; border-radius: 3px; }

        /* ═══════════════════════════════════════════
           BARRE TOPBAR — masquage scrollbar horizontale
        ═══════════════════════════════════════════ */
        .topbar-scroll { scrollbar-width: none; }
        .topbar-scroll::-webkit-scrollbar { display: none; }
    </style>
</head>
<body>

<!-- Skip link accessibilité — navigation clavier (WCAG 2.4.1) -->
<a href="#main-content" class="skip-link">Aller au contenu principal</a>

<?php
use App\Core\Auth;
use App\Core\Flash;

// Données utilisateur — utilisées dans header et footer
$user       = Auth::user();
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

        <!-- Overflow:hidden sur le wrapper + overflow-x:auto + min-width:max-content
            sur le flex intérieur UNIQUEMENT. -->
        <div class="max-w-7xl mx-auto px-4 pb-1.5" style="overflow:hidden;">
            <div class="topbar-scroll flex items-center gap-2 text-xs py-1"
                 style="font-family:'Source Sans 3',sans-serif;color:var(--muted);
                        overflow-x:auto;min-width:max-content;width:100%;max-width:100%;">

                <?php if ($user): ?>
                    <span style="color:var(--text2);">
                        Bonjour&nbsp;<strong style="color:var(--text)"><?= htmlspecialchars($user['username']) ?></strong>
                    </span>
                    <span class="topbar-sep">|</span>

                    <?php if ($isAdmin): ?>
                    <a href="<?= BASE_URL ?>/admin"
                       class="font-semibold whitespace-nowrap"
                       style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);
                              -webkit-background-clip:text;-webkit-text-fill-color:transparent;
                              background-clip:text;">
                        ⚙ Dashboard
                    </a>
                    <span class="topbar-sep">|</span>
                    <?php endif; ?>

                    <a href="<?= BASE_URL ?>/profil" class="whitespace-nowrap"
                       style="color:<?= str_ends_with($currentUri,'/profil') ? '#1d8fd8' : 'var(--text2)' ?>">
                        👤 Profil
                    </a>
                    <span class="topbar-sep">|</span>

                    <a href="<?= BASE_URL ?>/compte" class="whitespace-nowrap"
                       style="color:<?= str_ends_with($currentUri,'/compte') ? '#1d8fd8' : 'var(--text2)' ?>">
                        ✏️ Compte
                    </a>
                    <span class="topbar-sep">|</span>

                    <a href="<?= BASE_URL ?>/logout" class="whitespace-nowrap" style="color:var(--text2)">
                        Déconnexion
                    </a>

                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login"    class="whitespace-nowrap" style="color:var(--text2)">Connexion</a>
                    <span class="topbar-sep">|</span>
                    <a href="<?= BASE_URL ?>/register" class="whitespace-nowrap" style="color:var(--text2)">Inscription</a>
                <?php endif; ?>

                <!-- Toggle thème dark/light -->
                <button class="theme-toggle ml-1" onclick="toggleTheme()"
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

    <!-- Navigation principale avec scroll horizontal silencieux sur mobile -->
    <nav class="max-w-7xl mx-auto px-4" style="border-top:1px solid var(--border)">
        <ul class="topbar-scroll flex items-center gap-6 py-3 overflow-x-auto"
            style="font-family:'Source Sans 3',sans-serif;">
            <?php
            $navItems = [
                ['url' => '/',          'label' => 'Accueil'],
                ['url' => '/blog',       'label' => 'Blog'],
                ['url' => '/categories', 'label' => 'Catégories'],
                ['url' => '/agenda',     'label' => 'Agenda'],
                ['url' => '/a-propos',   'label' => 'À propos'],
                ['url' => '/contact',    'label' => 'Contact'],
            ];
            foreach ($navItems as $item):
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
    </nav>

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
<script>
// ── Thème dark/light ──────────────────────────────────────
function applyTheme(dark) {
    document.documentElement.classList.toggle('dark', dark);
    const sun  = document.querySelector('.sun-icon');
    const moon = document.querySelector('.moon-icon');
    if (sun && moon) {
        sun.style.display  = dark ? 'none'   : 'inline';
        moon.style.display = dark ? 'inline' : 'none';
    }
}
function toggleTheme() {
    const isDark = !document.documentElement.classList.contains('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    applyTheme(isDark);
}
// Applique le thème sauvegardé ou la préférence système au chargement
(function () {
    const saved      = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyTheme(saved ? saved === 'dark' : prefersDark);
})();

// ── Gestion des cookies ───────────────────────────────────
const COOKIE_KEY = 'bwa_cookies_choice';

function cookieAccept() {
    localStorage.setItem(COOKIE_KEY, JSON.stringify({
        choice: 'accepted', youtube: true, vimeo: true, dailymotion: true
    }));
    hideCookieBanner();
}
function cookieDeny() {
    localStorage.setItem(COOKIE_KEY, JSON.stringify({
        choice: 'denied', youtube: false, vimeo: false, dailymotion: false
    }));
    hideCookieBanner();
}
function cookieDetails() {
    hideCookieBanner();
    document.getElementById('cookie-modal').style.display = 'flex';
}
function closeCookieModal() {
    document.getElementById('cookie-modal').style.display = 'none';
}
function cookieAcceptAll() { saveCookieChoices(true); }
function cookieDenyAll()   { saveCookieChoices(false); }

function toggleCookie(service, allow) {
    const a = document.querySelector('.cookie-btn-allow-' + service);
    const d = document.querySelector('.cookie-btn-deny-'  + service);
    if (allow) { a && (a.style.outline = '2px solid white'); d && (d.style.opacity = '.6'); }
    else        { d && (d.style.outline = '2px solid white'); a && (a.style.opacity = '.6'); }
}
function saveCookieChoices(all = null) {
    const c = { choice: 'custom' };
    ['youtube', 'vimeo', 'dailymotion'].forEach(s => {
        c[s] = all === null
            ? (document.querySelector('.cookie-btn-allow-' + s)?.style.outline !== '')
            : all;
    });
    localStorage.setItem(COOKIE_KEY, JSON.stringify(c));
    closeCookieModal();
}
function hideCookieBanner() {
    document.getElementById('cookie-banner').style.display = 'none';
}

// Affiche la bannière cookies si pas de choix enregistré (délai 1.2s)
(function () {
    if (!localStorage.getItem(COOKIE_KEY)) {
        setTimeout(() => {
            document.getElementById('cookie-banner').style.display = 'block';
        }, 1200);
    }
})();
</script>

</body>
</html>