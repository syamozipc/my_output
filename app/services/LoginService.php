<?php
namespace App\Services;

use App\Libraries\Model;

class LoginService {
    use \App\Traits\SessionTrait;

    /**
     * ログイン処理
     * 
     * ・emailとpasswordが正しいか確認
     * ・sessionを生成
     * ・api_tokenを保存
     * 
     * ※vlidationとredirectは呼び出し元でする想定
     *
     * @param string $email
     * @param string $password
     * @param Model $model login対象のテーブルのモデル
     * @return boolean login成功でtrue、失敗でfalseを返す
     */
    public function baseLogin(string $email, string $password, Model $model):bool
    {
        $sql = 'SELECT * FROM `users` WHERE `email` = :email';

        $user = $model->db
            ->prepare($sql)
            ->bind(':email', $email)
            ->executeAndFetch();

        if (!$user) return false;

        $hashedPassword = $user->password;

        if (!password_verify($password, $hashedPassword)) return false;

        $this->loginSession(userId:$user->id);

        $this->updateApiToken(userId:$user->id, model:$model);

        return true;
    }

    /**
     * ログイン時にuserIdをセッションに保存する
     * @todo 有効期限設定やsession cookieを使う（ブラウザを閉じても破棄されないように）
     *
     * @param string $userId
     * @return void
     */
    private function loginSession($userId):void
    {
        $this->setSession(key:'user_id', param:$userId);

        return;
    }

    /**
     * ログイン時にapi_tokenを更新する
     *
     * @param string $userId
     * @return void
     */
    private function updateApiToken($userId, Model $model):void
    {
        $sql = 'UPDATE users SET `api_token` = :api_token WHERE `id` = :id';
        $model->db
            ->prepare($sql)
            ->bind(':api_token', str_random(length:80))
            ->bind(':id', $userId)
            ->execute();

        return;
    }
}