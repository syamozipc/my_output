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

    public function save()
    {
        /**
         * @todo ファイル保存用関数に切り出す
         */
        $tempPath = $_FILES['upload']['tmp_name'];
        $filePath = PUBLIC_PATH . 'upload/' . $_FILES['upload']['name'];

        move_uploaded_file($tempPath, $filePath);

        // path含めpost・post_detailsテーブルに保存
        $this->postModel->save(post:$_POST, filePath:$filePath);        
    }
}