<?php require APP_PATH . 'views/template/header.php' ?>
<p>確認</p>
<div>
    <form action="<?= URL_PATH . 'post/update/' . $data['post']->id ?>" method="POST" enctype="multipart/form-data">
        <p><?= $data['post']->country_name ?></p>
        <img
            src="<?= PUBLIC_PATH . 'upload/' . basename($data['post']->path) ?>"
            alt="アップロードファイル"
            width="400"
            height="400"
        >
        <p><?= $data['post']->description ?></p>


        <input type="hidden" name="country_id" value="<?= $data['post']->country_id ?>">
        <input type="hidden" name="description" value="<?= $data['post']->description ?>">

        <button type="submit">送信</button>
    </form>
</div>

<div>
    <form action="<?= URL_PATH . 'post/edit/' . $data['post']->id ?>" method="POST">
        <input type="hidden" name="country_id" value="<?= $data['post']->country_id ?>">
        <input type="hidden" name="description" value="<?= $data['post']->description ?>">

        <button type="submit">修正する</button>
    </form>
</div>

<script src="<?= PUBLIC_PATH . 'js/post/confirm.js' ?>"></script>
<?php require APP_PATH . 'views/template/footer.php' ?>