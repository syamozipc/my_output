<?php
namespace App\Services;

use App\models\User;

class RegisterService {
    public object $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * ユーザーを仮登録し、本登録メールを送信
     *
     * @param string $email
     * @return void
     */
    public function temporarilyRegister(string $email, string $registerToken)
    {
        // 仮登録済みかどうかで UPDATE/INSERT を分岐
        $user = $this->fetchTemporarilyRegisteredUser(email:$email);

        if (!$user) $user = new User();

        $currentDateTime = (new \DateTime())->format(DateTime_Default_Format);
        $params = [
            'email' => $email,
            'register_token' => $registerToken,
            'register_token_sent_at' => $currentDateTime
        ];

        $user->fill($params)->save();

        return;
    }

    /**
     * 仮登録状態のユーザーを取得
     * ・期限切れの有無に影響されない
     *
     * @param string $email
     * @return User|false
     */
    public function fetchTemporarilyRegisteredUser(string $email): User|false
    {
        $sql = 'SELECT * FROM `users` WHERE `email` = :email AND `status_id` = :status_id';

        // $userがいればobject、いなければfalseが返る
        $userOrFalse = $this->userModel->db
            ->prepare(sql:$sql)
            ->bindValue(':email', $email)
            ->bindValue(':status_id', ' tentative')
            ->executeAndFetch(get_class($this->userModel));

        return $userOrFalse;
    }

    /**
     * 仮登録完了メールを送信
     *
     * @param string $email
     * @param string $token
     * @return boolean 送信成功でtrue、失敗でfalseが返る
     */
    public function sendEmail(string $to, string $registerToken):bool
    {
        // 無くてもいけるかも
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        $url = route('register/verifyToken', "?token={$registerToken}");
        $hour = Email_Token_Valid_Period_Hour;

        $subject =  '【' . SITENAME . '】' . '仮登録が完了しました';

        $body = <<<EOD
            会員登録ありがとうございます！

            {$hour}時間以内に下記URLへアクセスし、本登録を完了してください。
            {$url}
            EOD;

        $headers = "From : syamozipc@gmail.com\n";
        $headers .= "Content-Type : text/plain";

        return mb_send_mail($to, $subject, $body, $headers);
    }

    /**
     * トークンに一致するユーザーを取得
     * トークンが一致しないもしくは期限切れの場合、falseをリターン
     *
     * @param string $token
     * @return User|false
     */
    public function fetchValidTemporarilyRegisteredUser(string $registerToken):User|false
    {
        $sql = 'SELECT * FROM `users` WHERE `register_token` = :register_token AND `register_token_sent_at` >= :register_token_sent_at AND `status_id` = :status_id';

        $hour = Email_Token_Valid_Period_Hour;
        $tokenValidPeriod = (new \DateTime())->modify("-{$hour} hour")->format(DateTime_Default_Format);

        $userOrFalse = $this->userModel->db
            ->prepare($sql)
            ->bindValue(':register_token', $registerToken)
            ->bindValue(':register_token_sent_at', $tokenValidPeriod)
            ->bindValue(':status_id', 'tentative')
            ->executeAndFetch(get_class($this->userModel));

            return $userOrFalse;
    }

    /**
     * userを本登録する
     *
     * @param User $user
     * @return void
     */
    public function regsterUser(User $user, string $password):void
    {
        $sql = 'UPDATE users SET `register_token_verified_at` = :register_token_verified_at, `password` = :password, `status_id` = :status_id, `status_updated_at` = :status_updated_at WHERE `register_token` = :register_token';

        $currentDateTime = (new \DateTime())->format(DateTime_Default_Format);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $params = [
            'register_token_verified_at' => $currentDateTime,
            'password' => $hashedPassword,
            'status_id' => 'public',
            'status_updated_at' => $currentDateTime,
            'register_token' => $user->register_token,
        ];

        $user->fill($params)->save();

        return;
    }

    public function sendRegisteredEmail(string $to):bool
    {
            // 無くてもいけるかも
            mb_language("Japanese");
            mb_internal_encoding("UTF-8");
    
            $subject =  '【' . SITENAME . '】' .  '本登録が完了しました';

            $loginUrl = route('login/loginForm');
            $topUrl = route('user/home/index');
    
            $body = <<<EOD
                会員登録ありがとうございます！

                本登録が完了しました。

                ログインページはコチラ：
                $loginUrl
                TOPページはコチラ：
                $topUrl
                EOD;
    
            $headers = "From : syamozipc@gmail.com\n";
            $headers .= "Content-Type : text/plain";
    
            return mb_send_mail($to, $subject, $body, $headers);
    }
}