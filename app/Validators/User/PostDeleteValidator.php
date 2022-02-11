<?php
namespace App\Validators\User;

use App\Libraries\Validator;

class PostDeleteValidator extends Validator{
    public bool $hasError = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function validate($id)
    {
        $this->validatePostId($id);
        // $idが存在するかチェック必要
        // $idとuser_idが同一かどうかのチェックも必要

        return !$this->hasError;
    }

    private function validatePostId($id)
    {
        if (!$this->isNumeric(key:'message', param:$id)) return $this->hasError = true;
        
        return true;
    }
}