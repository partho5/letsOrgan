<?php

$pageTitle = "All Members";

?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <h3>All Members <small>( {{ count($allMembers) }} )</small></h3>
        <div class="row col-md-12">
            @foreach($allMembers as $member)
                <div class="col-md-5 col-md-offset-1" style="background-color: #e7ecff; padding: 5px; margin-top: 5px">
                    <p>{{ $member->name }}</p>
                    <p>Academic Session : {{ $member->academic_session }}</p>
                    <a href="/users/profile/id/{{ $member->user_id }}">View Profile</a>
                </div>
            @endforeach
        </div>

    </div>
@endsection
