<?php
namespace App\Services;

class LoginService {
    use \App\Traits\SessionTrait;

    /**
     * emailとpasswordが正しければlogin処理を実行
     *
     * @param string $email
     * @param string $password
     * @param object $model login対象のテーブルのモデル
     * @return boolean login成功でtrue、失敗でfalseを返す
     */
    public function baseLogin(string $email, string $password, object $model):bool
    {
        $sql = 'SELECT * FROM `users` WHERE `email` = :email';

        $user = $model->db
            ->prepare($sql)
            ->bind(':email', $email)
            ->executeAndFetch();

        if (!$user) return false;

        $hashedPassword = $user->password;

        if (!password_verify($password, $hashedPassword)) return false;

        // @todo 有効期限設定やsession cookieを使う（ブラウザを閉じても破棄されないように）
        $this->loginSession(userId:$user->id);

        return true;
    }

    /**
     * ログイン時にuserIdをセッションに保存する
     *
     * @param string $userId
     * @return void
     */
    private function loginSession($userId):void
    {
        $this->setSession(key:'user_id', param:$userId);

        return;
    }
}