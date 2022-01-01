<?php require APP_PATH . 'views/template/header.php' ?>
<div>
    <p><?= $data['description']; ?></p>
    <a href='<?= URL_PATH . 'post/index'; ?>'>投稿一覧はこちら</a>
    <br>
    <a href='<?= URL_PATH . 'post/create'; ?>'>新規投稿はこちら</a>
</div>
<?php require APP_PATH . 'views/template/footer.php' ?>