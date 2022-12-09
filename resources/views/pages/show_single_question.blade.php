<?php

use App\Library\Library;

$pageTitle = $question->question ;

$questionTopic = json_decode($question->topic);

$Lib = new Library();

?>


@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <div id="questionWrapper">

            <h4 style="color: #000">{{$question->question}}</h4>
            <div class="col-md-12" id="q-wrapper">
                <div class="voting col-md-1  col-sm-2 col-xs-2">
                @if( $user->id != $question->user_id ) <!-- vote options wont show for his own question -->
                    @if( $Lib->alreadyVoted('q', $question->id , 'up') )
                        <p class="triangle_up upvoted_triangle" type="q" id="q-upvote" q-id="{{$question->id}}" title="Already Voted"></p>
                    @else
                        <p class="triangle_up" type="q" id="q-upvote" q-id="{{$question->id}}" title="This question is useful"></p>
                    @endif
                    <p class="vote_count">{{$question->upvote - $question->downvote}}</p>
                    @if( $Lib->alreadyVoted('q', $question->id , 'down') )
                        <p class="triangle_down downvoted_triangle" type="q" id="q-downvote" q-id="{{$question->id}}" title="Already Voted"></p>
                    @else
                        <p class="triangle_down" type="q" id="q-downvote" q-id="{{$question->id}}" title="The question needs improvement"></p>
                    @endif
                @else
                    <p class="triangle_up" title="You cannot vote for your own question"></p>
                    <p class="vote_count">{{$question->upvote - $question->downvote}}</p>
                    <p class="triangle_down" title="You cannot vote for your own question"></p>
                @endif
                </div>
                <div id="q_details{{$question->id}}" class="col-md-11 col-sm-10 col-xs-10 text-left">
                    {{$question->details}}
                </div>
            </div> <!-- #q_wrapper -->


            <div class="col-md-12"> <!-- some basic actions wrapper -->
                <span class="col-md-1">Tag:</span>
                <ul class="list-inline col-md-7 text-left" id="question-topic">
                    <li>{{$questionTopic}}</li>
                </ul>

                <div class="col-md-4 user_info text-left" id="q_asked_by_wrapper">
                    <p class="col-md-3"><img src="{{$question->pp_url}}" width="40px" height="40px" alt="avatar"></p>
                    {{$question->created_at}}
                    <p><a href="/users/profile/id/{{$question->user_id}}">{{$question->username}}</a></p>
                </div>

                {{--<div id="q_comments_wrapper" class="col-md-12">--}}
                    {{--<div class="single_q_comment text-left">--}}
                        {{--@foreach($qComments as $comment)--}}
                            {{--<p>--}}
                                {{--<a class="commenter_name" href="/users/profile/id/{{ $comment->comment_by }}">{{ $Lib->getUserName($comment->comment_by) }}</a>--}}
                                 {{-->> <span id="comment_text_q{{ $comment->id }}">{{ $comment->comment}}</span>--}}
                                {{--@if($comment->comment_by == $user->id)--}}
                                    {{--&nbsp;&nbsp;&nbsp;&nbsp; <span class="edit_comment" comment_for="q" id="{{ $comment->id }}" q_id="{{ $question->id }}">Edit</span>--}}
                                    {{--&nbsp;&nbsp;    <span class="delete_comment" comment_for="q" id="{{ $comment->id }}" q_id="{{ $question->id }}">Delete</span>--}}
                                {{--@endif--}}
                            {{--</p>--}}
                        {{--@endforeach--}}
                    {{--</div>--}}
                {{--</div> <!-- q_comments_wrapper -->--}}


                @if( ! $alreadyAnswered )
                    <div id="my-ans-wrapper" class="col-md-12">
                        <h5 class="text-center">Write your answer ( 20 letters at least )</h5>
                        <textarea name="my-ans" id="my-ans" rows="5" class="col-md-12"></textarea>
                        <button question-id="{{$question->id}}" id="post-my-ans-btn" class="btn spanBtn text-center">Post My Answer</button>
                    </div>
                @endif
            </div>
        </div>



        <br><p><Strong>{{ count($allAnswers) }}</Strong> Answers</p>
        <div id="ans-wrapper" class="col-md-12 col-sm-12 col-xs-12">
            @foreach( $allAnswers as $answer )
                <div class="single-ans-wrapper col-md-12 col-sm-12 col-xs-12">
                    <hr>
                    @if( $user->id != $answer->user_id ) <!-- vote options wont show for his own question -->
                        <div class="voting col-md-1  col-sm-2 col-xs-2">
                            @if( $Lib->alreadyVoted('a', $answer->id , 'up') )
                                <p class="triangle_up upvoted_triangle" title="Already Upvoted"></p>
                            @else
                                <p class="triangle_up" type="ans" ans-id="{{$answer->id}}" title="This answer is useful"></p>
                            @endif
                            <p class="vote_count">{{$answer->upvote - $answer->downvote}}</p>
                            @if( $Lib->alreadyVoted('a', $answer->id , 'down') )
                                <p class="triangle_down downvoted_triangle" title="Already Downvoted"></p>
                            @else
                                <p class="triangle_down" type="ans" ans-id="{{$answer->id}}" title="The answer isn't good"></p>
                            @endif
                        </div>
                    @endif

                    <div id="answer{{$answer->id}}" class="col-md-11 col-sm-10 col-xs-10 text-left">
                        {!! $answer->answer !!}
                    </div>

                    <div id=""><br>
                        <div class="col-md-5 col-sm-12 col-xs-12 user_info text-left">
                            <p class="col-md-3"><img src="{{$answer->pp_url}}" width="40px" height="40px" alt="avatar"></p>
                            <span class="text-right">{{$answer->created_at}}</span>
                            <p><a href="/users/profile/id/{{$answer->uid}}">{{$answer->username}}</a></p>
                        </div>

                        @if( $user->id == $answer->user_id )
                            <ul class="list-inline my-ans-actions">
                                <li class="spanBtn" ans-id="{{$answer->id}}" id="edit-ans-btn">Edit answer</li>
                                <li class="spanBtnDelete" id="ans-del-btn" ans-id="{{$answer->id}}">Delete</li>
                            </ul>
                        @endif


                        {{--<div id="ans_comments_wrapper{{ $question->id }}{{ $answer->id }}" class="col-md-12">--}}
                            {{--<div class="single_ans_comment text-left">--}}
                                {{--@foreach($qComments as $comment)--}}
                                    {{--<p>--}}
                                        {{--<a class="commenter_name" href="/users/profile/id/{{ $comment->comment_by }}">{{ $Lib->getUserName($comment->comment_by) }}</a>--}}
                                        {{-->> <span id="comment-text-{{ $question->id }}{{ $answer->id }}">{{ $comment->comment}}</span>--}}
                                        {{--@if($comment->comment_by == $user->id)--}}
                                            {{--&nbsp;&nbsp;&nbsp;&nbsp; <span class="edit-comment" comment-for="ans" comment-id="{{ $comment->id }}" q-id="{{ $question->id }}" ans-id="{{ $answer->id }}">Edit</span>--}}
                                            {{--&nbsp;&nbsp;    <span class="delete-comment" comment-for="ans" comment-id="{{ $comment->id }}" q-id="{{ $question->id }}" ans-id="{{ $answer->id }}">Delete</span>--}}
                                        {{--@endif--}}
                                    {{--</p>--}}
                                {{--@endforeach--}}
                            {{--</div>--}}
                        {{--</div> <!-- q_comments_wrapper -->--}}

                        {{--<ul class="list-inline text-left col-md-8" id="interact-to-q">--}}
                            {{--<li id="comment-to-q" data-toggle="collapse" data-target="#comment-field-wrapper">Add a comment</li>--}}
                            {{--<li class="spanBtn hidden" id="ans-to-q" data-toggle="collapse" data-target="#my-ans-wrapper">Write answer</li>--}}
                        {{--</ul>--}}

                        {{--<div id="comment-field-wrapper" class="collapse col-md-12">--}}
                            {{--<textarea name="" id="q_comment_field{{ $question->id }}" cols="30" class="q_comment_field col-md-10" placeholder="comment should be short and precise"></textarea>--}}
                            {{--<button class="q_comment_btn btn btn-default col-md-2" id="{{ $question->id }}">Comment</button>--}}
                        {{--</div>--}}
                        
                    </div>
                </div>
            @endforeach
            <hr>
        </div>
    </div>

    <script src="/assets/js/library.js"></script>
    <script type="text/javascript" src="/assets/js/ajaxMsgLibrary.js"></script>
    <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
    <script type="text/javascript">
        new nicEditor().panelInstance('my-ans'); //'#my-ans', it's 'id'
        $('.nicEdit-panelContain').parent().width('100%');
        $('.nicEdit-panelContain').parent().next().width('100%');
    </script>

    <script>
        $(document).ready(function () {
            $('#post-my-ans-btn').click(function(){
                var questionId = $(this).attr('question-id');
                nicEdit = new nicEditors.findEditor('my-ans');
                var ans = nicEdit.getContent();

                if(ans.length > 20){
                    $.ajax({
                        url : '/users/answer/save',
                        type : 'post',
                        dataType : 'text',
                        data : {
                            _token : "<?php echo csrf_token() ?>",
                            question_id : questionId,
                            answer : ans
                        },
                        success : function (ansId) {
                            if( ! isNaN(ansId) ){
                                nicEditors.findEditor('my-ans').setContent('');

                                $('#ans-wrapper').prepend(
                                    '<div class="single-ans-wrapper col-md-12 col-sm-12 col-xs-12">'+
                                    '<div id="answer'+ansId+'" class="col-md-11 col-sm-10 col-xs-10 text-left">'+ ans+ '</div>'+
                                    '<ul class="list-inline my-ans-actions">'+
                                    '<li class="spanBtn" ans-id="'+ansId+'" id="edit-ans-btn">Edit answer</li>'+
                                    '<li class="spanBtnDelete" id="ans-del-btn" ans-id="'+ansId+'">Delete</li>'+
                                    '</ul>'+
                                    '</div>'
                                );
                            }
                        },
                        error : function () {
                        }
                    }); //ajax
                } //if
            });//post-ans click

            $(document).on('click', '#ans-del-btn', function () {
                if( confirm("Delete Answer ?") ){
                    var ansId = $(this).attr('ans-id');
                    var THIS = $(this);

                    $.ajax({
                        url : '/users/answer/delete',
                        type : 'post',
                        dataType : 'text',
                        data : {
                            _token : "<?php  echo csrf_token() ?>",
                            ansId : ansId
                        },
                        success : function (response) {
                            if( response === 'deleted'){
                                THIS.closest('.single-ans-wrapper').css('opacity', 0.3);
                                THIS.closest('.single-ans-wrapper').slideToggle(1500);
                            }
                        }, error : function () {
                            alert("Error ! Try again or contact support");
                        }
                    });//ajax
                }
            }); //delete ans click

            $(document).on('click', '#edit-ans-btn', function () {
                var ansId = $(this).attr('ans-id');
                //nicEdit = new nicEditors.findEditor('');
                var ans = $("<div />").append($('#answer'+ansId).clone()).html();
                //https://stackoverflow.com/questions/3614212/jquery-get-html-of-a-whole-element
                setFakeCookie('ans'+ansId, ans);

                $('#answer'+ansId).replaceWith(
                    '<div id="'+'editable-ans-wrapper'+'">'+
                    '<textarea id="editable-ans-'+ansId+'" rows="5" class="col-md-12"></textarea>'+
                    '<button ans-id="'+ansId+'" id="update-btn" class="btn spanBtn text-center">Update Answer</button>'+
                    '<button ans-id="'+ansId+'" id="cancel-btn" class="btn spanBtnDelete text-center">Cancel</button>'+
                    '</div>'
                );
                $(this).closest('.my-ans-actions').hide();

                new nicEditor().panelInstance('editable-ans-'+ansId);

                nicEditors.findEditor('editable-ans-'+ansId).setContent(ans);
            }); // #edit-ans-btn click

            $(document).on('click', '#cancel-btn', function () {
                var ansId = $(this).attr('ans-id');
                $('#editable-ans-wrapper').replaceWith(
                    '<div id="answer'+ansId+'" class="text-left">'+
                    getFakeCookie('ans'+ansId)+
                    '</div>'+
                    '<ul class="list-inline my-ans-actions">'+
                    '<li class="spanBtn" ans-id="'+ansId+'" id="edit-ans-btn">Edit answer</li>'+
                    '<li class="spanBtnDelete" id="ans-del-btn" ans-id="'+ansId+'">Delete</li>'+
                    '</ul>'
                );
                //$('.my-ans-actions').show();
            }); // #cancel-btn clicked
            
            $(document).on('click', '#update-btn', function () {
                var ansId = $(this).attr('ans-id');
                nicEdit = new nicEditors.findEditor('editable-ans-'+ansId);
                var editedAns = nicEdit.getContent();

                $.ajax({
                    url : '/users/answer/update',
                    type : 'post',
                    dataType : 'text',
                    data : {
                        _token : "<?php echo csrf_token() ?>",
                        ansId : ansId,
                        answer : editedAns
                    },
                    success : function (response) {
                        if(response === 'success' ){
                            console.log(response);
                        }
                    }
                });

                $('#editable-ans-wrapper').replaceWith(
                    '<div id="answer'+ansId+'">'+
                    editedAns+
                    '</div>'+
                    '<ul class="list-inline my-ans-actions">'+
                    '<li class="spanBtn" ans-id="'+ansId+'" id="edit-ans-btn">Edit answer</li>'+
                    '<li class="spanBtnDelete" id="ans-del-btn" ans-id="'+ansId+'">Delete</li>'+
                    '</ul>'
                );
            }); //#update-btn clicked


            $('.triangle_up').click(function () {
                var THIS = $(this);
                var type = $(this).attr('type');
                var id = $(this).attr(type+'-id');

                $.ajax({
                    url : '/users/question/vote',
                    type : 'post',
                    dataType : 'text',
                    data : {
                        _token : "<?php echo csrf_token() ?>",
                        type : type, // q or ans
                        upvoteId : id
                    },
                    success : function (response) {
                        if( response === 'success' ){
                            THIS.css('border-bottom', '20px solid #e78d0d');
                            THIS.next().next().css('border-top', '20px solid #a5a5a5');
                            incrementByOne(THIS.next());
                        }
                    },error : function () {
                    }
                }); //ajax
            }); //upvote

            $('.triangle_down').click(function () {
                var THIS = $(this);
                var type = $(this).attr('type');
                var id = $(this).attr(type+'-id');

                $.ajax({
                    url : '/users/question/vote',
                    type : 'post',
                    dataType : 'text',
                    data : {
                        _token : "<?php echo csrf_token() ?>",
                        type : type, // q or ans
                        downvoteId : id
                    },
                    success : function (response) {
                        if( response === 'success' ){
                            THIS.css('border-top', '20px solid #e78d0d');
                            THIS.prev().prev().css('border-bottom', '20px solid #a5a5a5');
                            decrementByOne(THIS.prev());
                        }
                    },error : function () {
                    }
                }); //ajax
            }); //downvote


            $('.q_comment_btn').click(function () {
                var THIS = $(this);
                var wait4response = new Wait4Response();
                var qId = THIS.attr('id');
                var commentText = $('#q_comment_field'+qId).val();
                if(commentText){
                    wait4response.eventFired(THIS, "Wait...");
                    $.ajax({
                        url : '/users/question/comment_for_q',
                        type : 'post',
                        dataType : 'text',
                        data : {
                            _token : "<?php echo csrf_token() ?>",
                            q_id : qId,
                            comment : commentText
                        },
                        success : function (response) {
                            //console.log(response);
                            if(response === 'success' ){
                                $('#q_comments_wrapper').append(
                                    '<div class="single_q_comment text-left">'+
                                    '<p>[ Me ] '+commentText+'</p>'+
                                    '</div>'
                                );
                                $('#q_comment_field'+qId).val('');
                                wait4response.succeed(THIS, '' , 2);
                            }
                        },error : function (xhr, status, error) {
                        }
                    });  // ajax
                }
                console.log(commentText);
            });  // q_comment_btn clicked


            $('.edit_comment').click(function () {
                var commentFor = $(this).attr('comment-for');
                var commentId = $(this).attr('comment-id');
                var qId = $(this).attr('q-id');
                var ansId= $(this).attr('ans-id');

                $('#comment-text-'+qId+ansId).replaceWith(
                    '<div id="editable_comment_for_'+commentFor+commentId+'">' +
                        '<textarea class="form-control">'+($('#comment_text_q'+commentId).text())+'</textarea>' +
                        '<button class="finish_edit btn btn-default" q_id="'+qId+'" comment_id="'+commentId+'" comment_for="'+commentFor+'">Finish Editing</button>' +
                    '</div>'
                );

                console.log(commentId+"--"+commentFor);
            }); // edit_comment


            $(document).on('click', '.finish_edit', function () {
                var commentFor = $(this).attr('comment_for');
                var commentId = $(this).attr('comment_id');
                var qId = $(this).attr('q_id');
                var editableWrapper = $('#editable_comment_for_'+commentFor+commentId);
                var editedText = $('#editable_comment_for_'+commentFor+commentId+' textarea').val();

                if(editedText){
                    $.ajax({
                        url : '/users/question/edit_comment',
                        type : 'post',
                        dataType : 'text',
                        data : {
                            _token : "<?php echo csrf_token() ?>",
                            qId : qId,
                            commentFor : commentFor,
                            commentId : commentId,
                            editedComment : editedText
                        },
                        success : function (response) {
                            if(response === 'success' || 1){
                                console.log(response);
                                editableWrapper.replaceWith(
                                    '<span id="comment_text_'+commentFor+commentId+'">'+editedText+'</span>'
                                );
                            }
                        }
                    }); //ajax
                } //if
            });
            
        }); //ready
    </script>
@endsection
