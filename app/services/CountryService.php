<?php
namespace App\Services;

use App\models\Country;

class CountryService {
    public object $countryModel;

    public function __construct()
    {
        $this->countryModel = new Country();
    }

    /**
     * 国一覧を取得
     *
     * @return array
     */
    public function fetchCountriesList():array
    {
        $sql = 'SELECT * FROM countries';

        $countries = $this->countryModel->db->prepare($sql)->executeAndFetchAll(get_class($this->countryModel));

        return $countries;
    }

    /**
     * countriesテーブルのidで取得
     *
     * @param int $id
     * @return array
     */
    public function fetchCountryByID($id): Country
    {
        $sql = 'SELECT * FROM countries WHERE id = :id';

        $country = $this->countryModel->db->prepare($sql)
            ->bindValue(':id', $id)
            ->executeAndFetch(get_class($this->countryModel));

        return $country;
    }

    /**
     * countriesテーブルのnameカラムの完全一致で取得
     *
     * @param string $countryName
     * @return Country
     */
    public function fetchCountryByName($countryName):Country
    {
        $sql = 'SELECT * FROM countries WHERE name = :name';

        $country = $this->countryModel->db->prepare($sql)
            ->bindValue(':name', $countryName)
            ->executeAndFetch(get_class($this->countryModel));

        return $country;
    }

    /**
     * countriesテーブルのname/name_alphaをlike検索
     *
     * @param string $word
     * @return array
     */
    public function fetchCountriesByWord($word):array
    {
        $sql = 'SELECT * FROM countries WHERE name like :word OR name_alpha like :word';

        $countries = $this->countryModel->db->prepare($sql)
            ->bindValue(':word', "%{$word}%")
            ->executeAndFetchAll(get_class($this->countryModel));

        return $countries;
    }
}