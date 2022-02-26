<?php
namespace App\Models;

use App\Libraries\Model;

class Post extends Model {
    public string $table = 'posts';

    // テーブルカラム
    private int $user_id;
    private int $country_id;
    private string $description;
    private string $status_id;

    public function __construct(array $params= [])
    {
        parent::__construct($params);
    }
}