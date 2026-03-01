<h1>Modifier l'article</h1>

<form method="POST"
      action="/bienvenue-angouleme-blog/public/admin/posts/<?= $post['id'] ?>/update">

    <div>
        <label>Titre</label><br>
        <input type="text" name="title"
               value="<?= htmlspecialchars($post['title']) ?>" required>
    </div>

    <div>
        <label>Contenu</label><br>
        <textarea name="content" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea>
    </div>

    <button type="submit">Mettre à jour</button>
</form>