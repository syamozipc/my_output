<?php
/**
 * URL helper
 * 主にページ遷移・URL生成に使用
 */

/**
 * redirect処理
 *
 * @param string $route redirect先のpath
 * @return void
 */
function redirect($route, $replace = true, $status = 302)
{
    $url = BASE_URL . $route;

    return header("Location: {$url}", $replace, $status);
}

// function url($route) {
//     return BASE_URL . $route;
// }

/**
 * ページ遷移用url生成処理
 *
 * @param string $route 遷移先パス
 * @param string|array|null $params 任意のパラメータ
 * @return string ページ遷移用url
 */
function route(string $route, string|array $params = null):string
{
    if (is_null($params)) return BASE_URL . $route;

    $paramStr = is_array($params) 
        ? implode('/', $params)
        : $params;
    
    return BASE_URL . $route . '/' . $paramStr;
}

/**
 * public配下コンテンツ取得用URL生成処理
 * ・JS/CSS/画像
 *
 * @param string $route 遷移先パス
 * @return string public配下URL
 */
function public_url(string $route):string
{
    
    return BASE_URL . 'public/' . $route;
}