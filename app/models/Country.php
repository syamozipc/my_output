<?php

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
}