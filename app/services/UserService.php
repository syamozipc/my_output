<?php
namespace App\Services;

use App\models\User;
use DateTime;

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
}