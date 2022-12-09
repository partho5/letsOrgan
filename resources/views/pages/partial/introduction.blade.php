<?php

$bgImgClass = "page1-bg-img";


//$userIP = $_SERVER['REMOTE_ADDR'];
//$userIP = "103.220.205.198";
//
//$query = unserialize(file_get_contents('http://ip-api.com/php/'.$userIP));
//
//dd($query);
//
//if($query && $query['status'] == 'success')
//{
//    if($query['city'] == 'Dhaka' ){
//        $bgImgClass = '';
//    }
//}

$univCount = \App\Communities::distinct()->get(['institute_name'])->count();
$deptCount = \App\Communities::distinct()->get(['dept_name'])->count() -1; //one is 'ChoriyeDao Testers' community

?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">


<link rel="stylesheet" type="text/css" href="/assets/css/style.css" media="screen" />


<div class="container" id="introduction">

    <div id="page1" class="{{$bgImgClass}} col-md-10 col-md-offset-1 hidden">
        <div class="col-md-12 text-center">
            <img id="site-logo" class="" src="/assets/images/choriyedao-logo.png">
            <p>Being used by <strong>{{ $deptCount }}</strong> departments from <strong>{{ $univCount }}</strong> universities</p>
            <h2 id="motto" class="col-md-12 text-center">
                A platform organizes all your study materials
            </h2>
            <div class="col-md-12 text-center">
                <p class="small-msg">More efficiently, more smartly</p>
            </div>
        </div>
        <div id="get-started" class="col-md-12 text-center">
            @if(is_null(\Illuminate\Support\Facades\Auth::id()))
                <span><a href="/login#login">Login</a></span>
            @else
                <span><a href="/">Get Started</a></span>
            @endif
        </div>
        @if(is_null(Auth::id()))
            <div class="down-scroll col-md-12 text-center hidden">
                <a href="/login#page2"><img src="/assets/images/down-arrow.gif" alt=""></a>
            </div>
        @endif
    </div>

    <div id="page2" class="col-md-12 text-center hidden">
        <br><br><br>
        <div class="row col-md-8 left">
            <div class="col-md-12">
                <div class="col-md-4 study-resource-icon">
                    <img src="/assets/images/assignment.png" alt="Exam">
                    <figcaption>Assignments</figcaption>
                </div>
                <div class="col-md-4 study-resource-icon">
                    <img src="/assets/images/questions.png" alt="Previous Year Question">
                    <figcaption>Previous Year Questions</figcaption>
                </div>
                <div class="col-md-4 study-resource-icon">
                    <img src="/assets/images/books.png" alt="Books">
                    <figcaption>Books</figcaption>
                </div>

                <div class="col-md-4 study-resource-icon">
                    <img src="/assets/images/reports.gif" alt="Lab Reports">
                    <figcaption>Reports</figcaption>
                </div>
                <div class="col-md-4 study-resource-icon">
                    <img src="/assets/images/class-routine.png" alt="Class Routine">
                    <figcaption>Class Routine</figcaption>
                </div>
                <div class="col-md-4 study-resource-icon">
                    <img src="/assets/images/softwares.png" alt="Software">
                    <figcaption>Educational Softwares</figcaption>
                </div>
            </div>
        </div>
        <div class="col-md-4 right">
            <div class="col-md-12">
                <h1>Everything of your study in ONE platform</h1>
            </div>
            <div class="col-md-12">
                <div class="col-md-12" id="qa-icon">
                    <p style="background-color: #00a962; height: 2px; display: block"></p>
                    <img src="/assets/images/qa.png" width="300px" height="200px" alt="">
                    <p style="color: #000">Largest Question Answer community ( <b>BD perspective</b> )</p>
                </div>
            </div>
        </div>

    </div>

    <div id="page3" class="col-md-12 text-center hidden">
        <div class="col-md-4 left" style="margin-top: 70px">
            <div class="col-md-12">
                <div class="col-md-4 col-xs-4">
                    <br>
                    <img src="/assets/images/left-hand.png" width="100px" height="100px" alt="">
                </div>
                <div class="col-md-8 col-xs-8">
                    <br><p>Unlimited Cloud Storage</p>
                    <p>Unlike traditional ones</p>
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-4 col-xs-4">
                    <br>
                    <img src="/assets/images/left-hand.png" width="100px" height="100px" alt="">
                </div>
                <div class="col-md-8 col-xs-8">
                    <br><p>Web, Desktop, Laptop, Smart Phone all devices are synchronized</p>
                </div>
            </div>
        </div>
        <div class="col-md-8 right" style="margin-top: 70px">
            <img src="/assets/images/sync.png" width="300px" height="200px" alt="">
        </div>
    </div>

    @if(is_null(Auth::id()))
        <div class="down-scroll col-md-12 text-center hidden">
            <a href="/login#login"><img src="/assets/images/down-arrow.gif" alt=""></a>
        </div>
    @else
        <span><a href="/">Get Started</a></span>
    @endif

</div>