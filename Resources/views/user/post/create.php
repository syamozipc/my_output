<p>新規投稿フォーム</p>
<div>
    <form action="<?= route('/post/confirm'); ?>" method="POST" enctype="multipart/form-data">
        <?= csrf() ?>

        <!-- apiリクエスト形式 -->
        <label>
            <input
                type="text"
                placeholder="国名を検索"
                class="js-suggestionInput"
                data-suggest-url="<?= route('/api/suggest/getMatchedCountries') ?>"
                value="<?= $post->country_name ?? '' ?>"
                name="country_name"
            >
        </label>
        <ul class="js-suggestionList"></ul>

        <?php if (error('country_name')) : ?>
            <p class="error__message">※<?= error('country_name') ?></p>
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