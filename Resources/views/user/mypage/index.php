<h1><?= $description ?></h1>
<p>ようこそ、<?= $user->name ?? $user->email ?>さん</p>
<p><a href="<?= route('/logout/logout') ?>">ログアウト</a></p>