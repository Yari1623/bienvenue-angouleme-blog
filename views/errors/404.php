<?php
// views/errors/404.php
$pageTitle = '404 — Page introuvable';
?>

<div class="text-center py-24">
    <div class="font-display text-9xl font-black text-ink/10 leading-none select-none">404</div>
    <h2 class="font-display text-3xl font-bold text-ink mt-4 mb-3">Page introuvable</h2>
    <p class="font-body text-muted mb-8 max-w-md mx-auto">
        La page que vous recherchez n'existe pas ou a été déplacée.
    </p>
    <a href="<?= BASE_URL ?>/"
       class="inline-block px-8 py-3 bg-ink text-paper font-body font-semibold text-sm hover:bg-accent transition-colors">
        ← Retour à l'accueil
    </a>
</div>
