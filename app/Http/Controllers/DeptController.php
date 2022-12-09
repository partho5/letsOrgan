<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeptController extends Controller
{
    function __construct(){
        $this->middleware('auth')->except(['showDeptRegister']);
    }

    public function index(){
        $id=Auth::id();
        $userData=User::where('id', $id)->get(['univ', 'dept']);
        $userData=$userData[0];
        $univ=$userData->univ ? $userData->univ : "";
        $dept=$userData->dept ? $userData->dept : "";
        return view('pages.dept')->with(['univ'=>$univ, 'dept'=>$dept]);
    }

    public function showDeptRegister(){
        return view('pages.dept_register');
    }
}
