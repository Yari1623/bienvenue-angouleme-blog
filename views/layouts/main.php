<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue à Angoulême</title>
</head>
<body>

<header>
    <h1>Bienvenue à Angoulême – Le Blog</h1>
</header>

<main style="max-width:800px;margin:40px auto;">

    <?php
    use App\Core\Flash;

    $flashes = Flash::get();
    ?>

    <?php foreach ($flashes as $type => $messages): ?>
        <?php foreach ($messages as $message): ?>
            <div style="
                padding:12px;
                margin-bottom:15px;
                border-radius:6px;
                background-color: <?= $type === 'success' ? '#d1fae5' : '#fee2e2' ?>;
                color: <?= $type === 'success' ? '#065f46' : '#991b1b' ?>;
                border:1px solid <?= $type === 'success' ? '#10b981' : '#ef4444' ?>;
            ">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>

    <?php require $viewPath; ?>

</main>

<footer>
    <p>© <?= date('Y') ?> - Bienvenue à Angoulême</p>
</footer>

</body>
</html>