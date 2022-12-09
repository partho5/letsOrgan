<?php

namespace App\Http\Controllers\Noticeboard;

use App\Module\Noticeboard\MainBoard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    private $noticeboard;
    private $userId;

    function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function($request, $next){
            $this->userId = Auth::id();
            $this->noticeboard = new MainBoard($this->userId);
            return $next($request);
        });
    }

    public function index(){
        $userInfo = $this->noticeboard->getUserInfo($this->userId);
        return view('noticeBoard.board', compact('userInfo'));
    }
}
