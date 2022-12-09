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
<h2><span>{!! $uploadInfo['title'] !!}</span></h2>
<p id="details">
{!! $uploadInfo['details'] !!}
<p id="posted-by">
<p><b>Posted By : {{ $uploadInfo['postedBy'] }}</b></p>
<a href="{{ $uploadInfo['link'] }}">Read this post in website</a>
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