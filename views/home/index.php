<?php
// views/home/index.php
$pageTitle = 'Bienvenue à Angoulême — Le blog local';
 
/**
 * Helper date FR — protégé par function_exists pour éviter
 * la redéclaration si la vue est incluse dans un contexte
 * où dateFr() est déjà défini (ex: blog/index.php).
 *
 * CORRECTION passe 6 : ajout du guard function_exists manquant.
 */
if (!function_exists('dateFr')) {
    function dateFr(string $date): string {
        $mois = ['','jan.','fév.','mar.','avr.','mai','juin','juil.','août','sep.','oct.','nov.','déc.'];
        $d = date_create($date);
        return date_format($d,'d') . ' ' . $mois[(int)date_format($d,'n')] . ' ' . date_format($d,'Y');
    }
}
?>
 
<div class="space-y-14">
 
<!-- ═══════════════════════════════════════════════
     HERO
════════════════════════════════════════════════ -->
<section class="relative rounded-2xl overflow-hidden" style="min-height:420px;">
    <div class="absolute inset-0">
        <img src="<?= BASE_URL ?>/assets/images/panorama_angouleme.jpg"
             alt="Angoulême" class="w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(13,25,45,.82) 0%,rgba(29,143,216,.45) 60%,rgba(34,211,238,.2) 100%)"></div>
    </div>
 
    <div class="relative z-10 flex flex-col justify-center h-full px-8 md:px-16 py-16" style="min-height:420px;">
        <div class="max-w-2xl">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold text-white mb-4"
                  style="background:rgba(255,255,255,.18);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,.3);font-family:'Source Sans 3',sans-serif;">
                📍 Angoulême, Charente
            </span>
            <h2 class="font-display text-4xl md:text-5xl font-black text-white leading-tight mb-4">
                Découvrez<br>notre belle ville
            </h2>
            <p class="text-white/80 text-lg leading-relaxed mb-6 max-w-xl" style="font-family:'Source Sans 3',sans-serif;">
                Actualités locales, culture, événements, bons plans — tout ce qui fait vivre Angoulême au quotidien.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="<?= BASE_URL ?>/blog" class="btn-primary px-6 py-3">Lire le blog →</a>
                <a href="<?= BASE_URL ?>/agenda"
                   class="px-6 py-3 rounded-full font-semibold text-sm text-white transition-all hover:bg-white/20"
                   style="border:2px solid rgba(255,255,255,.6);font-family:'Source Sans 3',sans-serif;">
                    Voir l'agenda
                </a>
            </div>
        </div>
    </div>
 
    <!-- Stats flottantes (desktop uniquement) -->
    <div class="absolute bottom-4 right-6 hidden md:flex gap-4">
        <?php
        $statItems = [
            ['val' => $totalPosts   ?? 0, 'label' => 'Articles'],
            ['val' => $totalEvents  ?? 0, 'label' => 'Événements'],
            ['val' => $totalMembers ?? 0, 'label' => 'Membres'],
        ];
        foreach ($statItems as $s): ?>
        <div class="text-center px-4 py-2 rounded-xl"
             style="background:rgba(255,255,255,.12);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.2)">
            <div class="font-display text-2xl font-black text-white"><?= $s['val'] ?></div>
            <div class="text-xs text-white/70" style="font-family:'Source Sans 3',sans-serif;"><?= $s['label'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
 
<!-- ═══════════════════════════════════════════════
     HISTOIRE D'ANGOULÊME
════════════════════════════════════════════════ -->
<section class="surface rounded-2xl overflow-hidden">
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="relative" style="min-height:280px;">
            <img src="<?= BASE_URL ?>/assets/images/tour_mairie_angouleme.jpg"
                 alt="Histoire d'Angoulême"
                 class="w-full h-full object-cover absolute inset-0">
            <div class="absolute inset-0" style="background:linear-gradient(to right,transparent,var(--surface))"></div>
        </div>
        <div class="p-8 md:p-10 flex flex-col justify-center">
            <span class="text-xs font-bold uppercase tracking-widest mb-2"
                  style="color:#1d8fd8;font-family:'Source Sans 3',sans-serif;">Histoire & Patrimoine</span>
            <h2 class="font-display text-2xl font-black mb-4" style="color:var(--text)">
                Angoulême, une ville aux mille visages
            </h2>
            <p class="text-sm leading-relaxed mb-6" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                Perchée sur son éperon rocheux dominant la Charente, Angoulême est une ville d'art et d'histoire
                dont les origines remontent à l'Antiquité. Capitale mondiale de la bande dessinée, cité médiévale,
                carrefour industriel — elle conjugue tradition et modernité avec une élégance singulière.
            </p>
            <a href="<?= BASE_URL ?>/histoire-angouleme" class="btn-outline self-start">En savoir plus →</a>
        </div>
    </div>
</section>
 
<!-- ═══════════════════════════════════════════════
     DERNIERS ARTICLES
════════════════════════════════════════════════ -->
<?php if (!empty($latestPosts)): ?>
<section>
    <div class="flex items-center gap-4 mb-6">
        <h2 class="font-display text-2xl font-bold" style="color:var(--text)">Derniers articles</h2>
        <div class="flex-1 h-px" style="background:linear-gradient(to right,#1d8fd8,transparent)"></div>
        <a href="<?= BASE_URL ?>/blog" class="text-sm font-semibold" style="color:#1d8fd8;font-family:'Source Sans 3',sans-serif;">Voir tout →</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <?php foreach($latestPosts as $post): ?>
        <?php include __DIR__ . '/../partials/post-card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
 
<!-- ═══════════════════════════════════════════════
     ARTICLES LES PLUS LUS
════════════════════════════════════════════════ -->
<?php if (!empty($popularPosts)): ?>
<section>
    <div class="flex items-center gap-4 mb-6">
        <h2 class="font-display text-2xl font-bold" style="color:var(--text)">
            <span class="mr-2">👁</span>Les plus lus
        </h2>
        <div class="flex-1 h-px" style="background:linear-gradient(to right,#1d8fd8,transparent)"></div>
        <a href="<?= BASE_URL ?>/blog" class="text-sm font-semibold" style="color:#1d8fd8;font-family:'Source Sans 3',sans-serif;">Voir tout →</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <?php foreach($popularPosts as $post): ?>
        <?php include __DIR__ . '/../partials/post-card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
 
<!-- ═══════════════════════════════════════════════
     ARTICLES LES PLUS COMMENTÉS
════════════════════════════════════════════════ -->
<?php if (!empty($mostCommented)): ?>
<section>
    <div class="flex items-center gap-4 mb-6">
        <h2 class="font-display text-2xl font-bold" style="color:var(--text)">
            <span class="mr-2">💬</span>Les plus commentés
        </h2>
        <div class="flex-1 h-px" style="background:linear-gradient(to right,#1d8fd8,transparent)"></div>
        <a href="<?= BASE_URL ?>/blog" class="text-sm font-semibold" style="color:#1d8fd8;font-family:'Source Sans 3',sans-serif;">Voir tout →</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <?php foreach($mostCommented as $post): ?>
        <?php include __DIR__ . '/../partials/post-card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
 
</div>