<?php require APP_PATH . 'views/template/header.php' ?>
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
            <tr>
                <td>
                    <img 
                        src="<?= PUBLIC_PATH . 'upload/' . $data['post']->path ?>"
                        alt="アップロードファイル"
                        width="400"
                        height="400"
                    >
                </td>
                <td><?= $data['post']->description ?></td>
                <td><?= $data['post']->country_name ?></td>
                <td><?= $data['post']->user_name ?></td>
            </tr>
        </tbody>
    </table>
</div>
<?php require APP_PATH . 'views/template/footer.php' ?>