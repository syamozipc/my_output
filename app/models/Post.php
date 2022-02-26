<?php
namespace App\Models;

use App\Libraries\Model;

class Post extends Model {
    use \App\Traits\MagicMethodTrait;

    public string $table = 'posts';

    // テーブルカラム
    private int $id;
    private int $user_id;
    private int $country_id;
    private ?string $description;
    private string $status_id;
    private string $created_at;
    private string $updated_at;

    public function __construct(array $params= [])
    {
        parent::__construct($params);
    }
}