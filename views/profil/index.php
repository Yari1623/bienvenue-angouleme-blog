<?php
// views/profile/index.php
$pageTitle = 'Mon profil — Bienvenue à Angoulême';
use App\Core\Auth;
$user = Auth::user();
?>

<div class="space-y-8">

    <!-- ═══ EN-TÊTE PROFIL -->
    <div class="surface rounded-xl overflow-hidden">
        <div class="h-24 w-full" style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)"></div>
        <div class="px-6 pb-6">
            <div class="flex flex-col md:flex-row items-center md:items-end gap-4 -mt-10">
                <!-- Avatar -->
                <div class="w-20 h-20 rounded-full flex items-center justify-center text-3xl text-white font-bold shadow-lg border-4"
                     style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);border-color:var(--surface)">
                    <?= strtoupper(mb_substr($user['username'] ?? 'U', 0, 1)) ?>
                </div>
                <!-- Infos -->
                <div class="flex-1 text-center md:text-left">
                    <h2 class="font-display text-2xl font-bold" style="color:var(--text)">
                        <?= htmlspecialchars(trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''))) ?: htmlspecialchars($user['username']) ?>
                    </h2>
                    <p class="text-sm" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                        @<?= htmlspecialchars($user['username']) ?>
                        · Membre depuis <?= date('F Y', strtotime($user['created_at'] ?? 'now')) ?>
                    </p>
                </div>
                <!-- Badge rôle -->
                <span class="px-4 py-1.5 rounded-full text-xs font-bold text-white"
                      style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);font-family:'Source Sans 3',sans-serif;">
                    <?= ucfirst($user['role'] ?? 'member') ?>
                </span>
            </div>
        </div>
    </div>

    <!-- ═══ STATS RAPIDES -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php
        $stats = [
            ['icon'=>'❤️',  'label'=>'Likes donnés',      'value'=> count($userLikes ?? [])],
            ['icon'=>'💬',  'label'=>'Commentaires',       'value'=> count($userComments ?? [])],
            ['icon'=>'📖',  'label'=>'Articles lus',       'value'=> count($recentViews ?? [])],
            ['icon'=>'📅',  'label'=>'Événements suivis',  'value'=> count($interestedEvents ?? [])],
        ];
        foreach($stats as $s): ?>
        <div class="surface rounded-xl p-4 text-center">
            <div class="text-3xl mb-1"><?= $s['icon'] ?></div>
            <div class="font-display text-2xl font-black" style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                <?= $s['value'] ?>
            </div>
            <div class="text-xs mt-1" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;"><?= $s['label'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- ═══ CONTENU PRINCIPAL -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Colonne gauche — Infos + derniers likes -->
        <div class="space-y-6">

            <!-- Informations personnelles -->
            <div class="surface rounded-xl p-5">
                <h3 class="font-display text-base font-bold mb-4 pb-2" style="color:var(--text);border-bottom:2px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
                    Mes informations
                </h3>
                <ul class="space-y-3 text-sm" style="font-family:'Source Sans 3',sans-serif;">
                    <?php
                    $fields = [
                        ['icon'=>'👤', 'label'=>'Pseudo',     'value'=> $user['username']],
                        ['icon'=>'📧', 'label'=>'Email',      'value'=> $user['email']],
                        ['icon'=>'🏢', 'label'=>'Entreprise', 'value'=> $user['company'] ?? '—'],
                        ['icon'=>'📞', 'label'=>'Téléphone',  'value'=> $user['phone'] ?? '—'],
                    ];
                    foreach($fields as $f): ?>
                    <li class="flex items-center gap-3">
                        <span class="text-lg shrink-0"><?= $f['icon'] ?></span>
                        <div>
                            <div class="text-xs" style="color:var(--muted)"><?= $f['label'] ?></div>
                            <div class="font-semibold" style="color:var(--text)"><?= htmlspecialchars($f['value'] ?? '—') ?></div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Articles likés -->
            <div class="surface rounded-xl p-5">
                <h3 class="font-display text-base font-bold mb-4 pb-2" style="color:var(--text);border-bottom:2px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
                    ❤️ Mes likes
                </h3>
                <?php if (!empty($userLikes)): ?>
                <ul class="space-y-3">
                    <?php foreach(array_slice($userLikes, 0, 5) as $post): ?>
                    <li>
                        <a href="<?= BASE_URL ?>/article/<?= htmlspecialchars($post['slug']) ?>"
                           class="text-sm font-semibold hover:underline block"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                        <span class="text-xs" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                            <?= date('d/m/Y', strtotime($post['created_at'])) ?>
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="text-sm text-center py-4" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                    Vous n'avez pas encore liké d'article.
                </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Colonne droite — Articles lus + Événements -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Derniers articles lus -->
            <div class="surface rounded-xl p-5">
                <h3 class="font-display text-base font-bold mb-4 pb-2" style="color:var(--text);border-bottom:2px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
                    📖 Derniers articles lus
                </h3>
                <?php if (!empty($recentViews)): ?>
                <div class="space-y-3">
                    <?php foreach(array_slice($recentViews, 0, 6) as $post): ?>
                    <div class="flex items-center gap-4 p-3 rounded-lg transition-colors" style="background:var(--bg)">
                        <?php if (!empty($post['thumbnail'])): ?>
                        <img src="<?= htmlspecialchars($post['thumbnail']) ?>"
                             alt="<?= htmlspecialchars($post['title']) ?>"
                             class="w-14 h-14 rounded-lg object-cover shrink-0">
                        <?php else: ?>
                        <div class="w-14 h-14 rounded-lg shrink-0 flex items-center justify-center text-2xl"
                             style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">📰</div>
                        <?php endif; ?>
                        <div class="flex-1 min-w-0">
                            <a href="<?= BASE_URL ?>/article/<?= htmlspecialchars($post['slug']) ?>"
                               class="font-semibold text-sm hover:underline block truncate"
                               style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                            <?php if (!empty($post['category_name'])): ?>
                            <span class="text-xs px-2 py-0.5 rounded-full text-white mt-1 inline-block"
                                  style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);font-family:'Source Sans 3',sans-serif;">
                                <?= htmlspecialchars($post['category_name']) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        <span class="text-xs shrink-0" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                            <?= date('d/m', strtotime($post['viewed_at'] ?? $post['created_at'])) ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="text-sm text-center py-4" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                    Vous n'avez pas encore lu d'article.
                </p>
                <?php endif; ?>
            </div>

            <!-- Événements à venir qui m'intéressent -->
            <div class="surface rounded-xl p-5">
                <h3 class="font-display text-base font-bold mb-4 pb-2" style="color:var(--text);border-bottom:2px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
                    📅 Mes événements à venir
                </h3>
                <?php if (!empty($interestedEvents)): ?>
                <div class="space-y-3">
                    <?php foreach($interestedEvents as $event): ?>
                    <div class="flex items-center gap-4 p-3 rounded-lg" style="background:var(--bg)">
                        <div class="shrink-0 w-12 h-12 rounded-lg flex flex-col items-center justify-center text-white"
                             style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
                            <span class="font-black text-lg leading-none"><?= date('d', strtotime($event['event_date'])) ?></span>
                            <span class="text-xs uppercase"><?= date('M', strtotime($event['event_date'])) ?></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm truncate" style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                                <?= htmlspecialchars($event['title']) ?>
                            </p>
                            <?php if (!empty($event['location'])): ?>
                            <p class="text-xs" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                                📍 <?= htmlspecialchars($event['location']) ?>
                            </p>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($event['event_time'])): ?>
                        <span class="text-xs font-semibold shrink-0" style="color:#1d8fd8;font-family:'Source Sans 3',sans-serif;">
                            <?= substr($event['event_time'], 0, 5) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <a href="<?= BASE_URL ?>/agenda" class="text-xs mt-3 inline-block" style="color:#1d8fd8;font-family:'Source Sans 3',sans-serif;">
                    Voir tout l'agenda →
                </a>
                <?php else: ?>
                <p class="text-sm text-center py-4" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                    Vous ne suivez aucun événement pour le moment.
                    <a href="<?= BASE_URL ?>/agenda" class="underline" style="color:#1d8fd8">Voir l'agenda</a>
                </p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>