<p>編集フォーム</p>
<div>
    <form action="<?= route('post/editConfirm', $data['post']->id); ?>" method="POST" enctype="multipart/form-data">
        <?= csrf() ?>
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

        <?php if (old('error_country_id')) : ?>
            <p class="error__message">※<?= old('error_country_id') ?></p>
        <?php endif; ?>

        <p>画像・動画は編集できません</p>
        <img 
            src="<?= public_url('upload/' . $data['post']->path); ?>"
            alt="アップロードファイル"
            width="400"
            height="400"
        >

        <br>
        <br>

        <textarea name="description" cols="70" rows="10"><?= e($data['post']->description) ?></textarea>

        <?php if (old('error_description')) : ?>
            <p class="error__message">※<?= old('error_description') ?></p>
        <?php endif; ?>

        <br>
        <br>

        <button type="submit">送信</button>
    </form>
</div>