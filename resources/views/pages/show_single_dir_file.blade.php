<?php

$singleDirFile = $singleDirFile[0];

if( isset($cloudInfo) ){
    //then it's file
    $cloudInfo = $cloudInfo[0];
    $pageTitle = $singleDirFile->book_name ?: $singleDirFile->file_name ;
}
else{
    //then it's directory
    $pageTitle = $singleDirFile->name;
}

$myLibrary = new \App\Library\Library();


?>

@extends('app')


@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        @if( $fileType == 'book' )
            <h3>Book Details</h3>
            <table class="table">
                <tr>
                    <td class="text-left">Book Name</td>
                    <td class="text-left">{{$singleDirFile->book_name}}</td>
                </tr>
                <tr>
                    <td class="text-left">Author</td>
                    <td class="text-left">{{$singleDirFile->author}}</td>
                </tr>
                <tr>
                    <td class="text-left">Category</td>
                    <td class="text-left">{{$singleDirFile->category}}</td>
                </tr>
                <tr>
                    <td class="text-left">File Size</td>
                    <td class="text-left">{{$myLibrary->fileSizeReadAbleFormat($cloudInfo->file_size)}}</td>
                </tr>
                <tr>
                    <td class="text-left">Description</td>
                    <td class="text-left">{{$singleDirFile->description}}</td>
                </tr>
                <tr>
                    <td class="text-left">Uploaded at</td>
                    <td class="text-left">{{$singleDirFile->created_at}}</td>
                </tr>
                <tr>
                    <td class="text-left">Total View</td>
                    <td class="text-left">{{$singleDirFile->total_view}}</td>
                </tr>
                <tr>
                    <td class="text-left">Total Download</td>
                    <td class="text-left">{{$singleDirFile->total_download}}</td>
                </tr>
            </table>
            <div>
                <span><a class="spanBtn" href="/users/cloud/preview/{{$singleDirFile->cloud_id}}">Preview</a></span>
                <span><a class="spanBtn" href="/users/cloud/download/{{$singleDirFile->cloud_id}}">Download</a></span>
                <span><a class="spanBtn" href="/users/cloud/view/{{$singleDirFile->cloud_id}}/open" style="background-color: #de3200">Open in <b>My Files</b></a></span>
                <span class="hidden"><a class="spanBtn" href="/users/cloud/share/{{$singleDirFile->cloud_id}}">Share</a></span>
            </div>
        @elseif( $fileType == 'other_file' )
            <h3>File Details</h3>
            <table class="table">
                <tr>
                    <td>File Type</td>
                    <td>{{$singleDirFile->file_type}}</td>
                </tr>
                <tr>
                    <td>File Name</td>
                    <td>{{$singleDirFile->file_name}}</td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>{{$singleDirFile->category}}</td>
                </tr>
                <tr>
                    <td>File Size</td>
                    <td>{{$myLibrary->fileSizeReadAbleFormat($cloudInfo->file_size)}}</td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td>{{$singleDirFile->description}}</td>
                </tr>
                <tr>
                    <td>Uploaded at</td>
                    <td>{{$singleDirFile->created_at}}</td>
                </tr>
                <tr>
                    <td>Total View</td>
                    <td>{{$singleDirFile->total_view}}</td>
                </tr>
                <tr>
                    <td>Total Download</td>
                    <td>{{$singleDirFile->total_download}}</td>
                </tr>
            </table>
            <div>
                <span><a class="spanBtn" href="/users/cloud/preview/{{$singleDirFile->cloud_id}}">Preview</a></span>
                <span><a class="spanBtn" href="/users/cloud/download/{{$singleDirFile->cloud_id}}">Download</a></span>
                <span><a class="spanBtn" href="/users/cloud/view/{{$singleDirFile->cloud_id}}/open" style="background-color: #de3200">Open in <b>My Files</b></a></span>
                <span class="hidden"><a class="spanBtn" href="/users/cloud/share/{{$singleDirFile->cloud_id}}">Share</a></span>
            </div>

        @elseif($fileType == 'Question')
            <h3>Question Details</h3>
            <table class="table">
                <tr>
                    <td>File Name</td>
                    <td>{{ $singleDirFile->name }}</td>
                </tr>
                <tr>
                    <td>Course Name</td>
                    <td>{{ $singleDirFile->course_name }}</td>
                </tr>
                <tr>
                    <td>Course Code</td>
                    <td>{{ $singleDirFile->course_code }}</td>
                </tr>
                <tr>
                    <td>Question of</td>
                    <td>{{ $singleDirFile->question_of }} exam</td>
                </tr>
                <tr>
                    <td>Year</td>
                    <td>{{ $singleDirFile->year }}</td>
                </tr>
                <tr>
                    <td>File Size</td>
                    <td>{{ $singleDirFile->file_size }} KB</td>
                </tr>
            </table>
            <div>
                <span><a class="spanBtn" href="/users/cloud/preview/{{$singleDirFile->cloud_id}}">Preview</a></span>
                <span><a class="spanBtn" href="/users/cloud/download/{{$singleDirFile->cloud_id}}">Download</a></span>
                <span><a class="spanBtn" href="/users/cloud/view/{{$singleDirFile->cloud_id}}/open" style="background-color: #de3200">Open in <b>My Files</b></a></span>
                <span class="hidden"><a class="spanBtn" href="/users/cloud/share/{{$singleDirFile->cloud_id}}">Share</a></span>
            </div>

        @elseif( $fileType == 'dir' )
            <h3>Folder Details</h3>

            <table class="table">
                <tr>
                    <td>Folder Name</td>
                    <td>{{$singleDirFile->name}}</td>
                </tr>
                <tr>
                    <td>Parent Folder</td>
                    <td>{{$singleDirFile->full_dir_url}}</td>
                </tr>
                <tr>
                    <td>Total Files</td>
                    <td>{{$singleDirFile->total_files}}</td>
                </tr>
                <tr>
                    <td>Folder Size</td>
                    <td>{{$myLibrary->fileSizeReadAbleFormat($singleDirFile->dirSize)}}</td>
                </tr>
                <tr>
                    <td>Date Created</td>
                    <td>{{$singleDirFile->created_at}}</td>
                </tr>
            </table>
        @endif
    </div>
@endsection
