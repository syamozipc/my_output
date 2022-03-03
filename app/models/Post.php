<?php
namespace App\Models;

use App\Libraries\Model;

class Post extends Model {

    protected string $table = 'posts';

    // テーブルカラム
    protected $fillable = [
        'user_id',
        'country_id',
        'description',
        'status_id',
    ];

    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }
}