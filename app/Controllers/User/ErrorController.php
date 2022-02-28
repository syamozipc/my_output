<?php
namespace App\Controllers\User;

use App\Libraries\Controller;

class ErrorController extends Controller {

    public function response404()
    {
        $description = '存在しないページです。';

        $data = [
            // 'css' => 'css/user/home/index.css',
            // 'js' => 'js/user/home/index.js',
            'description' => $description
        ];

        $this->view(view:'user/error/404', data:$data);
    }
}