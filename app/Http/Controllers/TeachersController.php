<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeachersController extends Controller
{


    function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){

    }
}
