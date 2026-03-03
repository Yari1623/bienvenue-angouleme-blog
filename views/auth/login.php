<h2>Connexion</h2>

<form method="POST" action="/bienvenue-angouleme-blog/public/login" style="max-width:400px;">
    
<input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate(); ?>">

    <div style="margin-bottom:15px;">
        <label>Email ou username</label><br>
        <input type="text" name="login" required style="width:100%;padding:8px;">
    </div>

    <div style="margin-bottom:15px;">
        <label>Mot de passe</label><br>
        <input type="password" name="password" required style="width:100%;padding:8px;">
    </div>

    <button type="submit" style="padding:10px 15px;">
        Se connecter
    </button>

</form>