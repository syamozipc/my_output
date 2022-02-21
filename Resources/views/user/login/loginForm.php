<section>
    <p>ログイン</p>
    <form action="<?= route('login/login') ?>" method="POST">
        <?= csrf() ?>
        <hr>
        <label>メールアドレス
            <br>
            <input type="email" name="email">
        </label>
        <?php if (old('error_email')) : ?>
            <p class="error__message">※<?= old('error_email') ?></p>
        <?php endif; ?>
        <br>
        <br>
        <hr>

        <label>パスワード
            <br>
            <input type="password" name="password">
        </label>
        <?php if (old('error_password')) : ?>
            <p class="error__message">※<?= old('error_password') ?></p>
        <?php endif; ?>
        <br>
        <br>
        <hr>

        <input type="checkbox" name="remember_me" id="remember_me">
        <label for="remember_me">ログイン情報を記憶する</label>
        <?php if (old('error_remember_me')) : ?>
            <p class="error__message">※<?= old('error_remember_me') ?></p>
        <?php endif; ?>
        <br>
        <br>
        
        <button type="submit">送信する</button>
    </form>
    <a href="<?= route('passwordReset/resetRequest') ?>">パスワードを忘れた方はコチラ</a>
</section>