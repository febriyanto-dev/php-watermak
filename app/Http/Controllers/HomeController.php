<?php

namespace App\Http\Controllers;

class HomeController extends BaseController
{
    protected $pages = 'pages.home';

    public function __construct(){

        parent::__construct();
    }

    public function index(){
        
        try {

            return view($this->pages . '.index')->with($this->data);
        }
        catch (Exception $e) {
            throw $e;
        }
    }
}
