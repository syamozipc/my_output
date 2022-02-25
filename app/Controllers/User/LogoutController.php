<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Services\LogoutService;

class LogoutController extends Controller {
    use \App\Traits\SessionTrait;

    public LogoutService $logoutService;

    public function __construct()
    {
        parent::__construct();
        
        $this->logoutService = new LogoutService();
    }

    public function logout()
    {
        if (!$this->userId) return redirect('login/loginForm');

        $this->logoutService->baseLogout(userId:$this->userId);

        return redirect('home/index');
    }
}