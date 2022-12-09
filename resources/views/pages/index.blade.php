@extends('app')


@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <h2> Lets Organize All Your Study Materials
            <small>
                <a href="">
                    <span style="color: #00a962">Lets</span><span style="color: #dc9258">Organ</span><span style="color: #000">.com</span>
                </a>
                <br><span><small>(Study Resource Collection)</small></span>
            </small>
        </h2>
        <div class="col-md-10">
            <input type="search" id="searchBar" class="form-control" placeholder="Search feature is coming soon.......">
        </div>
        <div class="col-md-2 text-left">
            <button type="button" id="searchBtn" class="form-control btn btn-default">Search</button>
        </div>


        <div class="col-md-12 text-left" style="padding: 0;">
            <h3 class="text-center">Recent Activities</h3>

            @foreach($unreadNotifications as $notification)
                <div class="single-notification unread-notification">
                    <a href="{{ $notification->link }}">
                        <p class="" notific-id="{{ $notification->id }}" link="{{ $notification->link }}">{{ $notification->readable_text }}</p>
                        <p class="date-time text-right" title="{{ $notification->created_at }}">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans(\Carbon\Carbon::now()) }}</p>
                    </a>
                </div>
            @endforeach

            @foreach($readNotifications as $notification)
                <div class="single-notification">
                    <a href="{{ $notification->link }}">
                        <p class="" notific-id="{{ $notification->id }}" link="{{ $notification->link }}">{{ $notification->readable_text }}</p>
                        <p class="date-time text-right" title="{{ $notification->created_at }}">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans(\Carbon\Carbon::now()) }}</p>
                    </a>
                </div>
            @endforeach
        </div>




        <link href="https://fonts.googleapis.com/css?family=Risque" rel="stylesheet">
        <div class="col-md-12"style="display: none">
            <hr>
            <div id="result"></div>
            <img class="col-xs-12" src="/assets/images/contest.png" alt="img">
            <p class="my-h2">Online Circuit Contest... <br> Prepare Yourself</p>
        </div>

        <div class="contest-box-wrapper col-md-12 hidden">
            <p class="my-h2"><a href="/contest"> <span class="fa fa-link"></span> CONTEST !!</a></p>
            <a class="contest-box col-md-6">
                <h3>Circuit Contest</h3>
                <p class="p1">Running....</p>
                <p class="text-left">Compare your knowledge with the competitive world</p>
            </a>
            <div class="contest-box col-md-6">
                <h3>Programing Contest</h3>
                <p class="p1">Starting from February....</p>
                <p class="text-left">You are a ACM problem solver ! Well, what about fighting local coders around you....?</p>
            </div>
            <div class="contest-box col-md-6">
                <h3>Physics Contest</h3>
                <p class="p1">Coming....</p>
                <p class="text-left">Love Physics ? Physics is really fun in ChoriyeDao</p>
            </div>
            <div class="contest-box col-md-6">
                <h3>Math Contest</h3>
                <p class="p1">Coming....</p>
                <p class="text-left">Its not a Math Olympiad ! Then what's it ?? Join and explore.</p>
            </div>
            <div class="contest-box col-md-6">
                <h3>Business Case Study</h3>
                <p class="p1">Coming...</p>
                <p class="text-left">Great ideas spinning in your head ?</p>
            </div>
            <div class="contest-box col-md-6">
                <h3>More</h3>
                <p class="p1">More are coming....</p>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function(){

            $('#add-member').click(function () {
                $('#add-member-extension').show();
                $('#add-member-extension').slideUp(10);
                $('#add-member-extension').slideDown();
            });

            $('#copy-btn').click(function () {
                $('#link').select();
                document.execCommand('copy');
                $('#add-member-extension').slideUp();
                $('#copy-success-msg').show();
                setTimeout(function () {
                    $('#copy-success-msg').slideUp();
                },7000);
            });

            function arrayMax(arr) {
                var len = arr.length, max = -Infinity;
                while (len--) {
                    if (arr[len] > max) {
                        max = arr[len];
                    }
                }
                return max;
            };
            var heights = [];
            var bgColors = ['#223', '#008dd1','#8900ce', '#223','#110', '#8d0059'];
            //var bgColors = ['#223', '#008dd1','#008dd1', '#223','#223', '#008dd1'];
            var headColors = ['#223', '#008dd1','#8900ce', '#223','#110', '#8d0059'];
            $('.contest-box').each(function (i) {
                heights[i++] = $(this).height();
            });
            $('.contest-box').each(function (i) {
                $(this).css('height', arrayMax(heights));
                $(this).css('background-color', bgColors[i]);
                $(this).find('h3').css('color', '#fff');
                if(i%2==0){
                    $(this).css('border-right', '2px solid #fff');
                }else{
                    $(this).css('border-left', '2px solid #fff');
                }
            });



            {{--var prevVal = null ;--}}
            {{--if(typeof(EventSource) !== "undefined") {--}}
                {{--var source = new EventSource("{!! url("/sse") !!}");--}}
                {{--source.onmessage = function(event) {--}}
                    {{--var curVal = event.data ;--}}
                    {{--if(curVal !== prevVal){--}}
                        {{--$('#result').text("Total gen posts : "+curVal);--}}
                    {{--}--}}
                    {{--prevVal = curVal;--}}
                {{--};--}}
            {{--}--}}


        });
    </script>
@endsection