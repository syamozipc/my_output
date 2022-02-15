<?php
namespace App\Traits;

use JetBrains\PhpStorm\Internal\ReturnTypeContract;

trait LoginTrait {

    public function baseLogin(string $email, string $password, object $model)
    {
        $sql = 'SELECT * FROM `users` WHERE `email` = :email';

        $user = $model->db
            ->prepare($sql)
            ->bind(':email', $email)
            ->executeAndFetch();

        if (!$user) return false;

        $hashedPassword = $user->password;

        if (!password_verify($password, $hashedPassword)) return false;

        // @todo 有効期限設定やsession cookieを使う（ブラウザを閉じても破棄されないように）
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;

        return true;
    }
}   