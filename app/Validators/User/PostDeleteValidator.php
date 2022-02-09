<?php
namespace App\Validators\User;

use App\Libraries\Validator;

class PostDeleteValidator extends Validator{
    public bool $hasError = false;

    public function validate($id)
    {
        $this->validatePostId($id);

        return !$this->hasError;
    }

    private function validatePostId($id)
    {
        if (!$this->isNumeric(param:$id)) {
            $this->setFlashSession('error_message', '不正な操作です。');
            $this->hasError = true;
        }
        
        return;
    }
}