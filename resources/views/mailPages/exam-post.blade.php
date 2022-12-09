<html>
<head>
    <style>
        h2{
            font-family: Galada;
            color: #007c48;
            text-align: center;
        }
        h2 span{
            border-bottom: 1px solid #ff5500dd;
        }
        #details{
            padding: 5px 20px;
        }
        .my-hr-1 {
            background-color: #e2db2a;
            display: block;
            height: 2px;
        }
        #extension{
            text-align: center;
        }
        #extension #motto{
            font-size: 25px;
        }
        .site-name{
            color: #5b5d5f;
            font-size: 12px;
        }
        #posted-by{
            text-align: left;
        }
    </style>
</head>
<body>

<?php

//$examInfo = [
//    'course'    => 'poser 2321',
//    'declaredAt'=> '12/2/2017',
//    'examDate'  => ' 2017-12-23 10:02:00',
//    'details'   => 'v ndnfvdnnvdfkln kjldfn',
//    'link'      => 'linkkkkk',
//    'postedBy'  => 'partho'
//];

?>


<h2><span>{!! $examInfo['course'] !!}</span></h2>
<p id="details">
<p>Declared at : {{ Carbon\Carbon::parse($examInfo['declaredAt'])->format('j F') }}</p>
<p style="color: #ff0000dd">Exam Date : {{ \Carbon\Carbon::parse($examInfo['examDate'])->format('j F, h:i') }}</p>
<p>Details : {!! $examInfo['details'] !!}</p>
<p id="posted-by">
<p>Posted By : {{ $examInfo['postedBy'] }}</p>
<a href="{{ $examInfo['link'] }}">Read this post in website</a>
</p>
</p>
<p class="my-hr-1"></p>
<div id="extension">
    <p id="motto">
        Lets Organize All Your Study Materials <br>
        <small class="site-name">letsorgan.com</small>
    </p>
</div>
<p class="my-hr-1"></p>


</body>
</html>