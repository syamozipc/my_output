<?php
/**
 * 汎用helper
 */

/**
 * flash sessionを取得
 *
 * @param string|null $key sessionのkey名
 * @return string|int|array|null value
 */
function old(string $key = null): string|int|array|null
{
    return !$key 
        ? $_SESSION['_old']
        : $_SESSION['_old'][$key] ?? null;
}

/**
 * flash error sessionを取得
 *
 * @param string|null $key sessionのkey名
 * @return string|int|array|null value
 */
function error(string $key = null): string|int|array|null
{
    return !$key 
        ? $_SESSION['_old_error']
        : $_SESSION['_old_error'][$key] ?? null;
}

/**
 * 文字列のエスケープ
 *
 * @param string|null $string 
 * @return string|null
 */
function e(string $string = null): ?string
{
    if (is_null($string)) return null;

    return htmlspecialchars(string:$string);
}

/**
 * 文字列のエスケープ + 改行コードをbrタグで出力
 *
 * @param string|null $string 
 * @return string|null
 */
function bre(string $string = null): ?string
{
    if (is_null($string)) return null;

    return nl2br(htmlspecialchars(string:$string));
}

/*
 * userがログイン済みかどうか
 *
 * @return boolean
 */
function isLogedIn(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * csrf tokenを取得
 *
 * @return string
 */
function csrf(): string
{
    $csrfToken = $_SESSION['_csrf_token'];
    return "<input type='hidden' name='_csrf_token' value='{$csrfToken}'>";
}

/**
 * debug & dump
 *
 * @param mixed
 * @return void
 */
function dd($dump): void
{
    echo '<pre>';
    var_dump($dump);
    die;
}
?>
