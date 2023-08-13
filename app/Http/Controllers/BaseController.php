<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class BaseController extends Controller
{
    protected $request;
    protected $data;

    protected function __construct(){
        
        $this->request = request();
    
    }
}
