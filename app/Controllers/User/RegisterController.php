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
    public function tmpRegister()
    {
        $data = [
            'css' => 'css/user/register/tmpRegister.css',
            'js' => 'js/user/register/tmpRegister.js',
        ];

        return $this->view(view:'user/register/tmpRegister', data:$data);
    }

    /**
     * 会員登録フォームに入力されたemail、token、token生成時間をusersテーブルに保存し、
     * 本登録用URLを記載したメールを送信
     *
     * @return void
     */
    public function sendRegisterMail()
    {
        $request = filter_input_array(INPUT_POST);

        $validator = new TemporaryRegisterValidator();
        $isValidated = $validator->validate(post:$request);

        if (!$isValidated) return redirect('register/tmpRegister');

        try {
            /**
             * @todo user modelからdb呼ぶのも微妙？
             */
            $this->userModel->db->beginTransaction();

            /**
            * @todo 送り直しの都度tokenが同じなのは微妙？
            */
            $emailVerifyToken = base64_encode($request['email']);

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

        return $this->view(view:'user/register/success_temporary_register', data:$data);
    }

    /**
     * 仮登録完了メールにて記載URLにアクセスされると、
     * ・emailとquery stringのtokenがテーブルのレコードと合致するか
     * ・token発行から指定時間以内のアクセスか
     * をチェックし、
     * ・指定時間以内なら本登録案内
     * ・指定時間を過ぎていたら再度仮登録メールを送る
     *
     * @return void
     */
    public function verifyEmail()
    {
        $token = filter_input(INPUT_GET, 'token');

        $user = $this->registerService->getTemporarilyRegisteredUser(emailVerifyToken:$token);

        if (!$user) {
            $this->setFlashSession(key:"error_email", param:'無効なURLです。再度メールアドレスを入力してください。');

            return redirect('register/tmpRegister');
        }

        return $this->showRegisterForm(emailVerifyToken:$user->email_verify_token);
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

        if (!$isValidated) return redirect("register/verifyEmail?token={$request['email_verify_token']}");

        // sendRegisterMail()と異なり、こちらではtransaction張らなくてOK（mail送信必須では無いので）

        $this->registerService->regsterUser(request:$request);

        $user = $this->userService->getUserByEmailVerifyToken(emailVerifyToken:$request['email_verify_token']);

        $isSent = $this->registerService->sendRegisteredEmail(to:$user->email);

        // @todo log出力のみにする
        if (!$isSent) die('メール送信に失敗しましたが、登録は完了しています。');
                    
        // @todo loginServiceの方が良さそう（traitはinstance化できないので）
        $this->loginService->baseLogin(email:$user->email, password:$request['password'], model:$this->userModel);

        return redirect('mypage/index');
    }
}