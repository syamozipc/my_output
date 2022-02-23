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
        // api_token, remember_tokenの値をテーブルカラムから削除
        $this->deleteApiTokenAndRememberToken(userId:$userId);

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

    /**
     * ログアウト時にapi_token、remember_tokenを更新する
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