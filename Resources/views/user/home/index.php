<div>
    <p><?= $data['description']; ?></p>
    <a href='<?= route('register/tmpRegisterForm'); ?>'>会員登録はこちら</a>
    <br>
    <a href='<?= route('login/loginForm'); ?>'>ログインはこちら</a>
    <br>
    <a href='<?= route('post/index'); ?>'>投稿一覧はこちら</a>
    <br>
    <a href='<?= route('post/create'); ?>'>新規投稿はこちら</a>
</div>