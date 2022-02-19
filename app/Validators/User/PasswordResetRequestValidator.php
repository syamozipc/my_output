<?php
namespace App\Validators\User;

use App\Libraries\Validator;

class passwordResetRequestValidator extends Validator{
    public bool $hasError = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function validate($request)
    {
        $this->validateEmail(email:$request['email']);

        if ($this->hasError) {
            $this->setFlashSession(key:'email', param:$request['email']);
        }

        return !$this->hasError;
    }

    private function validateEmail($email)
    {
        if (!$this->isfilled(key:'email', param:$email)) return $this->hasError = true;

        if (!$this->isValidEmailFormat(key:'email', email:$email)) return $this->hasError = true;

        return;
    }
}