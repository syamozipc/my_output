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
    public function temporaryRegister($email)
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

            $emailVerifyToken =  base64_encode($email);

            $this->userModel->db->prepare($sql)
                ->bind(':email', $email)
                ->bind(':email_verify_token', $emailVerifyToken)
                ->bind(':email_verify_token_created_at', (new DateTime())->format('Y-m-d H:i:s'))
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
    public function sendEmail($to, $token):bool
    {
        // 無くてもいけるかも
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        $url = route('register/verifyEmail', "?token={$token}");

        $subject = SITENAME . 'への仮登録が完了しました';

        $body = <<<EOD
            会員登録ありがとうございます！

            24時間以内</span>に下記URLへアクセスし、本登録を完了してください。
            {$url}
            EOD;

        $headers = "From : syamozipc@gmail.com\n";
        $headers .= "Content-Type : text/plain";

        return mb_send_mail($to, $subject, $body, $headers);
    }
}