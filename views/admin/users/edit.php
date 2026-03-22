<?php
// views/admin/users/edit.php
$pageTitle = 'Modifier l\'utilisateur — Admin';
?>
 
<div class="max-w-lg mx-auto space-y-6">
 
    <div class="flex items-center justify-between pb-4" style="border-bottom:2px solid var(--border)">
        <h2 class="font-display text-2xl font-black" style="color:var(--text)">
            Modifier : <span style="background:linear-gradient(135deg,#1d8fd8,#22d3ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                <?= htmlspecialchars($user['username']) ?>
            </span>
        </h2>
        <a href="<?= BASE_URL ?>/admin/users" class="text-sm transition-colors"
           style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">← Retour</a>
    </div>
 
    <div class="surface rounded-xl p-6">
        <form method="POST" action="<?= BASE_URL ?>/admin/users/<?= $user['id'] ?>/update" class="space-y-5">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">
 
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Prénom</label>
                    <input type="text" name="first_name"
                           value="<?= htmlspecialchars($user['first_name'] ?? '') ?>"
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Nom</label>
                    <input type="text" name="last_name"
                           value="<?= htmlspecialchars($user['last_name'] ?? '') ?>"
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
            </div>
 
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    Nom d'utilisateur <span style="color:#1d8fd8">*</span>
                </label>
                <input type="text" name="username" required
                       value="<?= htmlspecialchars($user['username']) ?>"
                       class="w-full rounded-lg px-4 py-3 text-sm"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                       onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
            </div>
 
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    Email <span style="color:#1d8fd8">*</span>
                </label>
                <input type="email" name="email" required
                       value="<?= htmlspecialchars($user['email']) ?>"
                       class="w-full rounded-lg px-4 py-3 text-sm"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                       onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
            </div>
 
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Entreprise</label>
                    <input type="text" name="company"
                           value="<?= htmlspecialchars($user['company'] ?? '') ?>"
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Téléphone</label>
                    <input type="text" name="phone"
                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
            </div>
 
            <div class="flex justify-between pt-2">
                <a href="<?= BASE_URL ?>/admin/users" class="btn-ghost text-sm">Annuler</a>
                <button type="submit" class="btn-primary text-sm">Enregistrer</button>
            </div>
        </form>
    </div>
</div>