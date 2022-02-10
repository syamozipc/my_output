<?php
namespace App\Models;

use App\Libraries\Model;

class Post extends Model {
    public ?string $id;
    public ?string $country_id;
    public ?string $description;

    public function __construct($params = [])
    {
        parent::__construct();

        $this->id = $params['id'] ?? null;
        $this->country_id = $params['country_id'] ?? null;
        $this->description = $params['description'] ?? null;
    }

}