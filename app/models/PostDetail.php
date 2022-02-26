<?php
namespace App\Models;

use App\Libraries\Model;

class PostDetail extends Model{
    public string $table = 'post_details';

    // テーブルカラム
    private ?int $post_id;
    private ?string $type;
    private ?string $path;
    private ?int $sort_number;

    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }
}