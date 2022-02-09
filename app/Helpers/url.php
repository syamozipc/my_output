<?php
/**
 * redirect処理
 *
 * @param string $route redirect先のpath
 * @return void
 */
function redirect($route) {
    header('Location: ' . BASE_URL . $route);
}

// function url($route) {
//     return BASE_URL . $route;
// }

function route(string $route, $params = 'a') {
    $fp = fopen("sample.txt", "a");fwrite($fp, print_r(__FILE__.PHP_EOL, true));fclose($fp);
    if ($params = []) return BASE_URL . $route;

    $paramStr = is_array($params) ? implode('/', $params) : $params;
    return BASE_URL . $route . $paramStr;
}