<?php
namespace App\Validators\User;

use App\Libraries\Validator;

class PostEditValidator extends Validator{
    public bool $hasError = false;

    public function __construct()
    {
        parent::__construct();
    }

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
        if (!$this->isfilled(key:'country_id', param:$countryId)) return $this->hasError = true;

        if (!$this->isNumeric(key:'country_id', param:$countryId)) return $this->hasError = true;

        if (!$this->isValidRangeCountryId(key:'country_id', param:$countryId)) return $this->hasError = true;

        return;
    }

    private function validateDescription($description)
    {
        if (!$this->isfilled(key:'description', param:$description)) return $this->hasError = true;

        if (!$this->isString(key:'description', param:$description)) return $this->hasError = true;

        $length = CHAR_LENGTH['post_description'];
        if (!$this->isValidLength(key:'description', param:$description, minLength:0, maxLength:$length, isMb:true)) {
            return $this->hasError = true;
        }
        
        return;
    }
}