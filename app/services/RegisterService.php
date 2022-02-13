<?php
namespace App\Services;

use App\models\User;
use DateTime;

class RegisterService {
    const Token_Valid_Period_Hour = 1;
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
    public function temporarilyRegister(string $email)
    {
        try {
            $this->userModel->db->beginTransaction();

            // 仮登録済みかどうか（本登録済みの場合は、validationで弾かれている）
            // $userが取れれば仮登録済み、いなければ未登録
            $sql = 'SELECT * FROM `users` WHERE `email` = :email AND `password` IS NULL';

            // $userがいればobject、いなければfalseが返る
            $user = $this->userModel->db
                ->prepare(sql:$sql)
                ->bind(':email', $email)
                ->executeAndFetch();
            
            // 仮登録済みかどうかで UPDATE/INSERT 分岐
            $sql = $user
                ? 'UPDATE `users` SET `email_verify_token` = :email_verify_token, `email_verify_token_created_at` = :email_verify_token_created_at WHERE `email` = :email'
                : 'INSERT INTO `users` (`email`, `email_verify_token`, `email_verify_token_created_at`) VALUES (:email, :email_verify_token, :email_verify_token_created_at)';

            /**
             * @todo 送り直しの都度tokenが同じなのは微妙？
             */
            $emailVerifyToken = base64_encode($email);
            $currentDateTime = (new DateTime())->format(DateTime_Default_Format);

            $this->userModel->db
                ->prepare($sql)
                ->bind(':email', $email)
                ->bind(':email_verify_token', $emailVerifyToken)
                ->bind(':email_verify_token_created_at', $currentDateTime)
                ->execute();

            $isSent = $this->sendEmail(to:$email, token:$emailVerifyToken);

            if (!$isSent) throw new \Exception('メール送信に失敗しました。');

            $this->userModel->db->commit();

        } catch (\Exception $e) {
            $this->userModel->db->rollBack();

            exit($e->getMessage());
        }

        return;
    }

    /**
     * 仮登録完了メールを送信
     *
     * @param string $email
     * @param string $token
     * @return boolean 送信成功でtrue、失敗でfalseが返る
     */
    public function sendEmail(string $to, string $token):bool
    {
        // 無くてもいけるかも
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        $url = route('register/verifyEmail', "?token={$token}");
        $hour = self::Token_Valid_Period_Hour;

        $subject = SITENAME . 'への仮登録が完了しました';

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
    public function getTemporarilyRegisteredUser(string $token):object|false
    {
        $sql = 'SELECT * FROM `users` WHERE `email_verify_token` = :email_verify_token AND `email_verify_token_created_at` >= :email_verify_token_created_at AND `email_verified_at` IS NULL AND `password` IS NULL';

        $hour = self::Token_Valid_Period_Hour;
        $tokenValidPeriod = (new DateTime())->modify("-{$hour} hour")->format(DateTime_Default_Format);

        $user = $this->userModel->db
            ->prepare($sql)
            ->bind(':email_verify_token', $token)
            ->bind(':email_verify_token_created_at', $tokenValidPeriod)
            ->executeAndFetch();

        /**
         * @todo エラーメッセージを細かく出し分けたい
         *  email_verify_tokenだけで取得し、
         * ・userがいなければ無効なURL
         * ・passwordがNULLでなければ登録済み
         * ・期限切れであればその旨
         */

        return $user;
    }

    public function regsterUser($request)
    {
        // hash passwordとstr::randomのapi tokenを取得
        $sql = 'UPDATE users SET `email_verified_at` = :email_verified_at, `password` = :password, `api_token` = :api_token WHERE `email_verify_token` = :email_verify_token';

        $currentDateTime = (new DateTime())->format(DateTime_Default_Format);
        $hashPassword = password_hash($request['password'], PASSWORD_BCRYPT);

        $this->userModel->db
            ->prepare($sql)
            ->bind(':email_verified_at', $currentDateTime)
            ->bind(':password', $hashPassword)
            ->bind(':api_token', str_random(length:80))
            ->bind(':email_verify_token', $request['email_verify_token'])
            ->execute();

        return;
    }
}