<?php
/**
 * string関連helper
 */

 /**
  * LaravelのStr::randomのコピー
  *
  * @param integer $length
  * @return string
  */
function str_random($length = 16):string
{
    $string = '';

    while (($len = strlen($string)) < $length) {
        $size = $length - $len;

        $bytes = random_bytes($size);

        $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
    }

    return $string;
}