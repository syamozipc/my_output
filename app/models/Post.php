<?php
namespace App\Models;

use App\Libraries\Model;

class Post extends Model {

    protected string $table = 'posts';

    // テーブルカラム
    protected int $id;
    protected int $user_id;
    protected int $country_id;
    protected ?string $description;
    protected string $status_id;
    protected string $created_at;
    protected string $updated_at;

    public function __construct(array $params= [])
    {
        parent::__construct($params);
    }
}