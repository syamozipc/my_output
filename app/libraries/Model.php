<?php
namespace App\Libraries;

use App\Libraries\Database;

class Model {
    public object $db;

    public function __construct()
    {
        $this->db = new Database;
    }
}