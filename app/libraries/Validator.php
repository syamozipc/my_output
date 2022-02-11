<?php
namespace App\Libraries;

use App\Models\User;

class Validator {
    use \App\Traits\SessionTrait;

    public object $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // 基本的なバリデーション

    /**
     * 値が空かどうか
     *
     * @param mixed $param
     * @return boolean
     */
    public function isfilled($key, $param):bool
    {
        if (!($param === "" || is_null($param))) return true;

        $this->setFlashSession(key:"error_{$key}", param:'入力必須項目です。');

        return false;
    }

    /**
     * 値が数字または数値形式の文字列であるか
     *
     * @param mixed $param
     * @return boolean
     */
    public function isNumeric($key, $param):bool
    {
        if (is_numeric($param)) return true;

        $this->setFlashSession(key:"error_{$key}", param:'数字で入力してください');

        return false;
    }

    /**
     * 値がcountriesテーブルに登録されているidか
     *
     * @param mixed $param
     * @return boolean
     */
    public function isValidRangeCountryId($key, $param):bool
    {
        if (Counrty['min_id'] <= $param && $param <= Counrty['max_id']) return true;

        $this->setFlashSession(key:"error_{$key}", param:'登録されていない国です。');

        return false;
    }

    /**
     * 値が文字列か
     *
     * @param mixed $param
     * @return boolean
     */
    public function isString($key, $param):bool
    {
        if (is_string($param)) return true;

        $this->setFlashSession(key:"error_{$key}", param:'文字列で入力してください');

        return false;
    }

    /**
     * 値の長さが指定文字数以内か
     *
     * @param mixed $param
     * @param integer $length
     * @param boolean $isMb trueならマルチバイトに対応、falseなら非対応
     * @return boolean
     */
    public function isValidLength($key, $param, $length, $isMb = false):bool
    {
        if (
            ($isMb && mb_strlen($param) <= $length)
            || (!$isMb && strlen($param) <= $length)
        ) {
            return true;
        }

        $this->setFlashSession(key:"error_{$key}", param:"文字数は{$length}以内に収めてください。");

        return false;
    }

    // ここから画像アップロード系（現状、複数アップロード未対応）

    /**
     * アップロード処理時にエラーがあるか
     *
     * @param array $file アップロードしたファイルの情報
     * @return integer
     * ref：https://www.php.net/manual/ja/features.file-upload.errors.php
     */
    public function hasUploadError($key, $file):int
    {
        $errorNumber = $file['upload']['error'];

        if ($errorNumber === UPLOAD_ERR_OK) return false;

        $errorMessage = [
            UPLOAD_ERR_INI_SIZE => 'php.iniのupload_max_filesize制限を越えています。',
            UPLOAD_ERR_FORM_SIZE => 'HTMLのMAX_FILE_SIZE 制限を越えています。',
            UPLOAD_ERR_PARTIAL => 'ファイルが一部しかアップロードされていません。',
            UPLOAD_ERR_NO_FILE => 'ファイルを選択してください。',
            UPLOAD_ERR_NO_TMP_DIR => '一時保存フォルダーが存在しません。',
            UPLOAD_ERR_CANT_WRITE => 'ディスクへの書き込みに失敗しました。',
            UPLOAD_ERR_EXTENSION => '拡張モジュールによってアップロードが中断されました。'
        ];

        $this->setFlashSession("error_{$key}", $errorMessage[$errorNumber]);

        return true;
    }

    /**
     * 拡張子が許可されたものかチェック
     * $_FILES['type']は偽装が簡単なため、チェックには利用しない
     * ただしこれも、適当な拡張子で保存した矛盾したファイル（.pngのcsvファイルなど）を検出できない
     * 
     * @param array $file アップロードしたファイルの情報
     * @return boolean
     * ref：https://www.php.net/manual/ja/features.file-upload.errors.php
     */
    public function isValidExt($key, $file):bool
    {
        $isValidExt = in_array(
            strtolower(pathinfo($file['upload']['name'])['extension']),
            ['gif', 'jpg', 'jpeg', 'png'],
            true
        );

        if ($isValidExt) return true;

        $this->setFlashSession("error_{$key}", '有効な拡張子はgif, jpg, jpeg, pngのみです。');

        return false;
    }

    /**
     * ファイルの内容が画像かチェック
     *
     * @param array $file アップロードしたファイルの情報
     * @return boolean
     */
    public function isImgContent($key, $file):bool
    {
        $isImgContent = in_array(
            finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file['upload']['tmp_name']),
            ['image/gif', 'image/jpg', 'image/jpeg', 'image/png'],
            true
        );

        if ($isImgContent) return true;

        $this->setFlashSession("error_{$key}", 'ファイルの内容が画像ではありません。');

        return false;
    }

    // メールアドレスのバリデーション

    /**
     * 正しいメールアドレス形式か
     *
     * @param [type] $email
     * @return string|boolean 有効なメールアドレスならそれを、無効ならfalseを返す
     */
    public function isValidEmailFormat($key, $email):string|bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) return true;

        $this->setFlashSession("error_{$key}", '有効なメールアドレスの形式ではありません。');

        return false;
    }

    /**
     * 既に本登録済みのメールアドレスか
     *
     * @param [type] $email
     * @return boolean 本登録済みなら1（当てはまる桁数）、未登録もしくは仮登録（passwordがNULL）なら、0が返る
     */
    public function isExist($key, $email)
    {
        $sql = 'SELECT * FROM `users` WHERE `email` = :email AND `password` IS NOT NULL';

        $this->userModel->db
            ->prepare(sql:$sql)
            ->bind(param:':email', value:$email)
            ->execute();

        // 本登録済みなら1（当てはまる桁数）、未登録もしくは仮登録（passwordがNULL）なら、0が返る
        $isExist = $this->userModel->db->rowCount();

        if (!$isExist) return false;

        $this->setFlashSession("error_{$key}", '登録済みのメールアドレスです。');

        return true;
    }
}