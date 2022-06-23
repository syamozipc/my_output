<?php
namespace App\Controllers\User;

use App\Libraries\{Controller, Database};
use App\Services\{PasswordResetService, UserService};
use App\Validators\User\{passwordResetRequestValidator, PasswordResetStoreValidator};
use App\interface\EmailTokenInterface;

class PasswordResetController extends Controller implements EmailTokenInterface {
    use \App\Traits\SessionTrait;

    private PasswordResetService $passwordResetService;
    private UserService $userService;
    private Database $db;

    public function __construct()
    {
        parent::__construct();

        $this->passwordResetService = new PasswordResetService();
        $this->userService = new UserService();
        $this->db = Database::getSingleton();
    }

    /**
     * パスワードを忘れた方用のページを作成(メールアドレス入力欄を作成)
     *
     * @return void
     */
    public function resetRequest()
    {
        $data = [
            'css' => 'css/user/passwordReset/requestForm.css',
            'js' => 'js/user/passwordReset/requestForm.js',
        ];

        return $this->view(view:'user/passwordReset/requestForm', data:$data);
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
    public function sendEmail()
    {
        $request = filter_input_array(INPUT_POST);

        // emailのバリデーション（この時点では登録済みアドレスか未チェック）
        $validator = new passwordResetRequestValidator();
        $isValidated = $validator->validate(request:$request);

        if (!$isValidated) return redirect('/passwordReset/resetRequest');

        // 未登録のメールアドレスでも、その旨のエラーを出すと未登録とバレる（情報を与える）ので良くない
        // 未登録の場合は即座にメール送信完了画面にする
        $isExist = $this->userService->isPublicUser(email:$request['email']);
        if (!$isExist) return $this->view(view:'user/passwordReset/acceptRequest', data:['email' => $request['email']]);

        try {
            $this->db->beginTransaction();

            // 業務で使っているlaravelのtokenも60字だった（ただし生成メソッドはLaravelの方が複雑）
            $passwordResetToken = str_random(60);

            // password_resetsテーブルに保存
            $this->passwordResetService->saveRequest(email:$request['email'], passwordResetToken:$passwordResetToken);

            // tokenをqueryに持たせたリセット用URLをメール送信
            $isSent = $this->passwordResetService->sendEmail(to:$request['email'], passwordResetToken:$passwordResetToken);

            if (!$isSent) throw new \Exception('メール送信に失敗しました。');

            $this->db->commit();

        } catch (\Exception $e) {
            $this->db->rollBack();

            $this->setFlashErrorSession(key:'status', param:$e->getMessage());

            return redirect('/passwordReset/resetRequest');
        }

        $data = [
            'email' => $request['email']
        ];

        return $this->view(view:'user/passwordReset/acceptRequest', data:$data);
    }

    /**
     * 送信されたメールに記載のURLにアクセスすると、
     * ・query stringのtokenがテーブルのレコードと合致するか
     * ・token発行から指定時間以内のアクセスか
     * をチェックし、
     * ・どちらも満たせば本登録案内
     * ・片方でも満たさなければresetRequest画面へ
     *
     * @return void
     */
    public function verifyToken()
    {
        $token = filter_input(INPUT_GET, 'token');

        // tokenからpassword_resetsテーブルのレコードを取得
        $passwordReset = $this->passwordResetService->getValidRequestByToken(passwordResetToken:$token);

        if (!$passwordReset) {
            $this->setFlashErrorSession(key:'status', param:'無効なURLです。再度メールアドレスを入力してください。');

            return redirect('/passwordReset/resetRequest');
        }

        // password変更フォームを表示
        return $this->resetForm(passwordResetToken:$token);
    }

    /**
     * パスワード変更フォームを表示
     *
     * @param string $passwordResetToken
     * @return void
     */
    private function resetForm($passwordResetToken)
    {
        $data = [
            'css' => 'css/user/passwordReset/resetForm.css',
            'js' => 'js/user/passwordReset/resetForm.js',
            'passwordResetToken' => $passwordResetToken
        ];

        return $this->view(view:'user/passwordReset/resetForm', data:$data);
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
    public function reset()
    {
        $request = filter_input_array(INPUT_POST);

        // パスワードの妥当性をバリデーション
        $validator = new PasswordResetStoreValidator();
        $isValidated = $validator->validate($request);

        if (!$isValidated) return redirect("passwordReset/verifyToken?token={$request['password_reset_token']}");

        // tokenからpassword_resetsテーブルのレコードを取得
        $passwordReset = $this->passwordResetService->getValidRequestByToken(passwordResetToken:$request['password_reset_token']);

        // 一致レコードがなければ、リクエスト画面へ戻す
        if (!$passwordReset) {
            $this->setFlashErrorSession(key:'status', param:'無効なURLです。再度メールアドレスを入力してください。');

            return redirect('/passwordReset/resetRequest');
        }

        try {
            $this->db->beginTransaction();

            // 対象のユーザーを取得
            $user = $this->userService->getUserByEmail(email:$passwordReset->email);

            // パスワードをアップデート
            $user->password = password_hash(password:$request['password'], algo:PASSWORD_BCRYPT);
            $user->save();

            // パスワードリセットは物理削除
            $passwordReset->delete();

            $this->db->commit();

        } catch (\Exception $e) {
            $this->db->rollBack();

            exit($e->getMessage());
        }

        // ログイン失敗は無い想定なので、失敗時の処理は書いていない
        $this->loginService->baseLogin(email:$passwordReset->email, password:$request['password']);

        return redirect('/mypage/index');
    }
}