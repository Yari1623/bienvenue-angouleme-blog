<?php
// views/admin/events/create.php
$pageTitle = 'Nouvel événement — Admin';
?>

<div class="max-w-lg mx-auto space-y-6">

    <div class="flex items-center justify-between pb-4" style="border-bottom:2px solid var(--border)">
        <h2 class="font-display text-2xl font-black" style="color:var(--text)">Nouvel événement</h2>
        <a href="<?= BASE_URL ?>/admin/events" class="text-sm transition-colors"
           style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">← Retour</a>
    </div>

    <div class="surface rounded-xl p-6">
        <form method="POST" action="<?= BASE_URL ?>/admin/events/store" class="space-y-5">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    Titre <span style="color:#1d8fd8">*</span>
                </label>
                <input type="text" name="title" required autofocus
                       placeholder="Nom de l'événement"
                       class="w-full rounded-lg px-4 py-3 text-sm"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:2px solid transparent;outline-offset:2px;"
                       onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Description</label>
                <textarea name="description" rows="3"
                          placeholder="Décrivez l'événement…"
                          class="w-full rounded-lg px-4 py-3 text-sm resize-none"
                          style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:2px solid transparent;outline-offset:2px;"
                          onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                        Date <span style="color:#1d8fd8">*</span>
                    </label>
                    <input type="date" name="event_date" required
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:2px solid transparent;outline-offset:2px;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Heure</label>
                    <input type="time" name="event_time"
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:2px solid transparent;outline-offset:2px;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Lieu</label>
                <input type="text" name="location"
                       placeholder="Ex : Place du Champ de Mars, Angoulême"
                       class="w-full rounded-lg px-4 py-3 text-sm"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:2px solid transparent;outline-offset:2px;"
                       onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
            </div>

            <div class="flex justify-between pt-2">
                <a href="<?= BASE_URL ?>/admin/events" class="btn-ghost text-sm">Annuler</a>
                <button type="submit" class="btn-primary text-sm">Créer l'événement</button>
            </div>
        </form>
    </div>
</div>