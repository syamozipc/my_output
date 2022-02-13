<?php
namespace App\Validators\User;

use App\Libraries\Validator;

class RegisterValidator extends Validator{
    public bool $hasError = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function validate($post)
    {
        $this->validateEmailVerifyToken(token:$post['email_verify_token']);
        $this->validatePassword(password:$post['password']);
        $this->validatePasswordConfirmation(password:$post['password_confirmation'], passwordConfirmation:$post['password_confirmation']);

        return !$this->hasError;
    }

    private function validateEmailVerifyToken($token)
    {
        if (!$this->isfilled(key:'email_verify_token', param:$token)) return $this->hasError = true;

        if (!$this->isString(key:'email_verify_token', param:$token)) return $this->hasError = true;
        
        return;
    }

    private function validatePassword($password)
    {
        if (!$this->isfilled(key:'password', param:$password)) return $this->hasError = true;

        // これでisValidLengthも兼ねる
        if (!$this->isValidPasswordFormat(key:'password', password:$password)) return $this->hasError = true;
        
        return;
    }

    private function validatePasswordConfirmation($password, passwordConfirmation)
    {
        if (!$this->isMatch(
            key:'password_confirmation',
            compareKey:'パスワード',
            param1:$password,
            param2:$passwordConfirmation)
        ) return $this->hasError = true;
        
        return;
    }
}