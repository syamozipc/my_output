<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Services\PostService;
use App\models\{Post, Country};
use App\Validators\User\{PostCreateValidator, PostEditValidator, PostDeleteValidator};

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
            'css' => 'css/user/post/index.css',
            'js' => 'js/user/post/index.js',
            'description' => $description,
            'postsList' => $postsList,
        ];

        return $this->view(view:'user/post/index', data:$data);
    }

    public function create(): void
    {
        // 国一覧を取得
        $countriesList = $this->countryModel->fetchCountriesList();

        $data = [
            'css' => 'css/user/post/create.css',
            'js' => 'js/user/post/create.js',
            'countriesList' => $countriesList,
            'post' => old() ?: $_POST
        ];

        return $this->view(view:'user/post/create', data:$data);
    }

    public function confirm()
    {
        $validator = new PostCreateValidator();
        $isValidated = $validator->validate($_POST, $_FILES);

        if (!$isValidated) return redirect('post/create');

        $filePath = $this->postService->uploadFileToPublic($_FILES);

        $country = $this->countryModel->fetchCountryByID($_POST['country_id']);

        $data = [
            'css' => 'css/user/post/confirm.css',
            'js' => 'js/user/post/confirm.js',
            'post' => $_POST,
            'country' => $country,
            'filePath' => $filePath
        ];

        return $this->view(view:'user/post/confirm', data:$data);
    }

    public function save(): void
    {
        // path含めpost・post_detailsテーブルに保存
        $this->postModel->save(post:$_POST);

        return redirect('post/index');
    }

    public function show(int $id): void
    {
        $post = $this->postModel->fetchPostById($id);

        $data = [
            'css' => 'css/user/post/show.css',
            'js' => 'js/user/post/show.js',
            'post' => $post
        ];

        return $this->view(view:'user/post/show', data:$data);
    }

    public function edit(int $id)
    {
        $post = $this->postModel->fetchPostById($id);

        if (old() || $_POST) {
            $post->country_id = old('country_id') ?? $_POST['country_id'];
            $post->description = old('description') ?? $_POST['description'];
        }

        $countriesList = $this->countryModel->fetchCountriesList();

        $data = [
            'css' => 'css/user/post/edit.css',
            'js' => 'js/user/post/edit.js',
            'countriesList' => $countriesList,
            'post' => $post
        ];

        return $this->view(view:'user/post/edit', data:$data);
    }

    public function editConfirm($id)
    {
        $validator = new PostEditValidator();
        $isValidated = $validator->validate($_POST);

        if (!$isValidated) return redirect("post/edit/{$id}");

        $post = $this->postModel->fetchPostById($id);

        $post->country_id = $_POST['country_id'];
        $post->description = $_POST['description'];

        $country = $this->countryModel->fetchCountryByID($_POST['country_id']);
        $post->country_name = $country->name;

        $data = [
            'css' => 'css/user/post/editConfirm.css',
            'js' => 'js/user/post/editConfirm.js',
            'post' => $post,
        ];

        return $this->view(view:'user/post/edit_confirm', data:$data);
    }

    public function update(int $id): void
    {
        // path含めpost・post_detailsテーブルに保存
        $this->postModel->update(post:$_POST, id:$id);

        return redirect("post/show/{$id}");
    }

    public function delete(int $id)
    {
        // deleteもvalidation必要
        $validator = new PostDeleteValidator();
        $isValidated = $validator->validate($id);

        if (!$isValidated) return redirect("post/show/{$id}");

        $this->postModel->delete(id:$id);

        return redirect('post/index');
    }
}