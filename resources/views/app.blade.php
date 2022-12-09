<?php

use App\Profile;
use Illuminate\Support\Facades\Auth;
use App\UploadRequestResponse;
use App\CommunityExams;
use App\CommunityAssignments;
use App\UploadRequest;
use App\Library\Library;

$Lib = new Library();

if(Auth::check()){
    $loggedIn =true ;
    $userId = Auth::id();
    $profileInfo = Profile::where('user_id', $userId)->get();
    $profileInfo = $profileInfo[0];
    $currentCommunityId = $Lib->getCurrentCommunityId();
    $communityName = $Lib->getCommunityName($currentCommunityId);

    $exams = CommunityExams::where('community_id', $currentCommunityId)
        ->where('academic_session', '=', $Lib->getAcademicSession($currentCommunityId))
        ->where('exam_date', '>=', \Carbon\Carbon::now())
        ->orderBy('exam_date', 'asc')->take(8)->get();
    
    $assignments = CommunityAssignments::where('community_id', $currentCommunityId)
        ->where('academic_session', '=', $Lib->getAcademicSession($currentCommunityId))
        ->where('deadline', '>=', \Carbon\Carbon::now())
        ->orderBy('deadline', 'asc')->take(8)->get();

    //$requestedFiles = UploadRequest::where('shared_with_community', $currentCommunityId)->orderBy('id', 'desc')->take(7)->get();
    //$requestedFiles = UploadRequest::all()->take(5);

    //$recentlyUploaded =UploadRequestResponse::all()->take(10);




}else{
    $loggedIn = false ;
    $userId = 0;
    $profileInfo = null;
}

$agent = new Jenssegers\Agent\Agent();
$deviceType = $agent->isDesktop() ? "PC" : "Mobile";
$deviceName = $agent->device();
$browser = $agent->browser();


$OnesignalUserId = "a04221f9-b83c-4259-8587-a9ff07b09f64"; //desktop firefox

$OnesignalUserId = \App\OnesignalDeviceInformation::where('user_id', 4)->get();
$OnesignalUserId = $OnesignalUserId[0]->device_id;

$OnesignalUserId = "a04221f9-b83c-4259-8587-a9ff07b09f64"; //desktop firefox

//$OnesignalUserId = "43b048be-63af-47c0-bc3e-b74c5aca7ac4"; //sourav  firefox

?>

<!DOCTYPE html>
<html>

<head>
    <title>{{$pageTitle}}</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">



    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async='async'></script>
    <script>
        var OneSignal = window.OneSignal || [];
        OneSignal.push(["init", {
            appId: "145450a8-cfd6-4aa1-8ad1-60ca80483307",
            autoRegister: true, /* Set to true to automatically prompt visitors */
            subdomainName: 'choriyedao.os.tc', /* in case not having https site */
            promptOptions:{
                actionMessage: "Academic notifications will be given",
                exampleNotificationTitleDesktop: 'Let ChoriyeDao make your life easier',
                exampleNotificationMessageDesktop: 'Allow notification and be tension less',
                exampleNotificationTitleMobile: 'Let ChoriyeDao make your life easier',
                exampleNotificationMessageMobile: 'Allow notification and be tension less',
                showCredit: false
            },
            notifyButton: {
                enable: true, /* Required to use the notify button */
                size: 'medium', /* One of 'small', 'medium', or 'large' */
                theme: 'default', /* One of 'default' (red-white) or 'inverse" (white-red) */
                position: 'bottom-right', /* Either 'bottom-left' or 'bottom-right' */
                offset: {
                    bottom: '0px',
                    left: '0px', /* Only applied if bottom-left */
                    right: '0px' /* Only applied if bottom-right */
                },
                showCredit: false,
            },
            welcomeNotification: {
                disable: true
            },
            webhooks: {
                cors: true, // Defaults to false if omitted
                'notification.displayed': 'Message from ChoriyeDao', // e.g. https://site.com/hook
                'notification.clicked': 'http://choriyedao.com/',
                // ... follow the same format for any event in the list above
            }
        }]);


        OneSignal.push(function() {
            /* These examples are all valid */
            OneSignal.getUserId(function(userId) {
                console.log("OneSignal User ID:", userId);
                $.ajax({
                    url : '/push_notification/save_onesignal_device_id',
                    type : 'post',
                    dataType : 'text',
                    data : {
                        _token : "{{ csrf_token() }}",
                        deviceId : userId,
                        deviceType : "{{ $deviceType }}",
                        deviceName : "{{ $deviceName }}",
                        browser : "{{ $browser }}"
                    }, success : function (response) {
                        console.log(response);
                    }, error : function () {
                    }
                });
                //alert(userId);
                // (Output) OneSignal User ID: 270a35cd-4dda-4b3f-b04e-41d7463a2316
            });
        });
    </script>



    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    {{--
    <link href="https://fonts.googleapis.com/css?family=Galada" rel="stylesheet">--}} {{--
    <link rel="stylesheet" type="text/css" href="/assets/font/font-awesome.min.css" />--}}

    <link rel="stylesheet" type="text/css" href="/assets/font/font.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" media="screen" />


</head>

<body>

    {{--@include('pages.partial.introduction')--}}

    <div class="container">
        @include('pages.partial.menu-bar')
    </div>

    <div class="container" id="ad1">
        <p class="text-center text-capitalize">this section is for advertisemnet</p>
    </div>

    <div class="col-md-12 text-center" id="general-msg">

    </div>

    <div class="container row text-center">
        <div class="container-fluid" id="barWrapper">
            <div class="row">

                @yield('middleBarContent')

                <div id="leftBar" class="bars col-md-2 col-sm-pull-7">
                    <div id="exams">
                        <h3>Upcoming Exams</h3>
                        <ul>
                            @foreach($exams as $exam)
                            <li class="eachFile">
                                <span>Course:</span>
                                <strong>{{ $exam->course_name }}</strong><br>
                                <small>@ {{ \Carbon\Carbon::parse($exam->exam_date)->format('d-m-Y') }}</small>
                                <span class="examDetailsBtn"><a href="/users/exam/details/{{ $exam->id }}">Details</a></span>
                            </li>
                            @endforeach

                            @if( count($exams) < 1 )
                                <li class="eachFile">
                                    <span>Course:</span>
                                    <strong>Demo 2101</strong>
                                    <p>
                                        <small>@ {{ \Carbon\Carbon::today()->addDays(3)->toDateString() }}</small>
                                        <span class="examDetailsBtn"><a href="/users/exam/details/1">Details</a></span>
                                     </p>
                                </li>
                                <li class="eachFile">
                                    <span>Course:</span>
                                    <strong>Demo 3204</strong>
                                    <p>
                                        <small>@ {{ \Carbon\Carbon::today()->addDays(5)->toDateString() }}</small>
                                        <span class="examDetailsBtn"><a href="/users/exam/details/2">Details</a></span>
                                    </p>
                                </li>
                                <li class="eachFile">
                                    <span>Course:</span>
                                    <strong>Demo 1203</strong>
                                    <p>
                                        <small>@ {{ \Carbon\Carbon::today()->addDays(1)->toDateString() }}</small>
                                        <span class="examDetailsBtn"><a href="/users/exam/details/3">Details</a></span>
                                    </p>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <hr>

                    <div id="navLinks" class="hidden">
                        <h4>Navigation Links</h4>
                        <ul>
                            <li><a href="">Invite a friend</a></li>
                            <li><a href="">Leave us a message</a></li>
                            <li><a href="">Suggest to improve</a></li>
                        </ul>
                    </div>
                    <hr>

                    <div id="assignments">
                        <h3>Assignments</h3>
                        <ul>
                            @foreach($assignments as $assignment)
                            <li class="eachFile">
                                <span>Course:</span>
                                <strong>{{ $assignment->course_name }}</strong>
                                <p>
                                    <small>@ {{ \Carbon\Carbon::parse($assignment->deadline)->format('d-m-Y') }}</small>
                                    <span class="assignDetailsBtn"><a href="/users/assignment/details/{{$assignment->id}}">Details</a></span>
                                </p>
                            </li>
                            @endforeach

                            @if( count($assignments) < 1 )
                                    <li class="eachFile">
                                        <span>Course:</span>
                                        <strong>Demo 1101</strong>
                                        <p>
                                            <small>@ {{ \Carbon\Carbon::today()->addDays(3)->toDateString() }}</small>
                                            <span class="examDetailsBtn"><a href="/users/assignment/details/1">Details</a></span>
                                        </p>
                                    </li>
                                    <li class="eachFile">
                                        <span>Course:</span>
                                        <strong>Demo 4202</strong>
                                        <p>
                                            <small>@ {{ \Carbon\Carbon::today()->addDays(5)->toDateString() }}</small>
                                            <span class="examDetailsBtn"><a href="/users/assignment/details/2">Details</a></span>
                                        </p>
                                    </li>
                                    <li class="eachFile">
                                        <span>Course:</span>
                                        <strong>Demo 2206</strong>
                                        <p>
                                            <small>@ {{ \Carbon\Carbon::today()->addDays(1)->toDateString() }}</small>
                                            <span class="examDetailsBtn"><a href="/users/assignment/details/3">Details</a></span>
                                        </p>
                                    </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div id="rightBar" class="bars col-md-3 col-sm-3">


                    {{--Code by Sourav--}}

                    <div class="buttons">
                        <a href="/community/view/all_members"><button class="all-right-btn btn btn-default col-md-12">All Members</button></a>
                        <a href="/community/courses?com={{ $communityName }}&comId={{ $currentCommunityId }}"><button class="all-right-btn btn btn-default col-md-12">Courses</button></a>
                        <a class="hidden" href="/community/routine/view"><button class="all-right-btn btn btn-default col-md-12">Routine</button></a>
                    </div>

                    {{--End By Sourav--}}



                    <div id="upcoming-events">
                        <h3>Events Near You</h3>

                        <div id="admin-notice" class="col-md-10 col-md-offset-1 text-center" style="display: none">
                            <p style="color: #f3f3f3">
                                Dear {{ \Illuminate\Support\Facades\Auth::user()->name }},
                                we appreciate your suggestion <br>
                                <b style="color: #3a3a3a">01781 60 34 05</b>
                            </p>
                            <span id="admin-notice-cross-btn" title="Close">x</span>
                        </div>

                        <img class="col-xs-12 hidden" src="/assets/images/contest.png" alt="img">
                        <p class="text-left hidden">
                            Sharpen your brain. Online contest starting soon.
                            <span class="span-1">Anyone can join</span> throughout the Bangladesh
                        </p>
                        <p class="my-hr-1"></p>

                        <img class="col-xs-12" src="/assets/Smart_City_Hackathon.jpg" alt="img">
                        <p class="text-left">
                            <br> Finding shortest path for navigation / Traffic Decisions
                            Solving Parking Crisis Utilizing marginal Groups Road Safety
                        </p>
                        <p><a href="http://smartcityhackathon.com" target="_blank" class="spanBtn">Details</a></p>

                        <p class="my-hr-1"></p>
                        <img class="col-xs-12" src="/assets/physics.jpg" alt="img">
                        <p class="text-left">
                            <br> ICNCMP2018 is a flagship conference of the Department of Physics, Bangladesh University of Engineering and Technology (BUET). It is intended to stimulate discussions on the forefront of research in nanoscience and nanotechnology and broad area of condensed matter and materials physics. The organizers anticipate that the scope of the conference will serve the interest of the scientific community, engineers as well as the industry.
                        </p>
                        <a href="https://www.facebook.com/events/142383759754071/" target="_blank" class="spanBtn">Details</a>
                    </div>

                    <hr>

                    <div id="requestedFiles" style="display: none;">
                        <a href="/upload/request/all"><h4>Requested Files</h4></a>
                        <ul>
                            {{--@foreach( $requestedFiles as $requestedFile)--}}
                                {{--<li class="eachFile">--}}
                                    {{--<p class="text-left">{{ $requestedFile->file_name }}</p>--}}
                                    {{--<ul class="list-inline">--}}
                                        {{--@if(Auth::id() == $requestedFile->user_id)--}}
                                            {{--<span class="spanBtnDelete deleteUploadRequestBtn" id="{{$requestedFile->id}}">Delete</span>--}}
                                        {{--@else--}}
                                            {{--<li><a href="/upload/request/respond/{{ $requestedFile->id or "" }}">Help him/her</a></li>--}}
                                        {{--@endif--}}
                                    {{--</ul>--}}
                                {{--</li>--}}
                            {{--@endforeach--}}
                        </ul>
                    </div>
                </div>
            </div>
        </div> <!-- barWrapper -->

        @include('pages.partial.footer')

    </div>

<script>
    $(document).ready(function(){

        $('.deleteUploadRequestBtn').click(function(){
            var THIS = $(this);
            var requestId = $(this).attr('id');
            if(confirm('Delete this request ?')){
                $.ajax({
                    url : '/upload/request/delete/'+requestId,
                    type : 'post',
                    dataType : 'text',
                    data : {
                        _token : "<?php echo csrf_token() ?>",
                        requestId : requestId
                    },
                    success : function(response){
                        if( response === 'deleted' ){
                            THIS.closest('.eachFile').css('opacity', 0.2);
                            THIS.closest('.eachFile').slideToggle(1500);
                        }
                    },
                    error : function () {
                    }
                }); //ajax
            } //if
        }); //.deleteUploadRequestBtn click

        $("#notificationContainer").click(function () {
            $("#notificationContainer").css({
                'color'                 : '#000',
                'background-color'      : '#fff',
                'font-weight'           : 'normal',
                'font-size'             : '15px',
                'background-image'      : "url('/assets/images/bell-icon.png')"
            });
            $('#totalNotific').text('');
            $('select').removeAttr('size');
        });


        $('#admin-notice').animate({
            left : -600
        }, 10);
        setTimeout(function () {
            $('#admin-notice').animate({
                left : 0
            }, 500);
        }, 30);

        $('#admin-notice-cross-btn').click(function () {
            $('#admin-notice').animate({
                width : '0px',
                height : '0px',
                opacity : 0
            });
        });
    });
</script>
</body>

</html>
