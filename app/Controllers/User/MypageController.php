<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Models\User;
use App\Services\UserService;

class MypageController extends Controller {
    use \App\Traits\SessionTrait;

    private UserService $userService;
    private $user;

    public function __construct()
    {
        $this->userService = new UserService();
        
        $userId = $this->getSession('user_id');
        $this->user = $this->userService->getUserById($userId);

        $this->userService->updateLastLogin(userId:$this->user->id);
    }

    public function index()
    {
        $description = 'My Page';

        $data = [   
            'css' => 'css/user/mypage/index.css',
            'js' => 'js/user/mypage/index.js',
            'description' => $description,
            'user' => $this->user
        ];

        $this->view(view:'user/mypage/index', data:$data);
    }
}