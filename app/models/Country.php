<?php
namespace App\Models;

use App\Libraries\Model;

class Country extends Model{

    public function __construct()
    {
        parent::__construct();
    }

    public function fetchCountriesList()
    {
        $sql = 'SELECT * FROM countries';

        $countriesList = $this->db->prepare($sql)->executeAndFetchAll();

        return $countriesList;
    }

    public function fetchCountryByID($id)
    {
        $sql = 'SELECT * FROM countries WHERE id = :id';

        $countriesList = $this->db->prepare($sql)
            ->bindValue(':id', $id)
            ->executeAndFetch();

        return $countriesList;
    }
}