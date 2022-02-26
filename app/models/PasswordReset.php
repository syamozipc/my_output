<?php
namespace App\Models;

use App\Libraries\Model;

class PasswordReset extends Model{

    protected string $table = 'password_resets';

    // modelのdefaultを上書き
    protected string $primaryKey = 'email';

    // テーブルカラム
    protected string $email;
    protected string $token;
    protected string $token_sent_at;

    public function __construct()
    {
        parent::__construct();
    }
}