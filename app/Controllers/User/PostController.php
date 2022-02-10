<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Services\{CountryService, PostService};
use App\Models\{Country, Post};
use App\Validators\User\{PostCreateValidator, PostEditValidator, PostDeleteValidator};

class PostController extends Controller {
    public $countryService;
    public $postService;

    public function __construct()
    {
        $this->countryService = new CountryService();
        $this->postService = new PostService();
    }

    public function index()
    {
        $description = "投稿一覧";

        $postsList = $this->postService->fetchPostsList();

        $data = [
            'css' => 'css/user/post/index.css',
            'js' => 'js/user/post/index.js',
            'description' => $description,
            'postsList' => $postsList,
        ];

        return $this->view(view:'user/post/index', data:$data);
    }

    public function create()
    {
        // 国一覧を取得
        $countriesList = $this->countryService->fetchCountriesList();

        $post = new Post(old() ?: $_POST);

        $data = [
            'css' => 'css/user/post/create.css',
            'js' => 'js/user/post/create.js',
            'countriesList' => $countriesList,
            'post' => $post
        ];

        return $this->view(view:'user/post/create', data:$data);
    }

    public function confirm()
    {
        $validator = new PostCreateValidator();
        $isValidated = $validator->validate($_POST, $_FILES);

        if (!$isValidated) return redirect('post/create');

        $post = new Post(old() ?: $_POST);

        $filePath = $this->postService->uploadFileToPublic($_FILES);

        $country = $this->countryService->fetchCountryByID($_POST['country_id']);

        $data = [
            'css' => 'css/user/post/confirm.css',
            'js' => 'js/user/post/confirm.js',
            'post' => $post,
            'country' => $country,
            'filePath' => $filePath
        ];

        return $this->view(view:'user/post/confirm', data:$data);
    }

    public function save()
    {
        // path含めpost・post_detailsテーブルに保存
        $this->postService->savePost(post:$_POST);

        return redirect('post/index');
    }

    public function show(int $id)
    {
        $post = $this->postService->fetchPostById($id);

        $data = [
            'css' => 'css/user/post/show.css',
            'js' => 'js/user/post/show.js',
            'post' => $post
        ];

        return $this->view(view:'user/post/show', data:$data);
    }

    public function edit(int $id)
    {
        $post = $this->postService->fetchPostById($id);

        if (old() || $_POST) {
            $post->country_id = old('country_id') ?? $_POST['country_id'];
            $post->description = old('description') ?? $_POST['description'];
        }

        $countriesList = $this->countryService->fetchCountriesList();

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

        $post = $this->postService->fetchPostById($id);

        $post->country_id = $_POST['country_id'];
        $post->description = $_POST['description'];

        $country = $this->countryService->fetchCountryByID($_POST['country_id']);
        $post->country_name = $country->name;

        $data = [
            'css' => 'css/user/post/editConfirm.css',
            'js' => 'js/user/post/editConfirm.js',
            'post' => $post,
        ];

        return $this->view(view:'user/post/edit_confirm', data:$data);
    }

    public function update(int $id)
    {
        // path含めpost・post_detailsテーブルに保存
        $this->postService->updatePost(post:$_POST, id:$id);

        return redirect("post/show/{$id}");
    }

    public function delete(int $id)
    {
        // deleteもvalidation必要
        $validator = new PostDeleteValidator();
        $isValidated = $validator->validate($id);

        if (!$isValidated) return redirect("post/show/{$id}");

        $this->postService->deletePost(id:$id);

        return redirect('post/index');
    }
}