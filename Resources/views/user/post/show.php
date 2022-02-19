<?php if (old('error_message')) : ?>
        <p class="error__message">※<?= old('error_message') ?></p>
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
                            src="<?= public_url('upload/' . $data['post']->path); ?>"
                            alt="アップロードファイル"
                            width="400"
                            height="400"
                            class="js-imgContent"
                        >
                    </td>
                    <td class="js-description"><?= bre($data['post']->description) ?></td>
                    <td><?= $data['post']->country_name ?></td>
                    <td><?= $data['post']->user_name ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div>
        <a href="<?= route('post/edit', $data['post']->id); ?>">編集</a>
        &nbsp;
        <form action="<?= route('post/delete', $data['post']->id); ?>" method="POST" class="js-delete-form">
            <?= csrf() ?>
            <input type="submit" value="削除" class="btn-open-modal js-btn-open-modal">
        </form>
    </div>

    <p><a href="<?= route('post/index'); ?>">一覧へ戻る</a></p>

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
