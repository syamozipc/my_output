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

    /**
     * 会員登録フォームに入力されたemailとtoken、token生成時間をusersテーブルに保存し、
     * 本登録用URLを記載したメールを送信
     *
     * @return void
     */
    public function sendRegisterMail()
    {
        $validator = new TemporaryRegisterValidator();
        $isValidated = $validator->validate(post:$_POST);

        if (!$isValidated) return redirect('register/index');

        $this->registerService->temporaryRegister(email:$_POST['email']);
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
        echo '<pre>';var_dump($_GET);die;
    }
}