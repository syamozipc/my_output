<?php
namespace App\Libraries;

class Validator {
    use \App\Traits\SessionTrait;

    /**
     * 値が空かどうか
     *
     * @param mixed $param
     * @return boolean
     */
    public function isfilled($param)
    {
        return ($param !== "" && !is_null($param));
    }

    /**
     * 値が数字または数値形式の文字列であるか
     *
     * @param mixed $param
     * @return boolean
     */
    public function isNumeric($param)
    {
        return is_numeric($param);
    }

    /**
     * 値がcountriesテーブルに登録されているidか
     *
     * @param mixed $param
     * @return boolean
     */
    public function isValidRangeCountryId($param)
    {
        return (Counrty['min_id'] <= $param && $param <= Counrty['max_id']);
    }

    /**
     * 値が文字列か
     *
     * @param mixed $param
     * @return boolean
     */
    public function isString($param)
    {
        return is_string($param);
    }

    /**
     * 値の長さが指定文字数以内か
     *
     * @param mixed $param
     * @param integer $length
     * @param boolean $isMb trueならマルチバイトに対応、falseなら非対応
     * @return boolean
     */
    public function isValidLength($param, $length, $isMb = false)
    {
        if ($isMb) {
            return mb_strlen($param) <= $length;
        } else {
            return strlen($param) <= $length;
        }
    }

    // ここから画像アップロード系（現状、複数アップロード未対応）

    /**
     * アップロード処理時にエラーがあるか
     *
     * @param array $file アップロードしたファイルの情報
     * @return integer
     * ref：https://www.php.net/manual/ja/features.file-upload.errors.php
     */
    public function getUploadErrorNumber($file)
    {
        return $file['upload']['error'];
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
    public function isValidExt($file)
    {
        return in_array(
                strtolower(pathinfo($file['upload']['name'])['extension']),
                ['gif', 'jpg', 'jpeg', 'png']
        );
    }

    /**
     * ファイルの内容が画像かチェック
     *
     * @param array $file アップロードしたファイルの情報
     * @return boolean
     */
    public function isImgContent($file)
    {
        return in_array(
                finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file['upload']['tmp_name']),
                ['image/gif', 'image/jpg', 'image/jpeg', 'image/png']
        );
    }
}