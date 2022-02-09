<?php
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