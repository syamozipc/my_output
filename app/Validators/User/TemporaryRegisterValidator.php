<?php
namespace App\Validators\User;

use App\Libraries\Validator;

class TemporaryRegisterValidator extends Validator{
    public bool $hasError = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function validate($post)
    {
        $this->validateEmail(email:$post['email']);

        return !$this->hasError;
    }

    private function validateEmail($email)
    {
        if (!$this->isfilled(key:'email', param:$email)) return $this->hasError = true;

        if (!$this->isValidEmailFormat(key:'email', email:$email)) return $this->hasError = true;

        if ($this->isExist(key:'email', email:$email)) return $this->hasError = true;
        
        return;
    }
}