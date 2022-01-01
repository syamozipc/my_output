<?php require APP_ROOT . 'views/template/header.php' ?>
<div>
    <p><?= $data['description']; ?></p>
    <a href='<?= URL_ROOT . 'post/index'; ?>'>投稿一覧はこちら</a>  
</div>
<?php require APP_ROOT . 'views/template/footer.php' ?>