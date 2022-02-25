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
        $isAuthenticated = false;

        // ログイン済みの場合、last_login_atを更新
        if (isLogedIn()) {
            $userId = $this->getSession('user_id');
            $this->updateLastLogin(userId:$userId);

            $isAuthenticated = true;

        // 未ログインかつremember_tokenがある場合、ログイン処理
        } else if (isset($_COOKIE['remember_token'])) {
            $rememberToken = $_COOKIE['remember_token'];
            $this->loginByRememberToken(rememberToken:$rememberToken);

            $isAuthenticated = true;
        }

        return $isAuthenticated;
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
        $user = $this->userService->getUserByEmail($email);

        // emailが登録されていなければ失敗
        if (!$user) return false;

        // パスワードが一致しなければ失敗
        $hashedPassword = $user->password;
        if (!password_verify($password, $hashedPassword)) return false;

        // 以降ログイン処理
        $this->setLoginSession(userId:$user->id);

        $user->api_token = str_random(length:80);

        // 「ログイン情報を記憶するがチェックされたら、
        // tokenを生成してcookieに持たせ、tokenをhash化したものをusersテーブルに保存
        if ($rememberMe) {
            $token = str_random(40);
            $user->remember_token = md5($token);
    
            // optionsの連想配列は名前付き引数をサポートしていない
            setcookie('remember_token', $token, Cookie_Default_Options);
        }

        $user->save();

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

            $this->setLoginSession(userId:$user->id);
            $this->updateLastLogin(userId:$user->id);

            return true;
    }

    /**
     * ログイン時にuserIdをセッションに保存する
     *
     * @param User $user
     * @return void
     */
    private function setLoginSession(int $userId):void
    {
        $this->setSession(key:'user_id', param:$userId);

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
        $user = $this->userService->getUserById($userId);

        $currentDateTime = (new \DateTime())->format(DateTime_Default_Format);

        $user->last_login_at = $currentDateTime;

        $user->save();

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

        $this->setFlashErrorSession(key:'status', param:'ログインしてください');

        return redirect('login/loginForm');
    }
}