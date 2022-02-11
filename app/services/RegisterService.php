<?php
namespace App\Services;

use App\models\User;

class RegisterService {
    public object $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function temporaryRegister()
    {
        
    }
}