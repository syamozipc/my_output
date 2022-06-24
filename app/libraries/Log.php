<?php
namespace App\Libraries;

class Log {

    /**
     * エラーログ記録用メソッド
     *
     * @param string $message
     * @return void
     */
    public static function info(string $message):void
    {
        $data[] = date('Y/m/d H:i:s');
        $data[] = $_SERVER['SCRIPT_NAME'];
        $data[] = $_SERVER['HTTP_USER_AGENT'];
        $data[] = $_SERVER['HTTP_REFERER'];
        $data[] = $message;

        $path = base_path('logs/' . date('Y') . '/' . date('m') . '/' . date('d') . '.log');
        $file = @fopen($path, 'a') or die('ファイルを開けませんでした！');

        flock($file, LOCK_EX);
        fwrite($file, implode("\t", $data) . "\n");
        flock($file, LOCK_UN);
        fclose($file);

        return;
    }
}