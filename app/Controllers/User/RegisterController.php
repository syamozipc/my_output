<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Services\{RegisterService, UserService, LoginService};
use App\Models\User;
use App\Validators\User\{TemporaryRegisterValidator, RegisterValidator};

class RegisterController extends Controller {
    use \App\Traits\SessionTrait;

    public RegisterService $registerService;
    public UserService $userService;
    public LoginService $loginService;
    public user $userModel;

    public function __construct()
    {
        $this->registerService = new RegisterService();
        $this->userService = new UserService();
        $this->loginService = new LoginService();
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

        try {
            /**
             * @todo user modelからdb呼ぶのも微妙？
             */
            $this->userModel->db->beginTransaction();

            $emailVerifyToken = str_random(60);// 業務で使っているlaravelのtokenも60字だったので

            $this->registerService->temporarilyRegister(email:$request['email'], emailVerifyToken:$emailVerifyToken);

            $isSent = $this->registerService->sendEmail(to:$request['email'], emailVerifyToken:$emailVerifyToken);

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
    public function verifyEmail()
    {
        $token = filter_input(INPUT_GET, 'token');

        $user = $this->registerService->getTemporarilyRegisteredUser(emailVerifyToken:$token);

        if (!$user) {
            $this->setFlashSession(key:"error_email", param:'無効なURLです。再度メールアドレスを入力してください。');

            return redirect('register/tmpRegisterForm');
        }

        return $this->showRegisterForm(emailVerifyToken:$user->register_token);
    }

    private function showRegisterForm($emailVerifyToken)
    {
        $data = [
            'css' => 'css/user/register/showRegisterForm.css',
            'js' => 'js/user/register/showRegisterForm.js',
            'emailVerifyToken' => $emailVerifyToken
        ];

        return $this->view(view:'user/register/showRegisterForm', data:$data);
    }

    public function register()
    {
        $request = filter_input_array(INPUT_POST);

        $validator = new RegisterValidator();
        $isValidated = $validator->validate($request);

        if (!$isValidated) return redirect("register/verifyEmail?token={$request['register_token']}");

        // sendRegisterMail()と異なり、こちらではtransaction張らなくてOK（mail送信必須では無いので）

        $this->registerService->regsterUser(request:$request);

        $user = $this->userService->getUserByEmailVerifyToken(emailVerifyToken:$request['register_token']);

        $isSent = $this->registerService->sendRegisteredEmail(to:$user->email);

        // @todo log出力のみにする
        if (!$isSent) die('メール送信に失敗しましたが、登録は完了しています。');
                    
        $this->loginService->baseLogin(email:$user->email, password:$request['password'], model:$this->userModel);

        return redirect('mypage/index');
    }
}