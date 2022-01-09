<?php
namespace App\Controllers\User;

use App\Libraries\Controller;

class HomeController extends Controller {

    public function index()
    {
        $description = "My Outputへようこそ\n好きなように練習してね";

        $data = [
            'css' => 'home/index.css',
            'description' => $description
        ];

        $this->view(view:'user/home/index', data:$data);
    }
}