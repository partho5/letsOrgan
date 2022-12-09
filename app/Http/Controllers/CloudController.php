<?php

namespace App\Http\Controllers;

use App\AllCommunitiesOfUsers;
use App\Books;
use App\Communities;
use App\CurrentCommunityInMyCloud;
use App\DeptCourses;
use App\DesktopClientInfo;
use App\ExamQuestions;
use App\HelperClasses\PointBadges;
use App\Module\CloudControllerProcessor;
use App\MyCloud;
use App\Notifications;
use App\OtherFiles;
use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\mimetype_from_extension;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Library\Library;
use JmesPath\Env;
use Mockery\Exception;


class CloudController extends Controller
{
    public $Lib, $CloudProcessor ;

    function __construct()
    {
        $this->middleware('auth')->except(['previewFile', 'downloadFile']);

        $this->Lib = new Library();
    }




    public function index($communityId){
        $userId = Auth::id();
        $username = Auth::user()->name;

        $communityId = is_null($communityId) ? $this->Lib->getCurrentCommunityId() : $communityId;
        $data = AllCommunitiesOfUsers::where('user_id', $userId)->where('community_id', $communityId)->first();
        if( count($data) < 1 ){
            return redirect('/');
        }

        $DirFiles = MyCloud::where([
            'possessed_by_community' => $communityId,
            'parent_dir'    => '/',
            'soft_delete'   => 0
        ])->orderBy('id', 'desc')->get();


        $allCommunities = DB::table('all_communities_of_a_user as all')
            ->select('all.community_id', 'com.community_name')
            ->join('communities as com', 'com.id', '=', 'all.community_id')
            ->where('all.user_id', $userId)
            ->get();

        $currentCommunityId = $this->Lib->getCurrentCommunityId();
        $allPeopleOfCurrentCommunity = AllCommunitiesOfUsers::where('community_id', $communityId)->get();

        $courses = DeptCourses::where('possessed_by_community', $communityId)->select('course_name', 'id')->get();
//        $courses = $courses->sortBy(function ($course){
//            return $course->course_name;
//        });

        $communityInfo = Communities::where('id', $communityId)->get();

        return view('pages.my_cloud',[
            'username'              => $username,
            'dirFiles'              => $DirFiles,
            'full_dir_url'          => '/',
            'allCommunities'        => $allCommunities,
            'currentCommunityId'    => $currentCommunityId,
            'allPeople'             => $allPeopleOfCurrentCommunity,
            'userRole'              => $this->Lib->getUserRoleInCloud($communityId),
            'courses'               => $courses,
            'communityInfo'         => $communityInfo[0]
        ]);
    }



    public function cloudAction(Request $request){
        $identifier = $request->input('identifier');
        $possessedByCommunity = $request->input('possessed_by_community');
        //$possessedByCommunity = 7;
        //echo $possessedByCommunity; return;
        $userId = Auth::id();

        if($identifier == "saveDir"){
            $parent_dir = $request->input('parent_dir') ?: "/";

            $request['is_root'] = ($parent_dir == "/" )? 1 : 0;
            $request['parent_dir'] = $parent_dir ?: "/";
            $request['full_dir_url'] =$request->input('full_dir_url') ?: "/";
            $request['file_ext'] = "null";
            $request['file_size'] = 0; //int
            $request['user_id'] = $userId;

            $retrieved = MyCloud::where(
                [
                    'full_dir_url'              => $request['full_dir_url'],
                    'user_id'                   => $userId,
                    'possessed_by_community'    => $possessedByCommunity,
                    'name'                      =>$request->name,
                    'parent_dir'                =>$request->parent_dir,
                    'soft_delete'               => 0
                ]
            )->get();

            $exist = ( count($retrieved) == 1 ) ? true : false;
            if( ! $exist ){
                $savedDir = MyCloud::create($request->all());
                echo $savedDir->id;
            }else{
                echo "exist";
            }
        } //saveDir

        elseif($identifier == "loadDirFiles"){
            $dirFiles = MyCloud::where(
                [
                    'full_dir_url' => $request->input('full_dir_url'),
                    'possessed_by_community'    => $possessedByCommunity,
                    'parent_dir'=>$request->input('parent_dir'),
                    'soft_delete' => 0
                ]
            )->orderBy('id', 'desc')->get();

            echo json_encode($dirFiles);
        }
    }


    public function cloudUpload(Request $request){
        $userId = Auth::id();
        $identifier = $request->all()['identifier'];
        $request['access_code'] = $request->input('access_code');
        $possessedByCommunity = $request->input('possessed_by_community');

        $myLibrary = new Library();

        if($identifier == 'book'){
            try{
                $file = $request->file('book_file');
                $fileName = $file->getClientOriginalName();
                $fileName = str_replace(' ', '_', $fileName);
                $fileSize = ($file->getClientSize()) / 1000; // To get in KB divide by 1000, not 1024. !!


                $request['name'] = $fileName;
                $request['description'] = $request->input('description') ?: "No description added";
                $request['file_ext'] = $file->getClientOriginalExtension();
                $request['file_size'] = $fileSize;
                $request['is_root'] = ( $request->all()['parent_dir'] == "/" ) ? 1 : 0;
                $request['user_id'] = $userId;

                //save upload data in my_cloud table
                $cloudModel = MyCloud::create($request->all());
                $cloudId = $cloudModel->id;

                $request['book_file'] = "null";
                $file_path = "my-cloud/".$fileName."/".$file->hashName();
                $request['file_path'] = $file_path;
                $request['uploaded_by'] = $userId;
                $request['cloud_id'] = $cloudId;

                Storage::disk('s3')->put('my-cloud/'.$fileName, $file, 'public');
                //Storage::disk('uploaded')->put('my-cloud/'.$fileName, $file, 'public');

                /* Then, also save some others data into 'books' table **with 'cloud_id'** , if the uploaded file is book */
                Books::create($request->all());

                $pointBadge = new PointBadges($userId, $myLibrary->getCurrentCommunityId());
                $pointBadge->increaseReputationFor('add_book');

                $this->Lib->insertCloudUploadNotification($cloudId);


                $username = Auth::user()->name;
                $full_dir_url = $request->input('full_dir_url');

                $dirFiles = MyCloud::where(
                    [
                        'full_dir_url'              => $full_dir_url,
                        'user_id'                   => $userId,
                        'possessed_by_community'    => $possessedByCommunity,
                        'parent_dir'                =>$request->input('parent_dir'),
                        'soft_delete'               => 0,
                    ]
                )->get();

                $allCommunities = DB::table('all_communities_of_a_user as all')
                    ->select('all.community_id', 'com.community_name')
                    ->join('communities as com', 'com.id', '=', 'all.community_id')
                    ->where('all.user_id', $userId)
                    ->get();

                $communityId = $this->Lib->getCurrentCommunityId();
                $allPeopleOfCurrentCommunity = AllCommunitiesOfUsers::where('community_id', $communityId)->get();
                $courses = DeptCourses::where('possessed_by_community', $communityId)->get();
                $communityInfo = Communities::where('id', $communityId)->get();
                //return redirect()->back();
                return view('pages.my_cloud',[
                    'username'      => $username,
                    'dirFiles'      => $dirFiles,
                    'full_dir_url'  => $myLibrary->htmlifyFullDirUrl($full_dir_url),
                    'currentCommunityId'    => $this->Lib->getCurrentCommunityId(),
                    'allPeople'     => $allPeopleOfCurrentCommunity,
                    'allCommunities'=> $allCommunities,
                    'userRole'      => $this->Lib->getUserRoleInCloud( $communityId ),
                    'courses'       => $courses,
                    'communityInfo' => $communityInfo[0]
                ]);
            }catch (Exception $e){
                return "Error Occurred. Try again";
            }
        } // identifier book
        elseif($identifier == 'question'){
            $request['uploaded_by'] = $request['user_id'] = $userId; // uploaded_by column is in 'questions' table and user_id column is in 'my_cloud' table
            $fileObj = $request->file('question_file');
            $fileName = $request['course-name'].'_'.$request['year'].'_'.$request['question_of'].'_'.$fileObj->getClientOriginalName();
            $fileName = str_replace(' ', '_', $fileName);
            $request['file_name'] = $request['name'] = $fileName;
            $request['file_size'] = ($fileObj->getClientSize()) / 1000; // To get in KB divide by 1000, not 1024. !!
            $request['file_ext'] = $fileObj->getClientOriginalExtension();
            $file_path = "my-cloud/".$fileName."/".$fileObj->hashName();
            $request['file_path'] = $file_path;
            $request['is_root'] = ( $request->all()['parent_dir'] == "/" ) ? 1 : 0;
            $request['uploaded_by'] = $userId;

            $cloudModel = MyCloud::create($request->all());
            $cloudId = $cloudModel->id;
            Storage::disk('s3')->put("my-cloud/$fileName", $fileObj, 'public');
            //Storage::disk('uploaded')->put('my-cloud/'.$fileName, $fileObj, 'public');

            $pointBadge = new PointBadges($userId, $myLibrary->getCurrentCommunityId());
            $pointBadge->increaseReputationFor('add_question');

            $request['cloud_id'] = $cloudId;
            if( $request['course_id'] ==0 ){
                //i.e. this course name is not already added in course name list
                //so add it now
                $addedCourse = DeptCourses::create([
                    'course_code'           => $request['course-name'],
                    'course_name'           => $request['course-name'],
                    'about_course'          => 'No description added',
                    'possessed_by_community'=> $possessedByCommunity,
                    'data_added_by'         => $userId,
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ]);
                $request['course_id'] = $addedCourse->id;
            }
            ExamQuestions::create($request->all());

            $this->Lib->insertCloudUploadNotification($cloudId);

            $username = Auth::user()->name;
            $full_dir_url = $request->input('full_dir_url');

            $dirFiles = MyCloud::where(
                [
                    'full_dir_url'  => $full_dir_url,
                    'user_id'       => $userId,
                    'possessed_by_community'    => $possessedByCommunity,
                    'parent_dir'    =>$request->input('parent_dir'),
                    'soft_delete'   => 0,
                ]
            )->get();

            $allCommunities = DB::table('all_communities_of_a_user as all')
                ->select('all.community_id', 'com.community_name')
                ->join('communities as com', 'com.id', '=', 'all.community_id')
                ->where('all.user_id', $userId)
                ->get();

            $communityId = $this->Lib->getCurrentCommunityId();
            $allPeopleOfCurrentCommunity = AllCommunitiesOfUsers::where('community_id', $communityId)->get();
            $courses = DeptCourses::where('possessed_by_community', $communityId)->get();
            $communityInfo = Communities::where('id', $communityId)->get();
            //return redirect()->back();
            return view('pages.my_cloud',[
                'username'      => $username,
                'dirFiles'      => $dirFiles,
                'full_dir_url'  => $myLibrary->htmlifyFullDirUrl($full_dir_url),
                'currentCommunityId'    => $this->Lib->getCurrentCommunityId(),
                'allPeople'     => $allPeopleOfCurrentCommunity,
                'allCommunities'=> $allCommunities,
                'userRole'      => $this->Lib->getUserRoleInCloud( $communityId ),
                'courses'       => $courses,
                'communityInfo' => $communityInfo[0]
            ]);

            //return $request->all();
        } // $identifier 'question'
        elseif($identifier == "otherFile"){
            try{
                $description = $request->input('description');
                $description = $description ?: "No description added";

                $file = $request->file('other_file');
                $fileName = str_replace(' ', '_', $file->getClientOriginalName());
                $request['file_name'] = $fileName = str_replace(' ', '_', $request['file_name']).'_'.$fileName;
                $file_path = "my-cloud/".$fileName."/".$file->hashName();
                $request['file_path'] = $file_path;
                $request['file_ext'] = $file->getClientOriginalExtension();
                $request['file_size'] = ( $file->getClientSize() ) / 1024;

                $request['description'] = $description;
                $request['name'] = $request->input('file_name');

                $request['is_root'] = ( $request->all()['parent_dir'] == "/" ) ? 1 : 0;
                $request['access_code'] = 3;
                $request['user_id'] = $userId;

                //save to cloud
                $cloudModel = MyCloud::create($request->all());
                $cloudId = $cloudModel->id;

                $request['other_file'] = "null";
                $request['uploaded_by'] = $userId;
                $request['cloud_id'] = $cloudId;

                Storage::disk('s3')->put('my-cloud/'.$fileName, $file, 'public');
                //Storage::disk('uploaded')->put('my-cloud/'.$fileName, $file, 'public');

                //save to 'other_files'
                OtherFiles::create($request->all());

                $this->Lib->insertCloudUploadNotification($cloudId);

                $pointBadge = new PointBadges($userId, $myLibrary->getCurrentCommunityId());
                $pointBadge->increaseReputationFor('add_other_files');

                $username = Auth::user()->name;
                $full_dir_url = $request->input('full_dir_url');
                $dirFiles = MyCloud::where(
                    [
                        'full_dir_url'          => $full_dir_url,
                        'user_id'               => $userId,
                        'possessed_by_community'=> $possessedByCommunity,
                        'parent_dir'            =>$request->input('parent_dir'),
                        'soft_delete'           => 0
                    ]
                )->get();

                $allCommunities = DB::table('all_communities_of_a_user as all')
                    ->select('all.community_id', 'com.community_name')
                    ->join('communities as com', 'com.id', '=', 'all.community_id')
                    ->where('all.user_id', $userId)
                    ->get();
                $communityId = $this->Lib->getCurrentCommunityId();
                $allPeopleOfCurrentCommunity = AllCommunitiesOfUsers::where('community_id', $communityId)->get();
                $courses = DeptCourses::where('possessed_by_community', $communityId)->get();
                $communityInfo = Communities::where('id', $communityId)->get();

                //return redirect()->back();
                return view('pages.my_cloud',[
                    'username'      => $username,
                    'dirFiles'      => $dirFiles,
                    'full_dir_url'  => $myLibrary->htmlifyFullDirUrl($full_dir_url),
                    'currentCommunityId'    => $this->Lib->getCurrentCommunityId(),
                    'allPeople'     => $allPeopleOfCurrentCommunity,
                    'allCommunities'=> $allCommunities,
                    'userRole'      => $this->Lib->getUserRoleInCloud( $communityId ),
                    'courses'       => $courses,
                    'communityInfo' => $communityInfo[0]
                ]);
            }//try
            catch (\Exception $e){
                return "Error Occurred. Try again";
            }
        } //identifier 'otherFile'
    } //cloudUpload()


    public function showSingleDirFile($cloudId){
        $userId = Auth::id();
        $cloudInfo = MyCloud::where('id', $cloudId)->get();

        $fileType = 'other_file'; //it could also be type of 'book'. By default 'other_file'
        $singleDirFile = OtherFiles::where('cloud_id', $cloudId)->get();
        if( count($singleDirFile) == 1 ){
            //then decide, file  of this 'cloud_id' is in the 'other_files' table

            //increment 'total_view' by 1
            DB::table('other_files')->where('cloud_id', $cloudId)->increment('total_view');
            return view('pages.show_single_dir_file', [
                'singleDirFile' => $singleDirFile,
                'cloudInfo'     => $cloudInfo,
                'fileType'      => $fileType
            ]);
        }// if fileType 'other_file'

        $singleDirFile = ExamQuestions::where('cloud_id', $cloudId)->get();
        if( count($singleDirFile) == 1 ){
            //then decide, file  of this 'cloud_id' is in the 'exam_questions' table

            //increment 'total_view' by 1
            DB::table('exam_questions')->where('cloud_id', $cloudId)->increment('total_view');

            $singleDirFile = DB::table('exam_questions as ex')
                ->select('ex.*', 'my_cloud.name', 'my_cloud.file_size',  'dept_courses.course_code', 'dept_courses.course_name')
                ->join('my_cloud', 'ex.cloud_id', '=', 'my_cloud.id')
                ->join('dept_courses', 'ex.course_id', '=', 'dept_courses.id')
                ->where('my_cloud.id', $cloudId)
                ->get();
            return view('pages.show_single_dir_file', [
                'singleDirFile' => $singleDirFile,
                'fileType'      => 'Question'
            ]);
        }// if fileType 'other_file'

        else{
            //if fileType 'book'
            $singleDirFile = Books::where('cloud_id', $cloudId)->get();
            if( count($singleDirFile) ==1 ){
                //then decide, file or dir of this 'cloud_id' is in the 'books' table
                $fileType = 'book';

                //increment 'total_view' by 1
                DB::table('books')->where('cloud_id', $cloudId)->increment('total_view');
                return view('pages.show_single_dir_file', [
                    'singleDirFile' => $singleDirFile,
                    'cloudInfo' => $cloudInfo,
                    'fileType' => $fileType,
                ]);
            } //if fileType 'book'
            else{
                $retrievedData = MyCloud::where('id', $cloudId)->pluck('file_ext');
                if( $retrievedData[0] == 'null' ){

                    $fullDirUrl = MyCloud::where('id', $cloudId)->pluck('name');
                    $fullDirUrl = $fullDirUrl[0];

                    $retrievedData = MyCloud::where('user_id' , $userId)
                        ->where('full_dir_url', 'like', "%$fullDirUrl%")
                        ->where('file_ext', '!=', 'null')
                        ->get();
                    $totalFiles = count($retrievedData);

                    $retrievedData = DB::table('my_cloud') // needed for obtaining different result
                    ->selectRaw('sum(file_size) as dirSize')
                        ->where('full_dir_url', 'like', "%$fullDirUrl%")
                        ->where('file_ext', '!=', 'null')
                        ->get();
                    $dirSize = $retrievedData[0]->dirSize;

                    $singleDirFile = MyCloud::where('id', $cloudId)->get();
                    $singleDirFile[0]['total_files'] = $totalFiles;
                    $singleDirFile[0]['dirSize'] = $dirSize;

                    return view('pages.show_single_dir_file', [
                        'singleDirFile' => $singleDirFile,
                        'fileType' => 'dir',
                    ]);
                }
            } //if fileType not 'book'

            //404 otherwise
            return abort(404);
        }
        //otherwise this function will return nothing , and thus a blank page will be shown

    } //showSingleDirFile


    public function previewFile($cloudId){

        $fileInfo = Books::where('cloud_id', $cloudId)->get();
        $fileExt = MyCloud::where('id', $cloudId)->pluck('file_ext')[0];
        $loggedIn = Auth::check() ? true : false;

        if( count($fileInfo) == 1 ){
            return view('pages.file_preview',[
                'fileInfo' => $fileInfo[0],
                'file_ext' => $fileExt,
                'loggedIn' => $loggedIn,
                'file_name'=> $fileInfo[0]->book_name
            ]);
        }

        //if not in the books table, it may be in the other_files table
        $fileInfo = OtherFiles::where('cloud_id', $cloudId)->get();
        if( count($fileInfo) == 1 ){
            return view('pages.file_preview',[
                'fileInfo' => $fileInfo[0],
                'file_ext'=> $fileExt,
                'loggedIn' => $loggedIn,
                'file_name'=> $fileInfo[0]->file_name
            ]);
        }
        //otherwise it should be in the 'exam_questions' table
        $fileInfo = ExamQuestions::where('cloud_id', $cloudId)->get();
        if( count($fileInfo) > 0 ){
            return view('pages.file_preview',[
                'fileInfo' => $fileInfo[0],
                'file_ext'=> $fileExt,
                'loggedIn' => $loggedIn,
                'file_name'=> $fileInfo[0]->file_name
            ]);
        }

        return abort(404);
    } //previewFile()





    public function downloadFile($cloudId){

        $fileInfo = Books::where('cloud_id', $cloudId)->get();
        $fileInfo = DB::table('books')
            ->join('my_cloud as cloud', 'books.cloud_id', '=', 'cloud.id')
            ->select('cloud.file_ext', 'books.book_name', 'books.file_path')
            ->where('cloud.id', $cloudId)
            ->get();

        if( count($fileInfo) == 1 ){
            $fileInfo = $fileInfo[0];
            $fileName = $fileInfo->book_name;
            $fileExt = $fileInfo->file_ext;
            $mimeType = mimetype_from_extension($fileExt);
            $filePath = "https://s3.ap-south-1.amazonaws.com/choriyedao/".$fileInfo->file_path;
            //$filePath = "/uploaded/".$fileInfo->file_path;

            $this->Lib->increaseTotalDownloadBy1('books', $cloudId);

            $fileName = $fileName.".".$fileExt;
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header('Content-type:'.$mimeType);
            readfile($filePath);
        }

        //if not in the books table, it should be in the other_files table
        $fileInfo = OtherFiles::where('cloud_id', $cloudId)->get();
        $fileInfo = DB::table('other_files as other')
            ->join('my_cloud as cloud', 'other.cloud_id', '=', 'cloud.id')
            ->select('cloud.file_ext', 'other.file_name', 'other.file_path')
            ->where('cloud.id', $cloudId)
            ->get();

        if( count($fileInfo) == 1 ){
            $fileInfo = $fileInfo[0];
            $fileName = $fileInfo->file_name;
            $fileExt = $fileInfo->file_ext;
            $mimeType = mimetype_from_extension($fileExt);
            $filePath = "https://s3.ap-south-1.amazonaws.com/choriyedao/".$fileInfo->file_path;
            //$filePath = "/uploaded/".$fileInfo->file_path;

            $this->Lib->increaseTotalDownloadBy1('other_files', $cloudId);

            $fileName = $fileName.".".$fileExt;
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header('Content-type:'.$mimeType);
            readfile($filePath);

        }
        //otherwise it should be in the 'exam_questions' table
        $fileInfo = ExamQuestions::where('cloud_id', $cloudId)->get();
        $fileInfo = DB::table('exam_questions as q')
            ->join('my_cloud as cloud', 'q.cloud_id', '=', 'cloud.id')
            ->select('cloud.file_ext', 'q.book_name', 'q.file_path')
            ->where('cloud.id', $cloudId)
            ->get();
        if( count($fileInfo) == 1 ){
            $fileInfo = $fileInfo[0];
            $fileName = $fileInfo->file_name;
            $fileExt = $fileInfo->file_ext;
            $mimeType = mimetype_from_extension($fileExt);
            $filePath = "https://s3.ap-south-1.amazonaws.com/choriyedao/".$fileInfo->file_path;
            //$filePath = "/uploaded/".$fileInfo->file_path;

            $this->Lib->increaseTotalDownloadBy1('other_files', $cloudId);

            $fileName = $fileName.".".$fileExt;
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header('Content-type:'.$mimeType);
            readfile($filePath);
        }

        return abort(404);
    }


    //ajax
    public function cutCopyDirFile(Request $request){
        $userId = Auth::id();
        $myLibrary = new Library();
        $currentCommunityId = $this->Lib->getCurrentCommunityId();

        $name = $request->input('name');
        $fullDirUrl = $request->input('full_dir_url');
        $cloudId = $request->input('cloud_id');
        $command = $request->input('command');

        if( $fullDirUrl == '/' ){
            $parentDir = '/';
            $isRoot = 1;
        }else{
            //parent_dir is the last part of the full_dir_url
            $parentDir = explode('/', strrev($fullDirUrl));
            $parentDir = strrev( $parentDir[0] );
            $isRoot = 0;
        }

        $myCloud = MyCloud::where('id', $cloudId)->get();
        $myCloud = $myCloud[0];

        $dirOrFile = 'file';
        if($myCloud->file_ext == 'null' ){
            $dirOrFile = 'dir';
        }
        //echo $name.'--'.$parentDir.'--'.$fullDirUrl.'---'.$cloudId.'-'.$dirOrFile.'--'.$command;


        if( $dirOrFile == 'file' ){
            if( $command == 'cut' ){
                MyCloud::where('id', $cloudId)
                    ->update([
                        'parent_dir' => $parentDir,
                        'full_dir_url' => $fullDirUrl
                    ]);
                echo 'success';
            } //file cut done
            elseif ($command == 'copy' ){
                $dataToInsert = $myCloud;
                $dataToInsert['parent_dir'] = $parentDir;
                $dataToInsert['full_dir_url'];
                $newRow = MyCloud::create([
                    'user_id'           => $userId,
                    'name'              => $name,
                    'parent_dir'        => $parentDir,
                    'is_root'           => $isRoot,
                    'full_dir_url'      => $fullDirUrl,
                    'file_ext'          => $myCloud->file_ext,
                    'access_code'       => $myCloud->access_code,
                    'possessed_by_community'    => $currentCommunityId,
                    'file_size'         => $myCloud->file_size
                ]);

                $fileCategory = $myLibrary->getFileCategoryByCloudId($cloudId);
                if( $fileCategory == 'book' ){
                    $book = Books::where('cloud_id', $cloudId)->first();
                    $newBook = $book->replicate();
                    $newBook->cloud_id = $newRow->id;
                    $newBook->save();
                }
                elseif ( $fileCategory =='otherFile' ){
                    $other = OtherFiles::where('cloud_id', $cloudId)->first();
                    $newOther = $other->replicate();
                    $newOther->cloud_id = $newRow->id;
                    $newOther->save();
                }
                echo 'success';
            } //copy done

        } //if 'file' type
        elseif ( $dirOrFile == 'dir' ){
            if( $command == 'copy' ){
                /*  ===========NOT IMPLEMENTED YET========== */
//                $name = $request->input('name');
//                $destinationFullDirUrl = $request->input('full_dir_url');
//
//                $cloudId = $request->input('cloud_id');
//                $selectedDir = MyCloud::where('id', $cloudId)->get();
//                $selectedFullDirUrl = $selectedDir[0]->full_dir_url;
//                $selectedFullDirUrl = ($selectedFullDirUrl == '/') ? '' : $selectedFullDirUrl;
//
//                $selectedDirChildren = MyCloud::where('id', $cloudId)
//                    ->orWhere('full_dir_url', '=', "$selectedFullDirUrl/$name")
//                    ->orWhere('full_dir_url', 'like', "$selectedFullDirUrl/$name/%")
//                    ->get();
//                foreach ($selectedDirChildren as $key => $row){
//                    $toCopy = $row->replicate();
//                    $modifiedUrl = $this->Lib->modifyFullDirUrl($row->full_dir_url, $selectedFullDirUrl, $destinationFullDirUrl);
//                    $toCopy['full_dir_url'] = $modifiedUrl;
//                    if($key > 0){
//                        $toCopy->save();
//                    }
//                }
            } // copy dir done
            elseif ( $command == 'cut' ){

            }
        } // if 'dir' type
    } //curCopyDirFile()

    public function deleteDirFile(Request $request){
        try{
            MyCloud::where('id', $request->input('id'))->update(['soft_delete'=>1]);
            echo 'deleted';
        }catch (Exception $e){}
    }

    public function renameDir(Request $request){
        $id = $request->input('id');
        $oldName = $request->input('oldName');
        $newName = $request->input('newName');
        $dirOrFile = $request->input('dirOrFile');

        if( $dirOrFile == 'dir' ){
            try{
                //step 1
                MyCloud::where(['id'=>$id])->update(['name'=>$newName]);
                //step 2
                $targetFullDirUrl = MyCloud::where(['id'=> $id])->pluck('full_dir_url');
                $targetFullDirUrl = $targetFullDirUrl[0];
                //if its a root level directory, then $targetFullDirUrl assumes a value : '/' only. That case "$targetFullDirUrl/$oldName"  evaluates as : '//', which will result in bug
                $targetFullDirUrl = ($targetFullDirUrl == '/') ? '' : $targetFullDirUrl;
                //step 3
                MyCloud::where(['full_dir_url' => "$targetFullDirUrl/$oldName" ])
                    ->update(['parent_dir' => $newName ]);
                //step 4
                $findWhat = "$targetFullDirUrl/$oldName";
                $replaceWith = "$targetFullDirUrl/$newName";
                $query = "update my_cloud set full_dir_url = replace(full_dir_url, '$findWhat', '$replaceWith') where full_dir_url like '$findWhat/%' or full_dir_url='$findWhat' ";
                DB::statement($query);
                //DB::update($query); this update() function also works
                //echo $query;
            }catch (Exception $e){
            }
        }//if dir
        else{
            //if file
            MyCloud::where(['id'=>$id])->update(['name'=>$newName]);
        }
    }

    public function saveCurrentCloud(Request $request){
        $this->Lib->setCurrentCommunityInCloud(
            $request->input('communityId')
        );
    } // saveCurrentCloud()


    //ajax
    public function executeContributorAction(Request $request){
        $action = $request->input('action') ;
        $userId = $request->input('userId');
        $communityId = $request->input('communityId');

        if ( $action == 'makeContributor' ){
            AllCommunitiesOfUsers::where('user_id', $userId)->where('community_id', $communityId)
                ->update(['role' => 'contributor']);
            echo 'success';
        }
        elseif( $action == 'removeContributor' ){
            if ( $this->Lib->getTotalContributors($communityId) > 1 ){
                // remove only when at least 1 contributor exists
                AllCommunitiesOfUsers::where('user_id', $userId)->where('community_id', $communityId)
                    ->update(['role' => 'member']);
                echo 'success';
            }
        }
    }  // ()


    public function showRecycleBin($communityId){
        $userId = Auth::id();

        $communityId = is_null($communityId) ? $this->Lib->getCurrentCommunityId() : $communityId;
        $data = AllCommunitiesOfUsers::where('user_id', $userId)->where('community_id', $communityId)->first();

        if( count($data) < 1 ){
            return abort(401);
        }

        $deletedDirFiles = MyCloud::where('possessed_by_community', $communityId)
            ->where('soft_delete', 1)->get();

        return view('pages.my_cloud_recycle_bin', [
            'deletedDirFiles'   => $deletedDirFiles,
            'communityId'       => $communityId
        ]);
    }  // showRecycleBin()


    //ajax
    public function restoreFromRecycleBin(Request $request){
        try{
            $communityId = $this->Lib->getCurrentCommunityId(Auth::id());
            $dirOrFile = MyCloud::where('id', $request->input('id'))->get();
            $dirOrFile = $dirOrFile[0];
            $name = $dirOrFile->name;
            $fullDirUrl = $dirOrFile->full_dir_url;
            $duplicate = MyCloud::where('name', $name)->where('full_dir_url', $fullDirUrl)
                ->where('soft_delete', 0)->where('possessed_by_community', $communityId)->get();

            if( count($duplicate) > 0 ){
                echo "exist";
            }else{
                MyCloud::where('possessed_by_community', $request->input('communityId'))
                    ->where('id', $request->input('id'))
                    ->update(['soft_delete' => 0]);
                echo 'success';
            }
        }catch (Exception $e){}
    }



    public function openDirLocation($cloudId){
        // http://127.0.0.1:8000/users/cloud/view/41020/open?p=2016%20-%202017&f=/1st%20Year%201st%20Semester/CSE-1101,C%20Programming/Books/2016%20-%202017

        $communityId = $this->Lib->getCurrentCommunityId();
        $userId = Auth::id();
        $username = Auth::user()->name;

        $communityId = is_null($communityId) ? $this->Lib->getCurrentCommunityId() : $communityId;
        $data = AllCommunitiesOfUsers::where('user_id', $userId)->where('community_id', $communityId)->first();
        if( count($data) < 1 ){
            return redirect('/');
        }

        $parentDir = isset($_GET['p']) ? $_GET['p'] : '/';
        $fullDirUrl = isset($_GET['f']) ? $_GET['f'] : '/';

        $cloudInfo = MyCloud::find($cloudId);
        $parentDir = $cloudInfo->parent_dir;
        $fullDirUrl = $cloudInfo->full_dir_url;
        $fullDirUrl = empty($fullDirUrl) ? '/' : $fullDirUrl;

        $DirFiles = MyCloud::where([
            'possessed_by_community' => $communityId,
            'parent_dir'    => $parentDir,
            'full_dir_url'  => $fullDirUrl,
            'soft_delete'   => 0
        ])->get();


        $allCommunities = DB::table('all_communities_of_a_user as all')
            ->select('all.community_id', 'com.community_name')
            ->join('communities as com', 'com.id', '=', 'all.community_id')
            ->where('all.user_id', $userId)
            ->get();

        $currentCommunityId = $this->Lib->getCurrentCommunityId();
        $allPeopleOfCurrentCommunity = AllCommunitiesOfUsers::where('community_id', $communityId)->get();
        $courses = DeptCourses::where('possessed_by_community', $communityId)->select('course_name', 'id')->get();
        $communityInfo = Communities::where('id', $communityId)->get();

        return view('pages.my_cloud',[
            'username'              => $username,
            'dirFiles'              => $DirFiles,
            'full_dir_url'          => $this->Lib->htmlifyFullDirUrl($fullDirUrl),
            'allCommunities'        => $allCommunities,
            'currentCommunityId'    => $currentCommunityId,
            'allPeople'             => $allPeopleOfCurrentCommunity,
            'userRole'              => $this->Lib->getUserRoleInCloud($communityId),
            'courses'               => $courses,
            'communityInfo'         => $communityInfo[0]
        ]);
    }


}
