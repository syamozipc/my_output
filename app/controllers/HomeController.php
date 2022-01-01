<?php
class HomeController extends Controller {
    public function  __construct()
    {
    }

    public function index()
    {
        $description = "My Outputへようこそ\n好きなように練習してね";

        $data = [
            'description' => $description
        ];
        
        $this->view('home/index', $data);
    }
}