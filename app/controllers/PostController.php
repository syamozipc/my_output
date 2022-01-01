<?php
class PostController extends Controller {

    public $postModel;
    public $countryModel;

    public function __construct()
    {
        $this->postModel = $this->model('Post');
        $this->countryModel = $this->model('Country');
    }

    public function index()
    {
        $description = "投稿一覧";

        $data = [
            'description' => $description
        ];

        $this->view(view:'post/index', data:$data);
    }

    public function create()
    {
        // 国一覧を取得
        $countriesList = $this->countryModel->fetchCountriesList();

        $data = [
            'countriesList' => $countriesList
        ];

        $this->view(view:'post/create', data:$data);
    }
}