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

        $countries = $this->countryModel->db->prepare($sql)->executeAndFetchAll(get_class($this->countryModel));

        return $countries;
    }

    public function fetchCountryByID($id)
    {
        $sql = 'SELECT * FROM countries WHERE id = :id';

        $countries = $this->countryModel->db->prepare($sql)
            ->bindValue(':id', $id)
            ->executeAndFetch(get_class($this->countryModel));

        return $countries;
    }
}