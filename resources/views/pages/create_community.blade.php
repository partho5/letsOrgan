<?php

use App\Library\Library;

$pageTitle = "Create Community";

$Lib = new Library();

?>

@extends('app')


@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <div id="formWrapper">

            <h3 class="text-center">Create a community</h3>

            {!! Form::open(['method'=>'post', 'action'=>'CommunityController@doCreateCommunity']) !!}

            <div class="form-group text-left">
                {{--{!! Form::label('institute_name', 'University Name : (Full Form) <del>dd</del>', ['class'=>'text-left']) !!}--}}
                <label for="institute_name" class="text-left">University Name : (Full Name Please) <del style="color: red"><span style="color: #000;">Short Form</span></del></label>
                {!! Form::text('institute_name', null, ['class'=>'institute-name form-control col-md-8', 'required']) !!}
            </div>

            <div class="form-group text-left">
                {{--{!! Form::label('dept_name', 'Department Name') !!}--}}
                <label for="dept_name">Department Name (Full Name Please) <del style="color: red"><span style="color: #000;">Short Form</span></del> </label>
                {!! Form::text('dept_name', null, ['class'=>'dept-name form-control', 'required']) !!}
            </div>

            <div class="form-group text-left">
                {!! Form::label('community_name', 'Community Name : ', ['class'=>'text-left']) !!}
                {!! Form::text('community_name', null, ['class'=>'community-name form-control col-md-8', 'required']) !!}
            </div>

            <div class="form-group text-left">
                {!! Form::label('class_test_name', 'What do you call a \'class test\' ?') !!}
                <div>
                    <select name="class_test_name" id="class_test_name" class="form-control" required>
                        <option selected disabled>Select One</option>
                        <option value="Class Test">Class Test</option>
                        <option value="Mid-term">Mid-term</option>
                        <option value="Incourse">Incourse</option>
                    </select>
                </div>
            </div>

            <div class="form-group text-left">
                {!! Form::label('student_per_batch','Student Per Batch') !!}
                {!! Form::number('student_per_batch', null, ['class'=>'form-control', 'min'=>1, 'required']) !!}
            </div>

            <div class="form-group text-left">
                {!! Form::label('academic_term','What\'s your academic term ?') !!}
                <span class="text-center" style="color: #ea4d31">This information can't be edited in future.</span>
                <div>
                    <select name="academic_term" id="academic_term" class="form-control" required>
                        <option selected disabled>Year/Semester based ?</option>
                        <option value="Year">Year</option>
                        <option value="Semester">Semester</option>
                    </select>
                </div>
            </div>

            <div class="form-group text-left hidden">
                {!! Form::label('description', 'Some Description') !!}
                {!! Form::textarea('description', 'No description added', ['class'=>'form-control', 'rows'=>3, 'required']) !!}
            </div>

            <div class="form-group text-left">
                {!! Form::submit('Save', ['class'=>'btn btn-primary form-control', 'style'=>'width:200px']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">

    <script type="text/javascript" src="/assets/js/library.js"></script>
    <script>
        $(document).ready(function(){
            $('.institute-name, .dept-name').change(function () {
                var communityName =  $('.dept-name').val()+' - '+$('.institute-name').val() ;
                $('.community-name').val(communityName);
            });

            //some mobile device doesn't inyterprete change event, so this is necessary
            $('.community-name').click(function () {
                var communityName =  $('.dept-name').val()+' - '+$('.institute-name').val() ;
                $('.community-name').val(communityName);
            });


            //defined in library.js
            var instituteList = allInstituteList;
            var deptList = allDeptList;

            $('.institute-name').autocomplete({
                source : instituteList
            });
            $('.dept-name').autocomplete({
                source : deptList
            });

            $('form').submit(function (e) {
                var instituteName = $('.institute-name').val();
                var deptName = $('.dept-name').val();
                if( ! instituteList.includes(instituteName) || ! deptList.includes(deptName)){
                    e.preventDefault();
                    alert("Please select only suggested names. Don't modify");
                }
            });
        });
    </script>
@endsection
