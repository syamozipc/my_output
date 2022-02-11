<?php
namespace App\Validators\User;

use App\Libraries\Validator;

class PostCreateValidator extends Validator{
    public bool $hasError = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function validate($post, $files)
    {
        $this->validateCountryId(countryId:$post['country_id']);
        $this->validateDescription(description:$post['description']);
        $this->validateFiles(file:$files);

        if ($this->hasError) {
            $this->setFlashSession(key:'country_id', param:$post['country_id']);
            $this->setFlashSession(key:'description', param:$post['description']);
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
        if (!$this->isValidLength(key:'description', param:$description, length:$length, isMb:true)) {
            return $this->hasError = true;
        } 

        return;
    }

    /**
     * 現状、複数アップロードは無し
     * ref：独習PHP p396
     *
     * @param [type] $files
     * @return void
     */
    private function validateFiles($file)
    {
        // アップロード処理のエラーチェック
        if ($this->hasUploadError(key:'upload', file:$file)) return $this->hasError = true;

        // 拡張子が許可されたものかチェック
        if (!$this->isValidExt(key:'upload', file:$file)) return $this->hasError = true;
        
        // ファイルの内容が画像かチェック
        if (!$this->isImgContent(key:'upload', file:$file)) return $this->hasError = true;

        return;
            // if (!move_uploaded_file($src, 'doc/'.$dest)) {
            //     $err_msg = 'アップロード処理に失敗しました。';
            // }
    }
}