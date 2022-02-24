<section>
    <p>本会員登録</p>
    <form action="<?= route('register/register') ?>" method="POST">
        <?= csrf() ?>
        <input type="hidden" name="register_token" value="<?= $registerToken ?>">
        <?php if (error('register_token')) : ?>
            <p class="error__message">※<?= error('register_token') ?></p>
        <?php endif; ?>

        <label>パスワード
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