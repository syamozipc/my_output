<?php
namespace App\Libraries;

/**
 * App Core Class
 * Creates URL & loads core controller
 * URL FORMAT - /controller/method/params
 */
class Core {
    use \App\Traits\SessionTrait;

    protected $routes;

    public function __construct()
    {
        // セッションの開始とセッションIDの変更（セッションハイジャック対策）
        session_start();
        session_regenerate_id(true);

        // POSTリクエストであっても、リクエストURLはGETで取れる
        $url = filter_input(INPUT_GET, 'url');

        // topページの場合は$urlがnullなので、その場合は空配列にする
        if ($url) $url = $this->formatAndSanitizeUrl(url:$url);
        else $url = [];

        // 定義済みルートを取得
        $this->routes = $this->getRoutes();

        // URLによって呼び出すコントローラを特定
        $funcWithParams = $this->getControllerFromUrl(url:$url);

        // apiでなければ、sessionとcsrfを設定
        if (!$url || $url[0] !== 'api') {
            $this->initFlashSession();
            $this->checkCSRF();
            $this->initCSRF();
        }

        // controllerをインスタンス化し、methodにparamsを渡して呼び出す
        call_user_func_array(
            [
                new($funcWithParams['controller']),
                $funcWithParams['method']
            ],
            $funcWithParams['params']
        );
    }

    /**
     * 1.URL末尾の/を削除
     * 2.値をサニタイズ（例えば日本語など、無効な文字を取り除く）
     * 3.配列に分割（[0] => controller名, [1] => method名, [2] => parameter）
     *
     * @return array
     */
    private function formatAndSanitizeUrl(string $url): array
    {
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);

        return $url;
    }

     /**
     * URLを取得し、対応するclassのmethodを呼び出す基幹処理
     *
     * ・namespace（現状apiのみ）にも対応
     * ・$urlが空なら、homeページへ
     * ・$urlが定義済みルートにが存在しないurlなら、404を表示
     */
    private function getControllerFromUrl(array $url)
    {
        // $urlが空ならrootなので、TOPページをリターン
        if (count($url) === 0) {
            return [
                'controller' => "App\\Controllers\\User\\homeController",
                'method' => 'index',
                'params' => [],
            ];
        }

        // namespaceがurlにない場合は、配列の要素数を合わせるため、user namespaceを挿入
        if (!in_array($url[0], ['api'], true)) array_unshift($url, 'user');

        // get or post（大文字で入っているので、小文字へ変換）
        $method = strtolower($_SERVER["REQUEST_METHOD"]);

        // 定義済みルートと照合
        $route = $this->routes[$url[0]][$url[1]][$method][$url[2]] ?? null;

        // 合致したルートに紐づくコントローラ・メソッドを取得
        if ($route) {
            $namespace = ucwords($url[0]);
            $controller = ucwords($url[1]) . 'Controller';

            return [
                'controller' => "App\\Controllers\\{$namespace}\\{$controller}",
                'method' => $url[2],
                'params' => array_slice($url, 3),
            ];
        } else {
            // 合致するルートがない場合は404

            return [
                'controller' => "App\\Controllers\\User\\ErrorController",
                'method' => 'response404',
                'params' => [],
            ];
        }
    }

    /**
     * 定義済みルート一覧を取得
     *
     * @return array
     */
    private function getRoutes(): array
    {
        return [
            'user' => [
                'home' => [
                    'get' => [
                        'index' => 'index',
                    ],
                ],
                'register' => [
                    'get' => [
                        'tmpRegisterForm' => 'tmpRegisterForm',
                        'verifyToken' => 'verifyToken',
                    ],
                    'post' => [
                        'sendEmail' => 'sendEmail',
                        'register' => 'register',
                    ],
                ],
                'login' => [
                    'get' => [
                        'loginForm' => 'loginForm',
                    ],
                    'post' => [
                        'login' => 'login',
                    ],
                ],
                'logout' => [
                    'get' => [
                        'logout' => 'logout',
                    ],
                ],
                'passwordReset' => [
                    'get' => [
                        'resetRequest' => 'resetRequest',
                        'verifyToken' => 'verifyToken',
                    ],
                    'post' => [
                        'sendEmail' => 'sendEmail',
                        'reset' => 'reset',
                    ],
                ],
                'mypage' => [
                    'get' => [
                        'index' => 'index',
                    ],
                ],
                'post' => [
                    'get' => [
                        'index' => 'index',
                        'create' => 'create',
                        'show' => 'show',
                        'edit' => 'edit',
                    ],
                    'post' => [
                        'confirm' => 'confirm',
                        'save' => 'save',
                        'editConfirm' => 'editConfirm',
                        'update' => 'update',
                        'delete' => 'delete',
                    ],
                ],
            ],
            'api' => [
                'suggest' => [
                    'get' => [
                        'getMatchedCountries' => 'getMatchedCountries',
                    ],
                ],
            ],
        ];
    }

    /**
     * session宣言、flash sessionのoldへの移行
     *
     * @return void
     */
    private function initFlashSession()
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
    private function checkCSRF()
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
    private function initCSRF()
    {
        if ($this->getSession('_csrf_token')) return;

        $csrfToken = bin2hex(random_bytes(32));
        $this->setSession('_csrf_token', $csrfToken);
    }
}