<?php
namespace App\Libraries;

// use app\models\{Post, Country};

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
        $viewFile = "../App/Views/{$view}.php";

        if (!file_exists($viewFile)) die('View does not exist');
        
        require_once '../App/Views/user/template.php';
    }

}