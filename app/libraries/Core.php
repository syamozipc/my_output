<?php
namespace App\Libraries;

/**
 * App Core Class
 * Creates URL & loads core controller
 * URL FORMAT - /controller/method/params
 */
class Core {
    protected $currentController = 'HomeController';
    protected $currentMethod = 'index';
    protected $params = [];

    /**
     * URLを取得し、対応するclassのmethodを呼び出す基幹処理
     */
    public function __construct()
    {
        $url = isset($_GET['url'])
            ? $this->formatAndSanitizeUrl($_GET['url'])
            : NULL;

            // fileがあれば、それをcontrollerとしてセット
        if (
            isset($url[0])
            && file_exists('../App/Controllers/User/' . ucwords($url[0]) . 'Controller.php')
        ) {
            $this->currentController = ucwords($url[0]) . 'Controller';
            unset($url[0]);
        }

        // 該当のcontroller classを読み込む
        require_once '../App/Controllers/User/' . $this->currentController . '.php';

        $this->currentController = new ('App\\Controllers\\User\\' . $this->currentController);

        // methodが存在すれば、それに更新
        if (
            isset($url[1])
            && method_exists($this->currentController, $url[1])
        ) {
            $this->currentMethod = $url[1];
            unset($url[1]);
        }

        // 残った配列をparameterとしてセット
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    /**
     * 1.URL末尾の/を削除
     * 2.値をサニタイズ（例えば日本語など、無効な文字を取り除く）
     * 3.配列に分割（[0] => controller名, [1] => method名, [2] => parameter）
     *
     * @return array
     */
    public function formatAndSanitizeUrl($url)
    {
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);

        return $url;
    }
}