<?php
// views/admin/events/create.php
$pageTitle = 'Nouvel événement — Admin';
?>

<div class="max-w-lg mx-auto space-y-6">

    <div class="flex items-center justify-between border-b-2 border-ink pb-4">
        <h2 class="font-display text-2xl font-black text-ink">Nouvel événement</h2>
        <a href="<?= BASE_URL ?>/admin/events" class="text-sm font-body text-muted hover:text-accent transition-colors">← Retour</a>
    </div>

    <div class="bg-white border border-border p-6">
        <form method="POST" action="<?= BASE_URL ?>/admin/events/store" class="space-y-5">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">

            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                    Titre <span class="text-accent">*</span>
                </label>
                <input type="text" name="title" required autofocus
                       class="w-full border border-border px-4 py-3 text-sm font-body bg-paper focus:outline-none focus:border-ink"
                       placeholder="Nom de l'événement">
            </div>

            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">Description</label>
                <textarea name="description" rows="3"
                          class="w-full border border-border px-4 py-3 text-sm font-body bg-paper focus:outline-none focus:border-ink resize-none"
                          placeholder="Décrivez l'événement…"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                        Date <span class="text-accent">*</span>
                    </label>
                    <input type="date" name="event_date" required
                           class="w-full border border-border px-4 py-3 text-sm font-body bg-paper focus:outline-none focus:border-ink">
                </div>
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">Heure</label>
                    <input type="time" name="event_time"
                           class="w-full border border-border px-4 py-3 text-sm font-body bg-paper focus:outline-none focus:border-ink">
                </div>
            </div>

            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">Lieu</label>
                <input type="text" name="location"
                       class="w-full border border-border px-4 py-3 text-sm font-body bg-paper focus:outline-none focus:border-ink"
                       placeholder="Ex : Place du Champ de Mars, Angoulême">
            </div>

            <div class="flex justify-between pt-2">
                <a href="<?= BASE_URL ?>/admin/events"
                   class="px-5 py-3 border border-border font-body font-semibold text-sm text-muted hover:border-ink hover:text-ink transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-ink text-paper font-body font-semibold text-sm hover:bg-accent transition-colors">
                    Créer l'événement
                </button>
            </div>
        </form>
    </div>
</div>