<?php

$pageTitle = "Tutorials Suggested by Geeks";

$filePathPrefix = "https://s3.ap-south-1.amazonaws.com/choriyedao";

$Lib = new \App\Library\Library();

?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <h3>Tutorials Suggested by Geeks</h3>

        <div id="tut-container" class="col-md-12">
            @foreach($videos as $video)
                <div class="single-video">
                    <iframe class="col-md-12" height="500px" src="https://www.youtube.com/embed/{{ $Lib->getYoutubeVideoId($video->link) }}" frameborder="0" allowfullscreen></iframe>
                    <div>
                        <p>Category : {{ $video->category }}</p>
                        <p class="video-title">{{ $video->title }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection