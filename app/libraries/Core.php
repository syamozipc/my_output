<?php
namespace App\Libraries;

/**
 * App Core Class
 * Creates URL & loads core controller
 * URL FORMAT - /controller/method/params
 */
class Core {
    use \App\Traits\SessionTrait;

    protected $currentController = 'HomeController';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        $this->initSession();
        $this->checkCSRF();
        $this->initCSRF();
        $this->callFunction();
    }

    /**
     * session宣言、flash sessionのoldへの移行
     *
     * @return void
     */
    public function initSession()
    {
        session_start();
        //セッションIDを変更（セッションハイジャック対策）
        session_regenerate_id(true);

        // フラッシュセッションがあれば_oldへ移動し、フラッシュセッションは削除
        if ($this->getSession('_flash')) {
            $this->moveFlashSessionToOld();
        }
    }

    /**
     * postリクエスト時、
     * ・csrf tokenが設定されているか
     * ・sessionのtokenの値と同一か
     * をチェックし、不正ならひとつ前のURLへ戻す
     *
     * @return void
     */
    public function checkCSRF()
    {   
        // post以外はcheck不要
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        $csrfToken = filter_input(INPUT_POST, 'csrf_token');

        // csrf tokenが正しければOK
        if (isset($csrfToken) && $csrfToken === $_SESSION['csrf_token']) return;

        $this->setFlashSession(key:"error_status", param:'正規の画面からご利用ください。');

        // 直前のリクエストから必要部分を取得
        preg_match('|my_output/(.*)$|', $_SERVER['HTTP_REFERER'], $matches);

        $prevUrl = $matches[1] ?? 'home/index';

        return redirect($prevUrl);    
    }
    
    /**
     * csrfがsessionに保存されていなければ、新たに作成
     *
     * @return void
     */
    public function initCSRF()
    {   
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

     /**
     * URLを取得し、対応するclassのmethodを呼び出す基幹処理
     */
    public function callFunction()
    {
        $url = filter_input(INPUT_GET, 'url');

        if ($url) $url = $this->formatAndSanitizeUrl($url);

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