<?php 
// DB params
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = 'root';
const DB_NAME = 'my_output';

// URL rootを定義
const URL_PATH = 'http://localhost:8888/my_output/';
// app folderまでのpath
define('APP_PATH', dirname(dirname(__FILE__)) . '/');
// public folderまでのpath
define('PUBLIC_PATH', dirname(dirname(dirname(__FILE__))) . '/public/');


// Site Name
const SITENAME = 'My Output';