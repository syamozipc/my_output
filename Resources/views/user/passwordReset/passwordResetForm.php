<section>
    <p>パスワードリセット</p>
    <form action="<?= route('passwordReset/reset') ?>" method="POST">
        <?= csrf() ?>
        <input type="hidden" name="password_reset_token" value="<?= $data['passwordResetToken'] ?>">
        <?php if (old('error_password_reset_token')) : ?>
            <p class="error__message">※<?= old('error_password_reset_token') ?></p>
        <?php endif; ?>

        <label>新しいパスワード
            <br>
            <input type="password" name="password">
        </label>
        <?php if (old('error_password')) : ?>
            <p class="error__message">※<?= old('error_password') ?></p>
        <?php endif; ?>
        <br>
        ※8〜12文字
        <br>
        <br>

        <label>パスワード（確認用）
            <br>
            <input type="password" name="password_confirmation">
        </label>
        <?php if (old('error_password_confirmation')) : ?>
            <p class="error__message">※<?= old('error_password_confirmation') ?></p>
        <?php endif; ?>
        <br>
        <br>
        
        <button type="submit">送信する</button>
    </form>
</section>