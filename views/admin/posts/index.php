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
    </tr>

    <?php foreach ($posts as $post): ?>
        <tr>
            <td><?= $post['id'] ?></td>
            <td><?= htmlspecialchars($post['title']) ?></td>
            <td><?= $post['slug'] ?></td>
            <td><?= $post['status'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>