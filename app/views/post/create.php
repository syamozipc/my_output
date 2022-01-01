<?php require APP_PATH . 'views/template/header.php' ?>
<p>新規投稿フォーム</p>
<div>
    <form action="<?= URL_PATH . 'post/save' ?>" method="POST">
        <select name="country_id" class="js-countriesSelect">
            <?php foreach ($data['countriesList'] as $country) : ?>
                <option value="<?= $country->id ?>"><?= $country->name ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">送信</button>
    </form>
</div>

<br />

<input
    type="search"
    list="countriesSuggest"
    class="js-suggestionInput"
    placeholder="入力してください"
/>

<datalist id="countriesSuggest">
    <?php foreach ($data['countriesList'] as $country) : ?>
        <option value="<?= $country->name ?>" data-country-id="<?= $country->id ?>"></option>
    <?php endforeach; ?>
</datalist>

<script src="<?= PUBLIC_PATH . 'js/post/create.js' ?>"></script>
<?php require APP_PATH . 'views/template/footer.php' ?>