<h2>Commentaires en attente</h2>

<?php if (!empty($comments)): ?>

    <?php foreach ($comments as $comment): ?>
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            <strong><?= htmlspecialchars($comment['username']) ?></strong>
            sur <em><?= htmlspecialchars($comment['title']) ?></em><br>
            <small><?= $comment['created_at'] ?></small>

            <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>

            <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/approve" style="display:inline;">

            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate(); ?>">

                <button type="submit">Approuver</button>
            </form>

            <form method="POST" action="<?= BASE_URL ?>/admin/comments/<?= $comment['id'] ?>/reject" style="display:inline;">

            <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate(); ?>">
            
                <button type="submit">Refuser</button>
            </form>
        </div>
    <?php endforeach; ?>

<?php else: ?>
    <p>Aucun commentaire en attente.</p>
<?php endif; ?>