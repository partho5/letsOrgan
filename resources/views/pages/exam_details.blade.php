<?php

$pageTitle = $examDetails->course_name .' Exam';

?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <div id="examDetailsWrapper" class="col-md-12">
            <h3>Details About This Exam</h3>
            <table class="table">
                <tr>
                    <td class="col-md-4 text-left">Course Name</td>
                    <td class="col-md-8 text-left">{{ $examDetails->course_name }}</td>
                </tr>
                <tr>
                    <td class="col-md-4 text-left">Exam Declared at</td>
                    <td class="col-md-8 text-left">{{ \Carbon\Carbon::parse($examDetails->declared_at)->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td class="col-md-4 text-left">Exam Date</td>
                    <td class="col-md-8 text-left">{{ $examDetails->exam_date }}</td>
                </tr>
                <tr>
                    <td class="col-md-4 text-left">Details</td>
                </tr>
                <tr>
                    <td class="col-md-8 text-left">{!! $examDetails->details !!}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
