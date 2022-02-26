<?php
namespace App\Models;

use App\Libraries\Model;

class Region extends Model{
    use \App\Traits\MagicMethodTrait;
    
    public function __construct()
    {
        parent::__construct();
    }
}