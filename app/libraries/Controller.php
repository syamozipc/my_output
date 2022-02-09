<?php
namespace App\Libraries;

/**
 * base controller
 * modelとviewをloadする
 */
class Controller {
    /**
     * viewを読み込む
     *
     * @param string $view
     * @param array $data
     * @return void
     */
    public function view($view, $data = [])
    {
        $viewFile = BASE_PATH . "resources/views/{$view}.php";

        if (!file_exists($viewFile)) die('View does not exist');
        
        require_once BASE_PATH . 'resources/views/user/template.php';
    }

}