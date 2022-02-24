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

        $isAuthenticated = $this->loginService->authenticateUser();

        if ($isAuthenticated) $this->userId = $this->getSession('user_id');
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

        // 渡ってきた配列のkeyを変数名に、valueを変数の値にする
        foreach ($data as $key => $value) {
            ${$key} = $value;
        }

        unset($data);
        
        require_once base_path('resources/views/user/template.php');
    }
}