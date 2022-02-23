<?php
namespace App\Services;

use App\Models\User;
use App\Services\UserService;

class LoginService {
    use \App\Traits\SessionTrait;
    private User $userModel;
    private UserService $userService;

    public function __construct()
    {
        $this->userModel = new User();
        $this->userService = new UserService();
    }

    /**
     * ユーザーのログイン管理をする
     * ・既にログイン済みなら、last_login_atの更新のみ
     * ・未ログインでもremember_tokenがあれば、それを使ってログイン処理
     *
     * @return boolean
     */
    public function authenticateUser():bool
    {
        $authenticated = false;

        // ログイン済みの場合、last_login_atを更新
        if (isLogedIn()) {
            $userId = $this->getSession('user_id');
            $this->updateLastLogin(userId:$userId);

            $authenticated = true;

        // 未ログインかつremember_tokenがある場合、ログイン処理
        } else if (isset($_COOKIE['remember_token'])) {
            $rememberToken = $_COOKIE['remember_token'];
            $this->loginByRememberToken(rememberToken:$rememberToken);

            $authenticated = true;
        }

        return $authenticated;
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
     * @return boolean login成功でtrue、失敗でfalseを返す
     */
    public function baseLogin(string $email, string $password, bool $rememberMe = false):bool
    {
        $sql = 'SELECT * FROM `users` WHERE `email` = :email';

        $user = $this->userModel->db
            ->prepare($sql)
            ->bindValue(':email', $email)
            ->executeAndFetch(get_class($this->userModel));

        if (!$user) return false;

        $hashedPassword = $user->password;

        if (!password_verify($password, $hashedPassword)) return false;

        $this->setLoginSession(user:$user);

        $this->updateApiToken(user:$user);

        if ($rememberMe) $this->allocateRememberToken(user:$user);

        return true;
    }

    /**
     * remember tokenに合致するuserに下記ログイン処理をする
     * ・user_idをsessionに保存
     * ・last_login_atを更新
     * 
     * @param string $rememberToken
     * @return boolean
     */
    public function loginByRememberToken($rememberToken): bool
    {
            $user = $this->userService->getUserByRememberToken($rememberToken);

            if (!$user) return false;

            $this->setLoginSession($user->id);
            $this->updateLastLogin(userId:$user->id);

            return true;
    }

    /**
     * ログイン時にuserIdをセッションに保存する
     *
     * @param User $user
     * @return void
     */
    private function setLoginSession(User $user):void
    {
        $this->setSession(key:'user_id', param:$user->id);

        return;
    }

    /**
     * ログイン時にapi_tokenを更新する
     *
     * @param User $user
     * @return void
     */
    private function updateApiToken(User $user):void
    {
        $sql = 'UPDATE users SET `api_token` = :api_token WHERE `id` = :id';
        $this->userModel->db
            ->prepare($sql)
            ->bindValue(':api_token', str_random(length:80))
            ->bindValue(':id', $user->id)
            ->execute();

        return;
    }

    /**
     * remember meがチェックされたら、その仕組みを提供する
     * 
     * 1.remember_tokenを生成してcookieに持たせる
     * 2.そのtokenをhash化したものをusersテーブルに保存
     *
     * @param User $user
     * @return void
     */
    private function allocateRememberToken(User $user)
    {
        $token = str_random(40);
        $digest = md5($token);

        // optionsの連想配列は名前付き引数をサポートしていない
        setcookie('remember_token', $token, Cookie_Default_Options);

        $sql = 'UPDATE users SET `remember_token` = :remember_token WHERE `id` = :id';

        $user->db
            ->prepare($sql)
            ->bindValue(':remember_token', $digest)
            ->bindValue(':id', $user->id)
            ->execute();

        return;
    }

    /**
     * ユーザーがログイン中、HTTPリクエストの都度、usersテーブルのlast_login_atを更新する
     *
     * @param integer $userId
     * @return void
     */
    public function updateLastLogin(int $userId)
    {
        $sql = 'UPDATE users SET `last_login_at` = :last_login_at WHERE `id` = :id';

        $currentDateTime = (new \DateTime())->format(DateTime_Default_Format);

        $this->userModel->db
            ->prepare($sql)
            ->bindValue(':last_login_at', $currentDateTime)
            ->bindValue(':id', $userId)
            ->execute();

        return;
    }

    /**
     * ユーザーがログインしていなかったら、ログインページへ遷移する
     *
     * @return void
     */
    public function redirectToLoginFormIfNotLogedIn()
    {
        if (isLogedIn()) return;

        $this->setFlashSession(key:'error_status', param:'ログインしてください');

        return redirect('login/loginForm');
    }
}