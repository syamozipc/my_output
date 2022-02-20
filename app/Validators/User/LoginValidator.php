<?php
namespace App\Validators\User;

use App\Libraries\Validator;

class LoginValidator extends Validator{
    public bool $hasError = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function validate($request)
    {
        // ここのエラーは現状、呼び出し元でverifyToken()へリダイレクト時に別のエラーに引っ掛かり、エラーメッセージも上書きされる
        $this->validateEmail(email:$request['email']);
        $this->validatePassword(password:$request['password']);

        return !$this->hasError;
    }

    private function validateEmail($email)
    {
        if (!$this->isfilled(key:'email', param:$email)) return $this->hasError = true;

        if (!$this->isValidEmailFormat(key:'email', email:$email)) return $this->hasError = true;
        
        return;
    }

    private function validatePassword($password)
    {
        if (!$this->isfilled(key:'password', param:$password)) return $this->hasError = true;

        // これでisValidLengthも兼ねる
        if (!$this->isValidPasswordFormat(key:'password', password:$password)) return $this->hasError = true;
        
        return;
    }
}