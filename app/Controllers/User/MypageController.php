<?php
namespace App\Controllers\User;

use App\Libraries\Controller;

class MypageController extends Controller {
    use \App\Traits\SessionTrait;

    public function __construct()
    {
        parent::__construct();
        
        if (!isLogedIn()) return redirect('login/loginForm');
    }

    public function index()
    {
        $description = 'My Page';
        $user = $this->userService->getUserById($this->getSession('user_id'));

        $data = [   
            'css' => 'css/user/mypage/index.css',
            'js' => 'js/user/mypage/index.js',
            'description' => $description,
            'user' => $user
        ];

        $this->view(view:'user/mypage/index', data:$data);
    }
}