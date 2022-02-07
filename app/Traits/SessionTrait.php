<?php
namespace App\Traits;

trait SessionTrait {

    public function setSession(string $key, int|string|array $param)
    {
        $_SESSION[$key] = $param;
    }

    public function setFlashSession(string $key, int|string|array $param)
    {
        $_SESSION['flash'][$key] = $param;
    }
}   