<h2>Articles récents</h2>

<?php if (empty($posts)): ?>
    <p>Aucun article publié pour le moment.</p>
<?php endif; ?>

<?php foreach ($posts as $post): ?>
    <article style="margin-bottom:30px;">
        <h3>
            <a href="/bienvenue-angouleme-blog/public/article/<?= htmlspecialchars($post['slug']) ?>">
                <?= htmlspecialchars($post['title']) ?>
            </a>
        </h3>
        <p>
            <?= nl2br(htmlspecialchars(substr($post['content'], 0, 150))) ?>...
        </p>
    </article>
<?php endforeach; ?>