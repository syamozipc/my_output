<?php require APP_PATH . 'views/template/header.php' ?>
<p>編集フォーム</p>
<div>
    <form action="<?= URL_PATH . 'post/update/' . $data['post']->id ?>" method="POST" enctype="multipart/form-data">

        <select name="country_id" class="js-countriesSelect">
            <?php foreach ($data['countriesList'] as $country) : ?>
                <option
                    value="<?= $country->id ?>"
                    <?php if ((int)$country->id === (int)$data['post']->country_id) echo 'selected' ?> 
                >
                    <?= $country->name ?>
                </option>
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

        <p>画像・動画は編集できません</p>
        <img 
            src="<?= PUBLIC_PATH . 'upload/' . $data['post']->path ?>"
            alt="アップロードファイル"
            width="400"
            height="400"
        >

        <br>
        <br>

        <textarea name="description" cols="70" rows="10"><?= $data['post']->description ?></textarea>

        <br>
        <br>

        <button type="submit">送信</button>
    </form>
</div>

<script src="<?= PUBLIC_PATH . 'js/post/edit.js' ?>"></script>
<?php require APP_PATH . 'views/template/footer.php' ?>