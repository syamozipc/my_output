<?php
namespace App\Traits;

trait SessionTrait {

    public function setSession(string $key, int|string|array $param)
    {
        $_SESSION[$key] = $param;
    }

    public function getSession(string $key)
    {
        return $_SESSION[$key] ?? NULL;
    }

    public function unsetSession(string $key)
    {
        unset($_SESSION[$key]);

        return;
    }

    public function unsetAllSession()
    {
        return $_SESSION = [];
    }

    public function setFlashSession(string $key, int|string|array $param)
    {
        return $_SESSION['_flash'][$key] = $param;
    }

    public function moveFlashSessionToOld()
    {
        $_SESSION['_old'] = $_SESSION['_flash'];

        return $this->unsetSession('_flash');
    }
}   