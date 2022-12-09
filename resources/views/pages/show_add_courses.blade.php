<?php

$pageTitle = "Courses";

?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        @if($isAdmin)
            <h3>Add Courses</h3>
            You are adding information for <strong>{{ $_GET['com'] }}</strong>. Community can be switched from left bar in
            <a href="/users/cloud/{{ $_GET['comId'] }}">Cloud Explorer</a>  <hr>
        @else
            <div class="col-md-8 col-md-offset-2">
                <div class="form-group">
                    <label class="col-md-6">Roll No ( <small>Full Form</small> )</label>
                    <div class="input-group col-md-6">
                        <input type="text" id="roll-no-full-form" class="form-control" value="{{ $rollNo->roll_full_form or "" }}" placeholder="Example : JN-1002-23">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-6">Numeric value of roll no</label>
                    <div class="input-group col-md-6">
                        <input type="number" min="1" id="roll-no-numeric" class="form-control" value="{{ $rollNo->roll_numeric or "" }}" placeholder="23">
                    </div>
                </div>
                <button class="btn btn-success" id="save-roll-no">Save Roll No</button>
            </div>
        @endif

        <div id="course_field_container" class="container col-md-12">
            @if($isAdmin)
                @foreach($courses as $course)
                    <div class="field_wrapper">
                        <div class="form-group col-md-10 col-md-offset-1 text-left">
                            <label for="" class="col-md-4">Course Code</label>
                            <div class="input-group col-md-8">
                                <input type="hidden" value="{{ $course->id }}" class="id">
                                <input type="text" value="{{ $course->course_code }}" class="course_code form-control">
                            </div>
                        </div>
                        <div class="form-group col-md-10 col-md-offset-1 text-left">
                            <label for="" class="col-md-4">Course Name</label>
                            <div class="input-group col-md-8">
                                <input type="text" value="{{ $course->course_name }}" class="course_name form-control">
                            </div>
                        </div>
                        <div class="form-group col-md-10 col-md-offset-1 text-left">
                            <label for="" class="col-md-4">
                                <p>About this course</p>
                                <p title="Delete Course" id="{{ $course->id }}" class="remove_field btn spanBtnDelete text-right" style="width: 120px">X</p>
                            </label>
                            <div class="input-group col-md-8">
                                <textarea class="about_course form-control" rows="2">{{ $course->about_course }}</textarea>
                            </div>
                        </div>
                    </div>
                    <hr class="col-md-12">
                @endforeach
            @else
                <div id="join-course" class="col-md-12">
                    @foreach($courses as $course)
                        <div class="single-course col-md-10 col-md-offset-1 text-left">
                            <div class="col-md-8">
                                <p>{{ $course->course_code }}</p>
                                <p>{{ $course->course_name }}</p>
                                <p>{{ $course->already_joined ? 'Joined':'not joined' }}</p>
                                <p>Course Teacher : <a href="/users/profile/id/{{ $course->data_added_by }}">{{ $course->data_added_by_name }}</a></p>
                            </div>
                            <div class="col-md-4">
                                @if($course->already_joined)
                                    <button class="leave-course btn btn-danger" course-id="{{ $course->id }}">Leave Course</button>
                                @else
                                    <button class="join-course spanBtn" course-id="{{ $course->id }}" user-id-of-teacher="{{ $course->data_added_by }}">Join Course</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div> <!-- #course_name_container -->

        <div class="col-md-8 col-md-offset-2">
            @if($isAdmin)
                <button class="btn spanBtn" id="add_more_course">Add Course</button>
                <button class="btn spanBtn" id="save_courses_btn">Save All Information</button>
            @endif
        </div>



        <div id="add_course_template" style="display: none;">
            <div class="field_wrapper">
                <div class="form-group col-md-10 col-md-offset-1 text-left">
                    <label for="" class="col-md-4">Course Code</label>
                    <div class="input-group col-md-8">
                        <input type="hidden" value="" class="id">
                        <input type="text" class="course_code form-control">
                    </div>
                </div>
                <div class="form-group col-md-10 col-md-offset-1 text-left">
                    <label for="" class="col-md-4">Course Name</label>
                    <div class="input-group col-md-8">
                        <input type="text" class="course_name form-control">
                    </div>
                </div>
                <div class="form-group col-md-10 col-md-offset-1 text-left">
                    <label for="" class="col-md-4">
                        <p>About this course</p>
                        <p title="Delete Course" class="remove_field btn spanBtnDelete text-right" style="width: 120px">X</p>
                    </label>
                    <div class="input-group col-md-8">
                        <textarea class="about_course form-control" rows="2" placeholder="[ Optional ] Will be helpful for your classmates/juniors"></textarea>
                    </div>
                </div>
            </div>
            <hr class="col-md-12">
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            var template = $('#add_course_template').html();
            //$('#course_field_container').append(template);

            $('#add_more_course').click(function(){
                $('#course_field_container').append(template);
            });

            var courseInfo = [];
            $('#save_courses_btn').on('click', function () {
                $('.field_wrapper').each(function (index) {
                    var id = $(this).find('.id').val();
                    var courseCode = $(this).find('.course_code').val();
                    var courseName = $(this).find('.course_name').val();
                    var about_course = $(this).find('.about_course').val();
                    about_course = about_course ? about_course : "No description added";

                    var tmpArray = [];
                    if( courseCode && courseName && about_course ){
                        tmpArray.push(id);
                        tmpArray.push(courseCode);
                        tmpArray.push(courseName);
                        tmpArray.push(about_course);

                        courseInfo.push(tmpArray);
                    }
                }); //.each()
//                courseInfo = JSON.stringify(courseInfo);
//                console.log(courseInfo);
//                courseInfo = JSON.parse(courseInfo);
//                for(var  x in courseInfo ){
//                    console.log(courseInfo[x][0]+'---'+courseInfo[x][1]+'--'+courseInfo[x][2]);
//                }
                $.ajax({
                    url : '/community/action/save_courses',
                    type : 'post',
                    dataType : 'text',
                    data : {
                        _token : "<?php  echo csrf_token() ; ?>",
                        communityId : "<?php  echo $_GET['comId']; ?>",
                        courseInfo : JSON.stringify(courseInfo)
                    },
                    success : function (response) {
                        console.log(response);
                        courseInfo = [];
                        location.replace(window.location.href);
                    },
                    error : function (xhr, textStatus, error) {
                    }
                }); // ajax
            }); // #save_courses_btn clicked

            $(document).on('click', '.remove_field', function () {
                if ( confirm("Delete this course ?") ){
                    var THIS = $(this);
                    var id = THIS.attr('id');
                    if(id){
                        // ! undefined
                        $.ajax({
                            url : '/community/action/delete_course',
                            type : "post",
                            dataType : 'text',
                            data : {
                                _token : "<?php echo csrf_token() ?>",
                                id : id
                            },success : function () {
                                THIS.closest('.field_wrapper').remove();
                            },error : function () {
                                alert("Couldn't delete. Try again or contact support");
                            }
                        }); // ajax
                    }
                    else{
                        //undefined id. most probably means, dynamically created. as not fetched from database it has no id set
                        alert("This course data is not saved. So no need to delete.");
                    }
                }
            }); // .remove_field clicked


            $('#save-roll-no').click(function () {
                var rollNoFullForm = $('#roll-no-full-form').val();
                var rollNoNumeric = $('#roll-no-numeric').val();

                if(rollNoFullForm && rollNoNumeric>0){
                    $.ajax({
                        url : '/community/courses/save_roll_no',
                        type : 'post',
                        dataType : 'text',
                        data : {
                            _token : "{{ csrf_token() }}", rollNoFullForm : rollNoFullForm, rollNoNumeric : rollNoNumeric
                        }, success : function (response) {
                            console.log(response);
                            if(response === 'saved'){
                                alert("Roll Saved");
                            }else{
                                alert(response);
                            }
                        }, error : function (a, b, c) {
                            alert("Error occurred. Please try again or contact support");
                        }
                    });
                }else{
                    alert("Please enter roll no CORRECTLY");
                }
            }); // #save-roll-no click


            $('.join-course').click(function () {
                var courseId = $(this).attr('course-id');
                var userIdOfTeacher = $(this).attr('user-id-of-teacher');
                if(confirm("Sure Join ?")){
                    $.ajax({
                        url : '/community/courses/join_course',
                        type : 'post',
                        data : {
                            _token : "{{ csrf_token() }}", courseId : courseId, userIdOfTeacher : userIdOfTeacher
                        }, success : function (response) {
                            console.log(response);
                            alert("Successfully Joined");
                            location.reload();
                        }, error : function () {
                            alert("Error occurred. Please try again or contact support");
                        }
                    });
                }
            }); // .join-course


            $('.leave-course').click(function () {
                var courseId = $(this).attr('course-id');
                if(confirm("Leave This Course ?")){
                    $.ajax({
                        url : '/community/courses/leave_course',
                        type : 'post',
                        data : {
                            _token : "{{ csrf_token() }}", courseId : courseId
                        }, success : function (response) {
                            console.log(response);
                            alert("You can join after reloading the page");
                            location.reload();
                        }, error : function () {
                            alert("Error occurred. Please try again or contact support");
                        }
                    });
                }
            }); // .leave-course

        });
    </script>
@endsection