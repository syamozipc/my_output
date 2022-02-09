<div>
    <p><?= $data['description']; ?></p>
    <a href='<?= route('post/create'); ?>'>新規投稿はこちら</a>
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
            <?php foreach ($data['postsList'] as $post) : ?>
                <tr>
                    <td>
                        <a href="<?= route('post/show', $post->id); ?>">
                            <img 
                            src="<?= PUBLIC_URL . 'upload/' . $post->path ?>"
                            alt="アップロードファイル"
                            width="400"
                            height="400"
                            >
                        </a>
                    </td>
                    <td><?= $post->description ?></td>
                    <td><?= $post->country_name ?></td>
                    <td><?= $post->user_name ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>