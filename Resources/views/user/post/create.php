<p>新規投稿フォーム</p>
<div>
    <form action="<?= route('/post/confirm'); ?>" method="POST" enctype="multipart/form-data">
        <?= csrf() ?>
        <select name="country_id" class="js-countriesSelect">
            <option value="">選択してください</option>
            <?php foreach ($countries as $country) : ?>
                <option
                    value="<?= $country->id ?>"
                    <?php if (($post->country_id ?? null) === $country->id) echo 'selected'; ?>
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
            <?php foreach ($countries as $country) : ?>
                <option value="<?= $country->name ?>" data-country-id="<?= $country->id ?>"></option>
            <?php endforeach; ?>
        </datalist>

        <!-- apiリクエスト形式 -->
        <label class="js-apiSuggest" data-suggest-url="<?= route('/api/suggest/getMatchedCountries') ?>">
            <input type="text">
        </label>

        <?php if (error('country_id')) : ?>
            <p class="error__message">※<?= error('country_id') ?></p>
        <?php endif; ?>

        <br>
        <br>
        
        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
        <input type="file" name="upload" class="js-inputImg">

        <?php if (error('upload')) : ?>
            <p class="error__message">※<?= error('upload') ?></p>
        <?php endif; ?>

        <img width="400" height="400" class="is-hidden js-displayImg">

        <br>
        <br>

        <textarea name="description" cols="70" rows="10"><?= $post->description ?? null ?></textarea>
        <?php if (error('description')) : ?>
            <p class="error__message">※<?= error('description') ?></p>
        <?php endif; ?>

        <br>
        <br>

        <button type="submit">送信</button>
    </form>
</div>