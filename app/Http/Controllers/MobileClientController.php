<?php

namespace App\Http\Controllers;

use App\AllCommunitiesOfUsers;
use App\ClassRoutine;
use App\CommunityAssignments;
use App\CommunityExams;
use App\CommunityGeneralPosts;
use App\Library\Library;
use App\MobileClientInfo;
use App\Notifications;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use DB;

class MobileClientController extends Controller
{

    private $Lib;

    function __construct()
    {
        $this->Lib = new Library();
    }

    public function index(Request $request, $token){
        $mobileInfo = MobileClientInfo::where('token', $token)->get();
        if( count($mobileInfo) == 1 ){
            $mobileInfo = $mobileInfo[0];
            $userId = $mobileInfo->user_id;
            $communityId = $mobileInfo->community_id;
            $academicSession = $this->getAcademicSession($communityId, $userId);

            $generalPosts = CommunityGeneralPosts::where([
                'community_id'      => $communityId,
                'academic_session'  => $academicSession
            ])->get();
            $examInfo = CommunityExams::where([
                'community_id'      => $communityId,
                'academic_session'  => $academicSession
            ])->get();
            $assignmentInfo = CommunityAssignments::where([
                'community_id'      => $communityId,
                'academic_session'  => $academicSession
            ])->get();
            $syncAbleFiles = $this->Lib->getSyncAbleFiles($communityId, $userId);

            $allNotifications = new Collection();

            $notifications = $this->Lib->getWebNotifications($userId ,$communityId);
            foreach ($notifications as $notification){
                $notification->type = 'web_notification';
                $allNotifications->push($notification);
            }

            foreach($generalPosts as $post){
                $post->type = 'general_post';
                $allNotifications->push($post);
            }
            foreach ($assignmentInfo as $ass){
                $ass->type = 'assignment';
                $allNotifications->push($ass);
            }
            foreach ($examInfo as $exam){
                $exam->type = 'exam';
                $allNotifications->push($exam);
            }
            foreach ($syncAbleFiles as $file){
                $file->type = ($file->file_ext == 'null') ? 'dir' : 'file';
                if ($file->file_ext != 'null'){
                    $allNotifications->push($file);
                }
            }

            $classRoutine = ClassRoutine::where('possessed_by_community', $communityId)
                ->where('academic_session', $academicSession)->get();
            if( count($classRoutine) > 0 ){
                $classRoutine = $classRoutine[0];
                $classRoutine->type = 'routine';
                $allNotifications->push($classRoutine);
            }

            return $allNotifications;
        }

        return response('Unauthorized Access', 401);
    }


    private function getAcademicSession($communityId, $userId){
        $allCommunitiesOfUser = AllCommunitiesOfUsers::where(['community_id' => $communityId])
            ->where('user_id', $userId)
            ->pluck('academic_session');
        if(count($allCommunitiesOfUser) > 0){
            return $allCommunitiesOfUser[0];
        }
        return null;
    }
}
