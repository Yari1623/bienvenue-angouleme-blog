<?php
// views/admin/posts/create.php
$pageTitle = 'Créer un article — Admin';
?>

<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between border-b-2 border-ink pb-4">
        <h2 class="font-display text-2xl font-black text-ink">Nouvel article</h2>
        <a href="<?= BASE_URL ?>/admin/posts"
           class="text-sm font-body text-muted hover:text-accent transition-colors">
            ← Retour à la liste
        </a>
    </div>

    <form method="POST" action="<?= BASE_URL ?>/admin/posts/store" class="space-y-6">
        <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">

        <!-- ══ INFORMATIONS PRINCIPALES -->
        <div class="bg-white border border-border p-6 space-y-5">
            <h3 class="font-display text-base font-bold text-ink border-b border-border pb-2">
                Informations générales
            </h3>

            <!-- Titre -->
            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                    Titre <span class="text-accent">*</span>
                </label>
                <input type="text" name="title" required autofocus
                       class="w-full border border-border px-4 py-3 font-body text-ink bg-paper focus:outline-none focus:border-ink transition-colors text-lg"
                       placeholder="Titre de l'article">
            </div>

            <!-- Résumé / contenu principal -->
            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                    Résumé / introduction
                </label>
                <textarea name="content" rows="3"
                          class="w-full border border-border px-4 py-3 font-body text-sm text-ink bg-paper focus:outline-none focus:border-ink resize-none"
                          placeholder="Courte introduction ou résumé de l'article…"></textarea>
            </div>

            <!-- Thumbnail -->
            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                    Image principale (URL)
                </label>
                <input type="text" name="thumbnail"
                       class="w-full border border-border px-4 py-3 font-body text-sm text-ink bg-paper focus:outline-none focus:border-ink"
                       placeholder="https://…">
            </div>

            <!-- Catégorie + Lieu -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                        Catégorie
                    </label>
                    <select name="category_id"
                            class="w-full border border-border px-4 py-3 font-body text-sm text-ink bg-paper focus:outline-none focus:border-ink">
                        <option value="">— Choisir une catégorie —</option>
                        <?php foreach ($categories ?? [] as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                        Lieu
                    </label>
                    <select name="place_id"
                            class="w-full border border-border px-4 py-3 font-body text-sm text-ink bg-paper focus:outline-none focus:border-ink">
                        <option value="">— Choisir un lieu —</option>
                        <?php foreach ($places ?? [] as $place): ?>
                        <option value="<?= $place['id'] ?>"><?= htmlspecialchars($place['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Tags + Temps de lecture -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                        Tags <span class="text-muted font-normal">(séparés par des virgules)</span>
                    </label>
                    <input type="text" name="tags"
                           class="w-full border border-border px-4 py-3 font-body text-sm text-ink bg-paper focus:outline-none focus:border-ink"
                           placeholder="actualité, culture, angoulême">
                </div>
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                        Temps de lecture <span class="text-muted font-normal">(en minutes)</span>
                    </label>
                    <input type="number" name="reading_time" min="1" max="60"
                           class="w-full border border-border px-4 py-3 font-body text-sm text-ink bg-paper focus:outline-none focus:border-ink"
                           placeholder="5">
                </div>
            </div>
        </div>

        <!-- ══ SECTIONS DYNAMIQUES -->
        <div class="bg-white border border-border p-6">
            <div class="flex items-center justify-between border-b border-border pb-3 mb-4">
                <h3 class="font-display text-base font-bold text-ink">Contenu de l'article</h3>
                <span class="text-xs font-body text-muted">Ajoutez des blocs dans l'ordre souhaité</span>
            </div>

            <div id="sections-container" class="space-y-4 mb-4"></div>

            <!-- Boutons d'ajout de section -->
            <div class="flex flex-wrap gap-2 pt-4 border-t border-border">
                <span class="text-xs font-body text-muted self-center mr-2">Ajouter un bloc :</span>
                <button type="button" onclick="addSection('text')"
                        class="px-3 py-2 text-xs font-body font-semibold border border-border hover:border-ink hover:bg-ink hover:text-paper transition-all">
                    ¶ Texte
                </button>
                <button type="button" onclick="addSection('title')"
                        class="px-3 py-2 text-xs font-body font-semibold border border-border hover:border-ink hover:bg-ink hover:text-paper transition-all">
                    H Titre
                </button>
                <button type="button" onclick="addSection('image')"
                        class="px-3 py-2 text-xs font-body font-semibold border border-border hover:border-ink hover:bg-ink hover:text-paper transition-all">
                    🖼 Image
                </button>
                <button type="button" onclick="addSection('video')"
                        class="px-3 py-2 text-xs font-body font-semibold border border-border hover:border-ink hover:bg-ink hover:text-paper transition-all">
                    ▶ Vidéo
                </button>
                <button type="button" onclick="addSection('quote')"
                        class="px-3 py-2 text-xs font-body font-semibold border border-border hover:border-ink hover:bg-ink hover:text-paper transition-all">
                    " Citation
                </button>
            </div>
        </div>

        <!-- ══ ACTIONS -->
        <div class="flex items-center justify-between">
            <a href="<?= BASE_URL ?>/admin/posts"
               class="px-5 py-3 border border-border font-body font-semibold text-sm text-muted hover:border-ink hover:text-ink transition-colors">
                Annuler
            </a>
            <button type="submit"
                    class="px-6 py-3 bg-ink text-paper font-body font-semibold text-sm hover:bg-accent transition-colors">
                Enregistrer en brouillon
            </button>
        </div>

    </form>
</div>

<!-- ═══ JS éditeur de sections ═══ -->
<script>
let sectionCount = 0;

function addSection(type) {
    const container = document.getElementById('sections-container');
    const index = sectionCount++;
    const div = document.createElement('div');
    div.className = 'section-block border border-border bg-paper p-4 relative';
    div.dataset.index = index;

    let html = `
        <input type="hidden" name="section_type[${index}]" value="${type}">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-body font-semibold uppercase tracking-wider text-muted">${labelFor(type)}</span>
            <div class="flex items-center gap-2">
                <button type="button" onclick="moveSection(this, -1)"
                        class="text-xs text-muted hover:text-ink px-1" title="Monter">↑</button>
                <button type="button" onclick="moveSection(this, 1)"
                        class="text-xs text-muted hover:text-ink px-1" title="Descendre">↓</button>
                <button type="button" onclick="removeSection(this)"
                        class="text-xs text-accent hover:underline" title="Supprimer">✕ Supprimer</button>
            </div>
        </div>
    `;

    if (type === 'text') {
        html += `<textarea name="section_content[${index}]" rows="5"
                    class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink resize-none"
                    placeholder="Votre texte…"></textarea>
                 <input type="hidden" name="section_media_url[${index}]" value="">`;
    } else if (type === 'title') {
        html += `<input type="text" name="section_content[${index}]"
                    class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink font-semibold"
                    placeholder="Titre de section…">
                 <input type="hidden" name="section_media_url[${index}]" value="">`;
    } else if (type === 'image') {
        html += `<input type="text" name="section_media_url[${index}]"
                    class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink mb-2"
                    placeholder="URL de l'image (https://…)">
                 <input type="text" name="section_content[${index}]"
                    class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink"
                    placeholder="Légende (facultatif)">`;
    } else if (type === 'video') {
        html += `<input type="text" name="section_media_url[${index}]"
                    class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink mb-2"
                    placeholder="URL de la vidéo (YouTube, Pexels…)">
                 <input type="hidden" name="section_content[${index}]" value="">`;
    } else if (type === 'quote') {
        html += `<textarea name="section_content[${index}]" rows="2"
                    class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink italic resize-none"
                    placeholder="Texte de la citation…"></textarea>
                 <input type="hidden" name="section_media_url[${index}]" value="">`;
    }

    div.innerHTML = html;
    container.appendChild(div);
}

function labelFor(type) {
    const labels = { text: '¶ Bloc texte', title: 'H Titre', image: '🖼 Image', video: '▶ Vidéo', quote: '" Citation' };
    return labels[type] || type;
}

function removeSection(btn) {
    btn.closest('.section-block').remove();
}

function moveSection(btn, direction) {
    const block = btn.closest('.section-block');
    const container = document.getElementById('sections-container');
    const blocks = [...container.querySelectorAll('.section-block')];
    const idx = blocks.indexOf(block);
    const target = blocks[idx + direction];
    if (!target) return;
    if (direction === -1) container.insertBefore(block, target);
    else container.insertBefore(target, block);
    reindexSections();
}

function reindexSections() {
    const blocks = document.querySelectorAll('.section-block');
    blocks.forEach((block, i) => {
        block.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, `[${i}]`);
        });
    });
}
</script>