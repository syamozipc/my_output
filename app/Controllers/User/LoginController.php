<?php
namespace App\Controllers\User;

use App\Libraries\Controller;

class LoginController extends Controller {

    public function login()
    {
        echo '<pre>';var_dump($_POST);die;
        // ・https://qiita.com/mpyw/items/bb8305ba196f5105be15
        /**
         * login
         * 
         * ・validation
         * ・session登録
         * ・mypageへ遷移
         */
        $description = "My Outputへようこそ\n好きなように練習してね";

        $data = [
            'css' => 'css/user/home/index.css',
            'js' => 'js/user/home/index.js',
            'description' => $description
        ];

        $this->view(view:'user/home/index', data:$data);
    }
}