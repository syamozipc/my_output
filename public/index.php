<?php
// file読み込み系の処理
require_once '../bootstrap/app.php';

// session/csrfの処理、URLに対応したコントローラ呼び出し
new app\libraries\Core();