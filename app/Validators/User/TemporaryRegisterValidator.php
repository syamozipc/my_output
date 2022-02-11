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
        if (!$this->isfilled(param:$email)) {
            $this->setFlashSession(key:'error_email', param:'入力必須項目です。');
            $this->hasError = true;

            return;
        }
        if (!$this->isValidEmailFormat(email:$email)) {
            $this->setFlashSession(key:'error_email', param:'正しい形式で入力してください。');
            $this->hasError = true;

            return;
        }

        if ($this->isExist(email:$email)) {
            $this->setFlashSession(key:'error_email', param:'既に登録済みです。');
            $this->hasError = true;
            
            return;
        }
        
        return;
    }
}