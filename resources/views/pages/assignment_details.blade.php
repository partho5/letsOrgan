<?php

$pageTitle = $assignmentDetails->course_name;

?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <div id="examDetailsWrapper" class="col-md-12">
            <h3>Details About This Assignment</h3>
            <table class="table">
                <tr>
                    <td class="col-md-4 text-left">Course Name</td>
                    <td class="col-md-8 text-left">{{ $assignmentDetails->course_name }}</td>
                </tr>
                <tr>
                    <td class="col-md-4 text-left">Assignment given at</td>
                    <td class="col-md-8 text-left">{{ \Carbon\Carbon::parse($assignmentDetails->given_at)->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td class="col-md-4 text-left">Submit before</td>
                    <td class="col-md-8 text-left">{{ $assignmentDetails->deadline }}</td>
                </tr>
                <tr>
                    <td class="col-md-4 text-left">Details</td>
                    <td class="col-md-8 text-left">{!! $assignmentDetails->details !!}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
