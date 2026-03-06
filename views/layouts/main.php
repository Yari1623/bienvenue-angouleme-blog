<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Bienvenue à Angoulême' ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Source+Sans+3:wght@300;400;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Playfair Display"', 'Georgia', 'serif'],
                        body: ['"Source Sans 3"', 'sans-serif'],
                    },
                    colors: {
                        brand1: '#1d8fd8',
                        brand2: '#22d3ee',
                    }
                }
            }
        }
    </script>

    <style>
        /* ── Variables thème ── */
        :root {
            --bg:        #f0f4f8;
            --bg2:       #e2eaf2;
            --surface:   #ffffff;
            --border:    #c8d8e8;
            --text:      #1e2d3d;
            --text2:     #4a6275;
            --muted:     #7a95a8;
            --night-bg:  transparent;
        }
        html.dark {
            --bg:       #0f1923;
            --bg2:      #162030;
            --surface:  #1a2840;
            --border:   #243550;
            --text:     #e8f0f8;
            --text2:    #9ab0c8;
            --muted:    #5a7a95;
        }

        * { transition: background-color .3s ease, color .3s ease, border-color .3s ease; }
        body { font-family: 'Source Sans 3', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; flex-direction: column; }
        h1,h2,h3,h4 { font-family: 'Playfair Display', serif; }

        /* ── Gradient brand ── */
        .brand-gradient { background: linear-gradient(135deg, #1d8fd8, #22d3ee); }
        .brand-gradient-text { background: linear-gradient(135deg, #1d8fd8, #22d3ee); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .brand-border { border-image: linear-gradient(135deg, #1d8fd8, #22d3ee) 1; }

        /* ── Boutons ── */
        .btn-primary {
            background: linear-gradient(135deg, #1d8fd8, #22d3ee);
            color: white; font-weight: 600; padding: .6rem 1.4rem;
            border-radius: 9999px; border: none; cursor: pointer;
            transition: opacity .2s, transform .15s;
            font-family: 'Source Sans 3', sans-serif;
        }
        .btn-primary:hover { opacity: .9; transform: translateY(-1px); }
        .btn-outline {
            background: transparent; color: #1d8fd8;
            border: 2px solid #1d8fd8; font-weight: 600;
            padding: .5rem 1.3rem; border-radius: 9999px; cursor: pointer;
            transition: all .2s; font-family: 'Source Sans 3', sans-serif;
        }
        .btn-outline:hover { background: linear-gradient(135deg,#1d8fd8,#22d3ee); color: white; border-color: transparent; }
        .btn-ghost {
            background: transparent; color: var(--text2);
            border: 2px solid var(--border); font-weight: 600;
            padding: .5rem 1.3rem; border-radius: 9999px; cursor: pointer;
            transition: all .2s; font-family: 'Source Sans 3', sans-serif;
        }
        .btn-ghost:hover { border-color: #1d8fd8; color: #1d8fd8; }

        /* ── Surface ── */
        .surface { background: var(--surface); border: 1px solid var(--border); }
        .text-main { color: var(--text); }
        .text-sub  { color: var(--text2); }
        .text-dim  { color: var(--muted); }
        .bg-main   { background: var(--bg); }
        .bg-surface{ background: var(--surface); }
        .border-theme { border-color: var(--border); }

        /* ── Navbar ── */
        .navbar { background: var(--surface); border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 100; }
        .nav-link { position: relative; color: var(--text2); font-weight: 600; font-size: .875rem; padding: .4rem 0; transition: color .2s; }
        .nav-link::after { content:''; position:absolute; bottom:-2px; left:0; width:0; height:2px; background:linear-gradient(135deg,#1d8fd8,#22d3ee); transition:width .25s; border-radius:1px; }
        .nav-link:hover, .nav-link.active { color: #1d8fd8; }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }

        /* ── Toggle dark/light ── */
        .theme-toggle {
            width: 80px; height: 36px; border-radius: 9999px;
            position: relative; cursor: pointer; border: none; padding: 0;
            overflow: hidden;
            box-shadow: inset 2px 2px 5px rgba(0,0,0,.25), inset -2px -2px 5px rgba(255,255,255,.15);
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
        /* nuages mode clair */
        .toggle-cloud {
            position: absolute; border-radius: 9999px;
            background: rgba(255,255,255,.85);
            transition: opacity .3s, transform .4s;
        }
        html.dark .toggle-cloud { opacity: 0; }
        /* étoiles mode sombre */
        .toggle-star {
            position: absolute; border-radius: 50%;
            background: #fff; transition: opacity .3s;
        }
        html:not(.dark) .toggle-star { opacity: 0; }
        .theme-toggle .toggle-thumb {
            position: absolute; top: 4px; width: 28px; height: 28px;
            border-radius: 50%; transition: left .4s cubic-bezier(.34,1.56,.64,1);
            box-shadow: 2px 2px 6px rgba(0,0,0,.3), -1px -1px 4px rgba(255,255,255,.4);
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

        /* ── Cards articles ── */
        .post-card { background: var(--surface); border: 1px solid var(--border); transition: transform .2s, box-shadow .2s; border-radius: .5rem; overflow: hidden; }
        .post-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(29,143,216,.15); border-color: #1d8fd8; }

        /* ── Flash ── */
        .flash-success { background:#f0fdf4; border-left:4px solid #22c55e; color:#166534; }
        .flash-error   { background:#fef2f2; border-left:4px solid #ef4444; color:#991b1b; }

        /* ── Pagination ── */
        .page-btn { padding: .4rem .85rem; border-radius: .375rem; border: 1px solid var(--border); color: var(--text2); font-size:.875rem; transition: all .2s; background: var(--surface); }
        .page-btn:hover, .page-btn.active { background: linear-gradient(135deg,#1d8fd8,#22d3ee); color: white; border-color: transparent; }

        /* ── Cookie banner ── */
        #cookie-banner { position:fixed; bottom:1.5rem; left:50%; transform:translateX(-50%); z-index:9999; width:min(520px, 95vw); background: var(--surface); border:1px solid var(--border); border-radius:1rem; box-shadow: 0 20px 60px rgba(0,0,0,.25); overflow:hidden; }
        #cookie-modal  { position:fixed; inset:0; z-index:9998; background:rgba(0,0,0,.6); display:flex; align-items:center; justify-content:center; padding:1rem; }
        #cookie-modal-inner { background: var(--surface); border-radius:1rem; width:min(640px,95vw); max-height:85vh; overflow-y:auto; border:1px solid var(--border); }
        .cookie-section { border:1px solid var(--border); border-radius:.5rem; overflow:hidden; margin-bottom:1rem; }
        .cookie-section-header { background: var(--bg2); padding:.75rem 1rem; font-weight:700; font-size:.875rem; color:var(--text); cursor:pointer; display:flex; align-items:center; justify-content:between; gap:.5rem; }
        .btn-allow  { background:#16a34a; color:white; border:none; padding:.35rem .9rem; border-radius:9999px; font-size:.8rem; font-weight:700; cursor:pointer; transition: opacity .2s; }
        .btn-allow:hover { opacity:.85; }
        .btn-deny   { background:#dc2626; color:white; border:none; padding:.35rem .9rem; border-radius:9999px; font-size:.8rem; font-weight:700; cursor:pointer; transition: opacity .2s; }
        .btn-deny:hover { opacity:.85; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width:6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: #1d8fd8; border-radius:3px; }
    </style>
</head>
<body>

<?php
use App\Core\Auth;
use App\Core\Flash;
$user    = Auth::user();
$isAdmin = Auth::isAdmin();
?>

<!-- ════════════════════════════════ COOKIE BANNER -->
<div id="cookie-banner" style="display:none;">
    <div class="flex items-start gap-4 p-5">
        <div class="flex-1">
            <p class="text-dim text-sm mb-1" style="color:var(--text2)">Salut c'est nous…</p>
            <h3 class="font-display text-2xl font-bold mb-3" style="color:var(--text)">les Cookies ! 🍪</h3>
            <p class="text-sm leading-relaxed" style="color:var(--text2)">
                On a attendu d'être sûrs que le contenu de ce site vous intéresse avant de vous déranger,
                mais on aimerait bien vous accompagner pendant votre visite… C'est OK pour vous ?
            </p>
        </div>
        <div class="text-5xl shrink-0">🍪</div>
    </div>
    <div class="flex items-center justify-between px-5 py-3" style="border-top:1px solid var(--border)">
        <button onclick="cookieDeny()"   class="btn-ghost text-sm">Refuser</button>
        <button onclick="cookieDetails()" class="btn-outline text-sm">Je choisis</button>
        <button onclick="cookieAccept()" class="btn-primary text-sm">OK pour moi ✓</button>
    </div>
</div>

<!-- ════════════════════════════════ COOKIE MODAL DÉTAILS -->
<div id="cookie-modal" style="display:none;">
    <div id="cookie-modal-inner">
        <div class="p-6">
            <h2 class="font-display text-xl font-bold mb-2" style="color:var(--text)">Panneau de gestion des cookies</h2>
            <p class="text-sm mb-5" style="color:var(--text2)">
                En autorisant ces services, vous acceptez le dépôt et la lecture de cookies nécessaires à leur bon fonctionnement.
            </p>

            <!-- Global -->
            <div class="flex items-center justify-between mb-4 p-3 rounded-lg" style="background:var(--bg2)">
                <span class="font-semibold text-sm" style="color:var(--text)">Préférences pour tous les services</span>
                <div class="flex gap-2">
                    <button onclick="cookieAcceptAll()" class="btn-allow">✓ Tout accepter</button>
                    <button onclick="cookieDenyAll()"   class="btn-deny">✗ Tout refuser</button>
                </div>
            </div>

            <!-- Obligatoires -->
            <div class="cookie-section">
                <div class="cookie-section-header">
                    <span>🔒 Cookies obligatoires</span>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm" style="color:var(--text)">Ce site utilise des cookies nécessaires à son bon fonctionnement.</p>
                        <p class="text-xs mt-1" style="color:var(--muted)">Ils ne peuvent pas être désactivés.</p>
                    </div>
                    <button class="btn-allow" disabled style="opacity:.6;cursor:not-allowed;">✓ Autorisé</button>
                </div>
            </div>

            <!-- Vidéos -->
            <div class="cookie-section">
                <div class="cookie-section-header">
                    <span>🎬 Vidéos</span>
                </div>
                <?php
                $videoServices = [
                    ['id'=>'youtube',    'name'=>'YouTube',    'desc'=>'Lecteur vidéo YouTube intégré'],
                    ['id'=>'vimeo',      'name'=>'Vimeo',      'desc'=>'Lecteur vidéo Vimeo intégré'],
                    ['id'=>'dailymotion','name'=>'Dailymotion','desc'=>'Lecteur vidéo Dailymotion intégré'],
                ];
                foreach($videoServices as $s): ?>
                <div class="p-4 flex items-center justify-between" style="border-top:1px solid var(--border)">
                    <div>
                        <p class="font-semibold text-sm" style="color:var(--text)"><?= $s['name'] ?></p>
                        <p class="text-xs mt-0.5" style="color:var(--muted)"><?= $s['desc'] ?></p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="toggleCookie('<?= $s['id'] ?>', true,  this)" class="btn-allow cookie-btn-allow-<?= $s['id'] ?>">✓ Autoriser</button>
                        <button onclick="toggleCookie('<?= $s['id'] ?>', false, this)" class="btn-deny  cookie-btn-deny-<?= $s['id'] ?>">✗ Interdire</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <button onclick="closeCookieModal()" class="btn-ghost">Fermer</button>
                <button onclick="saveCookieChoices()" class="btn-primary">Enregistrer mes choix</button>
            </div>
        </div>
    </div>
</div>

<!-- ════════════════════════════════ HEADER -->
<header class="navbar shadow-sm">

    <!-- Barre supérieure -->
    <div style="border-bottom:1px solid var(--border)">
        <div class="max-w-7xl mx-auto px-4 py-2 flex justify-between items-center text-xs" style="color:var(--muted)">
            <span style="font-family:'Source Sans 3',sans-serif"><?php $jours = ["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"]; $mois = ["","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"]; echo $jours[date("N")-1] . " " . date("d") . " " . $mois[(int)date("n")] . " " . date("Y"); ?></span>
            <div class="flex items-center gap-4">
                <?php if ($user): ?>
                    <span style="color:var(--text2)">
                        Bonjour, <strong style="color:var(--text)"><?= htmlspecialchars($user['username']) ?></strong>
                    </span>
                    <?php if ($isAdmin): ?>
                        <a href="<?= BASE_URL ?>/admin"
                           class="font-semibold"
                           style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                            Dashboard
                        </a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/profil"
                       style="color:var(--text2)" class="hover:text-brand1 transition-colors">Mon profil</a>
                    <a href="<?= BASE_URL ?>/logout"
                       style="color:var(--text2)" class="hover:text-brand1 transition-colors">Déconnexion</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login"    style="color:var(--text2)" class="hover:text-brand1 transition-colors">Connexion</a>
                    <span style="color:var(--border)">|</span>
                    <a href="<?= BASE_URL ?>/register" style="color:var(--text2)" class="hover:text-brand1 transition-colors">Inscription</a>
                <?php endif; ?>

                <!-- Toggle dark/light -->
                <button class="theme-toggle ml-2" onclick="toggleTheme()" title="Changer le thème" aria-label="Changer le thème">
                    <div class="toggle-track">
                        <!-- Nuages mode clair -->
                        <div class="toggle-cloud" style="width:18px;height:7px;bottom:8px;left:8px;"></div>
                        <div class="toggle-cloud" style="width:12px;height:5px;bottom:14px;left:14px;"></div>
                        <!-- Étoiles mode sombre -->
                        <div class="toggle-star" style="width:3px;height:3px;top:8px;right:10px;"></div>
                        <div class="toggle-star" style="width:2px;height:2px;top:14px;right:18px;"></div>
                        <div class="toggle-star" style="width:2px;height:2px;top:20px;right:12px;"></div>
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
        <a href="<?= BASE_URL ?>/" class="group flex items-center justify-center gap-5">
            <!-- Image logo -->
            <img src="<?= BASE_URL ?>/assets/images/Logo_Blog_couleur_avec_fond_blanc.png"
                 alt="Logo Bienvenue à Angoulême — le blog"
                 width="120" height="120"
                 class="rounded-full shadow-md group-hover:scale-105 transition-transform duration-300"
                 style="width:120px;height:120px;object-fit:cover;">
            <!-- Titre + tagline -->
            <div class="text-left">
                <h1 class="font-display text-3xl md:text-4xl font-black leading-tight brand-gradient-text">
                    Bienvenue à Angoulême
                </h1>
                <div class="flex items-center gap-3 mt-1">
                    <div class="h-px w-8" style="background:linear-gradient(to right,#1d8fd8,#22d3ee)"></div>
                    <span class="text-xs tracking-widest uppercase" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">Actualités · Culture · Vie locale</span>
                </div>
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="max-w-7xl mx-auto px-4" style="border-top:1px solid var(--border)">
        <ul class="flex items-center gap-6 py-3 overflow-x-auto" style="font-family:'Source Sans 3',sans-serif;">
            <?php
            $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $navItems = [
                ['url'=>'/',            'label'=>'Accueil'],
                ['url'=>'/blog',         'label'=>'Blog'],
                ['url'=>'/categories',   'label'=>'Catégories'],
                ['url'=>'/agenda',       'label'=>'Agenda'],
                ['url'=>'/a-propos',     'label'=>'À propos'],
                ['url'=>'/contact',      'label'=>'Contact'],
            ];
            foreach($navItems as $item):
                $isActive = rtrim($currentUri,'/') === rtrim(BASE_URL.$item['url'],'/');
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

<!-- ════════════════════════════════ FLASH -->
<?php $flashes = Flash::get(); ?>
<?php if (!empty($flashes)): ?>
<div class="max-w-7xl mx-auto px-4 pt-4 space-y-2">
    <?php foreach ($flashes as $type => $messages): ?>
        <?php foreach ($messages as $msg): ?>
        <div class="flash-<?= $type ?> px-4 py-3 rounded text-sm" style="font-family:'Source Sans 3',sans-serif;">
            <?= htmlspecialchars($msg) ?>
        </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ════════════════════════════════ CONTENU -->
<main class="flex-1 max-w-7xl mx-auto w-full px-4 py-8">
    <?php require $viewPath; ?>
</main>

<!-- ════════════════════════════════ FOOTER -->
<footer style="background:var(--bg2); border-top:2px solid; border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1; margin-top:auto;">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            <!-- Colonne 1 — Logo -->
            <div class="flex flex-col items-start gap-3">
                <img src="<?= BASE_URL ?>/assets/images/Logo_Blog_couleur_avec_fond_blanc.png"
                     alt="Logo Bienvenue à Angoulême — le blog"
                     width="64" height="64"
                     class="rounded-full shadow"
                     style="width:64px;height:64px;object-fit:cover;">
                <div>
                    <h3 class="font-display text-base font-bold brand-gradient-text">Bienvenue à Angoulême</h3>
                    <p class="text-sm leading-relaxed mt-1" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                        Le blog indépendant dédié à la vie locale d'Angoulême et de la Charente.
                    </p>
                </div>
            </div>

            <!-- Colonne 2 — Navigation -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-3 brand-gradient-text" style="font-family:'Source Sans 3',sans-serif;">Navigation</h4>
                <ul class="space-y-2 text-sm" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                    <?php foreach($navItems as $item): ?>
                    <li><a href="<?= BASE_URL . $item['url'] ?>" class="hover:text-brand1 transition-colors"><?= $item['label'] ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Colonne 3 — Légal -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-3 brand-gradient-text" style="font-family:'Source Sans 3',sans-serif;">Informations légales</h4>
                <ul class="space-y-2 text-sm" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                    <li><a href="<?= BASE_URL ?>/mentions-legales"   class="hover:text-brand1 transition-colors">Mentions légales</a></li>
                    <li><a href="<?= BASE_URL ?>/politique-confidentialite" class="hover:text-brand1 transition-colors">Politique de confidentialité</a></li>
                    <li><a href="<?= BASE_URL ?>/politique-cookies"  class="hover:text-brand1 transition-colors">Politique des cookies</a></li>
                    <li><a href="<?= BASE_URL ?>/rgpd"               class="hover:text-brand1 transition-colors">RGPD</a></li>
                    <li>
                        <button onclick="document.getElementById('cookie-banner').style.display='block'"
                                class="hover:text-brand1 transition-colors text-left">
                            Gérer mes cookies
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Colonne 4 — Réseaux sociaux -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-3 brand-gradient-text" style="font-family:'Source Sans 3',sans-serif;">Suivez-nous</h4>
                <div class="flex flex-wrap gap-3">
                    <?php
                    $socials = [
                        ['name'=>'Facebook',  'icon'=>'f', 'url'=>'#', 'color'=>'#1877f2'],
                        ['name'=>'Instagram', 'icon'=>'📷','url'=>'#', 'color'=>'#e1306c'],
                        ['name'=>'TikTok',    'icon'=>'♪', 'url'=>'#', 'color'=>'#010101'],
                        ['name'=>'YouTube',   'icon'=>'▶', 'url'=>'#', 'color'=>'#ff0000'],
                    ];
                    foreach($socials as $s): ?>
                    <a href="<?= $s['url'] ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       title="<?= $s['name'] ?>"
                       class="flex items-center justify-center w-10 h-10 rounded-full text-white font-bold text-sm transition-all hover:scale-110 hover:shadow-lg"
                       style="background:<?= $s['color'] ?>">
                        <?= $s['icon'] ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <p class="text-xs mt-4" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                    Rejoignez notre communauté locale !
                </p>
            </div>
        </div>

        <div class="mt-8 pt-6 text-center text-xs" style="border-top:1px solid var(--border); color:var(--muted); font-family:'Source Sans 3',sans-serif;">
            © <?= date('Y') ?> Bienvenue à Angoulême — Tous droits réservés —
            <a href="<?= BASE_URL ?>/mentions-legales" class="hover:text-brand1 transition-colors ml-1">Mentions légales</a>
        </div>
    </div>
</footer>

<!-- ════════════════════════════════ JS -->
<script>
// ── Thème dark/light ──────────────────────────────
function applyTheme(dark) {
    document.documentElement.classList.toggle('dark', dark);
    const thumb = document.querySelector('.toggle-thumb');
    const sun   = document.querySelector('.sun-icon');
    const moon  = document.querySelector('.moon-icon');
    if (sun && moon) {
        sun.style.display  = dark ? 'none'  : 'inline';
        moon.style.display = dark ? 'inline': 'none';
    }
}
function toggleTheme() {
    const isDark = !document.documentElement.classList.contains('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    applyTheme(isDark);
}
// Init thème
(function() {
    const saved = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyTheme(saved ? saved === 'dark' : prefersDark);
})();

// ── Cookies ───────────────────────────────────────
const COOKIE_KEY = 'bwa_cookies_choice';

function cookieAccept() {
    localStorage.setItem(COOKIE_KEY, JSON.stringify({choice:'accepted', youtube:true, vimeo:true, dailymotion:true}));
    hideCookieBanner();
}
function cookieDeny() {
    localStorage.setItem(COOKIE_KEY, JSON.stringify({choice:'denied', youtube:false, vimeo:false, dailymotion:false}));
    hideCookieBanner();
}
function cookieDetails() {
    hideCookieBanner();
    document.getElementById('cookie-modal').style.display = 'flex';
}
function closeCookieModal() {
    document.getElementById('cookie-modal').style.display = 'none';
}
function cookieAcceptAll() {
    ['youtube','vimeo','dailymotion'].forEach(s => {
        document.querySelector('.cookie-btn-allow-' + s)?.classList.add('ring-2','ring-white');
        document.querySelector('.cookie-btn-deny-'  + s)?.style.setProperty('opacity', '.5');
    });
    saveCookieChoices(true);
}
function cookieDenyAll() {
    saveCookieChoices(false);
}
function toggleCookie(service, allow, btn) {
    // Feedback visuel
    const allowBtn = document.querySelector('.cookie-btn-allow-' + service);
    const denyBtn  = document.querySelector('.cookie-btn-deny-'  + service);
    if (allow) {
        allowBtn && (allowBtn.style.outline = '2px solid white');
        denyBtn  && (denyBtn.style.opacity  = '.6');
    } else {
        denyBtn  && (denyBtn.style.outline  = '2px solid white');
        allowBtn && (allowBtn.style.opacity = '.6');
    }
}
function saveCookieChoices(all = null) {
    const choices = { choice: 'custom' };
    ['youtube','vimeo','dailymotion'].forEach(s => {
        choices[s] = all === null
            ? document.querySelector('.cookie-btn-allow-' + s)?.style.outline !== ''
            : all;
    });
    localStorage.setItem(COOKIE_KEY, JSON.stringify(choices));
    closeCookieModal();
}
function hideCookieBanner() {
    document.getElementById('cookie-banner').style.display = 'none';
}
// Init cookies
(function() {
    if (!localStorage.getItem(COOKIE_KEY)) {
        setTimeout(() => {
            document.getElementById('cookie-banner').style.display = 'block';
        }, 1200);
    }
})();

// ── Déconnexion automatique si quitte le site ─────
<?php if ($user): ?>
window.addEventListener('beforeunload', function() {
    // Utilise sendBeacon pour être fiable même en fermeture d'onglet
    navigator.sendBeacon('<?= BASE_URL ?>/logout-beacon');
});
// Annuler si navigation interne
document.addEventListener('click', function(e) {
    const a = e.target.closest('a');
    if (a && a.href && a.href.includes(window.location.hostname)) {
        window.removeEventListener('beforeunload', arguments.callee);
    }
});
<?php endif; ?>
</script>

</body>
</html>