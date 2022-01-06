<?php
class PostController extends Controller {

    public $postModel;
    public $countryModel;

    public function __construct()
    {
        $this->postModel = $this->model('Post');
        $this->countryModel = $this->model('Country');
    }

    public function index(): void
    {
        $description = "投稿一覧";

        $postsList = $this->postModel->fetchPostsList();

        $data = [
            'css' => PUBLIC_PATH . 'css/post/index.css',
            'description' => $description,
            'postsList' => $postsList
        ];

        $this->view(view:'post/index', data:$data);
    }

    public function create(): void
    {
        // 国一覧を取得
        $countriesList = $this->countryModel->fetchCountriesList();

        $data = [
            'countriesList' => $countriesList
        ];

        $this->view(view:'post/create', data:$data);
    }

    public function confirm()
    {
        /**
         * @todo ファイル保存用関数に切り出す
         */
        $tempPath = $_FILES['upload']['tmp_name'];
        $filePath = UPLOAD_PATH . $_FILES['upload']['name'];

        move_uploaded_file($tempPath, $filePath);
        
        $data = [
            'css' => PUBLIC_PATH . 'css/post/confirm.css',
            'post' => $_POST,
            'tempPath' => $tempPath,
            'filePath' => $filePath,
            'file' => $_FILES['upload']['name']
        ];

        $this->view(view:'post/confirm', data:$data);
    }

    public function save(): void
    {
        // path含めpost・post_detailsテーブルに保存
        // $this->postModel->save(post:$_POST, filePath:$filePath); 
        
        header('Location: ' . URL_PATH . 'post/index');
    }

    public function show(int $id): void
    {
        $post = $this->postModel->fetchPostById($id);

        $data = [
            'css' => PUBLIC_PATH . 'css/post/show.css',
            'post' => $post
        ];

        $this->view(view:'post/show', data:$data);
    }

    public function edit(int $id): void
    {
        $countriesList = $this->countryModel->fetchCountriesList();
        $post = $this->postModel->fetchPostById($id);

        $data = [
            'css' => PUBLIC_PATH . 'css/post/edit.css',
            'countriesList' => $countriesList,
            'post' => $post
        ];

        $this->view(view:'post/edit', data:$data);
    }

    public function update(int $id): void
    {
        // path含めpost・post_detailsテーブルに保存
        $this->postModel->update(post:$_POST, id:$id); 
        
        header('Location: ' . URL_PATH . 'post/show/' . $id);
    }

    public function delete(int $id): void
    {
        $this->postModel->delete(id:$id);

        header('Location: ' . URL_PATH . 'post/index');
    }
}