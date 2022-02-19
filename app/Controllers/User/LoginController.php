<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Validators\User\LoginValidator;
use App\Services\LoginService;
use App\Models\User;

class LoginController extends Controller {
    use \App\Traits\SessionTrait;

    public user $userModel;
    public LoginService $loginService;

    public function __construct()
    {
        $this->userModel = new User();
        $this->loginService = new LoginService();
    }


    public function showLoginForm()
    {
        $data = [
            'css' => 'css/user/login/showLoginForm.css',
            'js' => 'js/user/login/showLoginForm.js',
        ];

        $this->view(view: 'user/login/showLoginForm', data:$data);
    }

    public function login()
    {
        $request = filter_input_array(INPUT_POST);

        $validator = new LoginValidator();
        $isValidated = $validator->validate($request);

        if (!$isValidated) return redirect('login/showLoginForm');

        $isLogedIn = $this->loginService->baseLogin(email:$request['email'], password:$request['password'], model:$this->userModel);

        if (!$isLogedIn) {
            $this->setFlashSession(key:"error_email", param:'ログインに失敗しました。');

            return redirect('mypage/index');
        }

        return redirect('mypage/index');
    }
}