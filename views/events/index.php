<?php
// views/events/index.php
$pageTitle = 'Agenda — Bienvenue à Angoulême';
use App\Core\Auth;
use App\Core\Csrf;
 
$moisCourt = ['','jan.','fév.','mar.','avr.','mai','juin','juil.','août','sep.','oct.','nov.','déc.'];
?>
 
<div class="space-y-8">
 
    <div class="pb-4" style="border-bottom:2px solid var(--border)">
        <h2 class="font-display text-2xl font-black" style="color:var(--text)">Agenda</h2>
        <p class="text-sm mt-1" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
            Les événements à venir à Angoulême et en Charente
        </p>
    </div>
 
    <?php if (empty($events)): ?>
    <div class="text-center py-16" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
        <p class="text-5xl mb-4">📅</p>
        <p class="text-lg">Aucun événement prévu pour le moment.</p>
    </div>
    <?php else: ?>
 
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($events as $event):
            $isPast  = strtotime($event['event_date']) < strtotime('today');
            $ts      = strtotime($event['event_date']);
            $jour    = date('d', $ts);
            $moisStr = strtoupper($moisCourt[(int)date('n', $ts)] . '. ' . date('Y', $ts));
            // Gradient bandeau : gris si passé, brand si à venir
            $bandeauBg = $isPast
                ? 'background:linear-gradient(135deg,#6b7280,#9ca3af);'
                : 'background:linear-gradient(135deg,#1d8fd8,#22d3ee);';
        ?>
        <div class="rounded-2xl overflow-hidden <?= $isPast ? 'opacity-70' : '' ?>"
             style="background:var(--surface);border:1px solid var(--border);
                    transition:transform .2s,box-shadow .2s,border-color .2s;"
             onmouseover="<?= !$isPast ? "this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(29,143,216,.2)';this.style.borderColor='#1d8fd8'" : '' ?>"
             onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--border)'">
 
            <!-- Bandeau date — gradient brand -->
            <div class="px-5 py-4 flex items-center justify-between"
                 style="<?= $bandeauBg ?>">
                <div>
                    <div class="font-display text-3xl font-black leading-none text-white">
                        <?= $jour ?>
                    </div>
                    <div class="text-xs uppercase tracking-widest mt-0.5 text-white/80"
                         style="font-family:'Source Sans 3',sans-serif;">
                        <?= $moisStr ?>
                    </div>
                </div>
                <?php if (!empty($event['event_time'])): ?>
                <div class="text-right">
                    <div class="text-sm font-bold text-white"
                         style="font-family:'Source Sans 3',sans-serif;">
                        <?= substr($event['event_time'], 0, 5) ?>
                    </div>
                    <div class="text-xs text-white/60" style="font-family:'Source Sans 3',sans-serif;">
                        heure
                    </div>
                </div>
                <?php endif; ?>
            </div>
 
            <!-- Corps de la card -->
            <div class="p-5">
                <h3 class="font-display text-lg font-bold mb-2" style="color:var(--text)">
                    <?= htmlspecialchars($event['title']) ?>
                </h3>
 
                <?php if (!empty($event['location'])): ?>
                <p class="text-xs mb-3 flex items-center gap-1"
                   style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                    📍 <?= htmlspecialchars($event['location']) ?>
                </p>
                <?php endif; ?>
 
                <?php if (!empty($event['description'])): ?>
                <p class="text-sm leading-relaxed mb-4"
                   style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                    <?= nl2br(htmlspecialchars($event['description'])) ?>
                </p>
                <?php endif; ?>
 
                <!-- Bas de card : intérêts + bouton -->
                <div class="flex items-center justify-between pt-3 mt-auto"
                     style="border-top:1px solid var(--border)">
 
                    <?php $count = $event['interest_count'] ?? 0; ?>
 
                    <?php if ($isPast): ?>
                        <span class="text-xs italic" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                            Événement passé
                        </span>
                        <span class="text-xs" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                            ★ <?= $count ?> intéressé<?= $count > 1 ? 's' : '' ?>
                        </span>
 
                    <?php else: ?>
                        <span class="text-xs" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                            ★ <?= $count ?> intéressé<?= $count > 1 ? 's' : '' ?>
                        </span>
 
                        <?php if (Auth::check()): ?>
                        <form method="POST" action="<?= BASE_URL ?>/agenda/<?= $event['id'] ?>/interest">
                            <input type="hidden" name="_csrf" value="<?= Csrf::generate() ?>">
                            <button type="submit"
                                    class="text-xs font-semibold px-4 py-1.5 rounded-full transition-all"
                                    style="background:transparent;border:1.5px solid #1d8fd8;color:#1d8fd8;font-family:'Source Sans 3',sans-serif;"
                                    onmouseover="this.style.background='linear-gradient(135deg,#1d8fd8,#22d3ee)';this.style.color='white';this.style.borderColor='transparent'"
                                    onmouseout="this.style.background='transparent';this.style.color='#1d8fd8';this.style.borderColor='#1d8fd8'">
                                ★ Je suis intéressé
                            </button>
                        </form>
                        <?php else: ?>
                        <a href="<?= BASE_URL ?>/login"
                           class="text-xs transition-colors"
                           style="color:#1d8fd8;font-family:'Source Sans 3',sans-serif;">
                            Connexion →
                        </a>
                        <?php endif; ?>
                    <?php endif; ?>
 
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
 
    <?php endif; ?>
</div>