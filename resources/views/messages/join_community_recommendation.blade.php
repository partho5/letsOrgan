<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LetsOrgan</title>
    <style>
        .container{
            width: 100%;
            padding: 0px;
            margin: 0px auto;
        }
        .msg-1{
            font-size: 25px;
            color: #00a963;
        }
        .msg-2{
            font-size: 20px;
            color: rgb(181, 55, 39);
        }
        #body{
            margin-top: 50px;
        }
        #menu-bar{
            background-color: #384e65;
        }
        #menu-bar ul li a{
            color: #fff;
        }
        #join-btn{
            text-decoration: none;
            padding: 10px 40px;
            border:2px solid #fff;
            font-size: 18px;
            background-color: rgb(0, 163, 217);
            color: #fff;
            cursor: pointer;
        }
        #join-btn:hover{
            background-color: #fff;
            color:  rgb(0, 163, 217);
            border: 2px solid rgb(0, 163, 217);
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
                    <li><a href="#row1">Q&A Forum</a></li>
                    <li><a href="#row2">Features</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
</div>

<div id="body" class="text-center">
    <div class="msg-1">
        <p>{{ $existingCommunity->institute_name }}</p>
        <p>{{ $existingCommunity->dept_name }}</p>
    </div>
    <p>For <strong>any</strong>  question, don't hesitate to mail : <b>letsorgan@gmail.com</b> </p>
    <br><div><a id="join-btn" href="{{ $joinLink }}">Join Now</a></div><br>
    @if(count($allMembers)>0)
        <p style="font-size: 20px"><u>All members are :</u></p>
    @endif
    <div class="row col-md-12">
        @foreach($allMembers as $member)
            <div class="col-md-3" style="background-color: rgba(226,230,249,0.86); padding: 5px; margin-top: 5px; border: 1px solid #ffffffaa">
                <p>Name : {{ $member->name }}</p>
                <p>Session : {{ $member->academic_session }}</p>
            </div>
        @endforeach
    </div>


    {{--<span style="font-size: 20px">Join</span>--}}
    {{--<span class="msg-2"><a href="/community/join/{{ $join_token }}"><b>{{ $community_name }}</b></a> </span>--}}
</div>

</body>
</html>