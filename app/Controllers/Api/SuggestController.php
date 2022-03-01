<?php
namespace App\Controllers\Api;

use App\Libraries\Controller;
use App\Services\CountryService;

class SuggestController extends Controller {

    private CountryService $countryService;

    public function __construct()
    {
        $this->countryService = new CountryService();
    }

    public function getMatchedCountries()
    {
        $request = filter_input(INPUT_GET, 'search');

        $countries = $this->countryService->fetchCountriesByWord($request);

        header('Content-Type: application/json');
        echo json_encode($countries, JSON_UNESCAPED_UNICODE);

        return;
    }
}