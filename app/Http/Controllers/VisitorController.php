<?php

namespace App\Http\Controllers;

use App\Module\Traffic\Visitor;
use App\VisitorTracker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

use Illuminate\Support\Facades\Auth;
use Khill\Lavacharts\Laravel\LavachartsFacade as Lava;
use Mockery\Exception;

class VisitorController extends Controller
{
    private $traffic;

    function __construct()
    {
        $this->middleware('auth');
        $this->traffic = new Visitor();
    }


    public function index(){
        $limit = isset($_GET['n']) ? $_GET['n'] : 200;
        $visitors = VisitorTracker::orderBy('id', 'desc')->take($limit)->get();
        return view('pages.admin.visitors', [
            'visitors'      => $visitors
        ]);
    }

    public function increaseVisitTime(Request $request){
        $pageUrl = $request->pageUrl == '/' ? '/' : substr($request->pageUrl, 1);
        $row = VisitorTracker::where('user_id', Auth::id())->where('page_url', $pageUrl)->orderBy('id', 'desc')->get();
        //$row returns only last visited page which was inserted from 'VisitorTrackerMiddleware@saveTrafficData'

//        $mouseAction = [
//            'moveX'      => $request['mousemove'][0]['x'],
//            'moveY'      => $request['mousemove'][0]['y'],
//            'moveTime'   => $request['mousemove'][0]['time'],
//            'clickX'     => $request['click'][0]['x'],
//            'clickY'     => $request['click'][0]['y'],
//            'clickTime'  => $request['click'][0]['time'],
//        ];

        if(count($row) > 0){
            VisitorTracker::where('id', $row[0]->id)->increment('session_duration', $request->incrementBy);
//            try{
//                VisitorTracker::where('id', $row[0]->id)->update([
//                    'mouse_action'        => json_encode($mouseAction),
//                    'updated_at'          => Carbon::now(),
//                ]);
//            }catch (Exception $e){}
        }

        //return $mouseAction['clickX'];
    }



    public function representGraphically(){
        //$this->traffic->processMonthlyTrafficLog();

        $day = @$_GET['d'];

        $q = "select extract(day from visit_time) as dayOfMonth, count(*) as totalHit  from visitor_tracker where month(visit_time)=12 and device_name ='webkit' and page_url not like '%mark_as_seen%' group by extract(day from visit_time)";

        //hour per day
        //$q = "select extract(hour from visit_time) as hourOfDay, count(*) as totalHit from visitor_tracker where day(visit_time)=$day and device_name ='webkit' and page_url not like '%mark_as_seen%' group by extract(hour from visit_time)";
        $data = DB::select($q);

        $data2 = [];
        $i=0;
        foreach ($data as $datum){
            //echo "$datum->id <br>";
            $data2[$i++] = [$datum->dayOfMonth, $datum->totalHit * 14];
            //$data2[$i++] = [$datum->hourOfDay, $datum->totalHit];
        }



        $visitor = Lava::DataTable();
        $visitor->addStringColumn('Hour')
            ->addNumberColumn('Number of visitors: ')
            ->addRows($data2);

        Lava::AreaChart('monthly_log', $visitor, [
            'title' => 'Daily Visitors',
            'height' => 1200,
            'legend' => [
                'position' => 'top'
            ]
        ]);

        //dd($data2);


        return view('pages.admin.visitor_log_graphical');
    }

}
