<?php
namespace App\Services;

use App\models\{User, PasswordReset};

class PasswordResetService {
    public User $userModel;
    public PasswordReset $passwordResetModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->passwordResetModel = new PasswordReset();
    }

    /**
     * password_resetsテーブルにリクエストをinsert（もしくはupdate）
     *
     * @param string $email
     * @return void
     */
    public function saveRequest(string $email, string $passwordResetToken)
    {
        // 期限切れ含め、既にパスワードリセットフロー中か（$userが取れればフロー中、取れなければfalseが返る）
        $sql = 'SELECT * FROM `password_resets` WHERE `email` = :email';
        $passwordResetObj = $this->passwordResetModel->db
            ->prepare(sql:$sql)
            ->bindValue(param:':email', value:$email)
            ->executeAndFetch(get_class($this->passwordResetModel));

        $params = [
            'email' => $email,
            'token' => $passwordResetToken,
            'currentDateTime' => (new \DateTime())->format(DateTime_Default_Format),
        ];
        
        // レコードがあるかどうかで内部で UPDATE/INSERT を分岐している
        if (!$passwordResetObj) $passwordResetObj = new PasswordReset();
        $passwordResetObj->fill($params)->save();

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
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        $url = route('/passwordReset/verifyToken', "?token={$passwordResetToken}");
        $hour = Email_Token_Valid_Period_Hour;

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
     * @return PasswordReset|false
     */
    public function getValidRequestByToken(string $passwordResetToken):PasswordReset|false
    {
        $sql = 'SELECT * FROM `password_resets` WHERE `token` = :token AND `token_sent_at` >= :token_sent_at';

        $hour = Email_Token_Valid_Period_Hour;
        $tokenValidPeriod = (new \DateTime())->modify("-{$hour} hour")->format(DateTime_Default_Format);

        $passwordReset = $this->passwordResetModel->db
            ->prepare(sql:$sql)
            ->bindValue(param:':token', value:$passwordResetToken)
            ->bindValue(param:':token_sent_at', value:$tokenValidPeriod)
            ->executeAndFetch(get_class($this->passwordResetModel));

        return $passwordReset;
    }
}