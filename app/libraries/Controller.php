<?php

/**
 * base controller
 * modelとviewをloadする
 */
class Controller {
    /**
     * modelを読み込み、return
     *
     * @param string $model
     * @return object
     */
    public function model($model)
    {
        require_once "../app/models/{$model}.php";

        return new $model();
    }

    /**
     * viewを読み込む
     *
     * @param string $view
     * @param array $data
     * @return void
     */
    public function view($view, $data = [])
    {
        $viewFile = "../public/views/{$view}.php";

        if (!file_exists($viewFile)) die('View does not exist');
        
        require_once $viewFile;
    }

}