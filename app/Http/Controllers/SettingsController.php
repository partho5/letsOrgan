<?php

namespace App\Http\Controllers;

use App\AllCommunitiesOfUsers;
use App\AutoSyncableDirectories;
use App\CommunityAssignments;
use App\CommunityExams;
use App\DesktopClientInfo;
use App\MobileClientInfo;
use App\MyCloud;
use App\Questions;
use App\UploadRequest;
use App\UploadRequestResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Library\Library;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class SettingsController extends Controller
{

    private $Lib;

    function __construct()
    {
        $this->middleware('auth');

        $this->Lib = new Library();
    }


    public function showSettings(Request $request){
        $userId = Auth::id();
        $curCommunityId = $this->Lib->getCurrentCommunityId();
        if( ! $this->Lib->mobileDesktopClientTokenExist($curCommunityId) ){
            $generatedToken  = $this->Lib->generateToken(40);
            $desktopInfo = DesktopClientInfo::insert([
                'user_id'       => $userId,
                'community_id'  => $curCommunityId,
                'auth_token'    => $generatedToken,
                'os'            => 'unspecified',
                'bit'           => 'non',
                'mac_addr'      => 'unspecified',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);

            //at the same time insert same token in the 'mobile_client_info' table
            $mobileInfo = MobileClientInfo::insert([
                'user_id'       => $userId,
                'community_id'  => $curCommunityId,
                'token'         => $generatedToken,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]);
        }

        $desktopInfo = DesktopClientInfo::where('community_id', $curCommunityId)
            ->where('user_id', $userId)->first();
        $academicSession = AllCommunitiesOfUsers::where('user_id', $userId)
            ->where('community_id', $curCommunityId)
            ->get();
        if (count($academicSession) > 0 ){
            $academicSession = $academicSession[0]->academic_session;
        }

        $rootDirs = MyCloud::where('possessed_by_community', $curCommunityId)
            ->where('is_root', 1)->where('file_ext', 'null')
            ->where('soft_delete', 0)->get();
        $dirIdToSync = $this->Lib->getDirIdToSync($curCommunityId, $rootDirs);

        return view('pages.cloud_settings', [
            'auth_token'        => $desktopInfo->auth_token,
            'community_name'    => $this->Lib->getCommunityName( $curCommunityId ),
            'academicSession'   => $academicSession,
            'rootDirs'          => $rootDirs,
            'dirIdToSync'      =>  $dirIdToSync,
        ]);
    } // showSettings()


    //ajax
    public function updateSyncableDirId(Request $request){
        $selectedDirId = $request->input('selectedDirId');
        $userId = Auth::id();
        $communityId = $this->Lib->getCurrentCommunityId($userId);
        try{
            AutoSyncableDirectories::where('user_id', $userId)->where('community_id', $communityId)
                ->update(['cloud_id' => $selectedDirId]);
            echo 'updated';
        }catch (Exception $e){
            echo 'error';
        }
    }

    //ajax
    public function saveAcademicSession(Request $request){
        $userId = Auth::id();
        $curCommunityId = $this->Lib->getCurrentCommunityId();
        $mySession = $request->input('mySession');
        try{
            AllCommunitiesOfUsers::where('user_id', $userId)->where('community_id', $curCommunityId)
                ->update(['academic_session' => $mySession ]);

            //insert some default demo data in some tables, immediately after setting session
            $examData = CommunityExams::where('community_id', $curCommunityId)->get();
            if(count($examData) == 0){
                DB::table('community_exams')->insert($this->Lib->demo_ExamData($curCommunityId, $mySession));
            }
            $assignmentData = CommunityAssignments::where('community_id', $curCommunityId)->get();
            if(count($assignmentData) == 0){
                DB::table('community_assignments')->insert($this->Lib->demo_AssignmentData($curCommunityId, $mySession));
            }
            $uploadRequest = UploadRequest::where('shared_with_community', $curCommunityId)->get();
            if(count($uploadRequest) == 0){
                DB::table('upload_requests')->insert($this->Lib->demo_UploadRequestData($curCommunityId));
            }
            $uploadRequestResponse = UploadRequestResponse::all();
            if(count($uploadRequestResponse) == 0){
                DB::table('upload_request_responses')->insert($this->Lib->demo_upRequestResponse());
            }
            //question in QA community
            $questions = Questions::all()->take(1);
            if(count($questions) == 0){
                DB::table('questions')->insert($this->Lib->demo_questions());
            }


            echo 'success';
        }catch (Exception $e){

        }
    } // saveAcademicSession()
}
