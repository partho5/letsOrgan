<?php

namespace App\Library;

use App\AllCommunitiesOfUsers;
use App\AutoSyncableDirectories;
use App\Books;
use App\Communities;
use App\CommunityGeneralPosts;
use App\ComPollOptions;
use App\CurrentCommunityInMyCloud;
use App\Mail\FileUploadedMailingQueue;
use App\MobileClientInfo;
use App\Module\Notification\NotificationProcessor;
use App\MyCloud;
use App\Notifications;
use App\NotificationSeenRecords;
use App\OtherFiles;
use App\UpDownVoteRecords;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use DB;

class Library{
    function __construct()
    {
        if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
            // Ignores notices and reports all other kinds... and warnings
            error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
            // error_reporting(E_ALL ^ E_WARNING); // Maybe this is enough
        }
    }


    public function getAdminsId(){
        // 1 partho, 3 sourav, 4 partho,
        return [1,3,4];
    }
    public function getAdminsIP(){
        // partho  >>
        return ['103.88.24.9', '127.0.0.1'];
    }

    public function fileNameExistInCloud($filename){
        $retrieved = MyCloud::where('name', $filename)->get();

        return count($retrieved) >0 ? true : false;
    }

    public function htmlifyFullDirUrl($fullDirUrl){
        $parts = explode('/', $fullDirUrl);
        $htmlStr = "";
        foreach ($parts as $part){
            if( $part ){
                //if $part is not empty
                $htmlStr = $htmlStr.'<span tabindex="1" class="dirPartInBar">'.$part.'</span>';
            }
        }
        return $htmlStr;
    }

    public function fileSizeReadAbleFormat($fileSize){
        if( $fileSize > 1024*1024 ){
            //in GB range
            return round($fileSize/1024/1024, 2).' GB';
        }elseif( $fileSize >1024 ){
            //in MB range
            return round($fileSize/1024, 2).' MB';
        }else{
            //in KB trange
            return $fileSize.' KB';
        }
    }

    public function getFileType($cloudId){
        //find in 'books' table
        $retrieved = Books::where('cloud_id', $cloudId)->get();
        if( count($retrieved) ==1 ){
            return 'book';
        }
        //otherwise it's a other_file
        return 'other_file';
    }

    public function pollCheckedStatus($pollId, $optionId){
        $checkedOptionId = ComPollOptions::where('poll_id', $pollId)
            ->where('voter_id', '!=', 0)
            ->pluck('option_id');
        if( count($checkedOptionId) == 1 ){
            return ($checkedOptionId[0] == $optionId) ? 'checked' : '' ;
        }
        return '';
    }

    public function totalCastedVote($pollId, $optionId){
        //SELECT count(*) FROM `com_poll_options` WHERE  poll_id =8 and option_id=1 and voter_id !=0
        $totalVote = ComPollOptions::where('poll_id', $pollId)
            ->where('option_id', $optionId)
            ->where('voter_id', '!=', 0)
            ->get();
        $totalVote = count($totalVote);
        return ( $totalVote > 0 ) ?: 0;
    }

    public  function getFileCategoryByCloudId($cloudId){
        $rawData = Books::where('cloud_id', $cloudId)->get();
        if( count($rawData) ==1 ){
            return 'book';
        }
        $rawData = OtherFiles::where('cloud_id', $cloudId)->get();
        if( count($rawData) == 1 ){
            return 'otherFile';
        }
        return '';
    }

    public function getTableNameByPostType($postType){
        switch ($postType){
            case 'general' :
                return 'community_general_posts';
            case 'exam' :
                return 'community_exams';
            case 'assignment' :
                return 'community_assignments';
            case 'poll' :
                return 'community_polls';
            case 'notice' :
                return 'community_notices';
        }
    }

    public function alreadyVoted($qAnsType, $postId, $voteType){
        $record = UpDownVoteRecords::where('post_id', $postId)
            ->where('q_ans_type', $qAnsType)
            ->where('vote_type', $voteType)
            ->where('user_id', Auth::id() )
            ->get();
        return count($record) > 0 ? true : false ;
    }

    public function rowExist($tableName, $columnName, $columnVal){
        $record = DB::table($tableName)->where($columnName, $columnVal)->get();
        if( count($record) >0 ){
            return true;
        }
        return false;
    }

    public function mobileDesktopClientTokenExist($communityId){
        $userId = Auth::id();
        $mobile = MobileClientInfo::where('user_id', $userId)->where('community_id', $communityId)->get();
        if( count($mobile) > 0 ){
            return true;
        }
        return false;
    }

    public function getUserName($userId){
        $data = DB::table('users')->where('id', $userId)->pluck('name');
        if( count($data) == 1 ){
            return $data[0];
        }
        return 'Unknown';
    }




    function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }


    public function getAcademicTerm($communityId){
        $term = Communities::where('id', $communityId)->pluck('academic_term');
        if( count($term) >0 ){
            return $term[0];
        }else{
            return 'Semester'; //default value
        }
    }

    public function generate_N_AcademicSession($howMany){
        $curYear = date('Y');
        $sessions = array();
        for ($year = $curYear-1 ; $year>= $curYear-$howMany; $year--){
            array_push($sessions, "$year - ".($year+1) ) ;
        }
        return $sessions;
    }

    public function generateRecommendedDirectories($communityId){
        $directories = array();
        $years = [
            '1st Year',
            '2nd Year',
            '3rd Year',
            '4th Year',
            'Masters'
        ];
        $semesters = [
            '1st Year 1st Semester',
            '1st Year 2nd Semester',
            '2nd Year 1st Semester',
            '2nd Year 2nd Semester',
            '3rd Year 1st Semester',
            '3rd Year 2nd Semester',
            '4th Year 1st Semester',
            '4th Year 2nd Semester',
            'Masters 1st Semester',
            'Masters 2nd Semester',
        ];
        $demoCourses = [
            'Course Name 1',
            'Course Name 2',
            'Course Name 3',
            'Course Name 4',
            'Course Name 5',
        ];
        $resourceType = [
            'Books',
            'Slides',
            'Lab Reports',
            'Assignments',
            'Questions',
            'Softwares',
            'Others'
        ];
        $sessions = $this->generate_N_AcademicSession(3);
        $yearOrSemesters = $this->getAcademicTerm($communityId) == 'Year' ? $years : $semesters ;

        foreach ($yearOrSemesters as $yearOrSemester){
            array_push($directories, [
                'user_id'               => Auth::id(),
                'name'                  => $yearOrSemester,
                'parent_dir'            => '/',
                'is_root'               => 1,
                'full_dir_url'          => '/',
                'file_ext'              => 'null',
                'access_code'           => 2, // shared with 'particular community'
                'possessed_by_community'=> $communityId,
                'file_size'             => 0,
                'soft_delete'           => 0,
                'permanent_delete'      => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString()
            ]);
            foreach ($demoCourses as $courseName){
                array_push($directories, [
                    'user_id'               => Auth::id(),
                    'name'                  => $courseName,
                    'parent_dir'            => $yearOrSemester,
                    'is_root'               => 0,
                    'full_dir_url'          => "/$yearOrSemester",
                    'file_ext'              => 'null',
                    'access_code'           => 2, // shared with 'particular community'
                    'possessed_by_community'=> $communityId,
                    'file_size'             => 0,
                    'soft_delete'           => 0,
                    'permanent_delete'      => 0,
                    'created_at'            => Carbon::now()->toDateTimeString(),
                    'updated_at'            => Carbon::now()->toDateTimeString()
                ]);
                foreach ($resourceType as $resourceItem){
                    array_push($directories, [
                        'user_id'               => Auth::id(),
                        'name'                  => $resourceItem,
                        'parent_dir'            => $courseName,
                        'is_root'               => 0,
                        'full_dir_url'          => "/$yearOrSemester/$courseName",
                        'file_ext'              => 'null',
                        'access_code'           => 2, // shared with 'particular community'
                        'possessed_by_community'=> $communityId,
                        'file_size'             => 0,
                        'soft_delete'           => 0,
                        'permanent_delete'      => 0,
                        'created_at'            => Carbon::now()->toDateTimeString(),
                        'updated_at'            => Carbon::now()->toDateTimeString()
                    ]);
                    foreach ($sessions as $session){
                        array_push($directories, [
                            'user_id'               => Auth::id(),
                            'name'                  => $session,
                            'parent_dir'            => $resourceItem,
                            'is_root'               => 0,
                            'full_dir_url'          => "/$yearOrSemester/$courseName/$resourceItem",
                            'file_ext'              => 'null',
                            'access_code'           => 2, // shared with 'particular community'
                            'possessed_by_community'=> $communityId,
                            'file_size'             => 0,
                            'soft_delete'           => 0,
                            'permanent_delete'      => 0,
                            'created_at'            => Carbon::now()->toDateTimeString(),
                            'updated_at'            => Carbon::now()->toDateTimeString()
                        ]);
                    }
                }
            }
        }

        return $directories;
    } // generateRecommendedDirectories()

    public function insertRecommendedDirectoriesInCloud($communityId){
        DB::table('my_cloud')->insert( $this->generateRecommendedDirectories($communityId) );
    }

    function generateToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
        }

        return $token;
    }


    public function insertCloudUploadNotification($cloudId){
        $userName = $this->getUserName(Auth::id());
        $cloudInfo = MyCloud::find($cloudId);
        $comId = "$cloudInfo->possessed_by_community";
        $cloudInfo->community_id = $comId;
        $cloudInfo['academic_session'] = $this->getAcademicSession($cloudInfo->possessed_by_community);
        Notifications::insert([
            'type'          => 'cloud_upload',
            'readable_text' => "$userName uploaded a file in $cloudInfo->full_dir_url",
            'link'          => "/users/cloud/view/$cloudId",
            'json_object'   => $cloudInfo,
            'seen_by'       => '0',
            'created_at'    => Carbon::now()->toDateTimeString(),
            'updated_at'    => Carbon::now()->toDateTimeString()
        ]);

        $notificationProcessor = new NotificationProcessor();
        $mailList = $notificationProcessor->getMailListToSendMail($comId);

        $link = "https://letsorgan.com/users/cloud/view/$cloudId";
        $uploadInfo = [
            'title'     => "New File Upload !",
            'details'   => "$userName uploaded a file in <a href='".($link)."'><b>$cloudInfo->full_dir_url</b></a>",
            'postedBy'  => $userName,
            'link'      => $link,
        ];
        Mail::bcc($mailList)->queue(new FileUploadedMailingQueue($uploadInfo));
    }

    public function getCommunityCount($userId){
        $all = AllCommunitiesOfUsers::where('user_id', $userId)->pluck('id');
        return count($all);
    }

    public function setCurrentCommunityInCloud($communityId){
        $curCommunity = CurrentCommunityInMyCloud::firstOrNew(['user_id' => Auth::id()]);
        $curCommunity->community_id = $communityId;
        $curCommunity->save();
    }

    public function getCurrentCommunityId(){
        if( is_null(Auth::id()) ){
            return 0;
        }
        $data = CurrentCommunityInMyCloud::where('user_id', Auth::id())->pluck('community_id');
        if ( count($data) > 0 ){
            return $data[0];
        }
        return 0; //though no community with id 0
    }

    public function getCurrentCommunityIdOfUser($userId){
        $data = CurrentCommunityInMyCloud::where('user_id', $userId)->pluck('community_id');
        if ( count($data) > 0 ){
            return $data[0];
        }
        return 0; //though no community with id 0
    }


    public function getInstituteInfo(){
        $instituteInfo = Communities::where('id', $this->getCurrentCommunityId())->select('institute_name', 'dept_name')->get();
        return $instituteInfo;
    }

    public function getInstituteInfoOfUserId($userId){
        $instituteInfo = Communities::where('id', $this->getCurrentCommunityIdOfUser($userId))->select('institute_name', 'dept_name')->get();
        return $instituteInfo;
    }

    public function getJoiningLink($communityId){
        $data = Communities::where('id', $communityId)->pluck('join_token');
        $userId = Auth::id();
        if( count($data) > 0 ){
            $generatedLink = URL::to('/').'/community/join/'.$data[0]."/$userId";
            return $generatedLink;
            //return "<a href='"."$generatedLink"."' target='_blank'>".$generatedLink."</a>";
        }
        return 'unknown';
    }

    public function getCommunityName($comId){
        $com = Communities::where('id', $comId)->pluck('community_name');
        if( count($com) > 0 ){
            return $com[0];
        }
        return ".....Error Occurred. Reload page";
    }

    public function getUserRoleInCloud($communityId){
        $data = AllCommunitiesOfUsers::where('user_id', Auth::id() )->where('community_id', $communityId)->pluck('role');
        if( count($data) >0 ){
            return $data[0];
        }
        return 'member';
    }

//    public function getCurrentCommunity(){
//        $userId = Auth::id();
//        $currentCommunityId = CurrentCommunityInMyCloud::where('user_id', $userId)->pluck('community_id');
//        if( count($currentCommunityId) > 0 ){
//            $currentCommunityId = $currentCommunityId[0];
//        }
//        else{
//            $currentCommunityId = 0;
//        }
//        return $currentCommunityId;
//    }

    public function getTotalContributors($communityId){
        $data = AllCommunitiesOfUsers::where('community_id', $communityId)->where('role', 'contributor')->get();
        return count($data);
    }

    public function increaseTotalDownloadBy1($tableName, $cloudId){
        DB::table($tableName)->where('cloud_id', $cloudId)->increment('total_download');
    }

    public function getTeacherDescriptionExample($givenNum){
        $example = array(
            "He/she mostly sets mathematical questions in final exam. Or something that describes him/her",
            "She likes more descriptive answer in answer script, even if actual answer might be short",
            "He gives good marks etc.. like this"
        );
        return $example[ ($givenNum % count($example) ) ];
    }


    public function getSyncAbleFiles($community_id, $userId){
        $dirIdToSync = AutoSyncableDirectories::where('user_id', $userId)
            ->where('community_id', $community_id)->pluck('cloud_id');
        $allowedToSync = new Collection();
        if( count($dirIdToSync) > 0 ){
            $dirIdToSync = $dirIdToSync[0];
            $allowedToSync = MyCloud::where('id', $dirIdToSync)->select('name')->get();
            $allowedToSync = $allowedToSync[0];
        }
        $filesToSync = MyCloud::where('possessed_by_community', $community_id)
            ->where('name', $allowedToSync->name)
            ->orWhere('full_dir_url', 'like', "/$allowedToSync->name/%")
            ->where('soft_delete', 0)->get();

        foreach ($filesToSync as $file){
            $remotePath = $this->getRemotePath($file->id);
            if( ! is_null($remotePath) ){
                $file->remote_path = $remotePath;
            }
        }
        return $filesToSync;
    }

    public function getDirIdToSync($communityId, $rootDirs){
        $userId = Auth::id();
        $dirIdToSync = AutoSyncableDirectories::where('user_id', $userId)
            ->where('community_id', $communityId)->pluck('cloud_id');
        if( count($dirIdToSync) > 0 ){
            $dirIdToSync = $dirIdToSync[0];
        }else{
            $dirIdToSync = $rootDirs[0]->id; // 0th value as default value
            AutoSyncableDirectories::create([
                'user_id'       => $userId,
                'community_id'  => $communityId,
                'cloud_id'      => $dirIdToSync,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }
        return $dirIdToSync;
    }

    public function getRemotePath($cloudId){
        //first check if its in 'book' table
        $data = Books::where('cloud_id', $cloudId)->get();
        if( count($data) == 1 ){
            return $data[0]->file_path;
        }
        else{
            //not in 'books', so may be in 'other_files' table
            $data = OtherFiles::where('cloud_id', $cloudId)->get();
            if( count($data) == 1 ){
                return $data[0]->file_path;
            }else{
                //not in 'other_files' too. so it certainly doesn't exist
                //return response('Not Found', 404);
                return null;
            }
        }
    } // getRemotePath()


    public function communityAccessibleTo($userId, $communityId){
        $userCommunities = AllCommunitiesOfUsers::where('user_id', $userId)->where('community_id', $communityId)->first();
        if( count($userCommunities) > 0 ){
            return true;
        }
        return false;
    }


    public function getYoutubeVideoId($url){
        if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $id)) {
            $values = $id[1];
        } else if (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $id)) {
            $values = $id[1];
        } else if (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $url, $id)) {
            $values = $id[1];
        } else if (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $id)) {
            $values = $id[1];
        }
        else if (preg_match('/youtube\.com\/verify_age\?next_url=\/watch%3Fv%3D([^\&\?\/]+)/', $url, $id)) {
            $values = $id[1];
        } else {
            // not an youtube video
        }
        return $values;
    } // getYoutubeVideoId()

    public function getAcademicSession($communityId){
        $allCommunitiesOfUser = AllCommunitiesOfUsers::where(['community_id' => $communityId])
            ->where('user_id', Auth::id())
            ->pluck('academic_session');
        if(count($allCommunitiesOfUser) > 0){
            return $allCommunitiesOfUser[0];
        }
        return null;
    }

    public function getAcademicSessionOfUser($userId, $communityId){
        $allCommunitiesOfUser = AllCommunitiesOfUsers::where(['community_id' => $communityId])
            ->where('user_id', $userId)
            ->pluck('academic_session');
        if(count($allCommunitiesOfUser) > 0){
            return $allCommunitiesOfUser[0];
        }
        return null;
    }

    public function setAcademicSession($userId, $communityId, $academicSession){
        AllCommunitiesOfUsers::where('user_id', $userId)->where('community_id', $communityId)
            ->update(['academic_session' => $academicSession]);
    }


    public function lastSeenNotificationId($userId, $communityId){
        $notifics = NotificationSeenRecords::where(['user_id' => $userId, 'community_id' => $communityId])
            ->orderBy('id', 'desc')->first();
        //dd($notifics);
        if( count($notifics) > 0 ){
            return $notifics->notification_id;
        }
        return 0;
    }

    public function getUnreadNotifications($userId ,$communityId){
        $academicSession = $this->getAcademicSession($communityId);
        $lastReceivedId = $this->lastSeenNotificationId($userId, $communityId);

        // this commented block uses json_extract() function, as mariadb 10.1.x doesn't support json_extract() function ; this is apparently commented out
//        $unreadNotifications = Notifications::where(['json_object->community_id' => DB::raw("\"$communityId\"") ])
//            ->where(['json_object->academic_session' => DB::raw("\"$academicSession\"") ])
//            ->where('id', '>', $lastReceivedId)
//            ->get();

        $notifications = Notifications::where('json_object', 'like', DB::raw("'%\"community_id\":\"$communityId\",%\"academic_session\":\"$academicSession\"%'") )
            ->where('id', '>', $lastReceivedId)->orderBy('id', 'desc')->get();

        return $notifications;
    }

    public function getReadNotifications($userId ,$communityId, $howManyRowToFetch){
        $academicSession = $this->getAcademicSession($communityId);
        $lastReceivedId = $this->lastSeenNotificationId($userId, $communityId);

        // this commented block uses json_extract() function, as mariadb 10.1.x doesn't support json_extract() function ; this is apparently commented out

//        $notifications = Notifications::where(['json_object->community_id' => DB::raw("\"$communityId\"") ])
//            ->where(['json_object->academic_session' => DB::raw("\"$academicSession\"") ])
//            ->where('id', '<=', $lastReceivedId)->orderBy('id', 'desc')->get()->take($howManyRowToFetch);

        $notifications = Notifications::where('json_object', 'like', DB::raw("'%\"community_id\":\"$communityId\",%\"academic_session\":\"$academicSession\"%'") )
            ->where('id', '<=', $lastReceivedId)->orderBy('id', 'desc')->get()->take($howManyRowToFetch);
        return $notifications;
    }

    public function getAllNotifications($userId ,$communityId){
        $lastReceivedId = $this->lastSeenNotificationId($userId, $communityId);

        $academicSession = AllCommunitiesOfUsers::where(['community_id' => $communityId])
            ->where('user_id', $userId)
            ->pluck('academic_session');
        if(count($academicSession) > 0){
            $academicSession = $academicSession[0];
        }

        $all = $notifications = Notifications::where('json_object', 'like', DB::raw("'%\"community_id\":\"$communityId\",%\"academic_session\":\"$academicSession\"%'") )
            ->where('id', '<=', $lastReceivedId)->orderBy('id', 'desc')->get();
        return $all;
    }

    public function getWebNotifications($userId ,$communityId){
        $lastReceivedId = $this->lastSeenNotificationId($userId, $communityId);

        $academicSession = AllCommunitiesOfUsers::where(['community_id' => $communityId])
            ->where('user_id', $userId)
            ->pluck('academic_session');
        if(count($academicSession) > 0){
            $academicSession = $academicSession[0];
        }

        $all = $notifications = Notifications::where('json_object', 'like', DB::raw("'%\"community_id\":\"$communityId\",%\"academic_session\":\"$academicSession\"%'") )
            ->where('id', '<=', $lastReceivedId)->orderBy('id', 'desc')->get();
        return $all;
    }

    public function demo_ExamData($communityId, $session){
        $data = [
            [
                'user_id'           => Auth::id(),
                'community_id'      => $communityId,
                'academic_session'  => $session,
                'course_name'       => "Demo 2101",
                'declared_at'       => Carbon::now()->subDays(1),
                'exam_date'         => Carbon::now()->addDays(15),
                'details'           => "This is some demo exam Details",
                'is_anonymous'      => 0,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            [
                'user_id'           => Auth::id(),
                'community_id'      => $communityId,
                'academic_session'  => $session,
                'course_name'       => "Demo 3204",
                'declared_at'       => Carbon::now(),
                'exam_date'         => Carbon::now()->addDays(20),
                'details'           => "This is some demo exam Details",
                'is_anonymous'      => 1,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            [
                'user_id'           => Auth::id(),
                'community_id'      => $communityId,
                'academic_session'  => $session,
                'course_name'       => "Demo 1203",
                'declared_at'       => Carbon::now()->subDays(4),
                'exam_date'         => Carbon::now()->addDays(17),
                'details'           => "This is some demo exam Details",
                'is_anonymous'      => 0,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
        ];
        return $data;
    } // demo_ExamData()


    public function demo_AssignmentData($communityId, $session){
        $data = [
            [
                'user_id'           => Auth::id(),
                'community_id'      => $communityId,
                'academic_session'  => $session,
                'course_name'       => "Demo 1101",
                'given_date'        => Carbon::now()->subDays(3),
                'deadline'          => Carbon::now()->addDays(15),
                'details'           => "This is some demo description",
                'is_anonymous'      => 0,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            [
                'user_id'           => Auth::id(),
                'community_id'      => $communityId,
                'academic_session'  => $session,
                'course_name'       => "Demo 4202",
                'given_date'       => Carbon::now()->subDays(1),
                'deadline'         => Carbon::now()->addDays(20),
                'details'           => "This is some demo description",
                'is_anonymous'      => 1,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
            [
                'user_id'           => Auth::id(),
                'community_id'      => $communityId,
                'academic_session'  => $session,
                'course_name'       => "Demo 2206",
                'given_date'       => Carbon::now(),
                'deadline'         => Carbon::now()->addDays(17),
                'details'           => "This is some demo description",
                'is_anonymous'      => 0,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now()
            ],
        ];
        return $data;
    } // demo_AssignmentData()


    public function demo_UploadRequestData($communityId){
        $data = [
            [
                'user_id'               => 0, // 0 means no user, so cannt be deleted by any user
                'file_name'             => "Calculus solution manual by Haward Anton",
                'file_category'         => 'Book',
                'details'               => "This is some demo details. This is some demo details",
                'shared_with_community' => $communityId,
                'uploaded_by'           => 0,
                'created_at'            => Carbon::now()->subDays(4),
                'updated_at'            => Carbon::now()
            ],
            [
                'user_id'               => 0,
                'file_name'             => "Circuit Analysis by Sadiku",
                'file_category'         => 'Book',
                'details'               => "This is some demo details. This is some demo details",
                'shared_with_community' => $communityId,
                'uploaded_by'           => Auth::id(),
                'created_at'            => Carbon::now()->subDays(4),
                'updated_at'            => Carbon::now()
            ],
            [
                'user_id'               => 0,
                'file_name'             => "Demo File Name",
                'file_category'         => 'Unknown Category',
                'details'               => "This is some demo details. This is some demo details",
                'shared_with_community' => $communityId,
                'uploaded_by'           => 0,
                'created_at'            => Carbon::now()->subDays(4),
                'updated_at'            => Carbon::now()
            ]
        ];
        return $data;
    } // demo_UploadRequestData()


    public function demo_upRequestResponse(){
        $data = [
            [
                'request_id'    => 0,
                'uploaded_by'   => 0,
                'file_name'     => "Calculus solution manual by Haward Anton",
                'file_path'     => "",
                'soft_delete'   => 0
            ],
            [
                'request_id'    => 0,
                'uploaded_by'   => 0,
                'file_name'     => "Data Structures and Algorithm by Reigngold",
                'file_path'     => "",
                'soft_delete'   => 0
            ],
            [
                'request_id'    => 0,
                'uploaded_by'   => 0,
                'file_name'     => "This is a example file",
                'file_path'     => "",
                'soft_delete'   => 0
            ]
        ];
        return $data;
    } // demo_upRequestResponse()


    public function demo_questions(){
        $data = [
            [
                'user_id'   => 0,
                'question'  => "What is cloud storage ?",
                'details'   => "Recently I see this word frequently. Searched google but still there's some confusion, How normal storage differs from cloud storage?",
                'topic'     => "",
                'upvote'    => 7,
                'downvote'  => 2,
                'created_at'=> Carbon::now()->subDays(4),
                'updated_at'=> Carbon::now()->subDays(2)
            ],
            [
                'user_id'   => 0,
                'question'  => "What is Artificial Intelligence, and what is Machine Learning?",
                'details'   => "I read some articles about Artificial Intelligence and Machine Learning. I couldn't differentiate between them. Aren't those of same concept? Can you please clarify ? And please some links for further study.",
                'topic'     => "",
                'upvote'    => 13,
                'downvote'  => 1,
                'created_at'=> Carbon::now()->subDays(16),
                'updated_at'=> Carbon::now()->subDays(3)
            ],
        ];
        return $data;
    } // demo_questions()


    public function modifyFullDirUrl($existing, $findWhat, $replaceWith){
        return str_replace_first($findWhat, $replaceWith, $existing);
    }


    public function isAdmin($userId, $communityId){
        $adminIds = (new \App\Library\rawData())->adminIds();
        $adminIds = isset($adminIds[$communityId]) ? $adminIds[$communityId] : [] ;
        if( empty($adminIds) ){
            //if empty array, ie. admin id is not set for this community. So all users should have admin privilege by default
            //so
            return true;
        }else{
            return in_array($userId, $adminIds) ? true : false;
        }
    }
}

?>