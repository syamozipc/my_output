<?php
namespace App\Controllers\User;

use App\Libraries\Controller;
use App\Services\{PasswordResetService, UserService, LoginService};
use App\Models\User;
use App\Validators\User\{passwordResetRequestValidator, PasswordResetStoreValidator};

class PasswordResetController extends Controller {
    use \App\Traits\SessionTrait;

    public PasswordResetService $passwordResetService;
    public UserService $userService;
    public LoginService $loginService;
    public user $userModel;

    public function __construct()
    {
        $this->passwordResetService = new PasswordResetService();
        $this->userService = new UserService();
        $this->loginService = new LoginService();
        $this->userModel = new User();
    }

    /**
     * パスワードを忘れた方用のページを作成(メールアドレス入力欄を作成)
     *
     * @return void
     */
    public function passwordResetRequest()
    {
        $data = [
            'css' => 'css/user/passwordReset/passwordResetRequest.css',
            'js' => 'js/user/passwordReset/passwordResetRequest.js',
        ];

        return $this->view(view:'user/passwordReset/passwordResetRequest', data:$data);
    }


    /**
     * 1.メールアドレスをバリデーション
     * 2.下記をpassword_resetsテーブルに保存
     *   ・会員登録フォームに入力されたemail
     *   ・URLに加えるために生成したtoken
     *   ・token生成時間
     * 3.本登録用URL（生成したtokenをクエリに持たせている）を記載したメールを送信
     *
     * @return void
     */
    public function sendPasswordResetMail()
    {
        $request = filter_input_array(INPUT_POST);

        $validator = new passwordResetRequestValidator();
        $isValidated = $validator->validate(request:$request);

        if (!$isValidated) return redirect('passwordReset/passwordResetRequest');

        $user = $this->userService->getUserByEmail(email:$request['email']);

        // 未登録のメールアドレスだった場合は、メール送信完了画面を表示する
        // セキュリティ上、メールアドレスが未登録である旨のエラー（情報）を出さないようにする
        if (!$user) return $this->view(view:'user/passwordReset/acceptPasswordResetRequest', data:['email' => $request['email']]); 

        try {
            /**
             * @todo user modelからdb呼ぶのも微妙？
             */
            $this->userModel->db->beginTransaction();

            $passwordResetToken = str_random(60);// 業務で使っているlaravelのtokenも60字だったので

            $this->passwordResetService->savePasswordResetRequest(email:$user->email, passwordResetToken:$passwordResetToken);

            $isSent = $this->passwordResetService->sendEmail(to:$user->email, passwordResetToken:$passwordResetToken);

            if (!$isSent) throw new \Exception('メール送信に失敗しました。');

            $this->userModel->db->commit();

        } catch (\Exception $e) {
            $this->userModel->db->rollBack();

            exit($e->getMessage());
        }

        $data = [
            'email' => $request['email']
        ];

        return $this->view(view:'user/passwordReset/acceptPasswordResetRequest', data:$data);
    }

    /**
     * パスワードリセットメールにて記載URLにアクセスされると、
     * ・emailとquery stringのtokenがテーブルのレコードと合致するか
     * ・token発行から指定時間以内のアクセスか
     * をチェックし、
     * ・どちらも満たせば本登録案内
     * ・片方でも満たさなければpasswordResetRequest画面へ
     *
     * @return void
     */
    public function verifyEmail()
    {
        $token = filter_input(INPUT_GET, 'token');

        $resetRequest = $this->passwordResetService->getValidRequestByToken(passwordResetToken:$token);

        if (!$resetRequest) {
            $this->setFlashSession(key:"error_email", param:'無効なURLです。再度メールアドレスを入力してください。');

            return redirect('passwordReset/passwordResetRequest');
        }

        return $this->passwordResetForm(passwordResetToken:$token);
    }

    private function passwordResetForm($passwordResetToken)
    {
        $data = [
            'css' => 'css/user/passwordReset/passwordResetForm.css',
            'js' => 'js/user/passwordReset/passwordResetForm.js',
            'passwordResetToken' => $passwordResetToken
        ];

        return $this->view(view:'user/passwordReset/passwordResetForm', data:$data);
    }

    public function reset()
    {
        $request = filter_input_array(INPUT_POST);

        $validator = new PasswordResetStoreValidator();
        $isValidated = $validator->validate($request);

        if (!$isValidated) return redirect("passwordReset/verifyEmail?token={$request['password_reset_token']}");

        $resetRequest = $this->passwordResetService->getValidRequestByToken(passwordResetToken:$request['password_reset_token']);

        try {
            /**
             * @todo user modelからdb呼ぶのも微妙？
             */
            $this->userModel->db->beginTransaction();

            $this->userService->updatePassword(email:$resetRequest->email, password:$request['password']);

            $this->passwordResetService->delete(passwordResetToken:$request['password_reset_token']);

            $this->userModel->db->commit();

        } catch (\Exception $e) {
            $this->userModel->db->rollBack();

            exit($e->getMessage());
        }

        $this->loginService->baseLogin(email:$resetRequest->email, password:$request['password'], model:$this->userModel);

        return redirect('mypage/index');
    }
}