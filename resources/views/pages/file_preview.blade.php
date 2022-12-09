<?php

$file_path = "https://s3.ap-south-1.amazonaws.com/choriyedao/".$fileInfo->file_path;
//$file_path = "/uploaded/".$fileInfo->file_path;



?>


<!DOCTYPE html>
<html>
<head>
    <title>{{ $file_name }}</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" media="screen" />
</head>
<body>
    <div class="container col-md-12">
        <div class="col-md-10 col-md-offset-1" id="filePreviewMenuBar">
            @if($loggedIn)
                <a onclick="history.go(-1)" href="/users/cloud/view/{{$fileInfo->cloud_id}}" class="btn btn-success col-md-3 col-md-offset-1"><< Back to Details</a>
                <a href="/users/cloud/download/{{$fileInfo->cloud_id}}" class="btn btn-success col-md-3 col-md-offset-1">Download</a>
                <a href="/users/cloud/share/{{$fileInfo->cloud_id}}" class="hidden btn btn-success col-md-3 col-md-offset-1">Share</a>
            @else
                <div class="col-md-12 text-center">
                    <img src="/assets/images/choriyedao-logo.png" class="col-md-3" width="170px" height="150px" alt="">
                    <div class="col-md-9">
                        <h1 style="color: #0c9540">ChoriyeDao</h1>
                        <h3 style="color: #d66c2d !important;">A platform organizes all your study materials</h3>
                        <h4><small>To smartly organize your own study materials <a href="/">Join ChoriyeDao</a></small></h4>
                    </div>
                    <div class="col-md-8">
                        <b>{{ $file_name }}</b> <a href="/users/cloud/download/{{$fileInfo->cloud_id}}" class="btn btn-success"> Download </a>
                    </div>
                </div>
            @endif
        </div>

        <?php $file_ext = strtolower($file_ext); ?>
        <div class="col-md-12">
            @if( in_array($file_ext, ['png', 'jpg', 'jpeg', 'gif', 'svg', 'bmp']) )
                <img src="{{$file_path}}" alt="Unable to load image" class="col-md-10 col-md-offset-1">
            @elseif( in_array($file_ext, ['pdf', 'doc', 'docx', 'txt', 'ppt', 'pptx', 'webm', 'mpeg4', 'mp4', '3gpp', 'mov', 'avi', 'mpegps', 'wmv', 'flv', 'ogg']) )
                <object data="{{$file_path}}">
                    <iframe src="https://docs.google.com/viewer?url={{$file_path}}&embedded=true" width="100%" height="800px"></iframe>
                </object>
            @else
                <p class="text-center">Sorry ! No preview available for {{ $file_ext }} file. Directly download it</p>
            @endif
        </div>
    </div>
</body>
</html>