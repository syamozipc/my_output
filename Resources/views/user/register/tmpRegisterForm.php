<section>
    <p>仮会員登録</p>
    <form action="<?= route('register/sendRegisterMail') ?>" method="POST">
        <?= csrf() ?>
        <label for="email">メールアドレスを入力してください</label>
        <br>
        <input type="email" name="email" id="email" value="<?= old('email') ?>">
        <?php if (error('email')) : ?>
            <p class="error__message">※<?= error('email') ?></p>
        <?php endif; ?>
        <br>
        <button type="submit">送信する</button>
    </form>
</section>