<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITENAME ?></title>
    <?php if (isset($data['css'])) : ?>
        <link rel="stylesheet" href="<?= public_url($data['css']); ?>">
    <?php endif; ?>
</head>
<body>
    <?php require_once(base_path('resources/views/components/errors/status.php')) ?>
    <main class="main">
        <?php require_once($viewFile) ?>
        <?php if (isset($data['js'])) : ?>
            <script src="<?= public_url($data['js']); ?>"></script>
        <?php endif; ?>
    </main>
</body>
</html>