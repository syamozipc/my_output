<?php
namespace App\Services;

use App\models\User;
use DateTime;

class PasswordResetService {
    public object $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * password_resetsテーブルに情報をinsert（もしくはupdate）
     *
     * @param string $email
     * @return void
     */
    public function savePasswordResetRequest(string $email, string $passwordResetToken)
    {
        $sql = 'SELECT * FROM `password_resets` WHERE `email` = :email';

        // 期限切れ含め、既にパスワードリセットフロー中か（$userが取れればフロー中、取れなければfalseが返る）
        $user = $this->userModel->db
            ->prepare(sql:$sql)
            ->bind(param:':email', value:$email)
            ->executeAndFetch();
        
        // レコードがあるかどうかで UPDATE/INSERT 分岐
        $sql = $user
            ? 'UPDATE `password_resets` SET `token` = :token, `token_sent_at` = :token_sent_at WHERE `email` = :email'
            : 'INSERT INTO `password_resets` (`email`, `token`, `token_sent_at`) VALUES (:email, :token, :token_sent_at)';

        $currentDateTime = (new DateTime())->format(DateTime_Default_Format);

        $this->userModel->db
            ->prepare(sql:$sql)
            ->bind(param:':email', value:$email)
            ->bind(param:':token', value:$passwordResetToken)
            ->bind(param:':token_sent_at', value:$currentDateTime)
            ->execute();

        return;
    }

    /**
     * パスワードリセット用メールを送信
     *
     * @param string $email
     * @param string $token
     * @return boolean 送信成功でtrue、失敗でfalseが返る
     */
    public function sendEmail(string $to, string $passwordResetToken):bool
    {
        // 無くてもいけるかも
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        $url = route('passwordReset/verifyEmail', "?token={$passwordResetToken}");
        $hour = Token_Valid_Period_Hour;

        $subject = '【' . SITENAME . '】' . 'パスワードリセット用URLをお送りします。';

        $body = <<<EOD
            下記URLへ{$hour}時間以内に下記URLへアクセスし、パスワードの変更を完了してください。
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
    public function getValidRequestByToken(string $passwordResetToken):object|false
    {
        $sql = 'SELECT * FROM `password_resets` WHERE `token` = :token AND `token_sent_at` >= :token_sent_at';

        $hour = Token_Valid_Period_Hour;
        $tokenValidPeriod = (new DateTime())->modify("-{$hour} hour")->format(DateTime_Default_Format);

        $user = $this->userModel->db
            ->prepare(sql:$sql)
            ->bind(param:':token', value:$passwordResetToken)
            ->bind(param:':token_sent_at', value:$tokenValidPeriod)
            ->executeAndFetch();

        return $user;
    }

    /**
     * userを本登録する
     *
     * @param array $request
     * @return void
     */
    public function regsterUser($request)
    {
        $sql = 'UPDATE users SET `register_token_verified_at` = :register_token_verified_at, `password` = :password, `api_token` = :api_token WHERE `register_token` = :register_token';

        $currentDateTime = (new DateTime())->format(DateTime_Default_Format);
        $hashPassword = password_hash($request['password'], PASSWORD_BCRYPT);

        $isRegistered = $this->userModel->db
            ->prepare($sql)
            ->bind(':register_token_verified_at', $currentDateTime)
            ->bind(':password', $hashPassword)
            ->bind(':api_token', str_random(length:80))
            ->bind(':register_token', $request['register_token'])
            ->execute();

        return $isRegistered;
    }

    public function sendRegisteredEmail(string $to):bool
    {
            // 無くてもいけるかも
            mb_language("Japanese");
            mb_internal_encoding("UTF-8");
    
            $subject = SITENAME . 'への本登録が完了しました';

            $loginUrl = route('login/showLoginForm');
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