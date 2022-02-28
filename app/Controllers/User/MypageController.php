<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Services\UserService;

class MypageController extends Controller {
    use \App\Traits\SessionTrait;

    private UserService $userService;

    public function __construct()
    {
        parent::__construct();
        
        if (!isLogedIn()) return redirect('/login/loginForm');

        $this->userService = new UserService();
    }

    public function index()
    {
        $description = 'My Page';
        $user = $this->userService->getUserById($this->userId);

        $data = [   
            'css' => 'css/user/mypage/index.css',
            'js' => 'js/user/mypage/index.js',
            'description' => $description,
            'user' => $user
        ];

        $this->view(view:'user/mypage/index', data:$data);
    }
}