<?php
// 使い回す定数のfile
require_once '../config/app.php';
require_once '../app/helpers/app.php';
require_once '../app/helpers/path.php';
require_once '../app/helpers/url.php';

// autoloader
// 未定義のclassが呼ばれた時に引数のcallbackが実行される。callbackの引数にはclass名が入る
spl_autoload_register(function ($className) {
    $className = str_replace('\\', '/', $className);

    require_once base_path("{$className}.php");
});