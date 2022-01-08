<?php
// 使い回す定数のfile
require_once 'config/config.php';

// autoloader
// 未定義のclassが呼ばれた時に引数のcallbackが実行される。callbackの引数にはclass名が入る
spl_autoload_register(function ($className) {
    $className = str_replace('\\', '/', $className);

    require_once "/Applications/MAMP/htdocs/my_output/{$className}.php";
});