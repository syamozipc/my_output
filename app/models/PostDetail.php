<?php
namespace App\Models;

use App\Libraries\Model;

class PostDetail extends Model{
    use \App\Traits\MagicMethodTrait;

    public string $table = 'post_details';

    // テーブルカラム
    private int $id;
    private ?int $post_id;
    private ?string $type;
    private ?string $path;
    private ?int $sort_number;
    private string $created_at;
    private string $updated_at;
    
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }
}