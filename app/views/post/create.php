<?php require APP_ROOT . 'views/template/header.php' ?>
<p>新規投稿フォーム</p>
<div>
    <form action="<?= APP_ROOT . 'post/save' ?>" method="POST">
        <select name="" id="">
            <?php foreach ($data['countriesList'] as $country) : ?>
                <option value="<?= $country->id; ?>"><?= $country->name; ?></option>
            <?php endforeach; ?>
        </select>
    </form>
</div>
<?php require APP_ROOT . 'views/template/footer.php' ?>