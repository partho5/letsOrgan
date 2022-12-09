<?php

$pageTitle = "Teachers";
$Lib = new \App\Library\Library();


?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <h3>Teachers of all courses <small>(Year wise)</small></h3>
        You are adding information for <strong>{{ $_GET['com'] }}</strong>. Community can be switched from left bar in
        <a href="/users/cloud/{{ $_GET['comId'] }}">Cloud Explorer</a>  <hr>

        <div id="teachers_container" class="container">
            @if( count($courses) == 0 )
                <p>To add teachers data, please add some <a href="/community/courses?com={{ urlencode($_GET['com']) }}&comId={{ $currentCommunityId }}">Course</a> names first</p>
            @else
                <p style="color: #f00">Please don't write the nickname of a teacher. Write the full name, and also avoid the word 'Sir' / 'Madam'</p><hr>
                @foreach($courses as $course)
                    <div class="row col-md-12">
                        <div class="col-md-5">
                            <p style="color: #00a962">{{ $course->course_name }} ( {{ $course->course_code }} )</p>
                            <?php $curYear = date('Y') ; ?>

                            <select class="form-control" id="year-{{ $course->id }}" course-id="{{ $course->id }}">
                                @for($year = $curYear; $year > $curYear-20 ; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>

                        </div>
                        <div class="col-md-7">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="input-group col-md-12">
                                        <input type="text" class="form-control" id="teacher-name-{{ $course->id }}" placeholder="Name of course teacher in selected year">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="input-group col-md-12">
                                    <button class="teacher-save-btn btn spanBtn" id="{{ $course->id }}" style="width: 120px">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="col-md-12">
                @endforeach
            @endif
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">

    <script type="text/javascript" src="/assets/js/ajaxMsgLibrary.js"></script>
    <script>
        $(document).ready(function(){
            var teachers = '<?php echo json_encode($teachers) ?>' ;
            teachers = JSON.parse(teachers);
            //console.log(teachers);
            $('input').autocomplete({
                source : teachers
            });

            $('.teacher-save-btn').click(function () {
                var THIS = $(this);

                var courseId = THIS.attr('id');
                var year = $('#year-'+courseId).val();
                var teacherName = $('#teacher-name-'+courseId).val();

                var wait4response = new Wait4Response();
                wait4response.eventFired(THIS, 'Saving...');
                $.ajax({
                    url : '/community/action/save_teachers',
                    type : 'post',
                    dataType : 'text',
                    data : {
                        _token : "<?php echo csrf_token() ?>",
                        courseId : courseId,
                        year : year,
                        teacherName : teacherName
                    },
                    success : function (response) {
                        if(response === 'success' ){
                            wait4response.succeed(THIS, 'Saved', 5);
                        }else{
                            wait4response.failed(THIS, 'Error occurred. Try again after reloading page');
                        }
                    },
                    error : function () {
                        wait4response.failed(THIS, 'Error occurred. Try again after reloading page');
                    }
                }); // ajax

                console.log(courseId+'--'+year+'--'+teacherName);
            }); // #tetacher-save-btn click

            $('select').on('change', function () {
                var year = $(this).val();
                var courseId = $(this).attr('course-id');
                var inputVal = $('#year-'+courseId+' option:selected').attr(year);
                //$('#teacher-name-'+courseId).val( inputVal );

                //console.log(inputVal);
            });
        });
    </script>
@endsection