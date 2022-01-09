<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Services\PostService;
use App\models\{Post, Country};

class PostController extends Controller {

    public $postModel;
    public $countryModel;
    public $postService;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->countryModel = new Country();
        $this->postService = new PostService();
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
            'css' => PUBLIC_PATH . 'css/post/create.css',
            'countriesList' => $countriesList,
            'post' => $_POST
        ];

        $this->view(view:'post/create', data:$data);
    }

    public function confirm()
    {
        $filePath = $this->postService->uploadFileToPublic($_FILES);

        $country = $this->countryModel->fetchCountryByID($_POST['country_id']);

        $data = [
            'css' => PUBLIC_PATH . 'css/post/confirm.css',
            'post' => $_POST,
            'country' => $country,
            'filePath' => $filePath
        ];

        $this->view(view:'post/confirm', data:$data);
    }

    public function save(): void
    {
        // path含めpost・post_detailsテーブルに保存
        $this->postModel->save(post:$_POST);

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
        $post = $this->postModel->fetchPostById($id);

        if ($_POST) {
            $post->country_id = $_POST['country_id'];
            $post->description = $_POST['description'];
        }

        $countriesList = $this->countryModel->fetchCountriesList();

        $data = [
            'css' => PUBLIC_PATH . 'css/post/edit.css',
            'countriesList' => $countriesList,
            'post' => $post
        ];

        $this->view(view:'post/edit', data:$data);
    }

    public function editConfirm($id)
    {
        $post = $this->postModel->fetchPostById($id);

        $post->country_id = $_POST['country_id'];
        $post->description = $_POST['description'];

        $country = $this->countryModel->fetchCountryByID($_POST['country_id']);
        $post->country_name = $country->name;

        $data = [
            'css' => PUBLIC_PATH . 'css/post/editConfirm.css',
            'post' => $post,
        ];

        $this->view(view:'post/edit_confirm', data:$data);
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