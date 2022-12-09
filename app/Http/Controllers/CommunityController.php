<?php

namespace App\Http\Controllers;


use App\AllCommunitiesOfUsers;
use App\ClassRoutine;
use App\Communities;
use App\CommunityAssignments;
use App\CommunityExams;
use App\CommunityGeneralPosts;
use App\CommunityNotices;
use App\CommunityPolls;
use App\ComPollOptions;
use App\CoursesTakenByStudents;
use App\DeptCourses;
use App\DeptTeachers;
use App\HelperClasses\PointBadges;
use App\Jobs\SendPushNotificationQueued;
use App\Library\Library;
use App\Mail\AssignmentPostMailingQueue;
use App\Mail\ExamPostMailingQueue;
use App\Mail\GeneralPostMailingQueue;
use App\Module\CommunityControllerProcessor;
use App\Module\Notification\NotificationProcessor;
use App\Notifications;
use App\OnesignalDeviceInformation;
use App\Profile;
use App\StudentRolls;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use App\Module\Noticeboard;

class CommunityController extends Controller
{
    public $Lib, $communityProcessor ;

    function __construct()
    {
        $this->middleware('auth');

        $this->Lib = new Library();
        $this->communityProcessor = new CommunityControllerProcessor();

    }


    public function index($community){
        return view('pages.communities', [
            'baseCommunity' => $community
        ]);
    }

    public function showExamDetails($examId){
        $examDetails = CommunityExams::where('id', $examId)->get();

        if( count($examDetails) == 1 ){
            return view('pages.exam_details', [
                'examDetails' => $examDetails[0],
                'user' => Auth::user(),
            ]);
        }
        return abort(404);
    }

    public function showAssignmentDetails($assignId){
        $assignDetails = CommunityAssignments::where('id', $assignId)->get();

        if( count($assignDetails) == 1 ){
            return view('pages.assignment_details', [
                'assignmentDetails' => $assignDetails[0],
                'user' => Auth::user(),
            ]);
        }
        return abort(404);
    }

    public function showAllCommunities(){
        $userId = Auth::id();
        $allCommunities = AllCommunitiesOfUsers::where('user_id', $userId)->get();
        $totalJoinedCommunities = count($allCommunities);

        $joinedCommunityDetails = array();
        foreach ($allCommunities as $row){
            $singleCommunity = Communities::where('id', $row->community_id)->get();

            //check weather user is the creator of a group, if yes then assign true
            //creating a property 'isCreator' and assigning boolean
            $singleCommunity[0]->isCreator = ($userId == $singleCommunity[0]->creator_id) ? true : false;

            array_push($joinedCommunityDetails, $singleCommunity);
            //Now $joinedCommunityDetails is a array of object(s) ($singleCommunity object)
        }

        return view('pages.communities', [
            'totalJoinedCommunities' => $totalJoinedCommunities,
            'joinedCommunityDetails' => array_reverse($joinedCommunityDetails), //order by time desc, i.e. latest created community will be shown first
            'user'                   => Auth::user(),
        ]);
    }


    public function showAllMembers(Request $request){
        $userId = Auth::id();
        $communityId = $this->Lib->getCurrentCommunityId();
        $session = $this->Lib->getAcademicSession($communityId);

        $allMembers = AllCommunitiesOfUsers::where('community_id', $communityId)
//            ->where('academic_session', $session)
            ->select('user_id', 'academic_session')
            ->whereNotIn('user_id', $this->Lib->getAdminsId())
            ->orderBy('user_id')->get();
        foreach ($allMembers as $member){
            $member->name = $this->Lib->getUserName($member->user_id);
        }

        return view('pages.show_all_members', [
            'allMembers'   => $allMembers
        ]);
    }


    public function changeRootCommunity(Request $request){
        $userId = Auth::id();
        $communityId = $request->input('root_community');

        User::where('id', $userId)->update(['root_community'=>$communityId]);
        echo 'updated';
    }


    public function fetchJoinToken(Request $request){
        $data = Communities::where('id', $request->input('communityId'))->pluck('join_token');
        $userId = Auth::id();
        if( count($data) > 0 ){
            $generatedLink = URL::to('/').'/community/join/'.$data[0]."/$userId";
            return $generatedLink;
            //return "<a href='"."$generatedLink"."' target='_blank'>".$generatedLink."</a>";
        }
        return 'unknown';
    }



    public function  showCreateCommunity(){
        $profile = Profile::where('user_id', Auth::id())->get();
        // force debugging !!! Horrible
        //default profile data should be inserted in RegisterUser.php not its not. so i am inserting default data here
        if(count($profile) == 0 ){
            $userId = Auth::id();
            $pp_url = "/assets/images/avatar.png";
            $data = [
                'user_id' => $userId,
                'phone' => "+88",
                'pp_url' => $pp_url,
                'reputation' => 1
            ];
            $profile = Profile::create($data);
        }
        //successfully inserted profile data
        //
        //echo "<h1>Under maintenance. We are coming back within 1 hour</h1>";
        return view('pages.create_community');
    }


    public function searchCommunity(Request $request){
        $existingCommunity = Communities::where('dept_name', $request['dept_name'])
            ->where('institute_name', $request['institute_name'])->get();

        $searchInfo = [
            'uid'  => Auth::id(),
            'univ' => $request['dept_name'],
            'dept' => $request['institute_name']
        ];
        Mail::send('messages.community-search-report', ['searchInfo' => $searchInfo ], function ($m) use ($searchInfo) {
            $m->from('choriyedao@gmail.com', 'LetsOrgan');
            $m->to("partho8181bd@gmail.com")->subject('Someone searched community');
        });


        if( count($existingCommunity) > 0 ){
            $existingCommunity = $existingCommunity[0];
            $allMembers = AllCommunitiesOfUsers::where('community_id', $existingCommunity->id)
                ->select('user_id', 'academic_session')->orderBy('user_id')->get();
            foreach ($allMembers as $member){
                $member->name = $this->Lib->getUserName($member->user_id);
            }

            $token = $existingCommunity->join_token;
            $userId = Auth::id();
            return view('messages.join_community_recommendation', [
                'existingCommunity' => $existingCommunity,
                'joinLink'          => URL::to('/')."/community/join/$token/$userId",
                'allMembers'        => $allMembers
            ]);
        }
        return view('messages.create_community_suggest', [
            'userName'  => Auth::user()->name
        ]);
    }

    public function doCreateCommunity(Request $request){
        $request['description'] = $request->input('description') ?: "No description added";
        $request['creator_id'] = Auth::id();
        $request['join_token'] = 'unknown' ;

        $existingCommunity = Communities::where('dept_name', $request['dept_name'])
            ->where('institute_name', $request['institute_name'])->get();
        if( count($existingCommunity) > 0 ){
            $allMembers = AllCommunitiesOfUsers::where('community_id', $existingCommunity[0]->id)
                ->select('user_id', 'academic_session')->orderBy('user_id')->get();
            foreach ($allMembers as $member){
                $member->name = $this->Lib->getUserName($member->user_id);
            }
            return view('messages.join_community_recommendation', [
                'existingCommunity' => $existingCommunity[0],
                'community_name'    => $existingCommunity[0]->community_name,
                'joinLink'          => '/community/join/'.$existingCommunity[0]->join_token,
                'creatorName'       => $this->Lib->getUserName( $existingCommunity[0]->creator_id ),
                'allMembers'        => $allMembers
            ]);
        }

        $createdCommunity = Communities::create($request->all());
        $communityId = $createdCommunity->id;

        Communities::where('id', $communityId)->update([
            'join_token'    => $this->Lib->generateToken(10).$communityId,
        ]);

        $this->Lib->setCurrentCommunityInCloud($communityId); //if record is not set already
        $this->Lib->insertRecommendedDirectoriesInCloud($communityId);

        //needed in view
        $userId = Auth::id();
        AllCommunitiesOfUsers::create([
            'user_id'       => $userId,
            'community_id'  => $communityId,
            'role'          => 'contributor'
        ]);

        return redirect('/community/all');
    }




    public function showEditCommunity($communityId){
        $userId = Auth::id();
        $Communities = Communities::where('id', $communityId)
            ->where('creator_id', $userId)
            ->get();
        $Communities = count($Communities) == 1 ? $Communities[0] : null ;

        if(isset($Communities)){
            return view('pages.edit_community', [
                'Communities' => $Communities,
                'communityId' => $communityId
            ]);
        }
        return abort(401);
    }




    public function doEditCommunity(Request $request, $communityId){
        $request['description'] = $request->input('description') ?: "No description added";
        $request['creator_id'] = Auth::id();

        Communities::find($communityId)->update($request->all());

        return redirect('/community/all');
    }


    public function joinRequestToCommunity($token){
        $tokenMatchedCommunity = Communities::where('join_token', $token)->get();
        $joinRequest = "rejected";
        $alreadyJoined = false;

        if( count($tokenMatchedCommunity) > 0 ){
            //means this join_token is valid
            $joinRequest = "accepted";
            $tokenMatchedCommunity = $tokenMatchedCommunity[0];

            $data = AllCommunitiesOfUsers::where('user_id', Auth::id())
                ->where('community_id', $tokenMatchedCommunity->id)->get();
            if (count($data) > 0 ){
                $alreadyJoined = true;
            }

            //now make him/her join that community
            if( ! $alreadyJoined ){
                AllCommunitiesOfUsers::insert([
                    'user_id'       => Auth::id(),
                    'community_id'  => $tokenMatchedCommunity->id,
                    'role'          => 'member',
                    'created_at'    => Carbon::now(),

                ]);
                //new PointBadges($userId, $communityId)->increaseReputationFor('community_join_self');
            }
            $this->Lib->setCurrentCommunityInCloud( $tokenMatchedCommunity->id ); //if record is not set already
        }

        $profileInfo = Profile::where('user_id', Auth::id())->get();
        if(count($profileInfo) == 0){
            Profile::insert([
                'user_id'       => Auth::id(),
                'phone'         => '+88',
                'pp_url'        => '/assets/images/avatar.png',
                'reputation'    => 1,
            ]);
        }

        $generatedLink = URL::to('/').'/community/join/'.$token;
        return view('pages.join_community', [
            'join_request'      => $joinRequest,
            'joinedCommunity'   => $tokenMatchedCommunity,
            'alreadyJoined'     => $alreadyJoined,
            'joined_url'        => $generatedLink
        ]);
    } // joinRequestToCommunity()


    public function showSingleCommunity($communityId){
        $userId = Auth::id();

        $allCommunitiesOfUser = AllCommunitiesOfUsers::where('user_id', $userId)
            ->where('community_id', $communityId)
            ->get();

        if( count($allCommunitiesOfUser) == 1 ){
            $this->Lib->setCurrentCommunityInCloud($communityId);

            $academicSession = $allCommunitiesOfUser[0]->academic_session;
            $com = Communities::where('id', $communityId)->get();

            //SELECT  p.* , u.name from community_general_posts p join users u WHERE p.community_id=1
            // and p.user_id = u.id
            $generalPosts = DB::table('community_general_posts as p')
                ->select('p.*', 'u.name as username')
                ->join('users as u', 'p.user_id', '=', 'u.id')
                ->where('p.community_id', $communityId)
                ->where('p.academic_session', $academicSession)
                ->orderBy('id', 'desc')
                ->get();


            //toSql()
//            echo DB::table('community_general_posts as p')
//                ->select('p.*', 'u.name')
//                ->join('users as u', 'p.user_id', '=', 'u.id')
//                ->where('p.community_id', $communityId)->toSql();

            $examPosts = DB::table('community_exams as exam')
                ->select('exam.*', 'u.name as username')
                ->join('users as u', 'exam.user_id', '=', 'u.id')
                ->where('exam.community_id', $communityId)
                ->where('exam.academic_session', $academicSession)
                ->orderBy('id', 'desc')
                ->get();

            $assignmentPosts = DB::table('community_assignments as a')
                ->select('a.*', 'u.name as username')
                ->join('users as u', 'a.user_id', '=', 'u.id')
                ->where('a.community_id', $communityId)
                ->where('a.academic_session', $academicSession)
                ->orderBy('id', 'desc')
                ->get();

/*-----------This feature will be implemented later----------*/
//            $polls = DB::table('community_polls as p')
//                ->select('p.*', 'u.name as username')
//                ->join('users as u', 'p.user_id', '=', 'u.id')
//                ->where('p.community_id', $communityId)
//                ->get();
/*-----------This feature will be implemented later----------*/


/*==========================This comment is permanently unused, But keep it================================*/
//            $polls = DB::table('community_polls as p')
//                ->select('p.*', 'u.name as username', 'op.option_id')
//                ->join('users as u', 'p.user_id', '=', 'u.id')
//                ->join('com_poll_options as op', 'p.id', '=', 'op.poll_id')
//                //->where('voter_id', '!=', 0)
//                ->where('p.community_id', $communityId)
//                ->get();


//            echo DB::table('community_polls as p')
//                ->select('p.*', 'u.name as username', 'op.option_id')
//                ->join('users as u', 'p.user_id', '=', 'u.id')
//                ->join('com_poll_options as op', 'p.id', '=', 'op.poll_id')
//                ->where('voter_id', '!=', 0)
//                ->where('p.community_id', $communityId)
//                ->toSql();
/*==========================This comment is permanently unused, But keep it================================*/

/*-----------This feature will be implemented later----------*/
//            $notices = DB::table('community_notices as n')
//                ->select('n.*', 'u.name as username')
//                ->join('users as u', 'n.user_id', '=', 'u.id')
//                ->where('n.community_id', $communityId)
//                ->orderBy('id', 'desc')
//                ->get();
/*-----------This feature will be implemented later----------*/


            $allPosts = $generalPosts->merge($examPosts);
            $allPosts = $allPosts->merge($assignmentPosts);

//            $allPosts = $allPosts->merge($polls);
//            $allPosts = $allPosts->merge($notices);

            $sorted = $allPosts->sortBy(function ($post){
                return $post->updated_at;
            });
            $sorted = $sorted->reverse(); //now collection is sorted by time desc
            $classRoutine = ClassRoutine::where('academic_session', $academicSession)
                ->where('possessed_by_community', $communityId)->get();
            $classRoutine = ( count($classRoutine) > 0 ) ? $classRoutine[0] :  null;

            return view('pages.single_community',[
                'academicSession'       => $academicSession,
                'classRoutine'          => $classRoutine,
                'community'             => $com[0],
                'posts'                 => $sorted,
                'user'                  => Auth::user(),
                'isAdmin'               => $this->Lib->isAdmin($userId, $communityId),
            ]);
        }

        return view('errors.acknowledgement');
    }


    public function getMailListToSendMail($communityId){
        $session = $this->Lib->getAcademicSession($communityId);
        $selectedUserId = AllCommunitiesOfUsers::where('community_id', $communityId)
            ->where('academic_session', $session)->select('user_id')->get();
        return User::whereIn('id', $selectedUserId)->pluck('email');
    }

    /* starting interactions of users in a community */

    //ajax
    public function saveGeneralPosts(Request $request){
        $this->Lib->setAcademicSession(Auth::id(), $this->Lib->getCurrentCommunityId(), $request['academic_session']);

        $userId = Auth::id();
        $request['user_id'] = $userId;
        $request['post_type'] = 'General Post';
        $communityId = $request->input('community_id');

        $savedGeneral = CommunityGeneralPosts::create($request->all());
        //unset($savedGeneral->user_id);
        $pointBadge = new PointBadges($userId, $communityId);
        $pointBadge->increaseReputationFor('add_general_post');
        $id = $savedGeneral->id;
        echo $id;

        Notifications::insert([
            'type'          => 'general_post',
            'readable_text' => $request->input('title'),
            'link'          => "/community/view/$communityId/#generalTitle$id",
            'json_object'   => $savedGeneral,
            'seen_by'       => "0",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        $mailList = $this->getMailListToSendMail($communityId);
        $post = [
            'title'     => $request['title'],
            'details'   => $request['details'],
            'postedBy'  => Auth::user()->name,
            'link'      => "https://letsorgan.com/community/view/$communityId/#generalTitle$id"
        ];
        Mail::bcc($mailList)->queue(new GeneralPostMailingQueue($post));

        $notification = new NotificationProcessor();
        $devices = $notification->deviceToReceivePushNotification();
        try{
            SendPushNotificationQueued::dispatch($devices, $request->input('title'), "https://letsorgan.com/community/view/$communityId/#generalTitle$id");
        }catch(\Exception $e){
        };

        // seen_by >> sample value: "1,34,43", which are user_id
    }

    //ajax
    public function saveExams(Request $request){
        $this->Lib->setAcademicSession(Auth::id(), $this->Lib->getCurrentCommunityId(), $request['academic_session']);

        $userId = Auth::id();
        $request['user_id'] = $userId;

        $savedExam = CommunityExams::create($request->all());
        $id = $savedExam->id;
        echo $id;

        $pointBadge = new PointBadges($userId, $this->Lib->getCurrentCommunityId());
        $pointBadge->increaseReputationFor('add_exam_post');

        $communityId = $request->input('community_id');
        $courseName = $request->input('course_name');
        $examDate = $request->input('exam_date');
        Notifications::insert([
            'type'          => 'exam',
            'readable_text' => "$courseName exam @ $examDate",
            'link'          => "/community/view/$communityId/#examCourseName$id",
            'json_object'   => $savedExam,
            'seen_by'       => "0",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        $mailList = $this->getMailListToSendMail($communityId);
        $examInfo = [
            'course'    => $courseName,
            'declaredAt'=> $request['declared_at'],
            'examDate'  => $request['exam_date'],
            'details'   => $request['details'],
            'link'      => "https://letsorgan.com/community/view/$communityId#examCourseName$id",
            'postedBy'  => Auth::user()->name
        ];
        Mail::bcc($mailList)->queue(new ExamPostMailingQueue($examInfo));

        $notification = new NotificationProcessor();
        $devices = $notification->deviceToReceivePushNotification();
        try{
            SendPushNotificationQueued::dispatch($devices, "$courseName Exam @ $examDate", "https://letsorgan.com/community/view/$communityId#examCourseName$id");
        }catch(\Exception $e){
        };

        // seen_by >> sample value: "1,34,43", which are user_id
    }

    //ajax
    public function saveAssignments(Request $request){
        $this->Lib->setAcademicSession(Auth::id(), $this->Lib->getCurrentCommunityId(), $request['academic_session']);

        $userId = Auth::id();
        $request['user_id'] = $userId;

        $savedAssign = CommunityAssignments::create($request->all());
        $id = $savedAssign->id;
        echo $id;

        $pointBadge = new PointBadges($userId, $this->Lib->getCurrentCommunityId());
        $pointBadge->increaseReputationFor('add_assignment_post');

        $communityId = $request->input('community_id');
        $courseName = $request->input('course_name');
        $deadline = $request->input('deadline');
        Notifications::insert([
            'type'          => 'assignment',
            'readable_text' => "$courseName assignment. Submit before $deadline",
            'link'          => "/community/view/$communityId/#assignment_container$id",
            'json_object'   => $savedAssign,
            'seen_by'       => "0",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        $mailList = $this->getMailListToSendMail($communityId);
        $assignmentInfo = [
            'course'    => $courseName,
            'givenAt'   => $request['given_date'],
            'deadline'  => $deadline,
            'details'   => 'v ndnfvdnnvdfkln kjldfn',
            'link'      => "https://letsorgan.com/community/view/$communityId#assignment_container$id",
            'postedBy'  => 'partho'
        ];
        Mail::bcc($mailList)->queue(new AssignmentPostMailingQueue($assignmentInfo));

        $notification = new NotificationProcessor();
        $devices = $notification->deviceToReceivePushNotification();
        try{
            SendPushNotificationQueued::dispatch($devices, "$courseName Assignment", "https://letsorgan.com/community/view/$communityId#assignment_container$id");
        }catch(\Exception $e){
        };

        // seen_by >> sample value: "1,34,43", which are user_id
    }

    //ajax
    public function savePolls(Request $request){
        $userId = Auth::id();

        $request['user_id'] = $userId;

        $pollQueryObj = CommunityPolls::create($request->all());
        $pollId = $pollQueryObj->id;

        $options = json_decode($request->input('options'));
        $optionsInsertObj = array();
        foreach($options as $index => $option){
            array_push($optionsInsertObj, [
                'poll_id' => $pollId,
                'option_id' => $index,
                'option_text' => $option,
                'voter_id' => 0,
                'created_at' => Carbon::now()
            ]);
        }

        ComPollOptions::insert($optionsInsertObj);

        echo $pollId;
    }


    //ajax
    public function saveNotices(Request $request){
        $userId = Auth::id();

        $request['user_id'] = $userId;

        $savedNote = CommunityNotices::create($request->all());

        echo $savedNote->id;
    }

    //ajax
    public function castVote(Request $request){
        $userId = Auth::id();
        $pollId = $request->input('poll_id');
        $optionId = $request->input('option_id');

        $rawData = ComPollOptions::where('poll_id', $pollId)
            ->where('voter_id', $userId)
            ->get();
        $alreadyVoted = ( count($rawData) == 1 ) ? true : false;

        if( $alreadyVoted ){
            //update vote
            //echo 'need to update';
            ComPollOptions::where('poll_id', $pollId)
                ->where('voter_id', $userId)
                ->update(['option_id' => $optionId]);
            echo 'vote updated';
        }
        else{
            //insert vote data
            ComPollOptions::insert([
                'poll_id' => $pollId,
                'option_id' => $optionId,
                'option_text' => 'null',
                'voter_id' => $userId
            ]);
            echo 'voted';
        }
    }

    //ajax
    public function deletePost($postType, $postId){
        $myLibrary = new Library();
        $tableName = $myLibrary->getTableNameByPostType($postType);

        DB::table($tableName)->where('id', $postId)->delete();
        echo 'deleted';
    }

    //ajax
    public function updatePost(Request $request){
        $postType = $request->input('postType');
        $postId = $request->input('postId');

        if( $postType == 'general' ){
            CommunityGeneralPosts::where('id', $postId)->update([
                'title'     => $request->input('title'),
                'details'   => $request->input('details')
            ]);
            echo 'updated';
        }
        elseif( $postType == 'exam' ){
            CommunityExams::where('id', $postId)->update([
                'course_name'       => $request->input('examCourseName'),
                'declared_at'       => $request->input('examDeclareDate'),
                'exam_date'         => $request->input('examDate'),
                'details'           => $request->input('examDetails'),
            ]);
            echo 'success';
        }
        elseif( $postType == 'assignment' ){
            CommunityAssignments::where('id', $postId)->update([
                'course_name'       => $request->input('courseName'),
                'given_date'        => $request->input('givenDate'),
                'deadline'          => $request->input('deadline'),
                'details'           => $request->input('details')
            ]);
            echo 'success';
        }
        elseif( $postType == 'poll' ){
            print_r($request->all());
        }
        elseif( $postType == 'notice' ){
            print_r($request->all());
        }
    }

    /* ended interactions of users in a community */


    public function showAddCourses(Request $request){
        $userId = Auth::id();
        $communityId = $_GET['comId'];
        if( ! $this->Lib->communityAccessibleTo($userId, $communityId) ){
            return abort(401);
        }

        $isAdmin = $this->Lib->isAdmin($userId, $communityId);
        $rollNo = $this->communityProcessor->getRollNo();
        return view('pages.show_add_courses', [
            'isAdmin'       => $isAdmin,
            'courses'       => $this->communityProcessor->getCourses(),
            'rollNo'        => $rollNo,
        ]);
    }// showAddTeachers()

    public function showAddTeachers(Request $request){
        $userId = Auth::id();
        $communityId = $_GET['comId'];
        if( ! $this->Lib->communityAccessibleTo($userId, $communityId) ){
            return abort(401);
        }

        $courses = DeptCourses::where('possessed_by_community', $communityId)->get();
        $teachers = DeptTeachers::where('possessed_by_community', $communityId)->pluck('teacher_name');
        return view('pages.show_add_teachers', [
            'courses'           => $courses,
            'teachers'          => $teachers,
            'currentCommunityId'=> $this->Lib->getCurrentCommunityId(),
        ]);
    } // showAddTeachers()


    //ajax
    public function saveCourses(Request $request){
        $userId = Auth::id();
        $communityId = $request->input('communityId');
        $courseInfo = json_decode( $request->input('courseInfo') ); // now courseInfo is a 2D php array

        $row = count($courseInfo);
        for($i=0; $i < $row; $i++){
            $id = $courseInfo[$i][0];
            $id = ctype_digit( $id ) ? $id : 0;
            $courseCode = $courseInfo[$i][1];
            $courseName = $courseInfo[$i][2];
            $aboutCourse = $courseInfo[$i][3];

            $dataSet = [
                'course_code'           => $courseCode,
                'course_name'           => $courseName,
                'about_course'          => $aboutCourse,
                'possessed_by_community'=> $communityId,
                'data_added_by'         => $userId
            ];
            $course = DeptCourses::where('id', $id)->get();
            if( count($course) == 0 ){
                if( ! $this->Lib->rowExist('dept_courses', 'course_code', $courseCode) ){
                    DeptCourses::insert( $dataSet );
                    $pointBadge = new PointBadges($userId, $communityId);
                    $pointBadge->increaseReputationFor('add_course');
                }else{
                    //to implement
                }
            }elseif (count($course) == 1 ){
                DeptCourses::where('id', $course[0]->id)->update( $dataSet );
            }else{
                echo 'error';
            }
        } //for
    } // saveCourses()

    public function saveRollNo(Request $request){
        $currentCommunityId = $this->Lib->getCurrentCommunityId();
        $userId = Auth::id();
        $rollNo = new rollNo($userId, $currentCommunityId, $request['rollNoNumeric'], $request['rollNoFullForm']);
        if( $rollNo->existInCommunity() ){
            //echo "exist ";
            if( $rollNo->isMine() ){
                //echo " and roll is mine. so update";
                StudentRolls::where('user_id', $userId)->update([
                    'roll_numeric'      => $request['rollNoNumeric'],
                    'roll_full_form'    => $request['rollNoFullForm'],
                    'community_id'      => $currentCommunityId,
                    'academic_session'  => $this->Lib->getAcademicSession($currentCommunityId),
                ]);
                echo 'saved';
            }else{
                echo $this->Lib->getUserName($rollNo->alreadyEnteredBy).' already entered this roll';
            }
        }
        else{
            //echo "roll doesn't exist";
            $myIdExist = StudentRolls::where('user_id', $userId)->get();
            if(count($myIdExist) > 0){
                StudentRolls::where('user_id', $userId)->update([
                    'roll_numeric'      => $request['rollNoNumeric'],
                    'roll_full_form'    => $request['rollNoFullForm'],
                    'community_id'      => $currentCommunityId,
                    'academic_session'  => $this->Lib->getAcademicSession($currentCommunityId),
                ]);
                echo 'saved';
                //echo "but my id exist. ie its my roll . so update";
            }else{
                StudentRolls::insert([
                    'roll_numeric'      => $request['rollNoNumeric'],
                    'roll_full_form'    => $request['rollNoFullForm'],
                    'user_id'           => $userId,
                    'community_id'      => $currentCommunityId,
                    'academic_session'  => $this->Lib->getAcademicSession($currentCommunityId),
                ]);
                echo 'saved';
                //echo "so inserted";
            }
        }
    }



    public function joinCourse(Request $request){
        $courseId = $request['courseId'];
        $userIdOfTeacher = $request['userIdOfTeacher'];
        if($this->communityProcessor->userHasJoinedThisCourse($courseId)){
            echo "already joined";
        }else{
            $takenCourse = CoursesTakenByStudents::firstOrNew([
                'course_id'             => $courseId,
                'user_id_of_student'    => Auth::id(),
            ]);
            $takenCourse->user_id_of_course_teacher = $userIdOfTeacher;
            $takenCourse->save();
            echo "successfully joined this course";
        }
    }

    public function leaveCourse(Request $request){
        $courseId = $request['courseId'];
        CoursesTakenByStudents::where('course_id', $courseId)->delete();
        echo 'success';
    }

    //delete course by teacher who created this course
    public function deleteCourse(Request $request){
        DeptCourses::find($request->input('id'))->delete();
    }

    public function saveTeachers(Request $request){
        try{
            $userId = Auth::id();
            $courseId = $request->input('courseId');
            $year = $request->input('year');
            $teacherName = $request->input('teacherName');
            $communityId = $this->Lib->getCurrentCommunityId();

            $teacher = DeptTeachers::firstOrNew(['possessed_by_community' => $communityId, 'year' => $year, 'course_id' => $courseId]);
            //$teacher->course_id = $courseId;
            //$teacher->year = $year;
            $teacher->teacher_name = $teacherName;
            //$teacher->possessed_by_community = $communityId;
            $teacher->data_added_by = $userId;
            $teacher->save();

            $pointBadge = new PointBadges($userId, $communityId);
            $pointBadge->increaseReputationFor('add_teacher');

            echo 'success';
        }catch(Exception $e){

        }
    }


    public function updateClassRoutine(Request $request){
        try{
            $userId = Auth::id();
            $academicSession = $request->input('academic_session');
            $communityId = $request->input('possessed_by_community');
            $fileObj = $request->file('cls_routine_file');
            $fileName = str_replace(' ', '_', $fileObj->getClientOriginalName());
            $filePath = 'my-cloud/'.$fileName;

            Storage::disk('s3')->put($filePath, $fileObj, 'public');

            $routine = ClassRoutine::firstOrNew([
                'academic_session'      => $academicSession,
                'possessed_by_community'=> $communityId
            ]);
            $routine->title = $request->input('title');
            $routine->file_path = $filePath.'/'.$fileObj->hashName();
            $routine->uploaded_by = $userId;
            $routine->possessed_by_community = $communityId;
            $routine->academic_session = $academicSession;
            $routine->updated_at = Carbon::now();
            $routine->save();

            $pointBadge = new PointBadges($userId, $communityId);
            $pointBadge->increaseReputationFor('add_class_routine');

            return redirect('/community/view/'.$communityId);
        }catch (Exception $e){
            return "Error occurred. You can try again or contact support";
        }
    }
}


class rollNo{
    private $userId, $communityId, $rollNumeric, $rollFullForm;
    private $Lib;
    public $alreadyEnteredBy;

    function __construct($userId, $communityId, $rollNumeric, $rollFullForm)
    {
        $this->userId = $userId;
        $this->communityId = $communityId;
        $this->rollNumeric = $rollNumeric;
        $this->rollFullForm = $rollFullForm;

        $this->Lib = new Library();

        return $this;
    }

    function existInCommunity(){
        $rowCount = StudentRolls::where('community_id', $this->communityId)
            ->where('academic_session', $this->Lib->getAcademicSession($this->communityId))
            ->where('roll_numeric', $this->rollNumeric)
            ->get();
        //echo "-----".count($rowCount)."-----";
        if(count($rowCount) > 0){
            $this->alreadyEnteredBy = $rowCount[0]->user_id;
            return true;
        }
        return false;
    }

    function isMine(){
        $rowCount = StudentRolls::where('user_id', $this->userId)
            ->where('community_id', $this->communityId)
            ->where('academic_session', $this->Lib->getAcademicSession($this->communityId))
            ->where('roll_numeric', $this->rollNumeric)
            ->get();
        //print_r($rowCount);
        //echo "-----".count($rowCount)."-----";
        return count($rowCount) > 0;
    }
}
