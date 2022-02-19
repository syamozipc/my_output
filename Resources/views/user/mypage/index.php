<h1><?= $data['description'] ?></h1>
<p>ようこそ、<?= $data['user']->name ?? $data['user']->email ?>さん</p>
<p><a href="<?= route('logout/logout') ?>">ログアウト</a></p>