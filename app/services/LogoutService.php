<?php
namespace App\Services;

use App\Models\User;
use App\Services\UserService;

class LogoutService {
    use \App\Traits\SessionTrait;

    private User $userModel;
    private UserService $userService;

    public function __construct()
    {
        $this->userModel = new User();
        $this->userService = new UserService();
    }

    /**
     * ログアウト処理
     * 
     * ・api_token、remember_tokenの値をテーブルカラムから削除
     * ・remember_tokenのcookieを削除
     * ・ログインsessionの初期化・削除処理
     *
     * @param string $userId
     * @param Model $model logout対象のテーブルのモデル
     * @return void
     */
    public function baseLogout(string $userId)
    {
        $user = $this->userService->getUserById(id:$userId);

        // api_token、remember_tokenの値を削除
        $user->api_token = null;
        $user->remember_token = null;
        $user->save();

        // remeber_tokenをcookieから削除
        setcookie('remember_token', '', time() - 6000, '/');

        // session変数を初期化（メモリから削除するため）
        $this->unsetAllSession();
        // session cookieを削除
        setcookie('PHPSESSID', '', time() - 6000, '/');
        // sessionファイル（sessionの実データ）を削除
        session_destroy();

        return;
    }
}