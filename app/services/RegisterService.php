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

    public function temporaryRegister($email)
    {
        try {
            $this->userModel->db->beginTransaction();

            // 仮登録済みかどうか（本登録済みの場合は、validationで弾かれている）
            // $userが取れれば仮登録済み、いなければ未登録
            $sql = 'SELECT * FROM `users` WHERE `email` = :email AND `password` IS NULL';

            $user = $this->userModel->db
                ->prepare(sql:$sql)
                ->bind(param:':email', value:$email)
                ->executeAndFetch();
            
            $sql = $user
                ? 'UPDATE `users` SET `email_verify_token` = :email_verify_token, `email_verify_token_created_at` = :email_verify_token_created_at WHERE `email` = :email'
                : 'INSERT INTO `users` (`email`, `email_verify_token`, `email_verify_token_created_at`) VALUES (:email, :email_verify_token, :email_verify_token_created_at)';

            $this->userModel->db->prepare($sql)
                ->bind(':email', $email)
                ->bind(':email_verify_token', base64_encode($email))
                ->bind(':email_verify_token_created_at', (new DateTime())->format('Y-m-d H:i:s'))
                ->execute();

            $this->userModel->db->commit();

        } catch (\Exception $e) {
            $this->userModel->db->rollBack();

            exit($e->getMessage());
        }
    }
}