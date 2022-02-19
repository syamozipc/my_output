<p>確認</p>
<div>
    <form action="<?=  route('post/update', $data['post']->id); ?>" method="POST" enctype="multipart/form-data">
        <?= csrf() ?>
        <p><?= $data['post']->country_name ?></p>
        <img
            src="<?= public_url('upload/' . basename($data['post']->path)); ?>"
            alt="アップロードファイル"
            width="400"
            height="400"
        >
        <p><?= bre($data['post']->description) ?></p>


        <input type="hidden" name="country_id" value="<?= $data['post']->country_id ?>">
        <input type="hidden" name="description" value="<?= e($data['post']->description) ?>">

        <button type="submit">送信</button>
    </form>
</div>

<div>
    <form action="<?=  route('post/edit', $data['post']->id); ?>" method="POST">
        <?= csrf() ?>
        <input type="hidden" name="country_id" value="<?= $data['post']->country_id ?>">
        <input type="hidden" name="description" value="<?= e($data['post']->description) ?>">

        <button type="submit">修正する</button>
    </form>
</div>