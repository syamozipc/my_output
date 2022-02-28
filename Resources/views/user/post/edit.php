<p>編集フォーム</p>
<div>
    <form action="<?= route('/post/editConfirm', $post->id); ?>" method="POST" enctype="multipart/form-data">
        <?= csrf() ?>
        <select name="country_id" class="js-countriesSelect">
            <?php foreach ($countries as $country) : ?>
                <option
                    value="<?= $country->id ?>"
                    <?php if ((int)$country->id === (int)$post->country_id) echo 'selected' ?> 
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

        <?php if (error('country_id')) : ?>
            <p class="error__message">※<?= error('country_id') ?></p>
        <?php endif; ?>

        <p>画像・動画は編集できません</p>
        <img 
            src="<?= public_url('upload/' . $post->path); ?>"
            alt="アップロードファイル"
            width="400"
            height="400"
        >

        <br>
        <br>

        <textarea name="description" cols="70" rows="10"><?= e($post->description) ?></textarea>

        <?php if (error('description')) : ?>
            <p class="error__message">※<?= error('description') ?></p>
        <?php endif; ?>

        <br>
        <br>

        <button type="submit">送信</button>
    </form>
</div>