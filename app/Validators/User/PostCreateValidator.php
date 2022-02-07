<?php
namespace App\Validators\User;

use App\Libraries\Validator;

class PostCreateValidator extends Validator{
    public bool $hasError = false;

    public function validate($post)
    {
        $this->validateCountryId(($post['country_id']));
        $this->validateDescription($post['description']);

        if ($this->hasError) {
            $this->setFlashSession('country_id', $post['country_id']);
            $this->setFlashSession('description', $post['description']);
        }

        return !$this->hasError;
    }

    private function validateCountryId($countryId)
    {
        if (!$this->isfilled(param:$countryId)) {
            $this->setFlashSession('error_country_id', '選択必須項目です');
            $this->hasError = true;
            return;
        }

        if (!$this->isNumeric(param:$countryId)) {
            $this->setFlashSession('error_country_id', '無効な入力形式です');
            $this->hasError = true;
            return;
        }

        if (!$this->isValidCountryId(param:$countryId)) {
            $this->setFlashSession('error_country_id', '無効な値です');
            $this->hasError = true;
            return;
        }
    }

    private function validateDescription($description)
    {
        if (!$this->isfilled(param:$description)) {
            $this->setFlashSession('error_description', '入力必須項目です');
            $this->hasError = true;
            return;
        }

        if (!$this->isString(param:$description)) {
            $this->setFlashSession('error_description', '文字列で入力してください');
            $this->hasError = true;
            return;
        }

        $length = CHAR_LENGTH['post_description'];
        if (!$this->isValidLength(param:$description, length:$length, isMb:true)) {
            $this->setFlashSession('error_description', "文字数は{$length}以内に収めてください");
            $this->hasError = true;
            return;
        }
    }
}