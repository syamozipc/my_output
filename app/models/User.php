<?php
namespace App\Models;

use App\Libraries\Model;

class User extends Model{

    protected string $table = 'users';

    protected int $id;
    protected ?int $country_id = null;
    protected ?string $name = null;
    protected ?string $tel = null;
    protected ?string $date_of_birth = null;
    protected ?string $profile_image_path = null;
    protected ?string $introduction = null;
    protected string $email;
    protected string $register_token;
    protected string $register_token_sent_at;
    protected ?string $register_token_verified_at = null;
    protected ?string $password = null;
    protected ?string $api_token = null;
    protected ?string $remember_token = null;
    protected ?string $last_login_at = null;
    protected string $status_id;
    protected string $status_updated_at;
    protected string $created_at;
    protected string $updated_at;

    public function __construct()
    {
        parent::__construct();
    }
}