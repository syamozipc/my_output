<?php require APP_PATH . 'views/template/header.php' ?>
<p>確認</p>
<div>
    <form action="<?= URL_PATH . 'post/save' ?>" method="POST" enctype="multipart/form-data">
        <p><?= $data['post']['country_id'] ?></p>
        <img
            src="<?= PUBLIC_PATH . 'upload/' . $data['file'] ?>"
            alt="アップロードファイル"
            width="400"
            height="400"
        >
        <p><?= $data['post']['description'] ?></p>


        <input type="hidden" name="country_id" value="<?= $data['post']['country_id'] ?>">
        <input type="hidden" name="description" value="<?= $data['post']['description'] ?>">
        <input type="hidden" name="file_tmp_name" value="<?= $data['tempPath'] ?>">
        <input type="hidden" name="file_name" value="<?= $data['filePath'] ?>">


        <button type="submit">送信</button>
    </form>
</div>

<script src="<?= PUBLIC_PATH . 'js/post/create.js' ?>"></script>
<?php require APP_PATH . 'views/template/footer.php' ?>