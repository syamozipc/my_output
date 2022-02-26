<?php
namespace App\Models;

use App\Libraries\Model;

class PostDetail extends Model{

    protected string $table = 'post_details';

    // テーブルカラム
    protected int $id;
    protected ?int $post_id = null;
    protected ?string $type = null;
    protected ?string $path = null;
    protected ?int $sort_number = null;
    protected string $created_at;
    protected string $updated_at;
    
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }
}