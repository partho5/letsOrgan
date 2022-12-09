<?php

namespace App\Http\Middleware;

use App\Library\Library;
use App\VisitorTracker;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;


class VisitorTrackerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    private $Lib;

    function __construct()
    {
        $this->Lib = new Library();
    }

    public function handle($request, Closure $next)
    {
        $this->saveTrafficData($request);

        return $next($request);
    }

    private function saveTrafficData(Request $request){
        //return;
        $agent = new Agent();
        $dataset = array();
        $userId = Auth::id();
        if(is_null($userId)){
            //can be null in logged out pages
            $userId = 0;
        }
        $communityId = $this->Lib->getCurrentCommunityId();
        $dataset['user_id'] = $userId;
        $dataset['community_id'] = $communityId;
        $dataset['ip'] = $_SERVER['REMOTE_ADDR'];
        $dataset['device_type'] = $agent->isDesktop() ? "PC" : "Mobile";
        $dataset['device_name'] = $agent->device();
        $dataset['browser'] = $agent->browser();
        $dataset['page_url'] = $request->path();
        $dataset['visit_time'] = Carbon::now();
        $dataset['session_duration'] = 0; //default value ( in seconds )
        //$dataset['created_at'] = Carbon::now();

        $visitedByAdmin = false;
        if( in_array($userId, $this->Lib->getAdminsId()) || in_array($_SERVER['REMOTE_ADDR'], $this->Lib->getAdminsIP()) ){
            $visitedByAdmin = true;
        }
        $isApiCall = substr($request->path(), 0, 3) == "api" ? true : false;
        $excludeablePages = [
            'visitor/increment_visit_time', 'push_notification/save_onesignal_device_id', 'visitor',
        ];

        //! $visitedByAdmin && ! $isApiCall
        //$visitedByAdmin = false; //development only
        if( ! $visitedByAdmin && ! $isApiCall && ! in_array($request->path(), $excludeablePages) ){
            VisitorTracker::create($dataset);
        }
    }
}
