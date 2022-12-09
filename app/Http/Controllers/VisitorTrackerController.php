<?php

namespace App\Http\Controllers;

use App\Library\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class visitorTrackerController extends Controller
{
    private $Lib;

    function __construct()
    {
        $this->middleware('auth');

        $this->Lib = new Library();
    }

    /*********************** Perhaps not necessary now ********************/

    public function index(Request $request){

    }

    public function insertTrafficData(Request $request){

    }
}
