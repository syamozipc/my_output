<?php
namespace App\Models;

use App\Libraries\Database;

class Country {
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function fetchCountriesList()
    {
        $sql = 'SELECT * FROM countries';

        $countriesList = $this->db->prepare($sql)->executeAndFetchAll();

        return $countriesList;
    }

    public function fetchCountryByID()
    {
        $sql = 'SELECT * FROM countries WHERE id = :id';

        $countriesList = $this->db->prepare($sql)
            ->bind(':id', 1)
            ->executeAndFetch();

        return $countriesList;
    }
}