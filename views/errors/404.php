<?php
// views/errors/404.php
$pageTitle = '404 — Page introuvable';
?>
 
<div class="text-center py-24">
 
    <!-- Grand 404 décoratif -->
    <div class="font-display text-9xl font-black leading-none select-none"
         style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;opacity:.15;">
        404
    </div>
 
    <h2 class="font-display text-3xl font-bold mt-4 mb-3" style="color:var(--text)">
        Page introuvable
    </h2>
 
    <p class="max-w-md mx-auto mb-8 text-sm leading-relaxed"
       style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
        La page que vous recherchez n'existe pas ou a été déplacée.
    </p>
 
    <div class="flex flex-wrap justify-center gap-4">
        <a href="<?= BASE_URL ?>/" class="btn-primary px-8 py-3">
            ← Retour à l'accueil
        </a>
        <a href="<?= BASE_URL ?>/blog" class="btn-outline px-8 py-3">
            Voir le blog
        </a>
    </div>
 
</div>
 