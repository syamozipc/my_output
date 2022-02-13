<?php
/**
 * path関連helper
 * 主に特定ディレクトリへのpath生成時に使用
 */

 /**
  * application root へのpathを生成する
  *
  * @param string|null $route root配下の任意のパラメータ
  * @return string 生成したpath
  */
function base_path(string $route = null):string
{
    return BASE_PATH . $route;
}

/**
 * public folderへのpathを生成する
 *
 * @param string|null $route public配下の任意のパラメータ
 * @return string 生成したpath
 */
function public_path(string $route = null):string
{
    return BASE_PATH . 'public/' . $route;
}