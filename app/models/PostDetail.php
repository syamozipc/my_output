<?php
namespace App\Models;

use App\Libraries\Model;

class PostDetail extends Model{
    public string $table = 'post_details';

    public array $fillable = [
        'post_id' => '',
        'type' => '',
        'path' => '',
        'sort_number' => '',
        'created_at' => '',
        'updated_at' => ''
    ];

    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }
}