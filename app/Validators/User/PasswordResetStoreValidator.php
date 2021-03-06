<?php
namespace App\Validators\User;

use App\Libraries\Validator;

class PasswordResetStoreValidator extends Validator{
    public bool $hasError = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function validate($request)
    {
        // ここのエラーは現状、呼び出し元でverifyToken()へリダイレクト時に別のエラーに引っ掛かり、エラーメッセージも上書きされる
        $this->validatePasswordResetToken(token:$request['password_reset_token']);
        $this->validatePassword(password:$request['password']);
        $this->validatePasswordConfirmation(password:$request['password'], passwordConfirmation:$request['password_confirmation']);

        return !$this->hasError;
    }

    private function validatePasswordResetToken($token)
    {
        if (!$this->isfilled(key:'register_token', param:$token)) return $this->hasError = true;

        if (!$this->isString(key:'register_token', param:$token)) return $this->hasError = true;
        
        return;
    }

    private function validatePassword($password)
    {
        if (!$this->isfilled(key:'password', param:$password)) return $this->hasError = true;

        // これでisValidLengthも兼ねる
        if (!$this->isValidPasswordFormat(key:'password', password:$password)) return $this->hasError = true;
        
        return;
    }

    private function validatePasswordConfirmation($password, $passwordConfirmation)
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