<?php

$files = [
    // アプリ内で使い回す定数のファイル
    '../config/app.php',

    // 各種helper
    '../app/helpers/app.php',
    '../app/helpers/path.php',
    '../app/helpers/url.php',
    '../app/helpers/string.php',
];

foreach ($files as $file) {
    require_once $file;
}

// autoloader
// 未定義のclassが呼ばれた時に引数のcallbackが実行される。callbackの引数にはclass名が入る
spl_autoload_register(function ($className) {
    $className = str_replace('\\', '/', $className);

    require_once base_path("{$className}.php");
});