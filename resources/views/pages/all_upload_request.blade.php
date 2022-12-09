<?php

$pageTitle = "Upload Requests";

$Lib = new \App\Library\Library();

?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        @if (session('success_msg'))
            <div class="alert alert-info">{{ session('success_msg') }}</div>
        @endif

            @if( count($allRequests) > 0 )
                <h3>All Upload Requests</h3>
            @endif
        <ul>
            @foreach( $allRequests as $request)
                <li class="each-upload-request alert alert-success">
                    <ul class="list-inline text-left">
                        <li><a href="/upload/request/respond/{{ $request->id }}"><strong>{{ $request->file_name }}</strong></a></li>
                        <li><small>Requested by</small> <a href="/users/profile/id/{{ $request->user_id }}">{{ $Lib->getUserName($request->user_id) }}</a></li>
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>
@endsection