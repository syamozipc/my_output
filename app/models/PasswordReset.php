<?php
namespace App\Models;

use App\Libraries\Model;

class PasswordReset extends Model{
    public string $table = 'password_resets';

    protected $primaryKey = 'email';

    public array $fillable = [
        'email' => '',
        'token' => '',
        'token_sent_at' => '',
    ];

    public function __construct()
    {
        parent::__construct();
    }
}