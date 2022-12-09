<?php

namespace App\Http\Controllers;

use App\DesktopClientInfo;
use App\Library\Library;
use App\MyCloud;
use Illuminate\Http\Request;
//use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class DesktopClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $Lib;
    private  $userId;

    function __construct()
    {
        $this->Lib = new Library();
    }

    public function index($token)
    {

        if( ! $this->Lib->rowExist('desktop_client_info', 'auth_token', $token) ){
            return response("Authorization Failed", 401);
        }
        $desktop = DesktopClientInfo::where('auth_token', $token)->get();
        if( count($desktop) < 1 ){
            return response("Authorization Failed", 401);
        }
        $community_id = $desktop[0]->community_id;

        $syncable = $this->Lib->getSyncAbleFiles($community_id);
        foreach ($syncable as $item){
            if( is_null($item->remote_path) ){
                $item->remote_path = "unknown";
            }
        }

        return $syncable;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
