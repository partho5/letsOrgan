<?php

$pageTitle = 'Respond To Upload Request';

?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <h3>Plesae upload the file <small>(if you have)</small></h3>
        <form action="/upload/request/respond/{{$request_id or ""}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" name="request_id" value="{{$request_id or ""}}">
            <input type="hidden" name="uploaded_by" value="{{$user_id or ""}}">
            <table class="table">
                <tr>
                    <td class="text-left">Name : </td>
                    <td class="text-left">{{ $requestedFile->file_name  or ""}}</td>
                </tr>
                <tr>
                    <td class="text-left">File Type : </td>
                    <td class="text-left">{{ $requestedFile->file_category  or ""}}</td>
                </tr>
                <tr>
                    <td class="text-left">Details</td>
                    <td class="text-left">{{ $requestedFile->details  or ""}}</td>
                </tr>
            </table>
            <div id="respondToUpRequest" class="collapse col-md-8 col-md-offset-2">
                <div class="col-md-8">
                    <input type="file" id="requested_file" name="requested_file" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <button id="uploadRequestedBtn" class="form-control btn btn-success">Upload</button>
                </div>
            </div>
        </form>
        <button class="btn spanBtn text-center" data-toggle="collapse"
                data-target="#respondToUpRequest">Yes, I have this file</button>
    </div>

    <script>
        $(document).ready(function () {

        });
    </script>
@endsection
