<?php
namespace App\Traits;

trait SessionTrait {

    public function setSession(string $key, int|string|array $param)
    {
        $_SESSION[$key] = $param;
    }

    public function getSession(string $key)
    {
        return $_SESSION[$key];
    }

    public function unsetSession(string $key)
    {
        unset($_SESSION[$key]);
    }

    public function setFlashSession(string $key, int|string|array $param)
    {
        $_SESSION['flash'][$key] = $param;
    }
}   