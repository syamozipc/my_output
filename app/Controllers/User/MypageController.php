<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Models\User;
use App\Services\UserService;

class MypageController extends Controller {
    use \App\Traits\SessionTrait;

    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function index()
    {
        $description = 'My Page';
        $userId = $this->getSession('user_id');

        $user = $this->userService->getUserById($userId);


        $data = [   
            'description' => $description,
            'user' => $user
        ];

        $this->view(view:'user/mypage/index', data:$data);
    }
}