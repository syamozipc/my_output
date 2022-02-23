<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Services\{RegisterService, UserService};
use App\Models\User;
use App\Validators\User\{TemporaryRegisterValidator, RegisterValidator};

class RegisterController extends Controller {
    use \App\Traits\SessionTrait;

    public RegisterService $registerService;
    public UserService $userService;
    public user $userModel;

    public function __construct()
    {
        parent::__construct();
        
        $this->registerService = new RegisterService();
        $this->userService = new UserService();
        $this->userModel = new User();
    }

    /**
     * 新規会員登録フォーム表示
     *
     * @return void
     */
    public function tmpRegisterForm()
    {
        $data = [
            'css' => 'css/user/register/tmpRegisterForm.css',
            'js' => 'js/user/register/tmpRegisterForm.js',
        ];

        return $this->view(view:'user/register/tmpRegisterForm', data:$data);
    }

    /**
     * 1.会員登録フォームに入力されたemailが本登録済みでないか確認
     * 2.下記をDBに保存
     *   ・会員登録フォームに入力されたemail
     *   ・URLに加えるために生成したtoken
     *   ・token生成時間
     * 3.本登録用URL（生成したtokenをクエリに持たせている）を記載したメールを送信
     *
     * @return void
     */
    public function sendRegisterMail()
    {
        $request = filter_input_array(INPUT_POST);

        $validator = new TemporaryRegisterValidator();
        $isValidated = $validator->validate(post:$request);

        if (!$isValidated) return redirect('register/tmpRegisterForm');

        // 本登録済みでも、その旨のエラーを出すと登録済みとバレる（情報を与える）ので良くない
        // 本登録済みの場合は即座にメール送信完了画面にする
        $isExistUser = $this->userService->isPublicUser($request['email']);
        if ($isExistUser) return $this->view(view:'user/register/successTemporaryRegister', data:['email' => $request['email']]);

        try {
            /**
             * @todo user modelからdb呼ぶのも微妙？
             */
            $this->userModel->db->beginTransaction();

            // 業務で使っているlaravelのtokenも60字だった（ただし生成メソッドはLaravelの方が複雑）
            $registerToken = str_random(60);

            $this->registerService->temporarilyRegister(email:$request['email'], registerToken:$registerToken);

            $isSent = $this->registerService->sendEmail(to:$request['email'], registerToken:$registerToken);

            if (!$isSent) throw new \Exception('メール送信に失敗しました。');

            $this->userModel->db->commit();

        } catch (\Exception $e) {
            $this->userModel->db->rollBack();

            exit($e->getMessage());
        }

        $data = [
            'email' => $request['email']
        ];

        return $this->view(view:'user/register/successTemporaryRegister', data:$data);
    }

    /**
     * 仮登録完了メールにて記載URLにアクセスされると、
     * ・emailとquery stringのtokenがテーブルのレコードと合致するか
     * ・token発行から指定時間以内のアクセスか
     * をチェックし、
     * ・どちらも満たせば本登録案内
     * ・片方でも満たさなければ仮登録画面へ遷移
     *
     * @return void
     */
    public function verifyToken()
    {
        $token = filter_input(INPUT_GET, 'token');

        $user = $this->registerService->getValidTemporarilyRegisteredUser(registerToken:$token);

        if (!$user) {
            $this->setFlashErrorSession(key:'status', param:'無効なURLです。再度メールアドレスを入力してください。');

            return redirect('register/tmpRegisterForm');
        }

        return $this->registerForm(registerToken:$user->register_token);
    }

    private function registerForm($registerToken)
    {
        $data = [
            'css' => 'css/user/register/registerForm.css',
            'js' => 'js/user/register/registerForm.js',
            'registerToken' => $registerToken
        ];

        return $this->view(view:'user/register/registerForm', data:$data);
    }

    /**
     * 1. 入力をバリデーション
     * 2. tokenからpassword_resetsテーブルのレコードを取得する
     * 3. パスワードを更新
     * 4. password_resetsから該当のレコードを削除
     * 5. ログイン処理
     *
     * @return void
     */
    public function register()
    {
        $request = filter_input_array(INPUT_POST);

        // 入力をバリデーション
        $validator = new RegisterValidator();
        $isValidated = $validator->validate($request);

        if (!$isValidated) return redirect("register/verifyToken?token={$request['register_token']}");

        // sendRegisterMail()と異なり、こちらではtransaction張らなくてOK（mail送信必須では無いので）

        // usersテーブルの該当レコードを本登録させる
        $this->registerService->regsterUser(request:$request);

        // tokenの値で、先ほど登録したuserを取得
        $user = $this->userService->getUserByRegisterToken(registerToken:$request['register_token']);

        // 本登録完了メール送信
        $isSent = $this->registerService->sendRegisteredEmail(to:$user->email);

        // @todo log出力のみにする
        if (!$isSent) die('メール送信に失敗しましたが、登録は完了しています。');
                    
        // ログイン失敗は無い想定なので、失敗時の処理は書いていない
        $this->loginService->baseLogin(email:$user->email, password:$request['password']);

        return redirect('mypage/index');
    }
}