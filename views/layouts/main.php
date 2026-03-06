<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Bienvenue à Angoulême' ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts : Playfair Display + Source Sans 3 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Playfair Display"', 'Georgia', 'serif'],
                        body:    ['"Source Sans 3"', 'sans-serif'],
                    },
                    colors: {
                        ink:     '#1a1a2e',
                        paper:   '#f5f0e8',
                        accent:  '#c8392b',
                        muted:   '#8a8070',
                        border:  '#d4c9b8',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Source Sans 3', sans-serif; background-color: #f5f0e8; color: #1a1a2e; }
        h1, h2, h3, h4 { font-family: 'Playfair Display', serif; }

        /* Ligne décorative header */
        .header-rule { background: repeating-linear-gradient(90deg, #1a1a2e 0px, #1a1a2e 2px, transparent 2px, transparent 8px); height: 3px; }

        /* Hover liens nav */
        .nav-link { position: relative; }
        .nav-link::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; background: #c8392b; transition: width .25s ease; }
        .nav-link:hover::after { width: 100%; }

        /* Flash messages */
        .flash-success { background: #f0fdf4; border-left: 4px solid #22c55e; color: #166534; }
        .flash-error   { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; }

        /* Card article */
        .post-card { transition: transform .2s ease, box-shadow .2s ease; }
        .post-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(26,26,46,.12); }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f5f0e8; }
        ::-webkit-scrollbar-thumb { background: #d4c9b8; border-radius: 3px; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

<?php
use App\Core\Auth;
use App\Core\Flash;
$user   = Auth::user();
$isAdmin = Auth::isAdmin();
?>

<!-- ═══════════════════════════════════════════════ HEADER -->
<header class="bg-paper border-b border-border">

    <!-- Bandeau supérieur -->
    <div class="border-b border-border">
        <div class="max-w-7xl mx-auto px-4 py-2 flex justify-between items-center text-xs text-muted font-body">
            <span><?= date('l d F Y') ?></span>
            <div class="flex items-center gap-4">
                <?php if ($user): ?>
                    <span>Bonjour, <strong class="text-ink"><?= htmlspecialchars($user['username']) ?></strong></span>
                    <?php if ($isAdmin): ?>
                        <a href="<?= BASE_URL ?>/admin" class="text-accent hover:underline font-semibold">Dashboard</a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/logout" class="hover:text-accent transition-colors">Déconnexion</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login"    class="hover:text-accent transition-colors">Connexion</a>
                    <span class="text-border">|</span>
                    <a href="<?= BASE_URL ?>/register" class="hover:text-accent transition-colors">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Logo -->
    <div class="max-w-7xl mx-auto px-4 py-6 text-center">
        <a href="<?= BASE_URL ?>/" class="group inline-block">
            <div class="text-xs font-body font-semibold tracking-[.35em] text-muted uppercase mb-1">Le blog local de</div>
            <h1 class="font-display text-4xl md:text-5xl font-black text-ink leading-none tracking-tight group-hover:text-accent transition-colors duration-300">
                Bienvenue à Angoulême
            </h1>
            <div class="flex items-center justify-center gap-3 mt-2">
                <div class="h-px flex-1 max-w-16 bg-border"></div>
                <span class="text-xs font-body tracking-widest text-muted uppercase">Actualités · Culture · Vie locale</span>
                <div class="h-px flex-1 max-w-16 bg-border"></div>
            </div>
        </a>
    </div>

    <div class="header-rule"></div>

    <!-- Navigation principale -->
    <nav class="max-w-7xl mx-auto px-4">
        <ul class="flex items-center gap-8 py-3 text-sm font-body font-semibold text-ink overflow-x-auto">
            <li><a href="<?= BASE_URL ?>/"          class="nav-link hover:text-accent transition-colors">Accueil</a></li>
            <li><a href="<?= BASE_URL ?>/blog"       class="nav-link hover:text-accent transition-colors">Blog</a></li>
            <li><a href="<?= BASE_URL ?>/agenda"     class="nav-link hover:text-accent transition-colors">Agenda</a></li>
            <li class="ml-auto text-muted font-normal text-xs tracking-wider uppercase">Catégories :</li>
            <?php
            // Affichage des catégories dans la nav si disponibles
            if (!empty($categories ?? [])):
                foreach (array_slice($categories, 0, 6) as $cat):
            ?>
            <li>
                <a href="<?= BASE_URL ?>/categorie/<?= htmlspecialchars($cat['slug']) ?>"
                   class="nav-link hover:text-accent transition-colors whitespace-nowrap">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            </li>
            <?php endforeach; endif; ?>
        </ul>
    </nav>

    <div class="header-rule opacity-30"></div>
</header>

<!-- ═══════════════════════════════════════════════ FLASH -->
<?php $flashes = Flash::get(); ?>
<?php if (!empty($flashes)): ?>
<div class="max-w-7xl mx-auto px-4 pt-4 space-y-2">
    <?php foreach ($flashes as $type => $messages): ?>
        <?php foreach ($messages as $message): ?>
            <div class="flash-<?= $type ?> px-4 py-3 rounded text-sm font-body">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════ CONTENU -->
<main class="flex-1 max-w-7xl mx-auto w-full px-4 py-8">
    <?php require $viewPath; ?>
</main>

<!-- ═══════════════════════════════════════════════ FOOTER -->
<footer class="bg-ink text-paper mt-auto">
    <div class="header-rule opacity-20"></div>
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Colonne 1 -->
            <div>
                <h3 class="font-display text-xl font-bold mb-3">Bienvenue à Angoulême</h3>
                <p class="text-sm text-paper/60 font-body leading-relaxed">
                    Le blog indépendant dédié à la vie locale d'Angoulême et de la Charente.
                    Actualités, culture, événements et bien plus.
                </p>
            </div>

            <!-- Colonne 2 -->
            <div>
                <h4 class="font-display text-sm font-bold uppercase tracking-widest mb-3 text-paper/50">Navigation</h4>
                <ul class="space-y-2 text-sm font-body text-paper/70">
                    <li><a href="<?= BASE_URL ?>/"       class="hover:text-accent transition-colors">Accueil</a></li>
                    <li><a href="<?= BASE_URL ?>/blog"    class="hover:text-accent transition-colors">Blog</a></li>
                    <li><a href="<?= BASE_URL ?>/agenda"  class="hover:text-accent transition-colors">Agenda</a></li>
                    <li><a href="<?= BASE_URL ?>/login"   class="hover:text-accent transition-colors">Connexion</a></li>
                </ul>
            </div>

            <!-- Colonne 3 -->
            <div>
                <h4 class="font-display text-sm font-bold uppercase tracking-widest mb-3 text-paper/50">À propos</h4>
                <p class="text-sm font-body text-paper/60 leading-relaxed">
                    Blog réalisé dans le cadre du titre professionnel DWWM.<br>
                    Développé en PHP MVC, Tailwind CSS, Chart.js.
                </p>
            </div>
        </div>

        <div class="border-t border-paper/10 mt-8 pt-6 text-center text-xs font-body text-paper/40">
            © <?= date('Y') ?> Bienvenue à Angoulême — Tous droits réservés
        </div>
    </div>
</footer>

</body>
</html>