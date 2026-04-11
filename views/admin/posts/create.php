<?php
// views/admin/posts/create.php
$pageTitle = 'Créer un article — Admin';
?>
 
<div class="max-w-4xl mx-auto space-y-6">
 
    <div class="flex items-center justify-between pb-4" style="border-bottom:2px solid var(--border)">
        <h2 class="font-display text-2xl font-black" style="color:var(--text)">Nouvel article</h2>
        <a href="<?= BASE_URL ?>/admin/posts" class="text-sm transition-colors"
           style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">← Retour à la liste</a>
    </div>
 
    <form method="POST" action="<?= BASE_URL ?>/admin/posts/store" class="space-y-6">
        <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
 
        <!-- Informations générales -->
        <div class="surface rounded-xl p-6 space-y-5">
            <h3 class="font-display text-base font-bold pb-2"
                style="color:var(--text);border-bottom:1px solid var(--border)">
                Informations générales
            </h3>
 
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    Titre <span style="color:#1d8fd8">*</span>
                </label>
                <input type="text" name="title" required autofocus
                       placeholder="Titre de l'article"
                       class="w-full rounded-lg px-4 py-3 text-lg font-semibold"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:2px solid transparent;outline-offset:2px;"
                       onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
            </div>
 
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Résumé / introduction</label>
                <textarea name="content" rows="3"
                          placeholder="Courte introduction ou résumé…"
                          class="w-full rounded-lg px-4 py-3 text-sm resize-none"
                          style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:2px solid transparent;outline-offset:2px;"
                          onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'"></textarea>
            </div>
 
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Image principale (URL)</label>
                <input type="text" name="thumbnail"
                       placeholder="https://…"
                       class="w-full rounded-lg px-4 py-3 text-sm"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:2px solid transparent;outline-offset:2px;"
                       onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
            </div>
 
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Catégorie</label>
                    <select name="category_id" class="w-full rounded-lg px-4 py-3 text-sm"
                            style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:2px solid transparent;outline-offset:2px;"
                            onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                        <option value="">— Choisir une catégorie —</option>
                        <?php foreach ($categories ?? [] as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Lieu</label>
                    <select name="place_id" class="w-full rounded-lg px-4 py-3 text-sm"
                            style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:2px solid transparent;outline-offset:2px;"
                            onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                        <option value="">— Choisir un lieu —</option>
                        <?php foreach ($places ?? [] as $place): ?>
                        <option value="<?= $place['id'] ?>"><?= htmlspecialchars($place['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
 
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                        Tags <span style="color:var(--muted);font-weight:400;text-transform:none;letter-spacing:0;">(séparés par virgules)</span>
                    </label>
                    <input type="text" name="tags"
                           placeholder="actualité, culture, angoulême"
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:2px solid transparent;outline-offset:2px;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Temps de lecture (min)</label>
                    <input type="number" name="reading_time" min="1" max="60"
                           placeholder="5"
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:2px solid transparent;outline-offset:2px;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
            </div>
        </div>
 
        <!-- Éditeur de sections -->
        <div class="surface rounded-xl p-6">
            <div class="flex items-center justify-between pb-3 mb-4" style="border-bottom:1px solid var(--border)">
                <h3 class="font-display text-base font-bold" style="color:var(--text)">Contenu de l'article</h3>
                <span class="text-xs" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">Ajoutez des blocs dans l'ordre souhaité</span>
            </div>
 
            <div id="sections-container" class="space-y-4 mb-4"></div>
 
            <div class="flex flex-wrap gap-2 pt-4" style="border-top:1px solid var(--border)">
                <span class="text-xs self-center mr-2" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">Ajouter un bloc :</span>
                <?php foreach(['text'=>'¶ Texte','title'=>'H Titre','image'=>'🖼 Image','video'=>'▶ Vidéo','quote'=>'" Citation'] as $type => $label): ?>
                <button type="button" onclick="addSection('<?= $type ?>')"
                        class="px-3 py-2 text-xs font-semibold rounded-lg transition-all"
                        style="background:var(--bg2);border:1px solid var(--border);color:var(--text2);font-family:'Source Sans 3',sans-serif;"
                        onmouseover="this.style.borderColor='#1d8fd8';this.style.color='#1d8fd8'"
                        onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text2)'">
                    <?= $label ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
 
        <!-- Actions -->
        <div class="flex items-center justify-between">
            <a href="<?= BASE_URL ?>/admin/posts" class="btn-ghost text-sm">Annuler</a>
            <button type="submit" class="btn-primary text-sm">Enregistrer en brouillon</button>
        </div>
    </form>
</div>
 
<script>
window.SECTION_COUNT = 0;
</script>
<script src="<?= BASE_URL ?>/assets/js/admin-editor.js"></script>