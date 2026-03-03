<h1>Créer un article</h1>

<?php if (!empty($_SESSION['error'])): ?>
    <p style="color:red;">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </p>
<?php endif; ?>

<form method="POST" action="/bienvenue-angouleme-blog/public/admin/posts/store">

<input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate(); ?>">

    <label>Titre :</label><br>
    <input type="text" name="title" required><br><br>

    <label>Contenu :</label><br>
    <textarea name="content" rows="6" cols="50" required></textarea><br><br>

    <button type="submit">Créer</button>
</form>