<section>
    <p>パスワードリセット</p>
    <form action="<?= route('passwordReset/sendPasswordResetMail') ?>" method="POST">
        <?= csrf() ?>
        <label for="email">メールアドレスを入力してください</label>
        <br>
        <input type="email" name="email" id="email" value="<?= old('email') ?>">
        <?php if (old('error_email')) : ?>
            <p class="error__message">※<?= old('error_email') ?></p>
        <?php endif; ?>
        <br>
        <button type="submit">送信する</button>
    </form>
</section>