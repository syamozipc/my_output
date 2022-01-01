<?php
class PostController extends Controller {

    public function index()
    {
        $description = "投稿一覧";

        $data = [
            'description' => $description
        ];

        $this->view('post/index', $data);
    }
}