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
            ->executeAndFetch(get_class($this->userModel));

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
        $sql = 'SELECT count(*) as count FROM users WHERE `email` = :email AND `status_id` = :status_id';

        $stdClass = $this->userModel->db
            ->prepare($sql)
            ->bindValue(':email', $email)
            ->bindValue(':status_id', 'public')
            ->executeAndFetch();

        $isExist = $stdClass->count > 0 ? true : false;

        return $isExist;
    }
}