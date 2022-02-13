<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Services\RegisterService;
use App\Validators\User\{TemporaryRegisterValidator, RegisterValidator};

class RegisterController extends Controller {
    use \App\Traits\SessionTrait;

    public $registerService;

    public function __construct()
    {
        $this->registerService = new RegisterService();
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
     * 会員登録フォームに入力されたemailとtoken、token生成時間をusersテーブルに保存し、
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

        $this->registerService->temporarilyRegister(email:$request['email']);

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

        $user = $this->registerService->getTemporarilyRegisteredUser(token:$token);

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

        $this->registerService->regsterUser($request);

        // login
        // ref：
        // ・laravelのやり方（login trait）
        // ・https://qiita.com/mpyw/items/bb8305ba196f5105be15
        exit('login');
    }
}