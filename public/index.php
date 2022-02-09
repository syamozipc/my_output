<?php
// file読み込み系の処理
require_once '../bootstrap/app.php';
// sessionの処理、URLに対応したコントローラ呼び出し
use app\libraries\Core;

$init = new Core();