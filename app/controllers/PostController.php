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

    public function save(): void
    {
        /**
         * @todo ファイル保存用関数に切り出す
         */
        $tempPath = $_FILES['upload']['tmp_name'];
        $filePath = PUBLIC_PATH . 'upload/' . $_FILES['upload']['name'];

        move_uploaded_file($tempPath, $filePath);

        // path含めpost・post_detailsテーブルに保存
        $this->postModel->save(post:$_POST, filePath:$filePath); 
        
        header('Location: ' . URL_PATH . 'post/index');
    }

    public function show(int $id): void
    {
        $post = $this->postModel->fetchPostById($id);

        $data = [
            'post' => $post
        ];

        $this->view(view:'post/show', data:$data);
    }
}