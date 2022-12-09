<?php

namespace App\Http\Controllers;

use App\Books;
use App\Library\Library;
use App\OtherFiles;
use App\UploadRequest;
use App\UploadRequestResponse;
use App\YoutubeLinks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request){
        $username = Auth::user()->name;

        return view('pages.upload',[
            'username' => $username
        ]);
    }
    
    
    public function showUploadRequest(){
        return view('pages.request_for_upload');
    }


    public function doUploadRequest(Request $request){
        $userId = Auth::id();
        $details = $request['details'] ?: 'No details available';

        $request['user_id'] = $userId;
        $request['details'] = $details;

        UploadRequest::create($request->all());

        echo 'success';
    }

    public function deleteUploadRequest(Request $request){
        UploadRequest::where('id', $request->input('requestId'))->delete();
        echo 'deleted';
    }

//    public function  doUpload(Request $request){
//        //for handling multiple form's upload, 'doUpload' -this single method is being used.
//        // 'identifier' identifies which form it is
//        $identifier = $request->all()['identifier'];
//
//        if($identifier == "book"){
//            $file = $request->file('book_file');
//            $bookName = $file->getClientOriginalName();
//            $bookName = str_replace(' ', '_', $bookName);
//            $file_path = "/uploaded/$bookName/".$file->hashName();
//
//            $request['description'] = $request->all()['description'] ?: "Please Add a description";
//            $request['uploaded_by']  = Auth::id();
//            $request['file_path'] = $file_path;
//
//            Storage::disk('uploaded')->put($bookName, $file); //'uploaded' disk is defined in filesystem.php
//            Books::create($request->all());
//
//        }
//
//        if($identifier == "otherFile"){
//            $file = $request->file('other_file');
//            $fileName = $file->getClientOriginalName();
//            $file_path = "/uploaded/$fileName/".$file->hashName();
//            $file_path = str_replace(' ', '_', $file_path);
//
//            Storage::disk('uploaded')->put($fileName, $file);
//
//            $request['uploaded_by'] = Auth::id();
//            $request['file_path'] = $file_path;
//
//            OtherFiles::create($request->all());
//
//            echo $file_path;
//        }
//    } //doUpload()

    public function showUploadRequestResponse($requestId){
        $requestedFile = UploadRequest::where('id', $requestId)->get();

        if ( count($requestedFile) == 1 ){
            return view('pages.respond_to_upload_request', [
                'requestedFile' => $requestedFile[0],
                'request_id'    => $requestId,
                'user_id'       => Auth::id(),
            ]);
        }
        return abort(404);
    }

    public function doUploadRequestResponse(Request $request){
        $Lib = new Library();
        $userId = Auth::id();
        $details = $request['details'] ?: 'No details available';

        $request['user_id'] = $userId;
        $request['details'] = $details;
        $requestId = $request->input('request_id');
        try{
            $fileObj = $request->file('requested_file');
            $fileName = $fileObj->getClientOriginalName();
            $fileName = str_replace(' ', '_', $fileName);
            $request['file_name'] = $fileName;
            $file_path = 'my-cloud/'.$fileName.'/'.$fileObj->hashName();
            $request['file_path'] = $file_path;

            if( $Lib->rowExist('upload_request_responses', 'request_id', $requestId) ){
                //think, weather something can be implemented under this condition
            }

            Storage::disk('s3')->put('my-cloud/'.$fileName, $fileObj, 'public');
            //Storage::disk('uploaded')->put('my-cloud/'.$fileName, $fileObj, 'public');
            UploadRequestResponse::create($request->all());
            UploadRequest::where('id', $requestId )->update(['uploaded_by' => $userId]);

            return redirect('/upload/request/all')
                ->with('success_msg', 'Thank you for uploading');
        }catch (Exception $e){

        }
    } //doUploadRequestResponse


    public function showAllUploadRequest(){
        $allRequests = UploadRequest::where('uploaded_by', 0)->get();

        return view('pages.all_upload_request', [
            'allRequests' => $allRequests,
        ]);
    }


    // ajax
    public function saveYoutubeLink(Request $request){
        $userId = Auth::id();
        $request['data_added_by'] = $userId;
        $request['description'] = $request->input('description') ?: "No description added";

        $tube = YoutubeLinks::create($request->all());
        echo $tube->id;
    } // saveYoutubeLink()
}
