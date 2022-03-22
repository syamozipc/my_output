<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITENAME ?></title>
    <!-- CSS -->
    <?php if (isset($css)) : ?>
        <link rel="stylesheet" href="<?= public_url($css); ?>">
    <?php endif; ?>
    <!-- JavaScript -->
    <?php if (isset($js)) : ?>
        <script defer src="<?= public_url($js); ?>"></script>
    <?php endif; ?>
</head>
<body>
    <!-- sessionのstatusメッセージ -->
    <?php require_once(base_path('resources/views/components/errors/status.php')) ?>
    <!-- 会員用ヘッダー -->
    <?php if (isLogedIn()) : ?>
        <?php require_once(base_path('resources/views/components/headers/headerNav.php')) ?>
    <?php endif; ?>
    <!-- メインコンテンツ -->
    <main class="main">
        <?php require_once($viewFile) ?>
    </main>
</body>
</html>