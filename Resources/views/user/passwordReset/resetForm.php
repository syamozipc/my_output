<section>
    <p>パスワードリセット</p>
    <form action="<?= route('/passwordReset/reset') ?>" method="POST">
        <?= csrf() ?>
        <input type="hidden" name="password_reset_token" value="<?= $passwordResetToken ?>">
        <?php if (error('password_reset_token')) : ?>
            <p class="error__message">※<?= error('password_reset_token') ?></p>
        <?php endif; ?>

        <label>新しいパスワード
            <br>
            <input type="password" name="password">
        </label>
        <?php if (error('password')) : ?>
            <p class="error__message">※<?= error('password') ?></p>
        <?php endif; ?>
        <br>
        ※8〜12文字
        <br>
        <br>

        <label>パスワード（確認用）
            <br>
            <input type="password" name="password_confirmation">
        </label>
        <?php if (error('password_confirmation')) : ?>
            <p class="error__message">※<?= error('password_confirmation') ?></p>
        <?php endif; ?>
        <br>
        <br>
        
        <button type="submit">送信する</button>
    </form>
</section>