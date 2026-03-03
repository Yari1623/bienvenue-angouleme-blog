<article>
    <h2><?= htmlspecialchars($post['title']) ?></h2>

    <p>
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </p>
</article>

<hr>
<h3>Commentaires</h3>

<?php if (!empty($comments)): ?>
    <?php foreach ($comments as $comment): ?>
        <div style="margin-bottom:15px;">
            <strong><?= htmlspecialchars($comment['username']) ?></strong><br>
            <small><?= $comment['created_at'] ?></small>
            <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucun commentaire pour le moment.</p>
<?php endif; ?>

<?php use App\Core\Auth; ?>

<?php if (Auth::check()): ?>

<hr>
<h4>Laisser un commentaire</h4>

<form method="POST" action="<?= BASE_URL ?>/article/<?= $post['slug'] ?>/comment">
    
    <input type="hidden" name="_csrf" value="<?= \App\Core\Csrf::generate(); ?>">

    <textarea name="content" rows="4" required></textarea><br><br>
    <button type="submit">Envoyer</button>
</form>

<?php else: ?>

<p>Vous devez être connecté pour commenter.</p>

<?php endif; ?>