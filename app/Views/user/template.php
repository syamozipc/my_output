<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITENAME ?></title>
    <link rel="stylesheet" href="<?= PUBLIC_PATH . $data['css'] ?>">
</head>
<body>
    <?php require_once($viewFile) ?>
    <script src="<?= PUBLIC_PATH . $data['js'] ?>"></script>
</body>
</html>