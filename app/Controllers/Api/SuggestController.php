<?php
namespace App\Controllers\Api;

use App\Libraries\Controller;

class SuggestController extends Controller {

    public function getMatchedCountries()
    {
        $description = '';

        $data = [
            // 'css' => 'css/user/home/index.css',
            // 'js' => 'js/user/home/index.js',
            'description' => $description
        ];

        $this->view(view:'user/error/404', data:$data);
    }
}