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
        $sql = 'SELECT * FROM users WHERE `id` = :id AND `status_id` = :status_id';

        $userOrFalse = $this->userModel->db
            ->prepare($sql)
            ->bind(':id', $id)
            ->bind(':status_id', 'public')
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
        $sql = 'SELECT * FROM users WHERE `email` = :email AND `status_id` = :status_id';

        $userOrFalse = $this->userModel->db
            ->prepare($sql)
            ->bind(':email', $email)
            ->bind(':status_id', 'public')
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
        $sql = 'SELECT * FROM users WHERE `register_token` = :register_token AND `status_id` = :status_id';

        $userOrFalse = $this->userModel->db
            ->prepare($sql)
            ->bind(':register_token', $emailVerifyToken)
            ->bind(':status_id', 'public')
            ->executeAndFetch();

        return $userOrFalse;
    }

    /**
     * emailからpublicステータスのユーザーを取得
     * 
     * @todo 非公開/削除済みには未対応
     *
     * @param string $email
     * @return boolean $userOrFalse
     */
    public function isPublicUser(string $email): bool
    {
        $sql = 'SELECT * FROM users WHERE `email` = :email AND `status_id` = :status_id';

        $this->userModel->db
            ->prepare($sql)
            ->bind(':email', $email)
            ->bind(':status_id', 'public')
            ->execute();

        // 本登録済みなら1（当てはまる桁数）、そうでなければ0が返る
        $isExist = $this->userModel->db->rowCount();

        return $isExist;
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