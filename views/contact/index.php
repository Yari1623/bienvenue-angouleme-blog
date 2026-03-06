<?php
// views/contact/index.php
$pageTitle = 'Contact — Bienvenue à Angoulême';
?>

<div class="max-w-2xl mx-auto space-y-8">

    <!-- Hero -->
    <div class="text-center">
        <h2 class="font-display text-3xl font-black mb-2" style="color:var(--text)">Contactez-nous</h2>
        <div class="h-1 w-24 mx-auto rounded-full mb-4" style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)"></div>
        <p class="text-sm" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Une question, un signalement, une suggestion ? On vous répond dans les plus brefs délais.
        </p>
    </div>

    <!-- Infos rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <?php
        $infos = [
            ['icon'=>'📧', 'label'=>'Email', 'value'=>'contact@bienvenue-angouleme.fr'],
            ['icon'=>'📍', 'label'=>'Localisation', 'value'=>'Angoulême, Charente'],
            ['icon'=>'⏱', 'label'=>'Réponse', 'value'=>'Sous 48h ouvrées'],
        ];
        foreach($infos as $info): ?>
        <div class="surface rounded-xl p-4 text-center">
            <div class="text-3xl mb-2"><?= $info['icon'] ?></div>
            <div class="text-xs font-bold uppercase tracking-wider mb-1" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;"><?= $info['label'] ?></div>
            <div class="text-sm font-semibold" style="color:var(--text);font-family:'Source Sans 3',sans-serif;"><?= $info['value'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Formulaire -->
    <div class="surface rounded-xl p-6 md:p-8">
        <h3 class="font-display text-xl font-bold mb-6" style="color:var(--text)">Envoyer un message</h3>

        <form method="POST" action="<?= BASE_URL ?>/contact/send" class="space-y-5">
            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate() ?>">

            <!-- Nom + Prénom -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                        Prénom <span style="color:#1d8fd8">*</span>
                    </label>
                    <input type="text" name="first_name" required
                           placeholder="Votre prénom"
                           class="w-full rounded-lg px-4 py-3 text-sm transition-all"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                        Nom <span style="color:#1d8fd8">*</span>
                    </label>
                    <input type="text" name="last_name" required
                           placeholder="Votre nom"
                           class="w-full rounded-lg px-4 py-3 text-sm transition-all"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
            </div>

            <!-- Email + Téléphone -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                        Email <span style="color:#1d8fd8">*</span>
                    </label>
                    <input type="email" name="email" required
                           placeholder="votre@email.fr"
                           class="w-full rounded-lg px-4 py-3 text-sm transition-all"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                           style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                        Téléphone
                    </label>
                    <input type="tel" name="phone"
                           placeholder="06 XX XX XX XX"
                           class="w-full rounded-lg px-4 py-3 text-sm transition-all"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
            </div>

            <!-- Entreprise -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    Entreprise / Organisation
                </label>
                <input type="text" name="company"
                       placeholder="Nom de votre entreprise (facultatif)"
                       class="w-full rounded-lg px-4 py-3 text-sm transition-all"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                       onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
            </div>

            <!-- Sujet -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    Sujet <span style="color:#1d8fd8">*</span>
                </label>
                <select name="subject" required
                        class="w-full rounded-lg px-4 py-3 text-sm transition-all"
                        style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                        onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                    <option value="">— Choisissez un sujet —</option>
                    <option value="question">Question générale</option>
                    <option value="signalement">Signalement de contenu</option>
                    <option value="suggestion">Suggestion d'article</option>
                    <option value="evenement">Proposer un événement</option>
                    <option value="partenariat">Partenariat</option>
                    <option value="rgpd">Demande RGPD (suppression de données)</option>
                    <option value="autre">Autre</option>
                </select>
            </div>

            <!-- Message -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                       style="color:var(--text);font-family:'Source Sans 3',sans-serif;">
                    Message <span style="color:#1d8fd8">*</span>
                </label>
                <textarea name="message" required rows="6"
                          placeholder="Décrivez votre demande en détail…"
                          class="w-full rounded-lg px-4 py-3 text-sm transition-all resize-none"
                          style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                          onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'"></textarea>
                <p class="text-xs mt-1" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">
                    Minimum 20 caractères.
                </p>
            </div>

            <!-- Anti-spam -->
            <div class="p-4 rounded-lg" style="background:var(--bg2)">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="consent" required class="mt-0.5 shrink-0" style="accent-color:#1d8fd8">
                    <span class="text-xs leading-relaxed" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                        J'accepte que mes données soient utilisées pour répondre à ma demande, conformément à notre
                        <a href="<?= BASE_URL ?>/politique-confidentialite" class="underline" style="color:#1d8fd8">politique de confidentialité</a>.
                    </span>
                </label>
            </div>

            <div class="flex items-center justify-between">
                <p class="text-xs" style="color:var(--muted);font-family:'Source Sans 3',sans-serif;">* Champs obligatoires</p>
                <button type="submit" class="btn-primary px-8 py-3">
                    Envoyer le message →
                </button>
            </div>
        </form>
    </div>
</div>