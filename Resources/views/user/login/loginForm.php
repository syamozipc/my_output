<section>
    <p>ログイン</p>
    <form action="<?= route('login/login') ?>" method="POST">
        <?= csrf() ?>
        <label>メールアドレス
            <br>
            <input type="email" name="email">
        </label>
        <?php if (old('error_email')) : ?>
            <p class="error__message">※<?= old('error_email') ?></p>
        <?php endif; ?>

        <br>

        <label>パスワード
            <br>
            <input type="password" name="password">
        </label>
        <?php if (old('error_password')) : ?>
            <p class="error__message">※<?= old('error_password') ?></p>
        <?php endif; ?>
        <br>
        <br>
        
        <button type="submit">送信する</button>
    </form>
    <a href="<?= route('passwordReset/resetRequest') ?>">パスワードを忘れた方はコチラ</a>
</section>