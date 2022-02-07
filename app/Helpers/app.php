<?php

/**
 * redirect処理
 *
 * @param string $route redirect先のpath
 * @return void
 */
function redirect($route) {
    header('Location: ' . URL_PATH . $route);
}

/**
 * flash sessionを取得
 *
 * @param string|null $key sessionのkey名
 * @return string|int|array|null value
 */
function old(string $key = null): string|int|array|null {
    return !$key 
        ? $_SESSION['old']
        : $_SESSION['old'][$key] ?? null;
}