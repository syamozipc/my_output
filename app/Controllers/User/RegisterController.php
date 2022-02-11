<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Services\RegisterService;
use App\Validators\User\TemporaryRegisterValidator;

class RegisterController extends Controller {
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
    public function index()
    {
        $data = [
            'css' => 'css/user/register/index.css',
            'js' => 'js/user/register/index.js',
        ];

        return $this->view(view:'user/register/index', data:$data);
    }

    // emailをvalidation
    // transaction
// テーブルに仮登録
// email登録済みかつパスワードnullなら、email、email_verify_token、email_verify_token_created_at
// なければ新規でemail登録、パスワードはnull
// メール送信
// commit
    public function sendRegisterMail()
    {
        $validator = new TemporaryRegisterValidator();
        $isValidated = $validator->validate(post:$_POST);

        if (!$isValidated) return redirect('register/index');

        return $this->registerService->temporaryRegister();
    }
}