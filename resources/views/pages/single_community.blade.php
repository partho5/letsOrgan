<?php

$pageTitle = $community->community_name;

$filePathPrefix = "https://s3.ap-south-1.amazonaws.com/choriyedao";
$classRoutinePath =  is_null($classRoutine) ? "/assets/images/sample_routine.png" : $filePathPrefix.'/'.$classRoutine->file_path;

$currentYear = date('Y');

?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <h3>{{$community->community_name}}</h3>
        @if( ! is_null($academicSession) )
            <strong id="my-session">{{ $academicSession }}</strong> Session
        @else
            <p id="suggestion-1" style="background-color: #f00; color: #fff">
                Please select your academic year from <a href="/users/community/settings" style="color: #000;">Settings</a>.
                Otherwise some important features won't work
            </p>
        @endif

        <div id="single-community-top1">
            <span><small><a href="/community/view/all_members">All members</a></small></span>
            <span><small id="addMemberBtn">Add Member</small></span>
            <span><small id="cls-routine-btn">Class Routine</small></span>
        </div>

        <span id="communityId" style="display: none">{{$community->id}}</span>

        <div id="cls-routine-container" class="col-md-12">
            <div class="col-md-12">
                <p style="color:#ff2a28">Class routine should be a high quality image</p>

                <img src="{{ $classRoutinePath or "/assets/images/sample_routine.png" }}" alt="Update class routine" height="500px" class="col-md-12">
            </div>

            <form action="/community/action/update_cls_routine" method="post" enctype="multipart/form-data" class="col-md-12">
                {{ csrf_field() }}
                <input type="hidden" name="possessed_by_community" value="{{$community->id}}">
                <input type="hidden" name="academic_session" value="{{ $academicSession }}">
                <input type="text" name="title" class="form-control" value="{{ $classRoutine->title or "Official Routine. Published at" }}" required style="background-color: #fff; color: #000">
                <div class="col-md-6">
                    <input type="file" name="cls_routine_file" value="Choose a file" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <input type="submit" value="Update Routine" class="form-control">
                </div>
            </form>

        </div>

        <div id="add_member_link" class="col-md-12">
            <p>Provide this link to others for joining this community</p>
            <div class="col-md-10 col-md-offset-1 input-group">
                <input type="text" id="link"  class="form-control">
                <span id="linkCopyBtn" class="input-group-addon">Copy</span>
            </div>
            <span id="success_msg" style="display: none; color: #1611ff">Copied to Clipboard</span>
        </div>


        <div id="post-wrapper" style="display: {{ $isAdmin ? 'block' : 'none' }}">
            <div> <!-- unnecessary wrapper div , AN ACCIDENT :(  -->
                <div class="col-md-12" id="generalPostContainer">
                    <h5>General Post</h5>
                    <input type="text" id="generalTitle" class="form-control col-md-12" placeholder="A little title">
                    <label class="text-left">Details (Optional) : </label>
                    <textarea name="postField" id="postField" rows="5" class="col-md-12" placeholder="Details"></textarea>
                </div>


                <div id="examContainer" class="col-md-12">
                    <h5>Exam</h5>
                    <hr>
                    <div class="form-group col-md-12">
                        <label for="" class="col-md-4 text-left">Course Code/Name</label>
                        <div class="input-group col-md-8">
                            <input type="text" id="examCourseName" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="" class="col-md-4 text-left">Exam Declared at</label>
                        <div class="input-group col-md-8">
                            <input type="text" id="examDeclareDate" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="" class="col-md-4 text-left">Exam Date Time</label>
                        <div class="input-group col-md-8">
                            <input type="text" id="examDate" class="examDate form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="input-group col-md-12">
                            <label class="text-left">Exam syllabus or other details(Optional) : </label>
                            <textarea name="examDetails" id="examDetails" rows="4" class="col-md-12">Exam syllabus:</textarea>
                        </div>
                    </div>
                </div>

                <div id="assignmentContainer" class="col-md-12">
                    <h5>Assignment</h5>
                    <hr>
                    <div class="form-group col-md-12">
                        <label for="" class="col-md-4 text-left">Course Code/Name</label>
                        <div class="input-group col-md-8">
                            <input type="text" id="assignCourseName" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="" class="col-md-4 text-left">Assignment given at</label>
                        <div class="input-group col-md-8">
                            <input type="text" id="assignGivenDate" class="assignGivenDate form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="" class="col-md-4 text-left">Deadline</label>
                        <div class="input-group col-md-8">
                            <input type="text" id="assignDeadline" class="assignDeadline form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="input-group col-md-12">
                            <label class="text-left">Assignment topics or other details (Optional) : </label>
                            <textarea name="assignmentDetails" id="assignmentDetails" rows="4" class="col-md-12 form-control" placeholder="Details"></textarea>
                        </div>
                    </div>
                </div>

                <div id="pollContainer" class="col-md-12">
                    <h5>Quick Poll</h5>
                    <input type="text" id="pollQuestion" class="form-control" placeholder="Write your question">
                    <br>
                    <div class="col-md-12" id="pollInputWrapper">

                        <div class="form-group">
                            <div class="input-group col-md-6">
                                <input type="text" class="form-control pollOption" placeholder="Option 1">
                                <span class="input-group-addon removePollOption">X</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group col-md-6">
                                <input type="text" class="form-control pollOption" placeholder="Option 2">
                                <span class="input-group-addon removePollOption">X</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <button id="addPollOption" class="btn btn-success">Add Option</button>
                            <span style="color: #20b442">Click 'Post', if done</span>
                        </div>
                    </div>
                </div>


                <div id="noticeContainer" class="col-md-12">
                    <h5>Official Notice</h5>
                    <hr>
                    <div class="form-group col-md-12">
                        <label for="" class="col-md-4 text-left">About what</label>
                        <div class="input-group col-md-8">
                            <input type="text" id="noticeAbout" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="" class="col-md-4 text-left">A notice for</label>
                        <div class="input-group col-md-8">
                            <input type="text" id="noticeFor" class="form-control" value="All students">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="" class="col-md-4 text-left">Published at</label>
                        <div class="input-group col-md-8">
                            <input type="text" id="noticePublishedAt" class="form-control">
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="input-group col-md-12">
                            <label class="text-left">Details (Optional) : </label>
                            <textarea name="noticeDetails" id="noticeDetails" rows="4" class="col-md-12 form-control"></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-12" id="postOptions">
                <div class="col-md-12">
                    <div class="col-md-5">
                        <select class="form-control" name="postType" id="postType">
                            <option value="GeneralPost">General Post</option>
                            <option value="Exam">Exam</option>
                            <option value="Assignment">Assignment</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <select name="academic_session" class="form-control" id="academic-session">
                            <option selected disabled>Select One</option>
                            @for($year = $currentYear+1; $year > $currentYear - 10 ; $year--)
                                <?php
                                $optionVal = ($year-1).' - '.$year;
                                $selectedState = ($optionVal == $academicSession) ? 'selected' : '';
                                ?>
                                <option value="{{ $optionVal }}" {{ $selectedState }}>{{ $optionVal }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-4 hidden">
                        <select class="form-control" name="postAs" id="postAs">
                            <option value="0">{{$user->name}}</option>
                            <option value="1">Anonymous</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button id="postBtn" class="btn spanBtn">Post</button>
                    </div>
                </div>
                <br/><hr/>
            </div>
        </div>


        <div id="CommunityUserPosts" class="col-md-12">

            @foreach($posts as $post)
                @if( isset($post->title) )
                    {{--only generaPosts has 'title' property, so its a general post  --}}
                    <div class="singleUserPost">
                        <div class="col-md-12"> <!-- start heading portion -->
                            @if($post->user_id == $user->id)
                                <div id="generalTitle{{$post->id}}" class="postHeading col-md-10">{{$post->title or ""}}</div>
                                @include('pages.partial.com_post_edit_bar', array('postType'=>'general'))
                            @else
                                <div class="postHeading col-md-12">{{$post->title or ""}}</div>
                            @endif
                        </div> <!-- end heading portion -->

                        <div id="generalDetails{{$post->id}}" class="postDetails">{!! $post->details or "" !!}</div> <!-- general post details -->

                        <div class="col-md-12"> <!-- start 'posted by' portion -->
                            @if($post->is_anonymous)
                                <div class="text-right"><a href="/users/profile/id/0">Anonymous</a> [ <span title="{{$post->created_at}}">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans()}}</span> ] </div>
                            @else
                                <div class="text-right"><a href="/users/profile/id/{{$post->user_id or ""}}">{{$post->username or ""}}</a> [ <span title="{{$post->created_at}}">{{  \Carbon\Carbon::parse($post->created_at)->diffForHumans()}}</span> ] </div>
                            @endif
                        </div> <!-- end 'posted by' portion -->
                        <br><hr>
                    </div>

                @elseif( isset($post->exam_date) )
                    {{--only examPosts has 'exam_date' property, so its a exam post  --}}
                    <div class="singleUserPost">
                        <div class="col-md-12"> <!-- start exam heading portion -->
                            @if($post->user_id == $user->id)
                                <div class="postHeading col-md-10"><span class="courseName" id="examCourseName{{$post->id}}">{{$post->course_name or ""}}</span> exam</div>
                                @include('pages.partial.com_post_edit_bar', array('postType'=>'exam'))
                            @else
                                <div class="postHeading col-md-12"><span class="courseName">{{$post->course_name or ""}}</span> exam</div>
                            @endif
                        </div> <!-- end exam heading portion -->

                        <!-- start exam body portion -->
                        <p for="" class="col-md-12">
                            <span class="col-md-4">Declared At : </span>
                            <span id="examDeclareDate{{$post->id}}" class="col-md-8">{{$post->declared_at}}</span>
                        </p>
                        <p for="" class="col-md-12">
                            <span class="col-md-4">Date time : </span>
                            <span  class="col-md-8">
                                <span style="display: none" id="examDate{{$post->id}}">{{ $post->exam_date }}</span>
                                <span>{{ \Carbon\Carbon::parse($post->exam_date)->format('F j') }}</span>
                                <span class="time-only">{{ \Carbon\Carbon::parse($post->exam_date)->format('g:i A') }}</span>
                            </span>
                        </p>

                        <div id="examDetails{{$post->id}}" class="details">
                            {!! $post->details !!}
                        </div><!-- end exam body portion -->

                        <div class="col-md-12"> <!-- start posted by portion -->
                            @if($post->is_anonymous)
                                <div class="text-right"><a href="/users/profile/id/0">Anonymous</a> [ <span title="{{$post->created_at}}">{{  \Carbon\Carbon::parse($post->created_at)->diffForHumans()}}</span> ] </div>
                            @else
                                <div class="text-right"><a href="/users/profile/id/{{$post->user_id or ""}}">{{$post->username or ""}}</a> [ <span title="{{$post->created_at}}">{{  \Carbon\Carbon::parse($post->created_at)->diffForHumans()}}</span> ] </div>
                            @endif
                        </div> <!-- end posted by portion -->
                        <br><hr>
                    </div> <!-- examPosts -->
                @elseif( isset($post->deadline) )
                    {{--only 'assignments' table has 'deadline' property/column, so its a assignment post  --}}
                    <div class="singleUserPost" id="assignment_container{{ $post->id }}">
                        <div class="col-md-12"> <!-- start assignment heading portion -->
                            @if($post->user_id == $user->id)
                                <div class="postHeading col-md-10">Assignment : <span id="assignment_course_name{{ $post->id }}">{{$post->course_name or ""}}</span> </div>
                                @include('pages.partial.com_post_edit_bar', array('postType'=>'assignment'))
                            @else
                                <div class="postHeading col-md-12">Assignment : <span id="assignment_course_name{{ $post->id }}">{{$post->course_name or ""}}</span> </div>
                            @endif
                        </div> <!-- end assignment heading portion -->

                        <!-- start assignment body portion -->
                        <p for="" class="col-md-12">
                            <span class="col-md-4">Given At : </span>
                            <span class="col-md-8" id="assignment_given_date{{ $post->id }}">{{ \Carbon\Carbon::parse($post->given_date)->format('F j') }}</span>
                        </p>
                        <p for="" class="col-md-12">
                            <span class="col-md-4">Deadline : </span>
                            <span class="col-md-8" id="assigned_deadline{{ $post->id }}">
                                <span>{{ \Carbon\Carbon::parse($post->deadline)->format('F j') }}</span>
                                <span class="time-only">{{ \Carbon\Carbon::parse($post->deadline)->format('g:i A') }}</span>
                                <?php $diffInHours = \Carbon\Carbon::parse($post->deadline)->diffInHours( \Carbon\Carbon::now() ); ?>
                                @if( \Carbon\Carbon::parse($post->deadline) > \Carbon\Carbon::now() )
                                    @if( $diffInHours >= 24 )
                                    <p> [ <small>{{ \Carbon\Carbon::parse($post->deadline)->diffInDays(\Carbon\Carbon::now()) }} days left. Prepared your assignment ? ] </small> </p>
                                    @elseif( $diffInHours < 24 && $diffInHours > 0 )
                                    <p> [ <small>{{ $diffInHours }} hours left. Prepared your assignment ?</small> ] </p>
                                    @endif
                                @endif
                            </span>
                        </p>
                        <div class="details" id="assignment{{ $post->id }}">
                            {!! $post->details !!}
                        </div> <!-- end assignment body portion -->

                        <div class="col-md-12"> <!-- start posted by portion -->
                            @if($post->is_anonymous)
                                <div class="text-right"><a href="/users/profile/id/0">Anonymous</a> [ <span title="{{$post->created_at}}">{{  \Carbon\Carbon::parse($post->created_at)->diffForHumans()}}</span> ] </div>
                            @else
                                <div class="text-right"><a href="/users/profile/id/{{$post->user_id or ""}}">{{$post->username or ""}}</a> [ <span title="{{$post->created_at}}">{{  \Carbon\Carbon::parse($post->created_at)->diffForHumans()}}</span> ] </div>
                            @endif
                        </div> <!-- end posted by portion -->
                        <br><hr>
                    </div> <!-- assignmentPosts -->

                    <div class="singleUserPost" id="editable_assignment_container{{ $post->id }}" style="display: none;">
                        @include('pages.partial.editable_assignment')
                        <hr>
                    </div>
                @elseif( isset($post->question) )
                    {{--only polls has 'question' property, so its a poll type post  --}}
                    <div class="singleUserPost" id="poll_container{{ $post->id }}">
                        <div class="col-md-12"> <!-- start poll heading portion -->
                            @if($post->user_id == $user->id)
                                <div class="postHeading col-md-10" id="poll_q{{ $post->id }}">{{$post->question}}</div>
                                @include('pages.partial.com_post_edit_bar', array('postType'=>'poll'))
                            @else
                                <div class="postHeading col-md-12">{{$post->question}}</div>
                            @endif
                        </div> <!-- end poll heading portion -->
                        <div class="col-md-12">
                            <div id="pollOptionsWrapper">
                                <?php $options = json_decode($post->options); ?>
                                @if(isset($options))
                                    @foreach($options as $key => $option)
                                        <?php
                                        $pollId = $post->id;
                                        $optionId = $key; //$key is the option_id
                                        $myLibrary = new \App\Library\Library();
                                        $checkedState = $myLibrary->pollCheckedStatus($pollId, $optionId);
                                        ?>
                                        <p><span class="totalCastedVote">{{$myLibrary->totalCastedVote($pollId, $optionId)}}</span><input type="radio" {{$checkedState}} poll_id="{{$pollId}}" option_id="{{$optionId}}" name="pollRadio{{$pollId}}" class="pollOptionsRadio">{{$option}}</p>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12"> <!-- start posted by portion -->
                            @if($post->is_anonymous)
                                <div class="text-right"><a href="/users/profile/id/0">Anonymous</a> [ <span title="{{$post->created_at}}">{{  \Carbon\Carbon::parse($post->created_at)->diffForHumans()}}</span> ] </div>
                            @else
                                <div class="text-right"><a href="/users/profile/id/{{$post->user_id or ""}}">{{$post->username or ""}}</a> [ <span title="{{$post->created_at}}">{{  \Carbon\Carbon::parse($post->created_at)->diffForHumans()}}</span> ] </div>
                            @endif
                        </div> <!-- end posted by portion -->
                        <br><hr>
                    </div> <!-- end polls -->

                    @include('pages.partial.editable_poll_container')

                @elseif( isset($post->notice_for) )
                    {{--only community_notices has 'notice_for' property, so its a notice post  --}}
                    <div class="singleUserPost">
                        <div class="col-md-12"> <!-- start notice heading portion -->
                            @if($post->user_id == $user->id)
                                <div class="postHeading col-md-10">Notice : {{$post->about_what or ""}}</div>
                                @include('pages.partial.com_post_edit_bar', array('postType'=>'notice'))
                            @else
                                <div class="postHeading col-md-12">Notice : {{$post->about_what or ""}}</div>
                            @endif
                        </div> <!-- end notice heading portion -->

                        <!-- start exam body portion -->
                        <p for="" class="col-md-12">
                            <span class="col-md-4">Notice published At : </span>
                            <span class="col-md-8">{{$post->published_at}}</span>
                        </p>
                        <p for="" class="col-md-12">
                            <span class="col-md-4">Notice For : </span>
                            <span class="col-md-8">{{$post->notice_for}}</span>
                        </p>
                        <div class="details">
                            {!! $post->details !!}
                        </div> <!-- end notice body portion -->

                        <div class="col-md-12"> <!-- start posted by portion -->
                            @if($post->is_anonymous)
                                <div class="text-right"><a href="/users/profile/id/0">Anonymous</a> [ <span title="{{$post->created_at}}">{{  \Carbon\Carbon::parse($post->created_at)->diffForHumans()}}</span> ] </div>
                            @else
                                <div class="text-right"><a href="/users/profile/id/{{$post->user_id or ""}}">{{$post->username or ""}}</a> [ <span title="{{$post->created_at}}">{{  \Carbon\Carbon::parse($post->created_at)->diffForHumans()}}</span> ] </div>
                            @endif
                        </div> <!-- end posted by portion -->
                        <br><hr>
                    </div> <!-- notice Post -->
                @endif
            @endforeach

        </div>

    </div>



    {{--bootstrap datetime picker--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js"></script>

    <script src="https://bitstorm.org/jquery/color-animation/jquery.animate-colors-min.js"></script>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>

    <script src="/assets/js/library.js"></script>
    <script src="/assets/js/ajaxMsgLibrary.js" type="text/javascript"></script>
    <script src="/nicEdit-latest.js" type="text/javascript"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-linkify/2.1.6/linkify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-linkify/2.1.6/linkify-jquery.min.js"></script>

    <script type="text/javascript">
        bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });

        new nicEditor().panelInstance('postField'); //'#postField', it's 'id'
        $('.nicEdit-panelContain').parent().width('100%');
        $('.nicEdit-panelContain').parent().next().width('100%');
    </script>

    <script>
        $(document).ready(function () {

            $(function () {
                $('#examDeclareDate, .examDeclareDate, .assignGivenDate').datetimepicker({
                    format : 'YYYY-MM-DD'
                });
                $('#examDate, .examDate, .assignDeadline').datetimepicker({
                    format : 'YYYY-MM-DD HH:mm:00'
                });
            });

            // >>>> forcely show exam instead of general post
            $('#postType :nth-child(2)').prop('selected', true);
            $('#examContainer').show();
            $('#examContainer').siblings().hide();
            //initialize niceEdit
            new nicEditor().panelInstance('examDetails');
            $('.nicEdit-panelContain').parent().width('100%');
            $('.nicEdit-panelContain').parent().next().width('100%');
            // >>>> forcely show exam instead of general post



            $(document).mousemove(function(){
                $("#suggestion-1").effect( "shake", { direction: "up", times: 4, distance: 3}, 1000 );
            });


            $('a').linkify();
            $('#CommunityUserPosts').linkify({
                target: "_blank"
            });

            var nicEdit;

            $('#add_member_link').slideUp();
            $('#cls-routine-container').slideUp();

            $('#postType').change(function () {
                var selectedIndex = $("#postType option:selected").index();

                if(selectedIndex == 0){
                    $('#generalPostContainer').show();
                    $('#generalPostContainer').siblings().hide();
                }
                else if (selectedIndex == 1){
                    $('#examContainer').show();
                    $('#examContainer').siblings().hide();

                    //initialize niceEdit
                    new nicEditor().panelInstance('examDetails');
                    $('.nicEdit-panelContain').parent().width('100%');
                    $('.nicEdit-panelContain').parent().next().width('100%');
                }
                else if (selectedIndex == 2){
                    $('#assignmentContainer').show();
                    $('#assignmentContainer').siblings().hide();

                    //initialize niceEdit
                    new nicEditor().panelInstance('assignmentDetails');
                    $('.nicEdit-panelContain').parent().width('100%');
                    $('.nicEdit-panelContain').parent().next().width('100%');
                }
                else if (selectedIndex == 3){
                    $('#pollContainer').show();
                    $('#pollContainer').siblings().hide();
                }
                else if (selectedIndex == 4){
                    $('#noticeContainer').show();
                    $('#noticeContainer').siblings().hide();

                    //initialize niceEdit
                    new nicEditor().panelInstance('noticeDetails');
                    $('.nicEdit-panelContain').parent().width('100%');
                    $('.nicEdit-panelContain').parent().next().width('100%');
                }
            }); //post type onChange


            $('#addPollOption').on('click', function(){
                $('#pollInputWrapper').append(
                    '<div class="form-group">'+
                    '<div class="input-group col-md-6">'+
                    '<input type="text" class="form-control pollOption">'+
                    '<span class="input-group-addon removePollOption">X</span>'+
                    '</div>'+
                    '</div>'
                );
            }); //addPollOption
            $('.editPollOption').on('click', function(){
                var postId = $(this).attr('post-id');
                $('#editPollInputWrapper'+postId).append(
                    '<div id="edit_poll_option_cont'+postId+'" class="form-group">'+
                    '<div class="input-group col-md-6">'+
                    '<input type="text" class="form-control editable_poll_option">'+
                    '<span class="input-group-addon removePollOption">X</span>'+
                    '</div>'+
                    '</div>'
                );
            });


            $(document).on('click', '.removePollOption', function () {
                if( confirm('Sure Delete ?') ){
                    $(this).parent().parent().remove();
                }
            }); //remove PollOption


            $('#addMemberBtn').click(function () {
                $('#add_member_link').slideToggle();
                var communityId = $('#communityId').text();
                $.ajax({
                    url : '/community/action/fetch_join_token',
                    type : 'post',
                    dataType : 'html',
                    data : {
                        _token : "{{csrf_token()}}",
                        communityId : communityId
                    },
                    success : function (response) {
                        $('#add_member_link #link').val(response);
                    },error : function () {
                        alert('Oops ! error occurred ! Reload page and try again. Contact support if needed');
                    }
                });//ajax
            }); // #addMemberBtn clicked


            $('#postBtn').click(function(){
                $(this).hide();
                $(this).text('Please wait...');
                $(this).after("<span style='color: #ff5b00'>Sending mail to all users.....</span>");
                var postType = $('#postType').find(':selected').text();
                var communityId = $('#communityId').text();
                var anonymousVal = $('#postAs').find(':selected').val();
                var postForWhichSession = $('#academic-session').val();

                switch (postType){
                    case 'General Post':
                        var generalTitle = $('#generalTitle').val();
                        nicEdit = new nicEditors.findEditor('postField');
                        var generalDetails = nicEdit.getContent();
                        var mySession = $('#my-session').text();

                        $.ajax({
                            url : '/community/action/save_general_posts',
                            type : 'post',
                            dataType : 'text',
                            data : {
                                _token : "<?php echo csrf_token() ?>",
                                community_id : communityId,
                                title : generalTitle,
                                details : generalDetails,
                                is_anonymous : anonymousVal,
                                //academic_session : mySession,
                                academic_session : postForWhichSession,
                            },
                            success : function(response){
                                console.log(response);
                                //here response is the id, so post-id=response
                                if( ! response.isNaN ){
                                    location.reload();
                                    //if reloaded, fillowing dom manipulation is redundant
                                    var domToAppend = '<div class="singleUserPost">'+
                                        '<div class="col-md-12"> <!-- start heading portion -->'+
                                        '<div id="generalTitle'+response+'" class="postHeading col-md-10">'+generalTitle+'</div>'+
                                        '<img title="Edit" post-type="general" post-id="'+response+'" class="editIcon col-md-1" src="/assets/images/edit-icon.png" alt="Edit">'+
                                        '<img title="Delete" post-type="general" post-id="'+response+'" class="deleteIcon col-md-1" src="/assets/images/delete-btn.png" alt="Delete">                                                    </div> <!-- end heading portion -->'+
                                        '<div id="generalDetails'+response+'" class="postDetails">'+generalDetails+'</div> <!-- general post details -->'+

                                        '<div class="col-md-12"> <!-- start "posted by" portion -->'+
                                        '<div class="text-right"><span>My</span> [ <span title="Just Now">Just Now</span> ] </div>'+
                                    '</div> <!-- end "posted by" portion -->'+
                                    '<br><hr>'+
                                    '</div>';

                                    $('#CommunityUserPosts').prepend(domToAppend);

                                    $('#generalTitle').val('');
                                    $('.nicEdit-main').html('');
                                }
                                else{
                                    //alert('Couldn\'t post. Please try again');
                                }
                            },
                            error : function (xhr, status) {
                                console.log(xhr+'\n'+status);
                                alert('Couldn\'t post. Please try again');
                            }
                        }); //General Post ajax
                        //console.log(generalTitle+'\n'+generalDetails);
                        break;
                    case 'Exam':
                        var examCourseName = $('#examCourseName').val();
                        var examDeclareDate = $('#examDeclareDate').val();
                        var examDate = $('#examDate').val();
                        nicEdit = new nicEditors.findEditor('examDetails');
                        var examDetails = nicEdit.getContent();
                        var mySession = $('#my-session').text();

                        $.ajax({
                            url : '/community/action/save_exam',
                            type : 'post',
                            dataType : 'text',
                            data : {
                                _token : "<?php echo csrf_token() ?>",
                                community_id : communityId,
                                course_name : examCourseName,
                                declared_at : examDeclareDate,
                                exam_date : examDate,
                                details : examDetails,
                                is_anonymous : anonymousVal,
                                //academic_session : mySession,
                                academic_session : postForWhichSession,
                            },
                            success : function(response){
                                if( ! response.isNaN ){
                                    location.reload();
                                    //if reloaded, fillowing dom manipulation is redundant
                                    var domToAppend = '<div class="singleUserPost">'+
                                        '<div class="col-md-12"> <!-- start exam heading portion -->'+
                                        '<div class="postHeading col-md-10"><span class="courseName">'+(examCourseName)+'</span> Exam</div>'+
                                        '<img title="Edit" post-type="exam" post-id="'+response+'"  class="editIcon col-md-1" src="/assets/images/edit-icon.png" alt="Edit">'+
                                        '<img title="Delete" post-type="exam" post-id="'+response+'" class="deleteIcon col-md-1" src="/assets/images/delete-btn.png" alt="Delete">'+
                                        '</div> <!-- end exam heading portion -->'+

                                        '<!-- start exam body portion -->'+
                                        '<p for="" class="col-md-12">'+
                                        '<span class="col-md-4">Exam Deeclared At : </span>'+
                                        '<span class="examDeclareDate col-md-8">'+(examDeclareDate)+'</span>'+
                                        '</p>'+
                                        '<p for="" class="col-md-12">'+
                                        '<span class="col-md-4">Exam Date time : </span>'+
                                        '<span class="examDate col-md-8">'+(examDate)+'</span>'+
                                        '</p>'+
                                        '<div class="details">'+
                                        examDetails+
                                        '</div> <!-- end exam body portion -->'+

                                        '<div class="col-md-12"> <!-- start posted by portion -->'+
                                        '<div class="text-right">Me [ <span title="just now">just now</span> ] </div>'+
                                        '</div> <!-- end posted by portion -->'+
                                        '<br><hr>'+
                                        '</div> <!-- examPosts -->';

                                    $('#CommunityUserPosts').prepend(domToAppend);

                                    $('#examCourseName').val('');
                                    $('#examDeclareDate').val('');
                                    $('#examDate').val('');
                                    nicEditors.findEditor( 'examDetails' ).setContent( '' );
                                }
                                else{
                                    alert('Couldn\'t post. Please try again');
                                    //console.log(response);
                                }
                            },
                            error : function (xhr, status) {
                                console.log(xhr+'\n'+status);
                                alert('Couldn\'t post. Please try again');
                            }
                        }); //exam ajax
                        //console.log(examCourseName+'\n'+examDeclareDate+'\n'+examDate+'\n'+examDetails);
                        break;
                    case 'Assignment':
                        var assignCourseName = $('#assignCourseName').val();
                        var assignGivenDate = $('#assignGivenDate').val();
                        var assignDeadline = $('#assignDeadline').val();
                        nicEdit = new nicEditors.findEditor('assignmentDetails');
                        var assignDetails = nicEdit.getContent();
                        var mySession = $('#my-session').text();

                        $.ajax({
                            url : '/community/action/save_assignment',
                            type : 'post',
                            dataType : 'text',
                            data : {
                                _token : "<?php echo csrf_token() ?>",
                                community_id : communityId,
                                course_name : assignCourseName,
                                given_date : assignGivenDate,
                                deadline : assignDeadline,
                                details : assignDetails,
                                is_anonymous : anonymousVal,
                                //academic_session : mySession,
                                academic_session : postForWhichSession,
                            },
                            success : function(response){
                                if( ! response.isNaN ){
                                    //console.log(response);
                                    location.reload();
                                    //if reloaded, fillowing dom manipulation is redundant
                                    var domToAppend = '<div class="singleUserPost">'+
                                        '<div class="col-md-12"> <!-- start assignment heading portion -->'+
                                        '<div class="postHeading col-md-10">Assignment : '+(assignCourseName)+'</div>'+
                                        '<img title="Edit" post-type="assignment" post-id="'+response+'"  class="editIcon col-md-1" src="/assets/images/edit-icon.png" alt="Edit">'+
                                        '<img title="Delete" post-type="assignment" post-id="'+response+'" class="deleteIcon col-md-1" src="/assets/images/delete-btn.png" alt="Delete">'+
                                        '</div> <!-- end assignment heading portion -->'+

                                        '<!-- start assignment body portion -->'+
                                    '<p for="" class="col-md-12">'+
                                    '<span class="col-md-4">Assignment given At : </span>'+
                                    '<span class="col-md-8">'+(assignGivenDate)+'</span>'+
                                    '</p>'+
                                    '<p for="" class="col-md-12">'+
                                    '<span class="col-md-4">Deadline : </span>'+
                                    '<span class="col-md-8">'+(assignDeadline)+'</span>'+
                                    '</p>'+
                                    '<div class="details">'+
                                    assignDetails+
                                    '</div> <!-- end assignment body portion -->'+

                                    '<div class="col-md-12"> <!-- start posted by portion -->'+
                                    '<div class="text-right">Me [ <span title="Just Now">Just Now</span> ] </div>'+
                                    '</div> <!-- end posted by portion -->'+
                                    '<br><hr>'+
                                    '</div> <!-- assignmentPosts -->';

                                    $('#CommunityUserPosts').prepend(domToAppend);

                                    $('#assignCourseName').val('');
                                    $('#assignGivenDate').val('');
                                    $('#assignDeadline').val('');
                                    nicEditors.findEditor( 'assignmentDetails' ).setContent( '' );
                                }
                                else{
                                    alert('Couldn\'t post. Please try again');
                                    //console.log(response);
                                }
                            },
                            error : function (xhr, status) {
                                console.log(xhr+'\n'+status);
                                alert('Couldn\'t post. Please try again');
                            }
                        }); //Assignment ajax
                        //console.log(assignCourseName+'\n'+assignGivenDate+'\n'+assignDeadline+'\n'+assignDetails);
                        break;
                } //end switch
            }); //postBtn clicked

            $('.pollOptionsRadio').click(function () {
                var pollId = $(this).attr('poll_id');
                var optionId = $(this).attr('option_id');

                $.ajax({
                    url : '/community/action/cast_vote',
                    type : 'post',
                    dataType : 'text',
                    data : {
                        _token : "<?php echo csrf_token() ?>",
                        poll_id : pollId,
                        option_id : optionId
                    },
                    success : function (response) {
                        //console.log(response);
                    },
                    error : function (xhr, status) {
                        console.log(xhr+'\n'+status);
                    }
                }); //end ajax
                //console.log(pollId+'--'+optionId);
            }); //pollOptionsRadio click


            $(document).on('click', '.deleteIcon', function(){
                var postType = $(this).attr('post-type');
                var postId = $(this).attr('post-id');

                var THIS = $(this);
                if (confirm('Delete this post ?')){
                    $.ajax({
                        url : '/community/action/delete_post/'+postType+'/'+postId,
                        type : 'GET',
                        dataType : 'text',
                        data : {
                            _token : "<?php echo csrf_token() ?>"
                        },
                        success : function (response) {
                            if( response === 'deleted' ){
                                hideCommunityPost(THIS);
                            }
                        },
                        error : function (xhr, status, error) {
                        }
                    }); //ajax
                }
            }); // .deleteIcon clicked



            $(document).on('click', '.editIcon', function () {
                var THIS = $(this);
                var postType = THIS.attr('post-type');
                var postId = THIS.attr('post-id');
                var panelId = 'editable'+postType+postId;

                if( postType === 'general' ){
                    var singleUserPost = THIS.parent().parent();
                    var postTitle = $('#generalTitle'+postId).text();
                    var postDetails = $("<div />").append($('#generalDetails'+postId).clone()).html();
                    //https://stackoverflow.com/questions/3614212/jquery-get-html-of-a-whole-element

                    setFakeCookie('generalTitle'+postId, postTitle);
                    setFakeCookie('generalDetails'+postId, postDetails);

                    updateGeneral_postToNicEdit(singleUserPost, postTitle, postDetails, postType, postId);
                }//if general editIcon
                if( postType === 'exam' ){
                    var examCourseName = $('#examCourseName'+postId).text();
                    var examDeclareDate = $('#examDeclareDate'+postId).text();
                    var examDate = $('#examDate'+postId).text();
                    var examDetails = $("<div />").append($('#examDetails'+postId).clone()).html();

                    setFakeCookie('examCourseName'+postId, examCourseName);
                    setFakeCookie('examDeclareDate'+postId, examDeclareDate);
                    setFakeCookie('examDate'+postId, examDate);
                    setFakeCookie('examDetails'+postId, examDetails);

                    var singleUserPost = THIS.parent().parent();
                    updateExam_postToNicEdit(singleUserPost, examCourseName, examDeclareDate, examDate, examDetails, postType, postId);
                    //console.log(courseName+'\n'+examDeclareDate+'\n'+examDate+'\n'+examDetails);
                } //if exam edit clicked
                if( postType === 'assignment' ){
                    var singleUserPost = THIS.parent().parent();
                    var fadeTime = 500;
                    singleUserPost.fadeToggle(fadeTime);
                    setTimeout(function(){
                        $('#editable_assignment_container'+postId).show();
                        var details = $('#assignment'+postId).html();

                        new nicEditor().panelInstance(panelId);
                        nicEdit = new nicEditors.findEditor(panelId);
                        nicEditors.findEditor( panelId ).setContent( details );
                    }, fadeTime);
                } //if assignment edit clicked
                if ( postType === 'poll' ){
                    var pollContainer = $('#poll_container'+postId);
                    var fadeTime = 500;
                    pollContainer.fadeToggle(fadeTime);
                    setTimeout(function(){
                        $('#editable_poll_container'+postId).show();
                    }, fadeTime);
                } //if poll edit click
            }); //editIcon



            $(document).on('click', '.updatePostBtn', function () {
                var THIS = $(this);
                var postType = THIS.attr('post-type');
                var postId = THIS.attr('post-id');
                var panelId = 'editable'+postType+postId;

                if( postType === 'general' ){
                    var postTitle = $('#generalTitle'+postId).val();

                    nicEdit = new nicEditors.findEditor(panelId);
                    var postDetails = nicEdit.getContent();

                    $.ajax({
                        url : '/community/action/update_post',
                        type : 'post',
                        dataType : 'text',
                        data : {
                            _token : "<?php echo csrf_token() ?>",
                            postType : postType,
                            postId : postId,
                            title : postTitle,
                            details : postDetails
                        },
                        success : function (response) {
                            if( response === 'updated' ){
                                var singleUserPost = THIS.parentsUntil('.singleUserPost');
                                bgcolorAnimate(singleUserPost, '#d7ce81', '#f7fcf9');
                                generalNicEditToPost(singleUserPost, postId, postTitle, postDetails);
                            }
                        },
                        error : function () {
                        }
                    }); //update general post ajax
                } //if postType === general

                else if( postType === 'exam' ){
                    var examCourseName = $('#examCourseName'+postId).val();
                    var examDeclareDate = $('#examDeclareDate'+postId).val();
                    var examDate = $('#examDate'+postId).val();
                    nicEdit = new nicEditors.findEditor(panelId);
                    var examDetails = nicEdit.getContent();

                    $.ajax({
                        url : '/community/action/update_post',
                        type : 'post',
                        dataType : 'text',
                        data : {
                            _token          : "<?php echo csrf_token(); ?>",
                            examCourseName  : examCourseName,
                            examDeclareDate : examDeclareDate,
                            examDate        : examDate,
                            examDetails     : examDetails,
                            postType        : postType,
                            postId          : postId
                        },
                        success : function(response){
                            if ( response === 'success' ){
                                var singleUserPost = THIS.parentsUntil('#CommunityUserPosts');
                                bgcolorAnimate(singleUserPost, '#d7ce81', '#f7fcf9');
                                examNicEditToPost(singleUserPost, examCourseName, examDeclareDate, examDate, examDetails,postType, postId);
                            }
                        }
                    }); //exam ajax
                } //postType === 'exam'
                else if( postType === 'assignment' ){
                    var courseName = $('#assignCourseName'+postId).val();
                    var givenDate = $('#assignGivenDate'+postId).val();
                    var deadline = $('#assignDeadline'+postId).val();

                    nicEdit = new nicEditors.findEditor(panelId);
                    var details = nicEdit.getContent();

                    $.ajax({
                        url : '/community/action/update_post',
                        type : 'post',
                        dataType : 'text',
                        data : {
                            _token          : "<?php echo csrf_token(); ?>",
                            courseName      : courseName,
                            givenDate       : givenDate,
                            deadline        : deadline,
                            details         : details,
                            postType        : postType,
                            postId          : postId
                        },
                        success : function(response){
                            if ( response === 'success' ){
                                $('#assignment_course_name'+postId).text(courseName);
                                $('#assignment_given_date'+postId).text(givenDate);
                                $('#assigned_deadline'+postId).text(deadline);
                                $('#assignment'+postId).html(details);

                                var singleUserPost = $('#editable_assignment_container'+postId);
                                singleUserPost.fadeToggle(500);
                                setTimeout(function(){
                                    $('#assignment_container'+postId).show();
                                }, 500);
                            }
                        }
                    }); //assignment ajax
                } // if postType === 'assignment'

                else if ( postType === 'poll' ){
/* ******************************** THIS FEATURE IS NOT COMPLETELY IMPLEMENTED YET ************************* */
                    var pollQuestion = $('#editable_q'+postId).val();
                    var pollOptions = [];
                    $('#edit_poll_option_cont'+postId+' .editable_poll_option').each(function(){
                        var val = $(this).val();
                        if(val){
                            pollOptions.push(val);
                        }
                    });
                    //console.log(pollQuestion+'\n'+pollOptions);
                    var pollOptionStr='';
                    for(var i=0; i<pollOptions.length;i++){
                        pollOptionStr+= '<p><input type="radio"  poll_id="'+postId+'" option_id="0" name="pollRadio'+postId+'" class="pollOptionsRadio">'+pollOptions[i]+'</p>';
                    }
                    console.log(JSON.stringify(pollOptions));
                    $.ajax({
                        url : '/community/action/update_post',
                        type : 'post',
                        dataType : 'text',
                        data : {
                            _token          : "<?php echo csrf_token(); ?>",
                            poll_q          : pollQuestion,
                            options         : JSON.stringify(pollOptions)
                        },
                        success : function(response){
                            if ( response === 'success' ){

                            }
                        }
                    }); //poll ajax

                    var pollContainer = $('#poll_container'+postId);
                    pollContainer.html( contentOfEditedPoll(postId, pollOptionStr) );
                    var fadeTime = 500;
                    $('#editable_poll_container'+postId).fadeToggle(fadeTime);
                    setTimeout(function(){
                        pollContainer.show();
                    }, fadeTime);
                }// else if postType == poll (edit)
                else if ( postType === 'notice' ){

                }
            }); //.updatePostbtn


            $(document).on('click', '.cancelUpdatePostBtn', function () {
                var THIS = $(this);
                var postType = THIS.attr('post-type');
                var postId = THIS.attr('post-id');

                if( postType === 'general' ){
                    var singleUserPost = THIS.parentsUntil('.singleUserPost');
                    bgcolorAnimate(singleUserPost, '#f7fcf9', '#f7fcf9');
                    generalNicEditToPost( singleUserPost, postId, getFakeCookie('generalTitle'+postId), getFakeCookie('generalDetails'+postId) );
                }
                else if( postType === 'exam' ){
                    var singleUserPost = THIS.parent().parent();
                    bgcolorAnimate(singleUserPost, '#f7fcf9', '#f7fcf9');

                    examNicEditToPost(singleUserPost,
                        getFakeCookie('examCourseName'+postId),
                        getFakeCookie('examDeclareDate'+postId),
                        getFakeCookie('examDate'+postId),
                        getFakeCookie('examDetails'+postId),
                        postType, postId
                    );
                } //exam postType cancel clicked
                else if( postType === 'assignment' ){
                    var singleUserPost = $('#editable_assignment_container'+postId);
                    singleUserPost.fadeToggle(500);
                    setTimeout(function(){
                        $('#assignment_container'+postId).show();
                    }, 500);
                    //console.log(postType+postId)
                }
                else if( postType === 'poll' ){
                    var pollContainer = $('#poll_container'+postId);
                    var fadeTime = 500;
                    $('#editable_poll_container'+postId).fadeToggle(fadeTime);
                    setTimeout(function(){
                        pollContainer.show();
                    }, fadeTime);
                    console.log(postType+'--'+postId);
                }
            }); // cancelUpdatePostBtn click


            $('#linkCopyBtn').click(function(){
                $('#link').select();
                document.execCommand('copy');
                $('#add_member_link #success_msg').show();
                setTimeout(function(){
                    $('#add_member_link').slideToggle(1000);
                    $('#add_member_link #success_msg').hide();
                }, 4000);
            }); // linkCopyBtn click


            $('#cls-routine-btn').click(function () {
                $('#cls-routine-container').slideToggle();


            });


        }); //ready

    </script>
@endsection
