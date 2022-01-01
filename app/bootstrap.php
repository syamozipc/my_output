<?php
// 使い回す定数のfile
require_once 'config/config.php';

// autoloader
// 未定義のclassが呼ばれた時に引数のcallbackが実行される。callbackの引数にはclass名が入る
spl_autoload_register(fn($className) => require_once "../app/libraries/{$className}.php");