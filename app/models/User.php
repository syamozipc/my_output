<?php
namespace App\Models;

use App\Libraries\Model;

class User extends Model{
    use \App\Traits\MagicMethodTrait;

    private string $table = 'users';

    private int $id;
    private ?int $country_id;
    private ?string $name;
    private ?string $tel;
    private ?string $date_of_birth;
    private ?string $profile_image_path;
    private ?string $introduction;
    private string $email;
    private string $register_token;
    private string $register_token_sent_at;
    private ?string $register_token_verified_at;
    private ?string $password;
    private ?string $api_token;
    private ?string $remember_token;
    private ?string $last_login_at;
    private string $status_id;
    private string $status_updated_at;
    private string $created_at;
    private string $updated_at;

    public function __construct()
    {
        parent::__construct();
    }
}