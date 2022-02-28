<?php
namespace App\Libraries;

/**
 * App Core Class
 * Creates URL & loads core controller
 * URL FORMAT - /controller/method/params
 */
class Core {
    use \App\Traits\SessionTrait;

    protected $currentController = 'Error';
    protected $currentMethod = 'response404';
    protected $params = [];
    protected $isApi = false;

    public function __construct()
    {
        // セッションの開始とセッションIDの変更（セッションハイジャック対策）
        session_start();
        session_regenerate_id(true);

        // POSTリクエストであっても、リクエストURLはGETで取れる
        $url = filter_input(INPUT_GET, 'url');
        if ($url) $url = $this->formatAndSanitizeUrl($url);

        // URLによって呼び出すコントローラを特定
        $this->brunchCallback($url);

        if (!$this->isApi) {
            $this->initFlashSession();
            $this->checkCSRF();
            $this->initCSRF();
        }

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

     /**
     * URLを取得し、対応するclassのmethodを呼び出す基幹処理
     * 
     * apiとかuserのnamespaceに対応する
     * $urlが空なら、homeページへ
     * 存在しないurlなら、404を表示
     */
    public function brunchCallback($url)
    {
        
        // $urlがなければTOPページをリターン
        if (!$url) {
            $this->currentController = new ("App\\Controllers\\User\\homeController");
            $this->currentMethod = 'index';

            return;
        }

        // amespaceがあるかをチェック
        if (in_array($url[0], ['api'], true)) {
            // namespaceだけなら 404 error
            if (count($url) === 1) return $this->setErrorController();

            if ($url[0] === 'api') $this->isApi = true;

            $namespace = ucwords($url[0]);
            $controller = ucwords($url[1]) . 'Controller';
            $method = $url[2] ?? 'index';;
            $params = array_slice($url, 3);
            $fp = fopen("sample.txt", "a");fwrite($fp, print_r([$namespace,$controller,$method ], true));fclose($fp);
        } else {
            $namespace = 'User';
            $controller = ucwords($url[0]) . 'Controller';
            $method = $url[1] ?? 'index';
            $params = array_slice($url, 2);
        }

        // fileが存在しなければ 404 error
        $fileName = base_path("App/Controllers/{$namespace}/{$controller}.php");
        if (!file_exists($fileName)) return $this->setErrorController();

        $this->currentController = new ("App\\Controllers\\{$namespace}\\{$controller}");
        $this->currentMethod = $method;

        // methodが存在しなければ 404 error
        if (!method_exists($this->currentController, $this->currentMethod)) return $this->setErrorController();

        // 残った配列をparameterとしてセット
        $this->params = $params;

        return;
    }

    /**
     * 呼び出されたらerrorコントローラと404メソッドをセット
     *
     * @return void
     */
    public function setErrorController() {
        $this->currentController = new ("App\\Controllers\\User\\ErrorController");
        $this->currentMethod = 'response404';

        return;
    }

    /**
     * session宣言、flash sessionのoldへの移行
     *
     * @return void
     */
    public function initFlashSession()
    {
        // フラッシュセッションがあれば_oldへ移動し、フラッシュセッションは削除
        if ($this->getSession('_flash') || $this->getSession('_flash_error')) {
            $this->moveFlashSessionToOld();
        } else {
            $this->setSession('_old', []);
            $this->setSession('_old_error', []);
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
        
        $csrfToken = filter_input(INPUT_POST, '_csrf_token');

        // csrf tokenが正しければOK
        if ($csrfToken && $csrfToken === $this->getSession('_csrf_token')) return;

        $this->setFlashErrorSession(key:'status', param:'正規の画面からご利用ください。');

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
        if ($this->getSession('_csrf_token')) return;

        $csrfToken = bin2hex(random_bytes(32));
        $this->setSession('_csrf_token', $csrfToken);
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