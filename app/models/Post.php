<?php
namespace App\Models;

use App\Libraries\Model;

class Post extends Model {
    public string $table = 'posts';

    public array $fillable = [
        'id' => '',
        'user_id' => '',
        'country_id' => '',
        'description' => '',
        'status_id' => '',
        'created_at' => '',
        'updated_at' => ''
    ];

    public function __construct(array $params= [])
    {
        parent::__construct($params);
    }
}