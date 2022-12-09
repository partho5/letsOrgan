<?php

namespace App\Http\Controllers;

use App\CommunityGeneralPosts;
use App\OnesignalDeviceInformation;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('/');

        return view('home');
        //return view('pages.partial.introduction');
    }

    public function sse(){
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        $posts = CommunityGeneralPosts::all()->count();
        echo "data: $posts \n\n";

        flush();
    }

    public function test(Request $request){

        //echo phpinfo();
        die();

        return view('mailPages.tmp');


        //$onesignalDevices = OnesignalDeviceInformation::all();
//        foreach ($onesignalDevices as $device){
//            \OneSignal::sendNotificationToUser("LetsOrgan is now ready to use !!", $device->device_id, $url = 'http://letsorgan.com', $data = null, $buttons = null, $schedule = null);
//        }

//        return view('mailPages.send-mail-to-all');
//        return view('pages.test');
//        return view('mailPages.assignment-post');
    }
}
