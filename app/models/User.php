<?php
namespace App\Models;

use App\Libraries\Model;

class User extends Model{

    protected string $table = 'users';

    protected $fillable = [
        'country_id',
        'name',
        'tel',
        'date_of_birth',
        'profile_image_path',
        'introduction',
        'email',
        'register_token',
        'register_token_sent_at',
        'register_token_verified_at',
        'password',
        'api_token',
        'remember_token',
        'last_login_at',
        'status_id',
        'status_updated_at',
    ];

    public function __construct()
    {
        parent::__construct();
    }
}