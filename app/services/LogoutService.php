<?php
namespace App\Services;

use App\Libraries\Model;

class LogoutService {
    use \App\Traits\SessionTrait;

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
    public function baseLogout(string $userId, Model $model)
    {
        $this->deleteApiToken(userId:$userId, model:$model);

        $this->logoutSession();

        return;
    }

    /**
     * ログアウト時にuserIdをセッションから破棄する
     * @todo session cookieの削除も必要（独習PHPを参照）

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
    private function deleteApiToken($userId, Model $model):void
    {
        $sql = 'UPDATE users SET `api_token` = NULL WHERE `id` = :id';
        $model->db
            ->prepare($sql)
            ->bind(':id', $userId)
            ->execute();

        return;
    }
}