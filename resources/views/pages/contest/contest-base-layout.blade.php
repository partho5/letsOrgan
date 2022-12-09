<html>
<head>
    <title>Contest</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="/assets/css/contest.css">

    <link href="https://fonts.googleapis.com/css?family=Risque" rel="stylesheet">

    <style>
        h1{
            opacity: 0.1;
            font-size: 4vh;
            font-family: 'Risque', cursive;
            color: #000;
        }
        .text-info{
            background-color: rgba(219, 96, 0, 0.36);
            color: #fff;
            font-size: 15px;
            padding: 3px 0px 3px 20px;
            border-left: 4px solid #a04600;
        }
    </style>
</head>
<body>
<div class="body-wrap">
    <div class="container-fluid" id="menu-bar">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menus">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="/" class="navbar-brand" style="color: rgba(221,221,221,0.89)">ChoriyeDao</a>
                </div>

                <div class="collapse navbar-collapse" id="menus">
                    <ul class="nav navbar-nav">
                        <li><a href="/contest">Contest Home</a></li>
                        <li><a href="">Item 2</a></li>
                        <li><a href="">Item 3</a></li>
                        <li><a href="">Item 4</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div id="page-content" class="col-md-12">
        <h1 class="text-center" id="head1"><span>
                @yield('contest_type')
            </span></h1>
        <p class="text-center"><span class="additional-info text-center">Beginner Level</span></p>
        <div id="left-bar" class="bar col-md-1">
            <p class='contest-options'><a href="/contest?page=create">Create</a></p>
            <p class='contest-options'><a href="/contest?page=created_by_me">Edit</a></p>
            <p class='contest-options'><a href="">Result</a></p>
            <p class='contest-options'><a href="">Check Answer Script</a></p>
        </div>

        <div id="middle-bar" class="bar text-center col-md-9">
            @if($success_msg = Session::get('success_msg'))
                <p class="text-info text-left">{!! $success_msg !!}</p>
                {{ session()->flush() }}
            @endif

            <div class="topic text-center">
                <b>Topic</b> : Basic knowledge (Learned in HSC)
            </div>

            <div class="col-md-12 text-center" id="available-contests">
                @yield('available_contests')
            </div>

            @if(false)
                <div id="timing" class="col-md-12">
                    <div id="start-time" class="text-center col-md-12">
                        <p class="col-md-6 text-left">Starts From :</p>
                        <p class="col-md-6 text-left">20 January, 2018 3PM</p>
                    </div>

                    <div id="end-time" class="text-center col-md-12">
                        <p class="col-md-6 text-left">Ends At :</p>
                        <p class="col-md-6 text-left">20 January, 2018 4:30 PM</p>
                    </div>

                    <div id="total-time" class="text-center col-md-12">
                        <p class="col-md-6 text-left">Total Time Span :</p>
                        <p class="col-md-6 text-left">90 Mins (From the time you start)</p>
                    </div>
                </div>

                <button class="btn btn-1" id="go-for-contest">Go For Contest</button>
            @endif

            <div id="contest-policy-box" class="col-md-12">
                <h3><span>Sure about starting ??</span></h3>
                <p class="alert-text">Once started you cannot cancel. And you will have to submit within 90 minutes</p>

                <div class="policy-text col-md-8 col-md-offset-2">
                    <h2 class="text-center"><span>Contest Policy</span></h2>
                    gkj kjgnkgg jni jgiojoig joij oijgoirjgoijgoji oroij rljgj jioj oitrj
                    gkj kjgnkgg jni jgiojoig joij oijgoirjgoijgoji oroij rljgj jioj oitrj
                    <p>gkj kjgnkgg jni jgiojoig joij oijgoirjgoijgoji oroij rljgj jioj oitrj</p>
                    <p>gkj kjgnkgg jni jgiojoig joij oijgoirjgoijgoji oroij rljgj jioj oitrj</p>
                    <p>gkj kjgnkgg jni jgiojoig joij oijgoirjgoijgoji oroij rljgj jioj oitrj</p>
                    <p>gkj kjgnkgg jni jgiojoig joij oijgoirjgoijgoji oroij rljgj jioj oitrj</p>
                    <p class="my-hr-1"></p>
                    <input type="checkbox" id="policy-acceptInput">
                    <span>If I violate any of these policies I will be banned for this contest</span>
                </div>

                <form action="/contest/start" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="contest-id" class="contest-id" value="0">
                    <input type="submit" value="Start Now" id="start-contest" class="btn btn-1 col-md-6 col-md-offset-3 text-center" style="color: #f1f1f1">
                </form>
            </div>

            <!-- START contest time screen -->
            @if( isset($runningContest) && count($runningContest) > 0 )
                @foreach($runningContest as $contest)
                        <div id="contest-time-box" class="col-md-12">
                            <div id="head2" class="col-md-12">
                                <h2>{{ $contest->title }}</h2>
                                <div class="col-md-3">
                                    <p>Some thing</p>
                                    <p>Some other</p>
                                </div>
                                <div class="col-md-7"></div>
                                <div class="col-md-2 text-left">
                                    <p>Time : 90 Minutes</p>
                                    <p>Marks: 40</p>
                                </div>
                            </div>

                            <div id="question-paper" class="col-md-12">
                                <a download href="https://s3.ap-south-1.amazonaws.com/choriyedao{{ $contest->question_file_link }}">Download Question Paper</a>
                            </div>

                            <div id="contest-answer-box" class="col-md-12">
                                <br><br>
                                <p>Submit your answer here</p>
                                <textarea name="contest-answer" id="contest-answer{{ $contest->id }}" class="col-md-12 col-xs-12" rows="10"></textarea>
                            </div>

                            <button id="contest-submit-btn" contest-id="{{ $contest->id }}" class="btn btn-1 col-md-4 col-md-offset-4">Submit Answer</button>
                        </div>
                @endforeach
            @endif
            <!-- END contest time screen -->

            <!-- START Admin Dashboard -->
            <div id="admin-panel" class="col-md-12">
                @yield('create_contest')

                @yield('contest_create_by_me')

                @yield('edit_contest')
            </div>
            <!-- END Admin Dashboard -->

        </div>
        <div id="right-bar" class="bar col-md-2">
            <p>This is some right column text. ...... TEST...</p>
            <p>This is some right column text. ...... TEST...</p>
            <p>This is some right column text. ...... TEST...</p>
            <p>This is some right column text. ...... TEST...</p>
            <p>This is some right column text. ...... TEST...</p>
            <p>This is some right column text. ...... TEST...</p>
            <p>This is some right column text. ...... TEST...</p>
            <p>This is some right column text. ...... TEST...</p>
            <p>This is some right column text. ...... TEST...</p>
            <p>This is some right column text. ...... TEST...</p>
            <p>This is some right column text. ...... TEST...</p>
            <p>This is some right column text. ...... TEST...</p>
        </div>
    </div>




</div>

<script>
    $(document).ready(function(){

        $('#head1').animate({
            'opacity' : 1,
            'font-size' : '8vh'
        }, 700);


        var leftBarHeight = $('#left-bar').height();
        var rightBarHeight = $('#right-bar').height();
        var middleBarHeight = $('#middle-bar').height();

        var isMobile = false; //initiate as false
        // device detection
        if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))){
            isMobile = true;
        }
        if( ! isMobile){
            $('#left-bar, #right-bar, #middle-bar').css('height', Math.max(leftBarHeight, middleBarHeight, rightBarHeight));
            $('#left-bar').show();
        }

    });
</script>
</body>
</html>