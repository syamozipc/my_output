<?php
namespace App\Services;

use App\models\Country;

class CountryService {
    public object $countryModel;

    public function __construct()
    {
        $this->countryModel = new Country();
    }

    public function fetchCountriesList()
    {
        $sql = 'SELECT * FROM countries';

        $countriesList = $this->countryModel->db->prepare($sql)->executeAndFetchAll();

        return $countriesList;
    }

    public function fetchCountryByID($id)
    {
        $sql = 'SELECT * FROM countries WHERE id = :id';

        $countriesList = $this->countryModel->db->prepare($sql)
            ->bind(':id', $id)
            ->executeAndFetch();

        return $countriesList;
    }
}