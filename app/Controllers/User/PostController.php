<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Services\{CountryService, PostService, LoginService};
use App\Models\{Country, Post};
use App\Validators\User\{PostCreateValidator, PostEditValidator, PostDeleteValidator};

class PostController extends Controller {
    use \App\Traits\SessionTrait;

    public CountryService $countryService;
    public PostService $postService;
    public LoginService $loginService;

    public function __construct()
    {
        parent::__construct();
        
        $this->countryService = new CountryService();
        $this->postService = new PostService();
        $this->loginService = new LoginService();
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
        $this->loginService->redirectToLoginFormIfNotLogedIn();
        
        // 国一覧を取得
        $countriesList = $this->countryService->fetchCountriesList();

        $post = new Post(old() ?: filter_input_array(INPUT_POST));

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
        $this->loginService->redirectToLoginFormIfNotLogedIn();

        $request = filter_input_array(INPUT_POST);

        $validator = new PostCreateValidator();
        $isValidated = $validator->validate(request:$request, files:$_FILES);

        if (!$isValidated) return redirect('post/create');

        $post = new Post(old() ?: $request);

        $filePath = $this->postService->uploadFileToPublic($_FILES);

        $country = $this->countryService->fetchCountryByID($request['country_id']);

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
        $this->loginService->redirectToLoginFormIfNotLogedIn();

        $request = filter_input_array(INPUT_POST);
        $userId = $this->getSession('user_id');

        if (!$userId) {
            $this->setFlashSession(key:"error_status", param:'ログインしてください。');

            return redirect('login/loginForm');
        }

        // path含めpost・post_detailsテーブルに保存
        $this->postService->savePost(post:$request, userId:$userId);

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
        $this->loginService->redirectToLoginFormIfNotLogedIn();

        $post = $this->postService->fetchPostById($id);

        $request = filter_input_array(INPUT_POST);

        if (old() || $request) {
            $post->country_id = old('country_id') ?? $request['country_id'];
            $post->description = old('description') ?? $request['description'];
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
        $this->loginService->redirectToLoginFormIfNotLogedIn();

        $request = filter_input_array(INPUT_POST);

        $validator = new PostEditValidator();
        $isValidated = $validator->validate($request);

        if (!$isValidated) return redirect("post/edit/{$id}");

        $post = $this->postService->fetchPostById($id);

        $post->country_id = $request['country_id'];
        $post->description = $request['description'];

        $country = $this->countryService->fetchCountryByID($request['country_id']);
        $post->country_name = $country->name;

        $data = [
            'css' => 'css/user/post/editConfirm.css',
            'js' => 'js/user/post/editConfirm.js',
            'post' => $post,
        ];

        return $this->view(view:'user/post/editConfirm', data:$data);
    }

    public function update(int $id)
    {
        $this->loginService->redirectToLoginFormIfNotLogedIn();

        // path含めpost・post_detailsテーブルに保存
        $this->postService->updatePost(post:filter_input_array(INPUT_POST), id:$id);

        return redirect("post/show/{$id}");
    }

    public function delete(int $id)
    {
        $this->loginService->redirectToLoginFormIfNotLogedIn();

        $validator = new PostDeleteValidator();
        $isValidated = $validator->validate($id);

        if (!$isValidated) return redirect("post/show/{$id}");

        $this->postService->deletePost(id:$id);

        return redirect('post/index');
    }
}