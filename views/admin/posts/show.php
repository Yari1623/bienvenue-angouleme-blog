<article>
    <h2><?= htmlspecialchars($post['title']) ?></h2>

    <p>
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </p>
</article>