<?php

namespace App\Http\Controllers;

use App\CurrentCommunityInMyCloud;
use App\HelperClasses\PointBadges;
use App\Library\Library;
use App\Profile;
use App\UploadRequestResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class HomepageController extends Controller
{

    private $Lib, $pointBadge;

    function __construct()
    {
        $this->middleware('auth')->except(['showPrivacyPolicy', 'showChoriyedaoFeatures', 'showSponsorBenefit']);

        $this->Lib = new Library();
    }

    public $loggedIn ;



    public function index(){
        if ($this->Lib->getCurrentCommunityId() == 0)
            return view('messages.u-r-not-connected');

        $userId = Auth::id();
        $profileInfo = Profile::where('user_id', $userId)->get();
        if(count($profileInfo) == 0){
            Profile::insert([
                'user_id'       => $userId,
                'phone'         => '+88',
                'pp_url'        => '/assets/images/avatar.png',
                'reputation'    => 1,
            ]);
        }
        $profileInfo = Profile::where('user_id', $userId)->get();
        $profileInfo = $profileInfo[0];

        //echo "<h1>We are coming back within 30 minutes</h1>";
        //dd($profileInfo);

        $recentlyUploaded = UploadRequestResponse::all();
        $currentCommunityId = $this->Lib->getCurrentCommunityId();


        $userName = Auth::user()->name;
        $unreadNotifications = $this->Lib->getUnreadNotifications($userId, $currentCommunityId);
        $readNotifications = $this->Lib->getReadNotifications($userId, $currentCommunityId, 10);


        return view('pages.index',
            [
                'userId'        => $userId,
                'profileInfo'   => $profileInfo,
                'pageTitle'     => "Lets Organize Study Materials",
                'currentCommunityId'   => $currentCommunityId,
                'communityName'        => $this->Lib->getCommunityName($currentCommunityId),
                'joinLink'             => $this->Lib->getJoiningLink($currentCommunityId),
                'unreadNotifications'  => $unreadNotifications,
                'readNotifications'    => $readNotifications,
            ]
        );
    } //index()


    public function showPrivacyPolicy(){
        return view('pages.privacy_policy');
    }
    public function showChoriyedaoFeatures(){
        return view('pages/choriyedao_features');
    }
    public function showSponsorBenefit(){
        return view('pages.misc.sponsor-benefit');
    }

    public function saveSponsorData(Request $request){
//        $name = $request->name;
//        $phone = $request->phone;
//        $email = $request->email;
//        $businessLink = $request->businessLink;
//        $additionalMsg = $request->additionalMsg;

        try{
            Mail::send('mailPages.tmp', ['request', $request], function ($mail) use ($request){
                $mail->from('choriyedao@gmail.com', 'LetsOrgan');
                $mail->to($request->email)->subject('Hello '.$request->name);
            });
            echo 'sent';
        }catch(Exception $e){
            echo 'error';
        };
    }


}
