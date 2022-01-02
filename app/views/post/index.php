<?php require APP_PATH . 'views/template/header.php' ?>
<div>
    <p><?= $data['description']; ?></p>
</div>

<div>
    <table>
        <thead>
            <tr>
                <th>国名</th>
                <th>概要</th>
                <th>画像</th>
                <th>投稿者</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['postsList'] as $post) : ?>
                <tr>
                    <td><?= $post->country_name ?></td>
                    <td><?= $post->description ?></td>
                    <td>
                        <img 
                            src="<?= PUBLIC_PATH . 'upload/' . $post->path ?>"
                            alt="アップロードファイル"
                            width="400"
                            height="400"
                        >
                    </td>
                    <td><?= $post->user_name ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require APP_PATH . 'views/template/footer.php' ?>