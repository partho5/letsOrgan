<?php

/**
 * Created by PhpStorm.
 * User: partho
 * Date: 1/6/18
 * Time: 3:59 PM
 */

namespace App\Module\Contest;

use App\ContestAllowedUsers;
use App\ContestInformation;
use App\ContestParticipants;
use App\ContestSubmittedAnswers;
use App\Library\Library;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ContestProcessor{

    private $Lib;

    function __construct()
    {
        $this->Lib = new Library();
    }

    public function createdByMe(){
        $created = ContestInformation::where('creator_id', Auth::id())->orderBy('id', 'desc')->get();
        return $created;
    }

    public  function allowedUsersAsString($contestId){
        $allowed = ContestAllowedUsers::where('contest_id', $contestId)->select('institute_name', 'dept_name')->get();
        return $allowed[0]->institute_name.';'.$allowed[0]->dept_name;
    }

    public function availableContestsForThisUser(){
        $instituteInfo = $this->Lib->getInstituteInfo();
        $availableContestId = ContestAllowedUsers::where('institute_name', $instituteInfo[0]->institute_name)
            ->orWhere('institute_name', 'all')
            ->where('dept_name', $instituteInfo[0]->dept_name)
            ->orWhere('dept_name', 'all')
            ->select('contest_id', 'institute_name', 'dept_name')
            ->get();
        if(count($availableContestId) > 0){
            $ids = [];
            foreach ($availableContestId as $contest){
                if( ! $this->alreadySubmittedAns($contest->contest_id) ){
                    array_push($ids, $contest->contest_id);
                }
            }
            $availableContests = ContestInformation::whereIn('id', $ids)->get();
            foreach ($availableContests as $contest){
                $contest->is_registered = $this->alreadyRegistered($contest->id);
                $contest->total_registered = $this->totalRegistered($contest->id);
            }
            return $availableContests;
        }
        return null;
    }

    public function isAllowedForContest($contestId){
        $instituteInfo = $this->Lib->getInstituteInfo();
        if(count($instituteInfo) > 0){
            $exist = ContestAllowedUsers::where('contest_id', $contestId)
                ->where('institute_name', $instituteInfo[0]->institute_name)
                ->orWhere('institute_name', 'all')
                ->where('dept_name', $instituteInfo[0]->dept_name)
                ->orWhere('dept_name', 'all')
                ->pluck('id')->count();
            return $exist > 0 ? true : false;
        }
        return false;
    }

    public function getRunningContest(){
        //assume that, submitted_at will be null if its a running contest
        $running = ContestSubmittedAnswers::where('user_id', Auth::id())
            ->where('submitted_at', null)->get();
        if(count($running) > 0){
            // submitted_at may remain null ACCIDENTALLY even after the contest is over. So check again by this query
            $contestInfo = ContestInformation::find(['id' => $running[0]->contest_id]);
            if( Carbon::now() < Carbon::parse($contestInfo[0]->end_time) ){
                return $contestInfo;
            }
        }
        return null;
    }

    public function alreadyRegistered($contestId){
        $participant = ContestParticipants::where('contest_id', $contestId)->where('user_id', Auth::id())->pluck('id')->count();
        return $participant > 0 ? true : false;
    }

    public function alreadySubmittedAns($contestId){
        $submittedContest = ContestSubmittedAnswers::where('contest_id', $contestId)->where('user_id', Auth::id())
            ->where('submitted_at', '>', Carbon::now()->format('y-m-d h:i:s') )->get();
        return count($submittedContest) > 0 ? true : false;
    }

    public function totalRegistered($contestId){
        return ContestParticipants::where('contest_id', $contestId)->pluck('id')->count();
    }

    public function isCreatorOf($contestId){
        $contestCount = ContestInformation::where('id', $contestId)->where('creator_id', Auth::id())->pluck('id')->count();
        return $contestCount==1 ? true : false;
    }

    public function completeRegistration($contestId){
        ContestParticipants::insert([
            'user_id'       => Auth::id(),
            'contest_id'    => $contestId,
            'created_at'    => Carbon::now() //rest of the columns have default value defined in migration
        ]);
    }


    public function contestCategories(){
        return ['Circuit', 'Programing', 'Business Case Study', 'Design'];
    }
}