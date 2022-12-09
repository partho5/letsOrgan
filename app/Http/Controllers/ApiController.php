<?php

namespace App\Http\Controllers;

use App\Books;
use App\Library\Library;
use App\Module\API\Attendance\AttendanceProcessor;
use App\StudentAttendance;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $AttendanceAppProcessor, $Lib;

    function __construct()
    {
        $this->AttendanceAppProcessor = new AttendanceProcessor();
        $this->Lib = new Library();
    }

    public function index()
    {
//        $apiKey = @$_GET['key'];
//        if($apiKey == 'yoyo'){
//            $books = Books::orderBy('id', 'desc')->get();
//            return $books;
//        }
//        return abort(401, 'No such key');
    }


    public function ATTENDANCE_getCourses(){
        $email = @$_GET['email'];
        $pass = @$_GET['pass'];
        $loginResponse = $this->AttendanceAppProcessor->loginResponse($email, $pass);
        if( $loginResponse->getStatusCode() != 200 ){
            return response("Access Denied", 403);
        }
        $userIdOfTeacher = $loginResponse->getContent();

        $data['userId'] = $userIdOfTeacher;
        $data['courses'] = $this->AttendanceAppProcessor->coursesRunBy($userIdOfTeacher);
        $data['courseTakerStudents'] = $this->AttendanceAppProcessor->courseTakerStudents($userIdOfTeacher);

        return $data;
        dd($data);
    }


    public function WORDS_REMINDER_respondToSimpleRequest(){
        $requestCode = @$_GET['requestCode'];
        $deviceId = @$_GET['device_id'];
        $msg = @$_GET['msg'];

        Mail::send('mailPages.tmp', ['data'=> $msg], function ($m) use ($msg){
            $m->from("choriyedao@gmail.com", "LetsOrgan");
            $m->to("partho8181bd@gmail.com")->subject("Words Reminder >> Msg From User");
        } );
    }


    public function ATTENDANCE_sendAttendanceReport(){
        $email = @$_GET['email'];
        $pass = @$_GET['pass'];
        $data = @$_GET['data'];

        $attendanceInfo = [];
        preg_match_all('/"(.*?)"/', $data, $matches);

        $tmp = [];
        for($i=0; $i < count($matches[1]); $i+=3){
            $tmp['user_id'] = $matches[1][$i];
            $tmp['date'] = $matches[1][$i+1];
            $tmp['attendance'] = $matches[1][$i+2];

            array_push($attendanceInfo, $tmp);
            //$tmp = [];
        }

        foreach ($attendanceInfo as $item){
            $attendance = new StudentAttendance();
            $attendance->user_id = $item['user_id'];
            $attendance->course_id = 38;
            $attendance->datetime = $item['date'];
            $attendance->attendance = $item['attendance'];
            //$attendance->save();

            //echo "saved data for user_id ".$item['user_id']." <br>";
        }

        $info = DB::table('student_attendances as att')
            ->select('att.*', 'roll.roll_numeric', 'roll.roll_full_form')
            ->join('student_rolls as roll', 'att.user_id', '=', 'roll.user_id')
            ->orderBy('roll.roll_numeric', 'asc')
            ->get();

        return $info;

        echo "<table border='1'>";
        echo "<tr> <th>user_id</th> <th>date_time</th> <th>attendance</th> </tr>";
        foreach ($info as $row) {
            echo "<tr>";
            echo "<td>$row->user_id</td>";
            echo "<td>$row->date_time</td>";
            echo "<td>$row->attendance</td>";
            echo "</tr>";
        }
        echo "</table>";

        Carbon::parse("0007-04-18 00:00:00")->format('d/M');


//        $html = view('generatePDF.event-details', ['data'=> $data])->render();
//        $pdf = App::make('dompdf.wrapper');
//        $pdf->loadHTML($html);
//        $savePath = 'generatedPDF/'.(Carbon::now()->format('d-m-Y').'_'.$event->client_dial_num).'.pdf';
//        try{
//            file_put_contents(public_path().'/'.$savePath, $pdf->stream('invoice.pdf'));
//            return $this->Lib->baseDomain().'/'.$savePath;
//        }catch (Exception $e){
//            return null;
//        }


        $html = view('generatePDF/attendance_sheet', ['data'=> $data])->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        $savePath = 'generatedPDF/'.(Carbon::now()->format('d-m-Y').'_38').'.pdf';
        try{
            file_put_contents(public_path().'/'.$savePath, $pdf->stream('invoice.pdf'));
            return $this->Lib->baseDomain().'/'.$savePath;
        }catch (Exception $e){
            return null;
        }



        $pdf = PDF::loadView('pdf.certificate', compact('data'));

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
        //
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
        //
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
        //
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
}
