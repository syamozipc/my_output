<p><a href="<?= route('/home/index'); ?>">TOPへ</a></p>
<br>
<div>
    <p><?= $description; ?></p>
    <a href='<?= route('/post/create'); ?>'>新規投稿はこちら</a>
</div>

<div>
    <table>
        <thead>
            <tr>
                <th>画像</th>
                <th>概要</th>
                <th>国名</th>
                <th>投稿者</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post) : ?>
                <tr>
                    <td>
                        <a href="<?= route('/post/show', $post->id); ?>">
                            <img 
                            src="<?= public_url('upload/' . $post->path); ?>"
                            alt="アップロードファイル"
                            width="400"
                            height="400"
                            >
                        </a>
                    </td>
                    <td><?= bre($post->description) ?></td>
                    <td><?= $post->country_name ?></td>
                    <td><?= e($post->user_name ?? $post->email) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>