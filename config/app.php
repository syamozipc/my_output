<?php
// DB params
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = 'root';
const DB_NAME = 'my_output';

// URL root
const BASE_URL = 'http://localhost:8888/my_output/';

// application root
define('BASE_PATH', dirname(dirname(__FILE__)) . '/');

// Site Name
const SITENAME = 'My Output';

// 入力文字列の上限
const CHAR_LENGTH = [
    // 新規投稿時のdescriptionフィールド
    'post_description' => 1000
];

// countriesテーブルのidの最初と最後（主にvalidatorで使う）
const Counrty = [
    'min_id' => 1,
    'max_id' => 195
];

const DateTime_Default_Format = 'Y-m-d H:i:s';

// 新規会員登録やパスワードリセットのメール送信時に使用
const Email_Token_Valid_Period_Hour = 24;

// set_cookie()のdefault値の指定
define('Cookie_Default_Options', [
    'expires' => time() + 60 * 60 * 24 * 365,// 1年間
    'path' => '/',// defaultはカレントディレクトリ
    'httponly' => true// defaultはfalse
]);