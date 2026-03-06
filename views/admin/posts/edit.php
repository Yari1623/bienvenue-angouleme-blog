<?php
// views/admin/posts/edit.php
$pageTitle = 'Modifier l\'article — Admin';
?>

<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between border-b-2 border-ink pb-4">
        <h2 class="font-display text-2xl font-black text-ink">
            Modifier : <span class="text-accent"><?= htmlspecialchars($post['title']) ?></span>
        </h2>
        <a href="<?= BASE_URL ?>/admin/posts"
           class="text-sm font-body text-muted hover:text-accent transition-colors">
            ← Retour à la liste
        </a>
    </div>

    <form method="POST" action="<?= BASE_URL ?>/admin/posts/<?= $post['id'] ?>/update" class="space-y-6">
        <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">

        <!-- ══ INFORMATIONS PRINCIPALES -->
        <div class="bg-white border border-border p-6 space-y-5">
            <h3 class="font-display text-base font-bold text-ink border-b border-border pb-2">
                Informations générales
            </h3>

            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                    Titre <span class="text-accent">*</span>
                </label>
                <input type="text" name="title" required
                       value="<?= htmlspecialchars($post['title']) ?>"
                       class="w-full border border-border px-4 py-3 font-body text-ink bg-paper focus:outline-none focus:border-ink transition-colors text-lg">
            </div>

            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                    Résumé / introduction
                </label>
                <textarea name="content" rows="3"
                          class="w-full border border-border px-4 py-3 font-body text-sm text-ink bg-paper focus:outline-none focus:border-ink resize-none"><?= htmlspecialchars($post['content'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                    Image principale (URL)
                </label>
                <input type="text" name="thumbnail"
                       value="<?= htmlspecialchars($post['thumbnail'] ?? '') ?>"
                       class="w-full border border-border px-4 py-3 font-body text-sm text-ink bg-paper focus:outline-none focus:border-ink">
                <?php if (!empty($post['thumbnail'])): ?>
                <img src="<?= htmlspecialchars($post['thumbnail']) ?>" alt="Thumbnail actuel"
                     class="mt-2 h-24 object-cover border border-border">
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">Catégorie</label>
                    <select name="category_id"
                            class="w-full border border-border px-4 py-3 font-body text-sm text-ink bg-paper focus:outline-none focus:border-ink">
                        <option value="">— Choisir une catégorie —</option>
                        <?php foreach ($categories ?? [] as $cat): ?>
                        <option value="<?= $cat['id'] ?>"
                            <?= ($post['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">Lieu</label>
                    <select name="place_id"
                            class="w-full border border-border px-4 py-3 font-body text-sm text-ink bg-paper focus:outline-none focus:border-ink">
                        <option value="">— Choisir un lieu —</option>
                        <?php foreach ($places ?? [] as $place): ?>
                        <option value="<?= $place['id'] ?>"
                            <?= ($post['place_id'] ?? '') == $place['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($place['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">Tags</label>
                    <input type="text" name="tags"
                           value="<?= htmlspecialchars($post['tags'] ?? '') ?>"
                           class="w-full border border-border px-4 py-3 font-body text-sm text-ink bg-paper focus:outline-none focus:border-ink">
                </div>
                <div>
                    <label class="block text-xs font-body font-semibold text-ink uppercase tracking-wider mb-2">
                        Temps de lecture (min)
                    </label>
                    <input type="number" name="reading_time" min="1" max="60"
                           value="<?= htmlspecialchars($post['reading_time'] ?? '') ?>"
                           class="w-full border border-border px-4 py-3 font-body text-sm text-ink bg-paper focus:outline-none focus:border-ink">
                </div>
            </div>
        </div>

        <!-- ══ SECTIONS EXISTANTES -->
        <div class="bg-white border border-border p-6">
            <div class="flex items-center justify-between border-b border-border pb-3 mb-4">
                <h3 class="font-display text-base font-bold text-ink">Contenu de l'article</h3>
                <span class="text-xs font-body text-muted">Les sections existantes seront remplacées</span>
            </div>

            <div id="sections-container" class="space-y-4 mb-4">
                <?php foreach ($sections ?? [] as $i => $section): ?>
                <div class="section-block border border-border bg-paper p-4 relative" data-index="<?= $i ?>">
                    <input type="hidden" name="section_type[<?= $i ?>]" value="<?= htmlspecialchars($section['type']) ?>">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-body font-semibold uppercase tracking-wider text-muted">
                            <?php
                            $labels = ['text'=>'¶ Texte','title'=>'H Titre','image'=>'🖼 Image','video'=>'▶ Vidéo','quote'=>'" Citation'];
                            echo $labels[$section['type']] ?? $section['type'];
                            ?>
                        </span>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="moveSection(this,-1)" class="text-xs text-muted hover:text-ink px-1">↑</button>
                            <button type="button" onclick="moveSection(this,1)"  class="text-xs text-muted hover:text-ink px-1">↓</button>
                            <button type="button" onclick="removeSection(this)"  class="text-xs text-accent hover:underline">✕ Supprimer</button>
                        </div>
                    </div>

                    <?php if ($section['type'] === 'text'): ?>
                        <textarea name="section_content[<?= $i ?>]" rows="5"
                                  class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink resize-none"><?= htmlspecialchars($section['content'] ?? '') ?></textarea>
                        <input type="hidden" name="section_media_url[<?= $i ?>]" value="">

                    <?php elseif ($section['type'] === 'title'): ?>
                        <input type="text" name="section_content[<?= $i ?>]"
                               value="<?= htmlspecialchars($section['content'] ?? '') ?>"
                               class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink font-semibold">
                        <input type="hidden" name="section_media_url[<?= $i ?>]" value="">

                    <?php elseif ($section['type'] === 'image'): ?>
                        <input type="text" name="section_media_url[<?= $i ?>]"
                               value="<?= htmlspecialchars($section['media_url'] ?? '') ?>"
                               class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink mb-2"
                               placeholder="URL de l'image">
                        <input type="text" name="section_content[<?= $i ?>]"
                               value="<?= htmlspecialchars($section['content'] ?? '') ?>"
                               class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink"
                               placeholder="Légende">

                    <?php elseif ($section['type'] === 'video'): ?>
                        <input type="text" name="section_media_url[<?= $i ?>]"
                               value="<?= htmlspecialchars($section['media_url'] ?? '') ?>"
                               class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink"
                               placeholder="URL de la vidéo">
                        <input type="hidden" name="section_content[<?= $i ?>]" value="">

                    <?php elseif ($section['type'] === 'quote'): ?>
                        <textarea name="section_content[<?= $i ?>]" rows="2"
                                  class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink italic resize-none"><?= htmlspecialchars($section['content'] ?? '') ?></textarea>
                        <input type="hidden" name="section_media_url[<?= $i ?>]" value="">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="flex flex-wrap gap-2 pt-4 border-t border-border">
                <span class="text-xs font-body text-muted self-center mr-2">Ajouter un bloc :</span>
                <button type="button" onclick="addSection('text')"   class="px-3 py-2 text-xs font-body font-semibold border border-border hover:border-ink hover:bg-ink hover:text-paper transition-all">¶ Texte</button>
                <button type="button" onclick="addSection('title')"  class="px-3 py-2 text-xs font-body font-semibold border border-border hover:border-ink hover:bg-ink hover:text-paper transition-all">H Titre</button>
                <button type="button" onclick="addSection('image')"  class="px-3 py-2 text-xs font-body font-semibold border border-border hover:border-ink hover:bg-ink hover:text-paper transition-all">🖼 Image</button>
                <button type="button" onclick="addSection('video')"  class="px-3 py-2 text-xs font-body font-semibold border border-border hover:border-ink hover:bg-ink hover:text-paper transition-all">▶ Vidéo</button>
                <button type="button" onclick="addSection('quote')"  class="px-3 py-2 text-xs font-body font-semibold border border-border hover:border-ink hover:bg-ink hover:text-paper transition-all">" Citation</button>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <a href="<?= BASE_URL ?>/admin/posts"
               class="px-5 py-3 border border-border font-body font-semibold text-sm text-muted hover:border-ink hover:text-ink transition-colors">
                Annuler
            </a>
            <button type="submit"
                    class="px-6 py-3 bg-ink text-paper font-body font-semibold text-sm hover:bg-accent transition-colors">
                Enregistrer les modifications
            </button>
        </div>

    </form>
</div>

<script>
let sectionCount = <?= count($sections ?? []) ?>;

function addSection(type) {
    const container = document.getElementById('sections-container');
    const index = sectionCount++;
    const div = document.createElement('div');
    div.className = 'section-block border border-border bg-paper p-4 relative';
    div.dataset.index = index;

    const labels = { text: '¶ Bloc texte', title: 'H Titre', image: '🖼 Image', video: '▶ Vidéo', quote: '" Citation' };

    let html = `
        <input type="hidden" name="section_type[${index}]" value="${type}">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-body font-semibold uppercase tracking-wider text-muted">${labels[type] || type}</span>
            <div class="flex items-center gap-2">
                <button type="button" onclick="moveSection(this,-1)" class="text-xs text-muted hover:text-ink px-1">↑</button>
                <button type="button" onclick="moveSection(this,1)"  class="text-xs text-muted hover:text-ink px-1">↓</button>
                <button type="button" onclick="removeSection(this)"  class="text-xs text-accent hover:underline">✕ Supprimer</button>
            </div>
        </div>`;

    if (type === 'text')   html += `<textarea name="section_content[${index}]" rows="5" class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink resize-none" placeholder="Votre texte…"></textarea><input type="hidden" name="section_media_url[${index}]" value="">`;
    if (type === 'title')  html += `<input type="text" name="section_content[${index}]" class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink font-semibold" placeholder="Titre de section…"><input type="hidden" name="section_media_url[${index}]" value="">`;
    if (type === 'image')  html += `<input type="text" name="section_media_url[${index}]" class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink mb-2" placeholder="URL image"><input type="text" name="section_content[${index}]" class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink" placeholder="Légende">`;
    if (type === 'video')  html += `<input type="text" name="section_media_url[${index}]" class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink" placeholder="URL vidéo"><input type="hidden" name="section_content[${index}]" value="">`;
    if (type === 'quote')  html += `<textarea name="section_content[${index}]" rows="2" class="w-full border border-border px-3 py-2 text-sm font-body bg-white focus:outline-none focus:border-ink italic resize-none" placeholder="Citation…"></textarea><input type="hidden" name="section_media_url[${index}]" value="">`;

    div.innerHTML = html;
    container.appendChild(div);
}

function removeSection(btn) { btn.closest('.section-block').remove(); }

function moveSection(btn, dir) {
    const block = btn.closest('.section-block');
    const container = document.getElementById('sections-container');
    const blocks = [...container.querySelectorAll('.section-block')];
    const idx = blocks.indexOf(block);
    const target = blocks[idx + dir];
    if (!target) return;
    dir === -1 ? container.insertBefore(block, target) : container.insertBefore(target, block);
    reindexSections();
}

function reindexSections() {
    document.querySelectorAll('.section-block').forEach((block, i) => {
        block.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, `[${i}]`);
        });
    });
}
</script>