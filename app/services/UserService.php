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
     * @return User|false $userOrFalse
     */
    public function getUserById(int $id): User|false
    {
        $sql = 'SELECT * FROM users WHERE `id` = :id AND `status_id` = :status_id';

        $userOrFalse = $this->userModel->db
            ->prepare($sql)
            ->bindValue(':id', $id)
            ->bindValue(':status_id', 'public')
            ->executeAndFetch(get_class($this->userModel));

        return $userOrFalse;
    }

    /**
     * emailからユーザーを取得
     *
     * @param string $email
     * @return user|false $userOrFalse
     */
    public function getUserByEmail(string $email): user|false
    {
        $sql = 'SELECT * FROM users WHERE `email` = :email AND `status_id` = :status_id';

        $userOrFalse = $this->userModel->db
            ->prepare($sql)
            ->bindValue(':email', $email)
            ->bindValue(':status_id', 'public')
            ->executeAndFetch();

        return $userOrFalse;
    }

    /**
     * registerTokenからユーザーを取得
     * 失敗時はfalseを返す
     * 
     * @param string $registerToken
     * @return User|false $user
     */
    public function getUserByRegisterToken(string $registerToken): User|false
    {
        $sql = 'SELECT * FROM users WHERE `register_token` = :register_token AND `status_id` = :status_id';

        $userOrFalse = $this->userModel->db
            ->prepare($sql)
            ->bindValue(':register_token', $registerToken)
            ->bindValue(':status_id', 'public')
            ->executeAndFetch(get_class($this->userModel));

        return $userOrFalse;
    }

    /**
     * rememberTokenからユーザーを取得
     * 失敗時はfalseを返す
     * 
     * @param string $rememberToken
     * @return User|false $user
     */
    public function getUserByRememberToken(string $rememberToken): User|false
    {
        $sql = 'SELECT * FROM users WHERE `remember_token` = :remember_token AND `status_id` = :status_id';

        $userOrFalse = $this->userModel->db
            ->prepare($sql)
            ->bindValue(':remember_token', md5($rememberToken))
            ->bindValue(':status_id', 'public')
            ->executeAndFetch(get_class($this));

        return $userOrFalse;
    }

    /**
     * emailからpublicステータスのユーザーを取得
     * 
     * @param string $email
     * @return boolean $userOrFalse
     */
    public function isPublicUser(string $email): bool
    {
        $sql = 'SELECT * FROM users WHERE `email` = :email AND `status_id` = :status_id';

        $this->userModel->db
            ->prepare($sql)
            ->bindValue(':email', $email)
            ->bindValue(':status_id', 'public')
            ->execute();

        // 本登録済みなら1（当てはまる桁数）、そうでなければ0が返る
        $isExist = $this->userModel->db->rowCount();

        return $isExist;
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
            ->bindValue(param:':password', value:$hashedPassword)
            ->bindValue(param:':email', value:$email)
            ->execute();

        return;
    }
}