<?php

$pageTitle = "Edit Community";

?>

@extends('app')


@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <div id="formWrapper">
            <h3 class="text-center">Edit community Information</h3>
            {!! Form::model($Communities, ['method'=>'patch', 'url'=>'/community/edit/'.$communityId]) !!}

            <div class="form-group">
                {!! Form::label('community_name', 'Community Name : ', ['class'=>'text-left']) !!}
                {!! Form::text('community_name', null, ['class'=>'form-control col-md-8']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('institute_name', 'Institute Name(University) : ', ['class'=>'text-left']) !!}
                {!! Form::text('institute_name', null, ['class'=>'form-control col-md-8', 'disabled']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('dept_name', 'Department') !!}
                {!! Form::text('dept_name', null, ['class'=>'form-control', 'disabled']) !!}
            </div>

            <div class="form-group">
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

            <div class="form-group">
                {!! Form::label('student_per_batch','Student Per Batch') !!}
                {!! Form::number('student_per_batch', null, ['class'=>'form-control', 'min'=>1]) !!}
            </div>

            <div class="form-group hidden">
                {!! Form::label('description', 'Some Description') !!}
                {!! Form::textarea('description', null, ['class'=>'form-control', 'rows'=>3]) !!}
            </div>

            <div class="form-group">
                {!! Form::submit('Save', ['class'=>'btn btn-primary form-control', 'style'=>'width:200px']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
