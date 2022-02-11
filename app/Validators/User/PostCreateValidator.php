<?php
namespace App\Validators\User;

use App\Libraries\Validator;

class PostCreateValidator extends Validator{
    public bool $hasError = false;

    public function validate($post, $files)
    {
        $this->validateCountryId(($post['country_id']));
        $this->validateDescription($post['description']);
        $this->validateFiles($files);

        if ($this->hasError) {
            $this->setFlashSession('country_id', $post['country_id']);
            $this->setFlashSession('description', $post['description']);
        }

        return !$this->hasError;
    }

    private function validateCountryId($countryId)
    {
        if (!$this->isfilled(param:$countryId)) {
            $this->setFlashSession('error_country_id', '選択必須項目です。');
            $this->hasError = true;
            return;
        }

        if (!$this->isNumeric(param:$countryId)) {
            $this->setFlashSession('error_country_id', '無効な入力形式です。');
            $this->hasError = true;
            return;
        }

        if (!$this->isValidRangeCountryId(param:$countryId)) {
            $this->setFlashSession('error_country_id', '無効な値です。');
            $this->hasError = true;
            return;
        }

        return;
    }

    private function validateDescription($description)
    {
        if (!$this->isfilled(param:$description)) {
            $this->setFlashSession('error_description', '入力必須項目です。');
            $this->hasError = true;
            return;
        }

        if (!$this->isString(param:$description)) {
            $this->setFlashSession('error_description', '文字列で入力してください。');
            $this->hasError = true;
            return;
        }

        $length = CHAR_LENGTH['post_description'];
        if (!$this->isValidLength(param:$description, length:$length, isMb:true)) {
            $this->setFlashSession('error_description', "文字数は{$length}以内に収めてください。");
            $this->hasError = true;
            return;
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
        if (($errorNumber = $this->getUploadErrorNumber($file)) !== UPLOAD_ERR_OK) {
            $msg = [
                UPLOAD_ERR_INI_SIZE => 'php.iniのupload_max_filesize制限を越えています。',
                UPLOAD_ERR_FORM_SIZE => 'HTMLのMAX_FILE_SIZE 制限を越えています。',
                UPLOAD_ERR_PARTIAL => 'ファイルが一部しかアップロードされていません。',
                UPLOAD_ERR_NO_FILE => 'ファイルを選択してください。',
                UPLOAD_ERR_NO_TMP_DIR => '一時保存フォルダーが存在しません。',
                UPLOAD_ERR_CANT_WRITE => 'ディスクへの書き込みに失敗しました。',
                UPLOAD_ERR_EXTENSION => '拡張モジュールによってアップロードが中断されました。'
            ];

            $this->setFlashSession('error_upload', $msg[$errorNumber]);
            $this->hasError = true;

            return;
        }    
        
        // 拡張子が許可されたものかチェック
        if (!$this->isValidExt($file)) {
            $this->setFlashSession('error_upload', '画像以外のファイルはアップロードできません。');
            $this->hasError = true;

            return;
        } 
        
        // ファイルの内容が画像かチェック
        if (!$this->isImgContent($file)) {
            $this->setFlashSession('error_upload', 'ファイルの内容が画像ではありません。');
            $this->hasError = true;

            return;
        }

        return;
            // if (!move_uploaded_file($src, 'doc/'.$dest)) {
            //     $err_msg = 'アップロード処理に失敗しました。';
            // }
    }
}