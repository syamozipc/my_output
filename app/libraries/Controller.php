<?php
namespace App\Libraries;

use App\Services\UserService;

/**
 * base controller
 * modelとviewをloadする
 */
class Controller {
    use \App\Traits\SessionTrait;

    protected UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();

        // @todo 整理
        /**
         * setLoginSession
         * updateApiToken
         * allocateRememberToken
         * updateLastLogin
         * 
         * user取得も必要？
         */

        /**
         * ログイン済みの場合
         * ・updateLastLogin
         */
        if (isLogedIn()) {
            $userId = $this->getSession('user_id');
            $this->userService->updateLastLogin(userId:$userId);

            return;
        }

        $rememberToken = $_COOKIE['remember_token'] ?? NULL;
        if ($rememberToken) {
            // @todo ログインユーザー取得時、api_tokenの更新とlast_loginの更新もしたい（login servieでremember_token login用メソッドを作り、そちらで対応する）
            $user = $this->userService->getUserByRememberToken($rememberToken);

            /**
             * remember_tokenの場合
             * ・setLoginSession
             * ・updateLastLogin
             */
            if ($user) {
                $this->setSession('user_id', $user->id);
                $this->userService->updateLastLogin(userId:$user->id);
            }

            return;
        }

        // sessionもremember_tokenもなければ、未ログインとする
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