<?php
namespace App\Controllers\User;

use App\Libraries\Controller;

class MypageController extends Controller {

    public function index()
    {
        echo '<pre>';var_dump('mypage/indexです');die;
        $description = "My Outputへようこそ\n好きなように練習してね";

        $data = [
            'css' => 'css/user/home/index.css',
            'js' => 'js/user/home/index.js',
            'description' => $description
        ];

        $this->view(view:'user/home/index', data:$data);
    }
}