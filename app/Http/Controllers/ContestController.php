<?php

namespace App\Http\Controllers;

use App\ContestAllowedUsers;
use App\ContestInformation;
use App\ContestParticipants;
use App\ContestSubmittedAnswers;
use App\Library\Library;
use App\Module\Contest\ContestProcessor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class ContestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $contest, $Lib;

    function __construct()
    {
        $this->middleware('auth');

        $this->contest = new ContestProcessor();
        $this->Lib = new Library();
    }

    public function index()
    {
        $page = @$_GET['page'];
        if($page == ''){
            $availableContest = $this->contest->availableContestsForThisUser();
            $runningContest = $this->contest->getRunningContest();
            return view('pages.contest.contest-home', [
                'availableContest'     => $availableContest,
                'userId'               => Auth::id(),
                'runningContest'       => $runningContest,
            ]);
        }
        if($page == 'create'){
            $contestCategories = $this->contest->contestCategories();
            $instituteInfo = $this->Lib->getInstituteInfo();
            if(count($instituteInfo) > 0){
                return view('pages.contest.create-contest', [
                    'contestCategories'    => $contestCategories,
                    'instituteInfo'        => $instituteInfo[0],
                ]);
            }
            return abort(401);
        }
        if($page == 'created_by_me'){
            $contestCreatedByMe = $this->contest->createdByMe();
            return view('pages.contest.created_by_me', [
                'contestCreatedByMe'    => $contestCreatedByMe
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['creator_id'] = $request['judge_id'] = Auth::id();
        $questionFileObject = $request->file('question_file_link');
        $answerFileObject = $request->file('answer_file_link');

        $allowedUniv = explode(';', $request->input('allowed_users'))[0];
        $allowedDept = explode(';', $request->input('allowed_users'))[1];
        unset($request['allowed_users']);

        Storage::disk('s3')->put('contest/'. $questionFileObject->getClientOriginalName(), $questionFileObject, 'public');
        Storage::disk('s3')->put('contest/'. $answerFileObject->getClientOriginalName(),   $answerFileObject  ,'public');

        $contest = ContestInformation::create($request->all());
        ContestInformation::where('id', $contest->id)->update([
            'question_file_link'    => '/contest/'.$questionFileObject->getClientOriginalName().'/'. $questionFileObject->hashName(),
            'answer_file_link'      => '/contest/'.$answerFileObject->getClientOriginalName()  .'/'. $answerFileObject->hashName(),
        ]);

        ContestAllowedUsers::insert([
            'contest_id'    => $contest->id,
            'institute_name'     => $allowedUniv,
            'dept_name'     => $allowedDept
        ]);

        Session::put('success_msg', "Project has been created. You can edit from <a class='btn-2' href='/contest?page=created_by_me'>here</a> ");
        return redirect('/contest');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contest = ContestInformation::where('id', $id)->get();
        $instituteInfo = $this->Lib->getInstituteInfo();
        if($this->contest->isCreatorOf($id) && count($contest) > 0 && count($instituteInfo) > 0){
            $contestCategories = $this->contest->contestCategories();
            $allowedUsers = $this->contest->allowedUsersAsString($id);
            return view('pages.contest.edit-contest', [
                'contest'           => $contest[0],
                'contestCategories' => $contestCategories,
                'instituteInfo'     => $instituteInfo[0],
                'allowedUsers'      => $allowedUsers,
            ]);
        }
        return abort(401);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        unset($request['_token']);
        unset($request['_method']);
        $allowedUniv = explode(';', $request->input('allowed_users'))[0];
        $allowedDept = explode(';', $request->input('allowed_users'))[1];
        unset($request['allowed_users']);

        if($this->contest->isCreatorOf($id)){
            ContestInformation::where('id', $id)->update($request->all());
            ContestAllowedUsers::where('contest_id', $id)->update([
                'institute_name'     => $allowedUniv,
                'dept_name'     => $allowedDept
            ]);

            Session::put('success_msg', "Data has been Updated");
            return redirect('/contest?page=created_by_me');
        }
        return abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function registerForContest(Request $request){
        $contestId = $request->input('contest_id');
        if( ! $this->contest->alreadyRegistered($contestId) ){
            $this->contest->completeRegistration($contestId);
            echo 'registered';
        }else{
            //to implement
            echo 'already registered';
        }
    }


    public function cancelRegistration(Request $request){
        $contestId = $request->input('contest_id');
        ContestParticipants::where('contest_id', $contestId)->where('user_id', Auth::id())->delete();
        echo 'canceled';
    }

    public function fetchTotalRegistered(){
        $contestId = 44;
        $total = $this->contest->totalRegistered($contestId);

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        echo "data: $total \n\n";

        flush();
    }

    public function startContest(Request $request){
        $contestId = $request['contest-id'];
        if( $this->contest->isAllowedForContest($contestId) ){
            $submittedAns = ContestSubmittedAnswers::firstOrNew([
                'user_id'       => Auth::id(),
                'contest_id'    => $contestId
            ]);
            $submittedAns->save();
            return redirect('/contest');
        }
        else{
            return abort(401);
        }
    }
}
