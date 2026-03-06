<?php
// views/events/index.php
$pageTitle = 'Agenda — Bienvenue à Angoulême';
use App\Core\Auth;
use App\Core\Csrf;
?>

<div class="space-y-8">

    <div class="border-b-2 border-ink pb-4">
        <h2 class="font-display text-2xl font-black text-ink">Agenda</h2>
        <p class="font-body text-sm text-muted mt-1">Les événements à venir à Angoulême et en Charente</p>
    </div>

    <?php if (empty($events)): ?>
    <div class="text-center py-16 text-muted font-body">
        <p class="text-5xl mb-4">📅</p>
        <p class="text-lg">Aucun événement prévu pour le moment.</p>
    </div>
    <?php else: ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($events as $event):
            $isPast = strtotime($event['event_date']) < strtotime('today');
        ?>
        <div class="bg-white border border-border overflow-hidden post-card <?= $isPast ? 'opacity-60' : '' ?>">

            <!-- Date banner -->
            <div class="bg-<?= $isPast ? 'muted' : 'ink' ?> text-paper px-5 py-4 flex items-center justify-between"
                 style="background-color: <?= $isPast ? '#8a8070' : '#1a1a2e' ?>">
                <div>
                    <div class="font-display text-3xl font-black leading-none">
                        <?= date('d', strtotime($event['event_date'])) ?>
                    </div>
                    <div class="font-body text-xs uppercase tracking-widest text-paper/70 mt-0.5">
                        <?= date('M Y', strtotime($event['event_date'])) ?>
                    </div>
                </div>
                <?php if (!empty($event['event_time'])): ?>
                <div class="text-right">
                    <div class="font-body text-sm font-semibold text-paper/80">
                        <?= substr($event['event_time'], 0, 5) ?>
                    </div>
                    <div class="text-xs text-paper/50">heure</div>
                </div>
                <?php endif; ?>
            </div>

            <div class="p-5">
                <h3 class="font-display text-lg font-bold text-ink mb-2">
                    <?= htmlspecialchars($event['title']) ?>
                </h3>

                <?php if (!empty($event['location'])): ?>
                <p class="text-xs font-body text-muted mb-3">
                    📍 <?= htmlspecialchars($event['location']) ?>
                </p>
                <?php endif; ?>

                <?php if (!empty($event['description'])): ?>
                <p class="text-sm font-body text-ink/70 leading-relaxed mb-4">
                    <?= nl2br(htmlspecialchars($event['description'])) ?>
                </p>
                <?php endif; ?>

                <!-- Intérêt -->
                <?php if (!$isPast): ?>
                <div class="flex items-center justify-between border-t border-border pt-3 mt-3">
                    <span class="text-xs font-body text-muted">
                        ★ <?= $event['interest_count'] ?? 0 ?> personne<?= ($event['interest_count'] ?? 0) > 1 ? 's' : '' ?> intéressée<?= ($event['interest_count'] ?? 0) > 1 ? 's' : '' ?>
                    </span>
                    <?php if (Auth::check()): ?>
                    <form method="POST" action="<?= BASE_URL ?>/agenda/<?= $event['id'] ?>/interest">
                        <input type="hidden" name="_csrf" value="<?= Csrf::generate() ?>">
                        <button class="text-xs font-body font-semibold px-3 py-1.5 border transition-colors
                            <?= Auth::check() ? 'border-accent text-accent hover:bg-accent hover:text-paper' : 'border-border text-muted' ?>">
                            ★ Je suis intéressé
                        </button>
                    </form>
                    <?php else: ?>
                    <a href="<?= BASE_URL ?>/login"
                       class="text-xs font-body text-muted hover:text-accent transition-colors">
                        Connectez-vous pour marquer votre intérêt
                    </a>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="border-t border-border pt-3 mt-3">
                    <span class="text-xs font-body text-muted italic">Événement passé</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>
</div>