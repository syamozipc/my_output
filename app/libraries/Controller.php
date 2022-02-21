<?php
namespace App\Libraries;

use App\Services\LoginService;

/**
 * base controller
 * modelとviewをloadする
 */
class Controller {
    use \App\Traits\SessionTrait;

    protected LoginService $loginService;

    public function __construct()
    {
        $this->loginService = new LoginService();

        // ログイン済みの場合、last_login_atを更新
        if (isLogedIn()) {
            $userId = $this->getSession('user_id');
            $this->loginService->updateLastLogin(userId:$userId);

        // 未ログインかつremember_tokenがある場合、ログイン処理
        } else if (isset($_COOKIE['remember_token'])) {
            $rememberToken = $_COOKIE['remember_token'];
            $this->loginService->loginByRememberToken(rememberToken:$rememberToken);
        }
    }

    /**
     * viewを読み込む
     *
     * @param string $view
     * @param array $data
     * @return void
     */
    public function view($view, $data = [])
    {
        $viewFile = base_path("resources/views/{$view}.php");

        if (!file_exists($viewFile)) die('View does not exist');
        
        require_once base_path('resources/views/user/template.php');
    }
}