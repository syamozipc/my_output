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

        $posts = $this->postService->fetchPostsList();

        $data = [
            'css' => 'css/user/post/index.css',
            'js' => 'js/user/post/index.js',
            'description' => $description,
            'posts' => $posts,
        ];

        return $this->view(view:'user/post/index', data:$data);
    }

    public function create()
    {
        $this->loginService->redirectToLoginFormIfNotLogedIn();

        $post = new Post(old() ?: filter_input_array(INPUT_POST) ?? []);

        $data = [
            'css' => 'css/user/post/create.css',
            'js' => 'js/user/post/create.js',
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

        if (!$isValidated) return redirect('/post/create');

        $post = new Post(old() ?: $request);

        $filePath = $this->postService->uploadFileToPublic($_FILES);

        $data = [
            'css' => 'css/user/post/confirm.css',
            'js' => 'js/user/post/confirm.js',
            'post' => $post,
            'filePath' => $filePath
        ];

        return $this->view(view:'user/post/confirm', data:$data);
    }

    public function save()
    {
        $this->loginService->redirectToLoginFormIfNotLogedIn();

        $request = filter_input_array(INPUT_POST);

        // validation 
        $validator = new PostCreateValidator();
        $isValidated = $validator->validate(request:$request);

        if (!$isValidated) return redirect('/post/create');

        // path含めpost・post_detailsテーブルに保存
        $request['user_id'] = $this->userId;
        $request['country_id'] = $this->countryService->fetchCountryByName($request['country_name'])->id;

        $this->postService->savePost(params:$request);

        return redirect('/post/index');
    }

    public function show(int $id)
    {
        $post = $this->postService->fetchPostById($id);
        
        if (!$post) return redirect('/error/response404');

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

        if (!$post) return redirect('/error/response404');

        // 投稿者とログインユーザーが別であれば、処理実行不可
        if ((int)$post->user_id !== $this->userId) return redirect('/post/index');

        $request = filter_input_array(INPUT_POST);

        if (old() || $request) {
            $post->country_id = old('country_id') ?? $request['country_id'];
            $post->description = old('description') ?? $request['description'];
        }

        $countries = $this->countryService->fetchCountriesList();

        $data = [
            'css' => 'css/user/post/edit.css',
            'js' => 'js/user/post/edit.js',
            'countries' => $countries,
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

        if (!$post) return redirect('/error/response404');

        // 投稿者とログインユーザーが別であれば、処理実行不可
        if ((int)$post->user_id !== $this->userId) return redirect("post/show/{$id}");

        $post->fill($request);

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

        $request = filter_input_array(INPUT_POST);

        $validator = new PostEditValidator();
        $isValidated = $validator->validate($request);

        if (!$isValidated) return redirect("post/edit/{$id}");

        $post = $this->postService->fetchPostById($id);

        if (!$post) return redirect('/error/response404');

        // 投稿者とログインユーザーが別であれば、処理実行不可
        if ((int)$post->user_id !== $this->userId) return redirect("post/show/{$id}");

        $post->country_id = $request['country_id'];
        $post->description = $request['description'];

        $post->save();

        return redirect("post/show/{$id}");
    }

    public function delete(int $id)
    {
        $this->loginService->redirectToLoginFormIfNotLogedIn();

        $validator = new PostDeleteValidator();
        $isValidated = $validator->validate($id);

        if (!$isValidated) return redirect("post/show/{$id}");

        $post = $this->postService->fetchPostById($id);

        if (!$post) return redirect('/error/response404');

        // 投稿者とログインユーザーが別であれば、処理実行不可
        if ((int)$post->user_id !== $this->userId) return redirect("post/show/{$id}");

        $this->postService->deletePost(post:$post);

        return redirect('/post/index');
    }
}