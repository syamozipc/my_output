<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Services\PostService;
use App\models\{Post, Country};
use App\Validations\User\PostCreate;

class PostController extends Controller {
    public $postModel;
    public $countryModel;
    public $postService;

    public function __construct()
    {
        session_start();

        $this->postModel = new Post();
        $this->countryModel = new Country();
        $this->postService = new PostService();
    }

    public function index(): void
    {
        $description = "投稿一覧";

        $postsList = $this->postModel->fetchPostsList();

        $data = [
            'css' => 'css/post/index.css',
            'js' => 'js/post/index.js',
            'description' => $description,
            'postsList' => $postsList
        ];

        $this->view(view:'user/post/index', data:$data);
    }

    public function create(): void
    {
        // 国一覧を取得
        $countriesList = $this->countryModel->fetchCountriesList();

        $data = [
            'css' => 'css/post/create.css',
            'js' => 'js/post/create.js',
            'countriesList' => $countriesList,
            'post' => $_SESSION['old_post'] ?? $_POST
        ];

        $this->view(view:'user/post/create', data:$data);
    }

    public function confirm()
    {
        unset($_SESSION['old_post'], $_SESSION['error_country_id'], $_SESSION['error_description']);

        $validation = new PostCreate();
        $isValidated = $validation->validate($_POST);

        if (!$isValidated) {
            $_SESSION['old_post'] = $_POST;
            redirect('post/create');
        }

        $filePath = $this->postService->uploadFileToPublic($_FILES);

        $country = $this->countryModel->fetchCountryByID($_POST['country_id']);

        $data = [
            'css' => 'css/post/confirm.css',
            'js' => 'js/post/confirm.js',
            'post' => $_POST,
            'country' => $country,
            'filePath' => $filePath
        ];

        $this->view(view:'user/post/confirm', data:$data);
    }

    public function save(): void
    {
        // path含めpost・post_detailsテーブルに保存
        $this->postModel->save(post:$_POST);

        redirect('post/index');
    }

    public function show(int $id): void
    {
        $post = $this->postModel->fetchPostById($id);

        $data = [
            'css' => 'css/post/show.css',
            'js' => 'js/post/show.js',
            'post' => $post
        ];

        $this->view(view:'user/post/show', data:$data);
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
            'css' => 'css/post/edit.css',
            'js' => 'js/post/edit.js',
            'countriesList' => $countriesList,
            'post' => $post
        ];

        $this->view(view:'user/post/edit', data:$data);
    }

    public function editConfirm($id)
    {
        $post = $this->postModel->fetchPostById($id);

        $post->country_id = $_POST['country_id'];
        $post->description = $_POST['description'];

        $country = $this->countryModel->fetchCountryByID($_POST['country_id']);
        $post->country_name = $country->name;

        $data = [
            'css' => 'css/post/editConfirm.css',
            'js' => 'js/post/editConfirm.js',
            'post' => $post,
        ];

        $this->view(view:'user/post/edit_confirm', data:$data);
    }

    public function update(int $id): void
    {
        // path含めpost・post_detailsテーブルに保存
        $this->postModel->update(post:$_POST, id:$id);

        redirect("post/show/{$id}");
    }

    public function delete(int $id): void
    {
        $this->postModel->delete(id:$id);

        redirect('post/index');
    }
}