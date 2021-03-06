<?php if (error('message')) : ?>
    <p class="error__message">※<?= error('message') ?></p>
<?php endif; ?>
<section class="section">
    <div>
        <table>
            <thead>
                <tr>
                    <th>画像</th>
                    <th>概要</th>
                    <th>国名</th>
                    <th>投稿者</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <img 
                            src="<?= public_url('upload/' . $post->path); ?>"
                            alt="アップロードファイル"
                            width="400"
                            height="400"
                            class="js-imgContent"
                        >
                    </td>
                    <td class="js-description"><?= bre($post->description) ?></td>
                    <td><?= $post->country_name ?></td>
                    <td><?= e($post->user_name ?? $post->email) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div>
        <a href="<?= route('/post/edit', $post->id); ?>">編集</a>
        &nbsp;
        <form action="<?= route('/post/delete', $post->id); ?>" method="POST" class="js-delete-form">
            <?= csrf() ?>
            <input type="submit" value="削除" class="btn-open-modal js-btn-open-modal">
        </form>
    </div>

    <p><a href="<?= route('/post/index'); ?>">一覧へ戻る</a></p>

    <!-- モーダル -->
    <div class="modal js-modal is-hidden">
        <button class="btn-close-modal js-btn-close-modal">×</button>
        <img src="" alt="アップロードファイル" width="400" height="400" class="js-modalImgContent">
        <p class="js-modalDescription"></p>
        <button class="js-btn-cancel">戻る</button>
        <button class="js-btn-delete">削除する</button>
    </div>
    <div class="overlay js-overlay is-hidden"></div>
</section>
