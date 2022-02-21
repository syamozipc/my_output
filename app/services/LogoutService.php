<?php
namespace App\Services;

use App\Models\User;

class LogoutService {
    use \App\Traits\SessionTrait;

    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * ログアウト処理
     * 
     * ・api_tokenを削除
     * ・sessionを削除
     *
     * @param string $userId
     * @param Model $model logout対象のテーブルのモデル
     * @return void
     */
    public function baseLogout(string $userId)
    {
        $this->deleteApiTokenAndRememberToken(userId:$userId);

        $this->logoutSession();

        setcookie('remember_token', '', time() - 6000, '/');

        return;
    }

    /**
     * ログアウト時にuserIdをセッションから破棄する
     * 
     * @param string $userId
     * @return void
     */
    private function logoutSession():void
    {
        $this->unsetSession(key:'user_id');

        return;
    }

    /**
     * ログイン時にapi_tokenを更新する
     *
     * @param string $userId
     * @return void
     */
    private function deleteApiTokenAndRememberToken($userId):void
    {
        $sql = 'UPDATE users SET `api_token` = NULL, `remember_token` = NULL WHERE `id` = :id';
        $this->userModel->db
            ->prepare($sql)
            ->bindValue(':id', $userId)
            ->execute();

        return;
    }
}