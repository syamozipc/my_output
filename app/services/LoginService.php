<?php
namespace App\Services;

use App\Models\User;

class LoginService {
    use \App\Traits\SessionTrait;
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

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
    public function baseLogin(string $email, string $password, bool $rememberMe = true):bool
    {
        $sql = 'SELECT * FROM `users` WHERE `email` = :email';

        $user = $this->userModel->db
            ->prepare($sql)
            ->bindValue(':email', $email)
            ->executeAndFetch();

        if (!$user) return false;

        $hashedPassword = $user->password;

        if (!password_verify($password, $hashedPassword)) return false;

        $this->setLoginSession(userId:$user->id);

        $this->updateApiToken(userId:$user->id);

        // updateLastLoginもここでやる？
        // @todo updateApiToken,  allocateRememberTokenは一つのfunction, SQLにまとめたい

        if ($rememberMe) $this->allocateRememberToken(userId:$user->id);

        return true;
    }

    /**
     * ログイン時にuserIdをセッションに保存する
     * @todo 有効期限設定やsession cookieを使う（ブラウザを閉じても破棄されないように）
     *
     * @param string $userId
     * @return void
     */
    private function setLoginSession($userId):void
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
    private function updateApiToken($userId):void
    {
        $sql = 'UPDATE users SET `api_token` = :api_token WHERE `id` = :id';
        $this->userModel->db
            ->prepare($sql)
            ->bindValue(':api_token', str_random(length:80))
            ->bindValue(':id', $userId)
            ->execute();

        return;
    }

    /**
     * remember meがチェックされたら、その仕組みを提供する
     * 
     * 1.remember_tokenを生成してcookieに持たせる
     * 2.そのtokenをhash化したものをusersテーブルに保存
     *
     * @todo updateApiToken()と合わせて1つのSQLで済ませたい
     * 
     * @param integer $userId
     * @return void
     */
    public function allocateRememberToken(int $userId)
    {
        $token = str_random(40);
        $digest = md5($token);

        // optionsの連想配列は名前付き引数をサポートしていない
        setcookie('remember_token', $token, Cookie_Default_Options);

        $sql = 'UPDATE users SET `remember_token` = :remember_token WHERE `id` = :id';

        $this->userModel->db
            ->prepare($sql)
            ->bindValue(':remember_token', $digest)
            ->bindValue(':id', $userId)
            ->execute();

        return;
    }
}