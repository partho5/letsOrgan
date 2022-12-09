<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LetsOrgan</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .container{
            width: 100%;
            padding: 0px;
            margin: 0px auto;
        }
        .msg-1{
            font-size: 25px;
            color: #f00;
        }
        .msg-2{
            font-size: 15px;
            color: #000;
        }
        #body{
            margin-top: 100px;
        }
        #menu-bar{
            background-color: #384e65;
        }
        #menu-bar ul li a{
            color: #fff;
        }
        #submit-btn{
            border-radius: 0px;
            text-decoration: none;
            padding: 10px 40px;
            border:2px solid #fff;
            font-size: 18px;
            background-color: rgb(0, 163, 217);
            color: #fff;
            cursor: pointer;
        }
    </style>
</head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<body>
    <div class="container text-center col-md-12">
            <nav class="navbar navbar-default navbar-fixed-top" id="menu-bar" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                        </button>
                        <a class="navbar-brand" href="/" style="color: #fff">LetsOrgan</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li><a href="/qa">Q&A Forum</a></li>
                            <li><a href="#row2">Features</a></li>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                </div>
                <!-- /.container-fluid -->
            </nav>
    </div>

    <div id="body" class="text-center">
        <div id="urnot-connected-body">
            <div class="text-center hidden">
                <p class="msg-1">
                    You must join your department's group. Request someone to add you.
                </p>
                <span class="msg-2" style="color: #009356; font-size: 20px" class="text-center">
                If your department doesn't have group, <a style="color: #00f" href="/community/create">Create Now</a>
                </span>
            </div>
            <div class="msg-2" style="color: #009356; font-size: 20px" class="text-center">
                <h3>Join your department's community to access all features</h3>
                <br>
                <form action="/community/search" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group col-md-8 col-md-offset-2">
                        <span class="col-md-4 text-left" style="color: #000">University Name (<small>Full Form</small>)</span>
                        <div class="input-group col-md-8 text-center">
                            <input type="text" name="institute_name" class="institute-name form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-8 col-md-offset-2">
                        <span class="col-md-4 text-left" style="color: #000">Department Name (<small>Full Form</small>)</span>
                        <div class="input-group col-md-8">
                            <input type="text" name="dept_name" class="dept-name form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-8 col-md-offset-2">
                        <button type="submit" id="submit-btn" class="col-md-4 col-md-offset-4 text-left btn btn-default">Find My Department</button>
                        <p class="msg-2 col-md-12 text-center" style="color: #0000ffee">We like to hear from you. Don't hesitate to mail : <b>letsorgan@gmail.com</b></p>
                    </div>
                </form>
            </div>
            <img src="/assets/images/bg_one_more_step.jpg" class="col-md-xs-12" width="100%";  alt="">
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">

    <script type="text/javascript" src="/assets/js/library.js"></script>
    <script>
        $(document).ready(function(){
            //defined in library.js
            var instituteList = allInstituteList;
            var deptList = allDeptList;

            $('.institute-name').autocomplete({
                source : instituteList
            });
            $('.dept-name').autocomplete({
                source : deptList
            });

            $('form').submit(function (e) {
                var instituteName = $('.institute-name').val();
                var deptName = $('.dept-name').val();
                if( ! instituteList.includes(instituteName) || ! deptList.includes(deptName)){
                    e.preventDefault();
                    alert("Please select only suggested names. Don't modify");
                }
            });
        });
    </script>

</body>
</html>