<?php
namespace App\Services;

use App\models\User;

class UserService {
    public User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }


    /**
     * idからユーザーを取得
     *
     * @param int $id
     * @return object|false $userOrFalse
     */
    public function getUserById(int $id): object|false
    {
        $sql = 'SELECT * FROM users WHERE `id` = :id';

        $userOrFalse = $this->userModel->db
            ->prepare($sql)
            ->bind(':id', $id)
            ->executeAndFetch();

        return $userOrFalse;
    }

    /**
     * emailからユーザーを取得
     *
     * @param string $email
     * @return object|false $userOrFalse
     */
    public function getUserByEmail(string $email): object|false
    {
        $sql = 'SELECT * FROM users WHERE `email` = :email';

        $userOrFalse = $this->userModel->db
            ->prepare($sql)
            ->bind(':email', $email)
            ->executeAndFetch();

        return $userOrFalse;
    }

    /**
     * emailVerifyTokenからユーザーを取得
     * 失敗時はfalseを返す
     *
     * @param int $id
     * @return object|false $user
     */
    public function getUserByEmailVerifyToken(string $emailVerifyToken):object|false
    {
        $sql = 'SELECT * FROM users WHERE `register_token` = :register_token';

        $userOrFalse = $this->userModel->db
            ->prepare($sql)
            ->bind(':register_token', $emailVerifyToken)
            ->executeAndFetch();

        return $userOrFalse;
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
            ->bind(':last_login_at', $currentDateTime)
            ->bind(':id', $userId)
            ->execute();

        return;
    }

    /**
     * パスワードを変更
     *
     * @param integer $userId
     * @return void
     */
    public function updatePassword(string $email, string $password)
    {
        $sql = 'UPDATE users SET `password` = :password WHERE `email` = :email';
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $this->userModel->db
            ->prepare(sql:$sql)
            ->bind(param:':password', value:$hashedPassword)
            ->bind(param:':email', value:$email)
            ->execute();

        return;
    }


}