<?php require APP_ROOT . 'views/template/header.php' ?>
<div>
    <p><?= $data['description']; ?></p>
    <a href='<?= URL_ROOT . 'post/index'; ?>'>投稿一覧はこちら</a>
    <br>
    <a href='<?= URL_ROOT . 'post/create'; ?>'>新規投稿はこちら</a>
</div>
<?php require APP_ROOT . 'views/template/footer.php' ?>