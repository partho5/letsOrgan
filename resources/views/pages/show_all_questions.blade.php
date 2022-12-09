<?php

$pageTitle = 'All Questions';


?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <h3>Most Recent Questions</h3>

        @foreach($allQuestions as $question)
            <div class="single-q-wrapper col-md-12">
                <p class="text-left"><strong><a href="/users/question/{{$question->id}}">{{$question->question}}</a></strong></p>
                <div class="col-md-12 text-left">
                    <span class="col-md-2">Tags : </span>
                    <ul class="list-inline col-md-10">
                        <li>{{json_decode($question->topic)}}</li>
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@endsection
