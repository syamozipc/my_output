<p>新規投稿フォーム</p>
<div>
    <form action="<?= URL_PATH . 'post/confirm' ?>" method="POST" enctype="multipart/form-data">
        <select name="country_id" class="js-countriesSelect">
            <option value="">選択してください</option>
            <?php foreach ($data['countriesList'] as $country) : ?>
                <option
                    value="<?= $country->id ?>"
                    <?php
                        if (
                            isset($data['post']['country_id'])
                            && $data['post']['country_id'] === $country->id
                        ) {
                            echo 'selected';
                        }
                     ?>
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

        <br>
        <br>

        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
        <input type="file" name="upload" class="js-inputImg">

        <img width="400" height="400" class="is-hidden js-displayImg">

        <br>
        <br>

        <textarea name="description" cols="70" rows="10"><?= $data['post']['description'] ?? '' ?></textarea>

        <br>
        <br>

        <button type="submit">送信</button>
    </form>
</div>

<script src="<?= PUBLIC_PATH . 'js/post/create.js' ?>"></script>