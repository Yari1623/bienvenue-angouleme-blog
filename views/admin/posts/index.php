<h1>Gestion des articles</h1>

<a href="/bienvenue-angouleme-blog/public/admin/posts/create">Créer un article</a>

<?php if (!empty($_SESSION['success'])): ?>
    <p style="color:green;">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </p>
<?php endif; ?>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Slug</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php foreach ($posts as $post): ?>
        <tr>
            <td><?= $post['id'] ?></td>
            <td><?= htmlspecialchars($post['title']) ?></td>
            <td><?= htmlspecialchars($post['slug']) ?></td>
            <td><?= $post['status'] ?></td>
            <td>
    <a href="/bienvenue-angouleme-blog/public/admin/posts/<?= $post['id'] ?>/edit">
        Modifier
    </a>
    <form method="POST"
          action="/bienvenue-angouleme-blog/public/admin/posts/<?= $post['id'] ?>/status"
          style="display:inline;">
        <button type="submit">
            <?= $post['status'] === 'draft' ? 'Publier' : 'Dépublier' ?>
        </button>
    </form>
    <form method="POST"
      action="/bienvenue-angouleme-blog/public/admin/posts/<?= $post['id'] ?>/delete"
      style="display:inline;"
      onsubmit="return confirm('Confirmer la suppression ?');">
    <button type="submit" style="color:red;">
        Supprimer
    </button>
</form>
</td>
        </tr>
    <?php endforeach; ?>
</table>