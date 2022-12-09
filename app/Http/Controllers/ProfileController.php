<?php

namespace App\Http\Controllers;

use App\Communities;
use App\Library\Library;
use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    private $Lib;

    function __construct()
    {
        $this->middleware('auth')->except(['showSingleProfile']);

        $this->Lib = new Library();
    }



    public function getPortFolioWhatIam(Request $request){
        $currentCommunityId = $this->Lib->getCurrentCommunityId();
        $academicInfo = Communities::where('id', $currentCommunityId)->select('institute_name', 'dept_name')->get();

        return $academicInfo;
    }

    public function showSingleProfile($id){
        if( User::findOrFail($id)  ){
            $currentCommunityId = $this->Lib->getCurrentCommunityIdOfUser($id);
            $session = $this->Lib->getAcademicSessionOfUser($id, $currentCommunityId);
            $academicInfo['session'] = $session;

            if($currentCommunityId == 0){
                return abort(404);
            }

            $com = Communities::where('id', $currentCommunityId)
                ->select('institute_name', 'dept_name')->get();
            $academicInfo['institute'] = $com[0]->institute_name;
            $academicInfo['dept'] = $com[0]->dept_name;

            $profileInfo = DB::table('users as u')
                ->join('profiles as p', 'p.user_id', '=', 'u.id')
                ->select('u.name', 'u.email', 'p.phone', 'p.pp_url')
                ->where('u.id', $id)
                ->get();
            $awsPrefix = "https://s3.ap-south-1.amazonaws.com/choriyedao";
            $profileInfo[0]->pp_url = in_array($profileInfo[0]->pp_url, ['/uploaded/avatar.png', '/assets/images/avatar.png']) ? $profileInfo[0]->pp_url : $awsPrefix.$profileInfo[0]->pp_url;;

            $basicInfo['currentCommunityId'] = $this->Lib->getCurrentCommunityId(); //of mine
            $basicInfo['isAdmin'] = $this->Lib->isAdmin($id, $currentCommunityId);

            if( in_array($id, [4]) ){
                $academicInfo['session'] = "2012 - 2013";
                $academicInfo['institute'] = "LetsOrgan";
                $academicInfo['dept'] = "Developer";
            }

            return view('pages/profile/profile', [
                'profileInfo'   => $profileInfo[0],
                'academicInfo'  => $academicInfo,
                'basicInfo'     => $basicInfo,
            ]);
        }
        return view('errors.404');
    }


    public function showUpdateProfile(){
        if(Auth::check()){
            $loggedIn =true ;
            $userId = Auth::id();
            $profileInfo = Profile::where('user_id', $userId)->get();
            $profileInfo = $profileInfo[0];
        }else{
            return redirect('/login');
        }

        return view('pages.profile',
            [
                'loggedIn' => $loggedIn,
                'userId' => $userId,
                'user' => Auth::user(),
                'profileInfo' => $profileInfo,
                'pageTitle' => (Auth::user())->name
            ]);
    }


    public function doUpdateProfile(Request $request){
        $userId = Auth::id();
        $pp_url = "/assets/images/avatar.png"; //default value

        //array of default value
        $data = [
            'user_id' => $userId,
            'phone' => "+88",
            'pp_url' => $pp_url,
            'reputation' => 1
        ];

        //check weather user id exist at all
        $retrievedRows = Profile::where('user_id', $userId)->get();
        $exist= count($retrievedRows)==1 ? true : false ;

        if( ! $exist ){
            //insert default value for the user
            Profile::create($data);
        }

        $phone = $request->input('phone') ;
        if( isset($phone) ){
            DB::table('profiles')->where('user_id', $userId)->update(['phone'=>$phone]);
        }

        if( $request->file('pp') !==null ){
            $file = $request->file('pp');
            $fileName = str_replace(' ', '_', $file->getClientOriginalName());
            //Storage::disk('uploaded')->put($fileName, $file); //'uploaded' is the name of disk, in filesystem.php
            Storage::disk('s3')->put('my-cloud/'.$fileName, $file, 'public');
            $pp_url = "/my-cloud/".$fileName."/".$file->hashName();
            DB::table('profiles')->where('user_id', $userId)->update(['pp_url'=>$pp_url]);
        }

        $profileInfo = Profile::where('user_id', $userId)->get();
        $profileInfo = $profileInfo[0];

        return view('pages.profile',
            [
                'loggedIn' => true,
                'userId' => $userId,
                'user' => Auth::user(),
                'profileInfo' => $profileInfo,
                'pageTitle' => "Profile"
            ]);
    }
}
