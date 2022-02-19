<p>新規投稿フォーム</p>
<div>
    <form action="<?= route('post/confirm'); ?>" method="POST" enctype="multipart/form-data">
        <?= csrf() ?>
        <select name="country_id" class="js-countriesSelect">
            <option value="">選択してください</option>
            <?php foreach ($data['countriesList'] as $country) : ?>
                <option
                    value="<?= $country->id ?>"
                    <?php if (($data['post']->country_id ?? null) === $country->id) echo 'selected'; ?>
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

        <?php if (old('error_country_id')) : ?>
            <p class="error__message">※<?= old('error_country_id') ?></p>
        <?php endif; ?>

        <br>
        <br>
        
        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
        <input type="file" name="upload" class="js-inputImg">

        <?php if (old('error_upload')) : ?>
            <p class="error__message">※<?= old('error_upload') ?></p>
        <?php endif; ?>

        <img width="400" height="400" class="is-hidden js-displayImg">

        <br>
        <br>

        <textarea name="description" cols="70" rows="10"><?= $data['post']->description ?? null ?></textarea>
        <?php if (old('error_description')) : ?>
            <p class="error__message">※<?= old('error_description') ?></p>
        <?php endif; ?>

        <br>
        <br>

        <button type="submit">送信</button>
    </form>
</div>