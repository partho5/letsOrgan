<?php

$currentYear = date('Y');

?>

    <h1 class="col-md-12">
        <small class="col-md-10">Upload <b>:</b></small>
        <span class="col-md-2" id="closeUploadContainerBtn">Close</span>
    </h1>
    <hr>
    <div id="radio-container" class="col-md-12">
        <ul class="list-inline">
            <li style="background-color: #00AAFF"><input type="radio" checked name="fileType" value="book">Book(pdf)</li>
            <li style="background-color: #00b138; color: #fff"><input type="radio" name="fileType" value="question">Question</li>
            {{--bookLink--}}
            <li style="background-color: #ff3a00; color: #fff"><input type="radio" name="fileType" value="otherFile">Other File/Slide/Sheet</li>
            <li><input type="radio" name="fileType" value="videoLink">Youtube video link</li>
        </ul>
    </div> <hr>

    <div id="fileCategories">
        <div class="category col-md-12" id="book">
            {!! Form::open(['url'=>"/users/cloud/$currentCommunityId", 'method'=>'post', 'files'=>'true', 'id'=>'book_form']) !!}
            {!! Form::hidden('identifier', 'book') !!}
            <input type="hidden" name="parent_dir" id="parentDir" class="parentDir" value="">
            <input type="hidden" name="full_dir_url" id="full_dir_url" class="full_dir_url" value="">
            <input type="hidden" name="possessed_by_community" id="possessed_by_community" class="possessed_by_community" value="">

            <div class="form-group col-md-12">
                <div class="col-md-4 text-left">
                    <label for="book_name">Book Name : </label>
                </div>
                <div class="col-md-8">
                    {!! Form::text('book_name', null, ['class'=>'form-control col-md-7', 'placeholder'=>'Max 50 characters', 'maxlength'=>40, 'required']) !!}
                </div>
            </div>

            <div class="form-group col-md-12">
                <div class="col-md-4 text-left">
                    <label for="author">Author : </label>
                </div>
                <div class="col-md-8">
                    {!! Form::text('author', null, ['class'=>'form-control col-md-7', 'placeholder'=>'Author', 'required']) !!}
                </div>
            </div>

            <div class="form-group col-md-12 hidden">
                <div class="col-md-4 text-left">
                    <label for="category">Book Category : </label>
                </div>
                <div class="col-md-8">
                    {!! Form::text('category', "Not Specified", ['class'=>'form-control col-md-7', 'placeholder'=>'Ex:Circuit, Business Studies, Programming ', 'required']) !!}
                </div>
            </div>

            <div class="form-group col-md-12 hidden">
                <div class="col-md-4 text-left">
                    <label for="description">Short Description : </label>
                </div>
                <div class="col-md-8">
                    {!! Form::text('description', "No description added", ['class'=>'form-control col-md-7', 'placeholder'=>'Description is optional']) !!}
                </div>
            </div>

            <div class="form-group col-md-12">
                <div class="col-md-4 text-left">
                    <label for="bookFile">Choose File : </label> 
                    <span style="color: #ea4d3b;">Max 200 MB</span>
                </div>
                <div class="col-md-8">
                    <input id="fileupload" type="file" name="book_file" data-url="/users/cloud/{{$currentCommunityId}}" multiple class="form-control col-md-7" required>
                </div>
                <img src="/assets/images/uploading.gif" class="loader_img" alt="Uploading..." class="text-center" style="display: none">
            </div>


            <div class="form-group col-md-12">
                <div class="col-md-8 col-md-offset-2">
                    <div class="col-md-5">
                        <input type="submit" name="submit" id="submitBtn" class="form-control btn btn-primary" value="Upload">
                    </div>

                    <div class="col-md-5">
                        <select name="access_code" id="access_code" class="form-control">
                            <option value="3">Public</option>
                            <option value="2">Root Community</option>
                            <option value="0">Only Me</option>
                        </select>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}


            <div id="progress-div"><div id="progress-bar"></div></div>
            <div id="targetLayer"></div>

        </div>


        <div class="category col-md-12" style="display: none" id="question">
            {!! Form::open(['url'=>"/users/cloud/$currentCommunityId", 'method'=>'post', 'files'=>'true', 'id'=>'question_form']) !!}
            {!! Form::hidden('identifier', 'question') !!}
            <input type="hidden" name="parent_dir" id="parentDir" class="parentDir" value="">
            <input type="hidden" name="full_dir_url" id="full_dir_url" class="full_dir_url" value="">
            <input type="hidden" name="possessed_by_community" id="possessed_by_community" class="possessed_by_community" value="">

            <div class="form-group col-md-12">
                <div class="col-md-4 text-left">
                    <label for="course-name">Course Name : </label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="course-name" name="course-name" maxlength="50" class="form-control" placeholder="Max 50 characters">
                    <input type="hidden" name="course_id" id="course_id" value="0">
                </div>
            </div>

            <div class="form-group col-md-12">
                <div class="col-md-4 text-left">
                    <label for="question-year">Year : </label>
                </div>
                <div class="col-md-8">
                    <select class="form-control" name="year" id="question-year">
                        @for($year= $currentYear; $year >= $currentYear-20; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="form-group col-md-12">
                <div class="col-md-4 text-left">
                    <label for="exam-type">Question Of : </label>
                </div>
                <div class="col-md-8">
                    <select class="form-control" name="question_of" id="exam-type">
                        <option value="final">Final Exam</option>
                        <option value="{{ $communityInfo->class_test_name }}">{{ $communityInfo->class_test_name }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group col-md-12">
                <div class="col-md-4 text-left">
                    <label for="bookFile">Choose File : </label>
                    <span style="color: #ea4d3b;">Max 200 MB</span>
                </div>
                <div class="col-md-8">
                    <input id="fileupload" type="file" name="question_file" data-url="/users/cloud/{{$currentCommunityId}}" multiple class="form-control col-md-7" required>
                </div>
                <img src="/assets/images/uploading.gif" class="loader_img" alt="Uploading..." class="text-center" style="display: none">
            </div>

            <div class="form-group col-md-12">
                <div class="col-md-8 col-md-offset-2">
                    <div class="col-md-5">
                        <input type="submit" name="submit" id="submitBtn" class="form-control btn btn-primary" value="Upload">
                    </div>

                    <div class="col-md-5">
                        <select name="access_code" id="access_code" class="form-control">
                            <option value="3">Public</option>
                            <option value="2">Root Community</option>
                            <option value="0">Only Me</option>
                        </select>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>


        <div class="category col-md-12" style="display: none" id="otherFile">
            {!! Form::open(['url'=>"/users/cloud/$currentCommunityId", 'method'=>'post', 'files'=>'true']) !!}

            {!! Form::hidden('identifier', 'otherFile') !!}
            <input type="hidden" name="parent_dir" id="parentDir" class="parentDir" value="">
            <input type="hidden" name="full_dir_url" id="full_dir_url" class="full_dir_url" value="">
            <input type="hidden" name="possessed_by_community" id="possessed_by_community" class="possessed_by_community" value="">

            <div class="form-group col-md-12">
                <div class="col-md-4 text-left">
                    <label for="file_name">Give a name : </label>
                </div>
                <div class="col-md-8">
                    {!! Form::text('file_name', null, ['class'=>'form-control col-md-7', 'maxlength'=>40, 'placeholder'=>'Max 50 characters', 'required']) !!}
                </div>
            </div>

            <div class="form-group col-md-12">
                <div class="col-md-4 text-left">
                    <label for="file_type">File Type : </label>
                </div>
                <div class="col-md-8">
                    <select name="file_type" id="" class="form-control col-md-7" required>
                        <option disabled selected>Select</option>
                        <option value="Slide">Slide</option>
                        <option value="Sheet">Sheet</option>
                        <option value="Lecture">Lecture Note</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <div class="form-group col-md-12 hidden">
                <div class="col-md-4 text-left">
                    <label for="category">Category : </label>
                </div>
                <div class="col-md-8">
                    {!! Form::text('category', "Not Specified", ['class'=>'form-control col-md-7', 'placeholder'=>'Ex. Semiconductor/Circuit/Optics ', 'required']) !!}
                </div>
            </div>

            <div class="form-group col-md-12 hidden">
                <div class="col-md-4 text-left">
                    <label for="description">Description : </label>
                </div>
                <div class="col-md-8">
                    {!! Form::text('description', "No description added", ['class'=>'form-control col-md-7', 'placeholder'=>'Short Description [ optional ]']) !!}
                </div>
            </div>

            <div class="form-group col-md-12">
                <div class="col-md-4 text-left">
                    <label for="other_file"> Choose File : </label>
                    <span style="color: #ea4d3b;">Max 200 MB</span>
                </div>
                <div class="col-md-8">
                    <input type="file" name="other_file" required class="form-control col-md-7">
                </div>
                <img src="/assets/images/uploading.gif" class="loader_img" alt="Uploading..." class="text-center" style="display: none">
            </div>

            <div class="form-group col-md-12">
                <div class="col-md-8 col-md-offset-2">
                    <div class="col-md-5">
                        <input type="submit" name="submit" id="submitBtn" class="form-control btn btn-primary" value="Upload">
                    </div>

                    <div class="col-md-5">
                        <select name="access_code" id="access_code" class="form-control">
                            <option value="3">Public</option>
                            <option value="2">Root Community</option>
                            <option value="0">Only Me</option>
                        </select>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}

            @if($errors->any())
                <ul>
                    @foreach($errors as $error)
                        <li class="alert alert-danger">{{$error}}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="category col-md-12" style="display: none" id="videoLink">
            <div class="form-group col-md-12">
                <span class="text-left col-md-4">Video Title</span>
                <div class="input-group col-md-8">
                    <input type="hidden" name="possessed_by_community" class="possessed_by_community" value="">
                    <input type="text" id="title" class="form-control" placeholder="Ex. Fourier Transform Videos">
                </div>
            </div>

            <div class="form-group col-md-12">
                <span class="text-left col-md-4">Video Category</span>
                <div class="input-group col-md-8">
                    <input type="text" id="category" class="form-control" placeholder="Ex. Digital Signal Processing / Fourier Transform">
                </div>
            </div>

            <div class="form-group col-md-12">
                <span class="text-left col-md-4">Video / Channel Link</span>
                <div class="input-group col-md-8">
                    <input type="url" id="link" class="form-control" placeholder="Must start with https://">
                </div>
            </div>

            <div class="form-group col-md-12 hidden">
                <span class="text-left col-md-4">Description</span>
                <div class="input-group col-md-8">
                    <input type="text" id="description" class="form-control" placeholder="[ Optional ] Helps people get in search result">
                </div>
            </div>

            <div class="form-group col-md-12">
                <button id="save-youtube-btn" class="btn btn-info col-md-2 col-md-offset-5">Save</button>
            </div>
        </div>

    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">

<script>
    $(document).ready(function(){
        var courses = '<?php echo json_encode($courses) ?>';
        courses = JSON.parse(courses);
        var raw = [] ;
        for ( var i=0; i < courses.length; i++ ){
            raw.push({
                label : courses[i].course_name,
                value : courses[i].id
            });
        }
        var source  = [ ];
        var mapping = { };
        for(var i = 0; i < raw.length; ++i) {
            source.push(raw[i].label);
            mapping[raw[i].label] = raw[i].value;
        }

        $('#course-name').autocomplete({
            source: source,
            select: function(event, ui) {
                $('#course_id').val( mapping[ui.item.value] );
                console.log($('#course_id').val());
            }
        });
        // http://jsfiddle.net/ambiguous/GVPPy/
    });



//    $('#course_id').val( courseNames.indexOf($('#course-name').val()) );
//    console.log( $('#course_id').val() );

</script>