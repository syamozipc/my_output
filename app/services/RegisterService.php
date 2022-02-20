<?php
namespace App\Services;

use App\models\User;
use DateTime;

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
        if ($this->isTemporarilyRegistered(email:$email)) {
            $sql = 'UPDATE `users` SET `register_token` = :register_token, `register_token_sent_at` = :register_token_sent_at WHERE `email` = :email';
        } else {
            $sql = 'INSERT INTO `users` (`email`, `register_token`, `register_token_sent_at`) VALUES (:email, :register_token, :register_token_sent_at)';
        }
        
        $currentDateTime = (new DateTime())->format(DateTime_Default_Format);

        $this->userModel->db
            ->prepare($sql)
            ->bind(':email', $email)
            ->bind(':register_token', $registerToken)
            ->bind(':register_token_sent_at', $currentDateTime)
            ->execute();

        return;
    }

    /**
     * ユーザーが仮登録状態かどうか
     *
     * @param string $email
     * @return boolean
     */
    public function isTemporarilyRegistered(string $email): bool
    {
        // 仮登録済みかどうか
        // $userが取れれば仮登録済み、いなければ未登録
        $sql = 'SELECT * FROM `users` WHERE `email` = :email AND `status_id` = :status_id';

        // $userがいればobject、いなければfalseが返る
        $this->userModel->db
            ->prepare(sql:$sql)
            ->bind(':email', $email)
            ->bind(':status_id', ' tentative')
            ->execute();

        // 仮登録済みなら1（当てはまる桁数）、未登録もしくは本登録なら、0が返る
        $isExist = $this->userModel->db->rowCount();

        return $isExist;
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
        $hour = Token_Valid_Period_Hour;

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
     * @return object|false
     */
    public function getValidTemporarilyRegisteredUser(string $registerToken):object|false
    {
        $sql = 'SELECT * FROM `users` WHERE `register_token` = :register_token AND `register_token_sent_at` >= :register_token_sent_at AND `status_id` = :status_id';

        $hour = Token_Valid_Period_Hour;
        $tokenValidPeriod = (new DateTime())->modify("-{$hour} hour")->format(DateTime_Default_Format);

        $userOrFalse = $this->userModel->db
            ->prepare($sql)
            ->bind(':register_token', $registerToken)
            ->bind(':register_token_sent_at', $tokenValidPeriod)
            ->bind(':status_id', 'tentative')
            ->executeAndFetch();

            return $userOrFalse;
    }

    /**
     * userを本登録する
     *
     * @param array $request
     * @return void
     */
    public function regsterUser($request)
    {
        $sql = 'UPDATE users SET `register_token_verified_at` = :register_token_verified_at, `password` = :password, `status_id` = :status_id, `status_updated_at` = :status_updated_at WHERE `register_token` = :register_token';

        $currentDateTime = (new DateTime())->format(DateTime_Default_Format);
        $hashedPassword = password_hash($request['password'], PASSWORD_BCRYPT);

        $isRegistered = $this->userModel->db
            ->prepare($sql)
            ->bind(':register_token_verified_at', $currentDateTime)
            ->bind(':password', $hashedPassword)
            ->bind(':status_id', 'public')
            ->bind(':status_updated_at', $currentDateTime)
            ->bind(':register_token', $request['register_token'])
            ->execute();

        return $isRegistered;
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