<?php

namespace App\Http\Controllers;

use App\BookDownloadLinks;
use App\UploadRequests;
use App\YoutubeLinks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function index(){
        echo "LOL";
    }



    public function store(Request $request){
        $identifier=$request->all()['identifier'];

        if($identifier=='bookLink'){
            $modifiedRequestArray=$request->all();
            $upload_by=(Cookie::has('upload_by')) ? Cookie::get('upload_by') : '';
            $modifiedRequestArray['upload_by']=$upload_by;
            BookDownloadLinks::create($modifiedRequestArray);

            echo 'success';
        }
        elseif ($identifier=='videoLink'){
            $modifiedRequestArray=$request->all();
            $short_des=$request->all()['short_description'];
            $modifiedRequestArray['short_description']=($short_des) ? $short_des : '';
            $modifiedRequestArray['upload_by']=(Cookie::has('upload_by')) ? Cookie::get('upload_by') : '';
            YoutubeLinks::create($modifiedRequestArray);

            echo 'success';
        }
        elseif ($identifier=='upload-request'){
            $modifiedRequestArray=$request->all();
            $modifiedRequestArray['request_by']= ($request->hasCookie('upload_by')) ? Cookie::get('upload_by') : '';
            $details=$modifiedRequestArray['details'];
            $modifiedRequestArray['details']= ($details) ? $details : '';

            UploadRequests::create($modifiedRequestArray);

            echo 'success';
        }

        elseif ($identifier=="viva_question"){
            $questions=json_decode($request->all()['questions']);
            $answers=json_decode($request->all()['answers']);
            print_r($answers);
        }
    }
}
