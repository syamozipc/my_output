<section>
    <p>本会員登録</p>
    <form action="<?= route('register/register') ?>" method="POST">
        <?= csrf() ?>
        <input type="hidden" name="email_verify_token" value="<?= $data['emailVerifyToken'] ?>">
        <?php if (old('error_email_verify_token')) : ?>
            <p class="error__message">※<?= old('error_email_verify_token') ?></p>
        <?php endif; ?>

        <label>パスワード
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