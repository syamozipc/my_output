<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Validators\User\LoginValidator;

class LoginController extends Controller {

    public function __construct()
    {
        parent::__construct();

        if (isLogedIn()) return redirect('mypage/index');
    }

    public function loginForm()
    {
        $data = [
            'css' => 'css/user/login/loginForm.css',
            'js' => 'js/user/login/loginForm.js',
        ];

        $this->view(view: 'user/login/loginForm', data:$data);
    }

    public function login()
    {
        $request = filter_input_array(INPUT_POST);

        $validator = new LoginValidator();
        $isValidated = $validator->validate($request);

        if (!$isValidated) return redirect('login/loginForm');

        $shouldRememberMe = isset($request['remember_me']);
        
        $isLogedIn = $this->loginService->baseLogin(email:$request['email'], password:$request['password'], rememberMe:$shouldRememberMe);
        
        if (!$isLogedIn) {
            $this->setFlashErrorSession(key:'status', param:'ログインに失敗しました。');
            
            return redirect('login/loginForm');
        }

        return redirect('mypage/index');
    }
}