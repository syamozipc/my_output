<?php
class PostController extends Controller {

    public $postModel;

    public function __construct()
    {
        $this->postModel = $this->model('Post');
    }

    public function index()
    {
        $description = "投稿一覧";

        $data = [
            'description' => $description
        ];

        $this->view('post/index', $data);
    }

    public function create()
    {

    }
}