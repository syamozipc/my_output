<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Services\LogoutService;
use App\Models\User;

class LogoutController extends Controller {
    use \App\Traits\SessionTrait;

    public user $userModel;
    public LogoutService $logoutService;

    public function __construct()
    {
        $this->userModel = new User();
        $this->logoutService = new LogoutService();
    }

    public function logout()
    {
        $userId = $this->getSession('user_id');

        if (!$userId) return redirect('login/loginForm');

        $this->logoutService->baseLogout(userId:$userId, model:$this->userModel);

        return redirect('login/loginForm');
    }
}