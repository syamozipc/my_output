<?php
// DB params
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = 'root';
const DB_NAME = 'my_output';

// URL rootを定義
const BASE_URL = 'http://localhost:8888/my_output/';
// public folderまでのpath
const PUBLIC_URL = 'http://localhost:8888/my_output/public/';
// app folderまでのpath
define('APP_PATH', dirname(__FILE__) . '/');

define('BASE_PATH', dirname(dirname(__FILE__)) . '/');

define('UPLOAD_PATH', dirname(dirname(__FILE__)) . '/public/upload/');

// Site Name
const SITENAME = 'My Output';

const CHAR_LENGTH = [
    'post_description' => 1000
];

const Counrty = [
    'min_id' => 1,
    'max_id' => 195
];