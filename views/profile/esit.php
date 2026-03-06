<?php
// views/profile/edit.php
$pageTitle = 'Mon compte — Bienvenue à Angoulême';
use App\Core\Auth;
use App\Core\Csrf;
$user = Auth::user();
?>

<div class="max-w-2xl mx-auto space-y-8">

    <div class="text-center">
        <h2 class="font-display text-3xl font-black mb-2" style="color:var(--text)">Mon compte</h2>
        <div class="h-1 w-24 mx-auto rounded-full" style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)"></div>
        <p class="text-sm mt-3" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            Modifiez vos informations personnelles et votre mot de passe.
        </p>
    </div>

    <!-- Avatar + rôle -->
    <div class="surface rounded-xl p-5 flex items-center gap-5">
        <div class="w-16 h-16 rounded-full flex items-center justify-center text-2xl text-white font-black shrink-0"
             style="background:linear-gradient(135deg,#1d8fd8,#22d3ee)">
            <?= strtoupper(mb_substr($user['username'] ?? 'U', 0, 1)) ?>
        </div>
        <div>
            <p class="font-display text-xl font-bold" style="color:var(--text)"><?= htmlspecialchars($user['username']) ?></p>
            <p class="text-xs mt-1" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
                Membre depuis <?= date('d/m/Y', strtotime($user['created_at'] ?? 'now')) ?>
                · <span class="font-semibold" style="color:#1d8fd8"><?= ucfirst($user['role'] ?? 'member') ?></span>
            </p>
        </div>
        <a href="<?= BASE_URL ?>/profil" class="btn-ghost ml-auto text-sm">← Mon profil</a>
    </div>

    <!-- Infos personnelles -->
    <div class="surface rounded-xl p-6">
        <h3 class="font-display text-lg font-bold mb-5 pb-2" style="color:var(--text);border-bottom:2px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
            Informations personnelles
        </h3>
        <form method="POST" action="<?= BASE_URL ?>/compte/update-infos" class="space-y-4">
            <input type="hidden" name="_csrf" value="<?= Csrf::generate() ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Prénom</label>
                    <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>"
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Nom</label>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>"
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Nom d'utilisateur <span style="color:#1d8fd8">*</span></label>
                <input type="text" name="username" required value="<?= htmlspecialchars($user['username'] ?? '') ?>"
                       class="w-full rounded-lg px-4 py-3 text-sm"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                       onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Email <span style="color:#1d8fd8">*</span></label>
                <input type="email" name="email" required value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                       class="w-full rounded-lg px-4 py-3 text-sm"
                       style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                       onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Téléphone</label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Entreprise</label>
                    <input type="text" name="company" value="<?= htmlspecialchars($user['company'] ?? '') ?>"
                           class="w-full rounded-lg px-4 py-3 text-sm"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                </div>
            </div>
            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-primary px-8">Enregistrer les modifications</button>
            </div>
        </form>
    </div>

    <!-- Changer le mot de passe -->
    <div class="surface rounded-xl p-6">
        <h3 class="font-display text-lg font-bold mb-5 pb-2" style="color:var(--text);border-bottom:2px solid;border-image:linear-gradient(135deg,#1d8fd8,#22d3ee) 1;">
            Changer le mot de passe
        </h3>
        <form method="POST" action="<?= BASE_URL ?>/compte/update-password" class="space-y-4" id="pwdForm">
            <input type="hidden" name="_csrf" value="<?= Csrf::generate() ?>">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Mot de passe actuel</label>
                <div class="relative">
                    <input type="password" name="current_password" required id="pwd_current"
                           class="w-full rounded-lg px-4 py-3 text-sm pr-12"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'">
                    <button type="button" onclick="togglePwd('pwd_current')" class="absolute right-3 top-1/2 -translate-y-1/2" style="color:var(--muted)">👁</button>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Nouveau mot de passe</label>
                <div class="relative">
                    <input type="password" name="new_password" required id="pwd_new" minlength="8"
                           class="w-full rounded-lg px-4 py-3 text-sm pr-12"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'"
                           oninput="checkStrength(this.value)">
                    <button type="button" onclick="togglePwd('pwd_new')" class="absolute right-3 top-1/2 -translate-y-1/2" style="color:var(--muted)">👁</button>
                </div>
                <div class="mt-2 h-1.5 rounded-full overflow-hidden" style="background:var(--border)">
                    <div id="strength-bar" class="h-full rounded-full transition-all" style="width:0%;background:#dc2626"></div>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color:var(--text);font-family:'Source Sans 3',sans-serif;">Confirmer le nouveau mot de passe</label>
                <div class="relative">
                    <input type="password" name="new_password_confirm" required id="pwd_confirm" minlength="8"
                           class="w-full rounded-lg px-4 py-3 text-sm pr-12"
                           style="background:var(--bg);border:1.5px solid var(--border);color:var(--text);font-family:'Source Sans 3',sans-serif;outline:none;"
                           onfocus="this.style.borderColor='#1d8fd8'" onblur="this.style.borderColor='var(--border)'"
                           oninput="checkMatch()">
                    <button type="button" onclick="togglePwd('pwd_confirm')" class="absolute right-3 top-1/2 -translate-y-1/2" style="color:var(--muted)">👁</button>
                </div>
                <p id="match-msg" class="text-xs mt-1" style="font-family:'Source Sans 3',sans-serif;min-height:1rem"></p>
            </div>
            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-primary px-8">Changer le mot de passe</button>
            </div>
        </form>
    </div>

    <!-- Zone danger -->
    <div class="rounded-xl p-6" style="border:2px solid #dc2626;background:var(--surface)">
        <h3 class="font-display text-lg font-bold mb-2" style="color:#dc2626">Zone danger</h3>
        <p class="text-sm mb-4" style="color:var(--text2);font-family:'Source Sans 3',sans-serif;">
            La suppression de votre compte est définitive. Toutes vos données seront effacées.
        </p>
        <a href="<?= BASE_URL ?>/contact"
           class="inline-block px-5 py-2 rounded-full font-semibold text-sm text-white"
           style="background:#dc2626;font-family:'Source Sans 3',sans-serif;">
            Demander la suppression de mon compte
        </a>
    </div>

</div>

<script>
function togglePwd(id) { const i=document.getElementById(id); i.type=i.type==='password'?'text':'password'; }
function checkStrength(val) {
    let s=0;
    if(val.length>=8)s++; if(val.length>=12)s++; if(/[A-Z]/.test(val))s++; if(/[0-9]/.test(val))s++; if(/[^A-Za-z0-9]/.test(val))s++;
    const c=['#dc2626','#f97316','#eab308','#22c55e','#16a34a'],w=['20%','40%','60%','80%','100%'];
    const b=document.getElementById('strength-bar');
    b.style.width=val.length?(w[s-1]||'20%'):'0%'; b.style.background=val.length?(c[s-1]||'#dc2626'):'#dc2626';
}
function checkMatch() {
    const p1=document.getElementById('pwd_new').value, p2=document.getElementById('pwd_confirm').value;
    const m=document.getElementById('match-msg');
    if(!p2){m.textContent='';return;}
    if(p1===p2){m.textContent='✓ Les mots de passe correspondent';m.style.color='#16a34a';document.getElementById('pwd_confirm').style.borderColor='#16a34a';}
    else{m.textContent='✗ Les mots de passe ne correspondent pas';m.style.color='#dc2626';document.getElementById('pwd_confirm').style.borderColor='#dc2626';}
}
document.getElementById('pwdForm').addEventListener('submit',function(e){
    if(document.getElementById('pwd_new').value!==document.getElementById('pwd_confirm').value){e.preventDefault();checkMatch();}
});
</script>