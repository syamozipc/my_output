<?php require APP_PATH . 'views/template/header.php' ?>
<p>新規投稿フォーム</p>
<div>
    <form action="<?= URL_PATH . 'post/save' ?>" method="POST" enctype="multipart/form-data">
        <select name="country_id" class="js-countriesSelect">
            <?php foreach ($data['countriesList'] as $country) : ?>
                <option value="<?= $country->id ?>"><?= $country->name ?></option>
            <?php endforeach; ?>
        </select>

        <input
            type="search"
            list="countriesSuggest"
            class="js-suggestionInput"
            placeholder="国名を検索"
        />

        <datalist id="countriesSuggest">
            <?php foreach ($data['countriesList'] as $country) : ?>
                <option value="<?= $country->name ?>" data-country-id="<?= $country->id ?>"></option>
            <?php endforeach; ?>
        </datalist>

        <br>
        <br>

        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
        <input type="file" name="upload">

        <br>
        <br>

        <textarea name="description" cols="70" rows="10"></textarea>

        <br>
        <br>

        <button type="submit">送信</button>
    </form>
</div>

<script src="<?= PUBLIC_PATH . 'js/post/create.js' ?>"></script>
<?php require APP_PATH . 'views/template/footer.php' ?>