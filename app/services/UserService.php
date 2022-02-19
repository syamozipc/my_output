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
     * @return object $user
     */
    public function getUserById(int $id):object
    {
        $sql = 'SELECT * FROM users WHERE `id` = :id';

        $user = $this->userModel->db
            ->prepare($sql)
            ->bind(':id', $id)
            ->executeAndFetch();

        return $user;
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
        $sql = 'SELECT * FROM users WHERE `email_verify_token` = :email_verify_token';

        $user = $this->userModel->db
            ->prepare($sql)
            ->bind(':email_verify_token', $emailVerifyToken)
            ->executeAndFetch();

        return $user;
    }
}