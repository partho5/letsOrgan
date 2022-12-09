<html>
<head>
    <title>Notice Board</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="/assets/css/noticeboard.css">

</head>
<body>
    <div class="container-fluid col-md-12 text-center" id="noticeboard-container">
        <div id="top-bar" class="col-md-12">
            @if($userInfo['userType'] == 'admin')
            <ul class="notice-btn list-inline">
                <li>New</li>
                <li>All</li>
                <li>Edit</li>
                <li>Delete</li>
            </ul>
            @else
                <ul class="notice-btn list-inline">
                    <a href="/" style="color: #000"><b>Home</b></a>
                    <li>Noticeboard - Upcoming Feature</li>
                </ul>
            @endif
        </div>

        <div id="board" class="container col-md-12 text-center">
            <div class="single-notice col-md-4">
                <div class="col-md-8 col-md-offset-2">
                    <h3>1st year 1st semester Final Result has been published</h3>
                    <p>Publish Date: 2/12/2017</p>
                    <p><a href="">Details</a></p>
                </div>
            </div>
            <div class="single-notice col-md-4">
                <div class="col-md-8 col-md-offset-2">
                    <h3>2nd year 1st semester EEE 2201 first incourse result has been published</h3>
                    <p>Publish Date: 2/12/2017</p>
                    <p><a href="">Details</a></p>
                </div>
            </div>
            <div class="single-notice col-md-4">
                <div class="col-md-8 col-md-offset-2">
                    <h3>Submit your MAT 3101, Computer Programming Assignment by 05-05-2016</h3>
                    <p>Publish Date: 2/12/2017</p>
                    <p><a href="">Details</a></p>
                </div>
            </div>
        </div>

        <div id="board" class="container col-md-12 text-center">
            <div class="single-notice col-md-4">
                <div class="col-md-8 col-md-offset-2">
                    <h3>Complete your 4th year admission fee by 04-04-2017 </h3>
                    <p>Publish Date: 2/12/2017</p>
                    <p><a href="">Details</a></p>
                </div>
            </div>
            <div class="single-notice col-md-4">
                <div class="col-md-8 col-md-offset-2">
                    <h3>Submit your CSE 3101, Computer Programming Assignment by 05-05-2016</h3>
                    <p>Publish Date: 2/12/2017</p>
                    <p><a href="">Details</a></p>
                </div>
            </div>
            <div class="single-notice col-md-4">
                <div class="col-md-8 col-md-offset-2">
                    <h3>2nd year 1st semester ME 2201 first incourse result has been published</h3>
                    <p>Publish Date: 2/12/2017</p>
                    <p><a href="">Details</a></p>
                </div>
            </div>
        </div>

        <div id="board" class="container col-md-12 text-center">
            <div class="single-notice col-md-4">
                <div class="col-md-8 col-md-offset-2">
                    <p>Coming Feature 1</p>
                </div>
            </div>
            <div class="single-notice col-md-4">
                <div class="col-md-8 col-md-offset-2">
                    <p>Coming Feature 2</p>
                </div>
            </div>
            <div class="single-notice col-md-4">
                <div class="col-md-8 col-md-offset-2">
                    <p>Coming Feature 3</p>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="/assets/js/library.js"></script>
    <script>
        $(document).ready(function () {


            //update session_duration at every certain interval
            var incrementSessionDurationAfterEvery = incrementSessionDurationAjaxInterval; //defined in library.js
            setInterval(function () {
                $.ajax({
                    url : '/visitor/increment_visit_time',
                    type : 'post',
                    data :{
                        _token : "{{ csrf_token() }}",
                        incrementBy : incrementSessionDurationAfterEvery , // seconds
                        pageUrl : window.location.pathname+window.location.search,
                    },
                    success : function (response) {
                        console.log(response);
                    },error : function () {
                        console.log('error');
                    }
                });
            }, incrementSessionDurationAfterEvery*1000);
        });
    </script>
</body>
</html>