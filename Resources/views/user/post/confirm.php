<p>確認</p>
<div>
    <form action="<?= route('post/save'); ?>" method="POST" enctype="multipart/form-data">
        <p><?= $data['country']->name ?></p>
        <img
            src="<?= PUBLIC_URL . 'upload/' . basename($data['filePath']); ?>"
            alt="アップロードファイル"
            width="400"
            height="400"
        >
        <p><?= $data['post']['description'] ?></p>


        <input type="hidden" name="country_id" value="<?= $data['post']['country_id'] ?>">
        <input type="hidden" name="description" value="<?= $data['post']['description'] ?>">
        <input type="hidden" name="file_path" value="<?= $data['filePath'] ?>">


        <button type="submit">送信</button>
    </form>
</div>

<div>
    <form action="<?= route('post/create'); ?>" method="POST">
        <input type="hidden" name="country_id" value="<?= $data['post']['country_id'] ?>">
        <input type="hidden" name="description" value="<?= $data['post']['description'] ?>">

        <button type="submit">修正する</button>
    </form>
</div>