@extends('app')


@section('pageContent')
    <div class="bar middle-bar col-md-7 text-center">
        <h3>
            <small>
                <strong>{{$univ}},{{$dept}}</strong> <br>
                Resource Manager
            </small>
        </h3>

        <h2><small>Upload <b>:</b></small></h2>
        <div id="radio-container">
            <label for=""><input type="radio" name="fileType" value="book">Book(pdf)</label>
            <label for=""><input type="radio" name="fileType" value="bookLink">Download link of a book</label>
            <label for=""><input type="radio" checked="checked" name="fileType" value="otherFile">Slide/Other File</label>
            <label for=""><input type="radio" name="fileType" value="videoLink">Youtube video link</label>
            <label for=""><input type="radio" name="fileType" value="incourse_question">Incourse/Midterm Question</label>
            <label for=""><input type="radio" name="fileType" value="final_question">Final Question</label>
            <label for=""><input type="radio" name="fileType" value="viva_question">Viva Question</label>
        </div> <hr>

        <div id="fileCategories">
            <div class="category" id="book">
                {!! Form::open(['url'=>'/handle_upload', 'files'=>'true']) !!}
                {!! Form::hidden('identifier', 'book') !!}
                <div class="form-group">
                    {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Book Name', 'required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('author', null, ['class'=>'form-control', 'placeholder'=>'Author', 'required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('category', null, ['class'=>'form-control', 'placeholder'=>'Book Category', 'required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::text('short_description', null, ['class'=>'form-control', 'placeholder'=>'[ Optional ] Short description']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('my_file', 'Upload book') !!}
                    <input type="file" name="my_file" required>
                </div>
                <div class="">
                    @include('pages.partial._year_semester_radio')
                </div> <br>
                <div class="form-group">
                    {!! Form::submit('Upload', ['class'=>'btn btn-primary form-control', 'id'=>'save_btn', 'style'=>'width:30%']) !!}
                </div>
                {!! Form::close() !!}

                @if($errors->any())
                    <ul class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                @endif

            </div>
            <div class="category" style="display: none" id="bookLink">
                <input id="name" type="text" class="form-control" placeholder="Book Name">
                <input id="author" type="text" class="form-control" placeholder="Author">
                <input id="category" type="text" class="form-control" placeholder="Book Category">
                <input id="link" type="text" class="form-control" placeholder="Link">
                <button class="btn btn-primary" id="saveBookLink">Save data</button>
                <span id="bookLinkMsg" style="display: none; color: #2ca02c">Saved</span>
            </div>
            <div class="category" style="display: none" id="otherFile">
                {!! Form::open(['url'=>'/handle_upload', 'files'=>'true']) !!}

                {!! Form::hidden('identifier', 'otherFile') !!}
                {!! Form::text('title', null, ['class'=>'form-control', 'placeholder'=>'Title for the file', 'required']) !!}
                {!! Form::text('author', null, ['class'=>'form-control', 'placeholder'=>'Author of this file', 'required']) !!}
                {!! Form::text('category', null, ['class'=>'form-control', 'placeholder'=>'File category', 'required']) !!}

                {!! Form::label('Upload File [max 100 MB]') !!}
                <input type="file" name="my_file" required>
                <br>
                {!! Form::submit('Upload', ['class'=>'btn btn-primary form-control', 'style'=>'width:30%']) !!}

                {!! Form::close() !!}

                @if($errors->any())
                    <ul>
                        @foreach($errors as $error)
                            <li class="alert alert-danger">{{$error}}</li>
                        @endforeach
                    </ul>
                @endif

            </div>
            <div class="category" style="display: none" id="videoLink">
                <input id="title" type="text" class="form-control" placeholder="Video Title">
                <input id="category" type="text" class="form-control" placeholder="Category">
                <input id="link" type="text" class="form-control" placeholder="Link">
                <input id="short_description" type="text" class="form-control" placeholder="[ Optional ] Short Description">
                <button class="btn btn-primary" id="saveYoutubeLink">Save data</button>
                <span id="videoLinkMsg" style="display: none; color: #2ca02c">Saved</span>
            </div>

            <div class="category" id="incourse_question" style="display: none;">
                {!! form::open(['url'=>'/handle_upload', 'files'=>'true']) !!}
                {!! Form::hidden('identifier', 'incourse_question') !!}
                {!! Form::text('course_title', null, ['class'=>'form-control', 'placeholder'=>'Course Title', 'required']) !!}
                {!! Form::text('course_code', null, ['class'=>'form-control', 'placeholder'=>'[ Optional ] Course Code']) !!}
                {!! Form::text('course_teacher_name', null, ['class'=>'form-control', 'placeholder'=>'Course Teacher Name', 'required']) !!}
                {!! Form::text('short_description', null, ['class'=>'form-control', 'placeholder'=>'[ optional ] Something about this course']) !!}

                {!! Form::label('my_file', 'Upload midterm / incourse question :') !!}
                <input type="file" name="my_file" required>

                <div class="form-group">
                    {!! Form::submit('Upload', ['class'=>'btn btn-primary form-control', 'style'=>'width:30%']) !!}
                </div>
                {!! Form::close() !!}
            </div>

            <div class="category" id="final_question" style="display: none;">
                {!! form::open(['url'=>'/handle_upload', 'files'=>'true']) !!}
                {!! Form::hidden('identifier', 'final_question') !!}
                {!! Form::text('course_title', null, ['class'=>'form-control', 'placeholder'=>'Course Title', 'required']) !!}
                {!! Form::text('course_code', null, ['class'=>'form-control', 'placeholder'=>'[ Optional ] Course Code']) !!}
                {!! Form::text('course_teacher_name', null, ['class'=>'form-control', 'placeholder'=>'Course Teacher Name', 'required']) !!}
                {!! Form::text('short_description', null, ['class'=>'form-control', 'placeholder'=>'[ optional ] Something about this course']) !!}

                {!! Form::label('my_file', 'Final exam question :') !!}
                <input type="file" name="my_file" required>

                <div class="form-group">
                    {!! Form::submit('Upload', ['class'=>'btn btn-primary form-control', 'style'=>'width:30%']) !!}
                </div>
                {!! Form::close() !!}
            </div>

            <div class="category" id="viva_question" style="display: none;">
                <div id="qa_wrapper" class="text-center" style="border: 3px solid #bebb65; padding: 5px;">
                    <div class="single_qa_row" style="border: 1px solid #c997a2 ; box-shadow: 2px 2px #bbbbbb; border-radius: 5px">
                        <div>
                            <input class="q form-control" type="text" placeholder="Question 1" style="width: 90%; margin-bottom: 3px">
                        </div>
                        <div>
                            <input class="a form-control" type="text" placeholder="Answer (If you don't know, keep blank, people will answer)" style="width: 90%">
                        </div>
                    </div>
                </div>
                <p id="viva_msg1" style="color: #ee4e4a; display: none">Blank question field will not be saved</p>
                <br><br>
                <button id="add_more" class="brn btn-primary" style="width: 30%">Add more</button>
                <button id="viva_q_save_btn" class="brn btn-primary" style="width: 30%">Save and Exit</button>
            </div>
        </div>
        <hr>
        <div id="ad2" class="col-md-12">
            advertisement here
        </div>


    </div>

    <script type="text/javascript" src="/assets/js/library.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var activeCategory=$("#radio-container input[type='radio']:checked").val();

            $('#'+activeCategory).siblings().hide();
            $('#'+activeCategory).show();

            $("#radio-container input").click(function () {
                activeCategory=$("#radio-container input[type='radio']:checked").val();
                $('#'+activeCategory).siblings().hide();
                $('#'+activeCategory).show();
            });

            $("#saveBookLink").click(function () {
                var THIS=$(this);
                var name=$("#name").val();
                var author=$("#author").val();
                var category=$("#category").val();
                var link=$("#link").val();
                if(name && author && category && link){
                    if(validateUrl(link)){
                        THIS.text("Saving....");
                        $.ajax({
                            url:"ajax",
                            type:"post",
                            dataType:"text",
                            data:{
                                _token: "<?php echo csrf_token(); ?>",
                                identifier:'bookLink',
                                name:name,
                                author:author,
                                category:category,
                                link:link
                            },
                            success:function (response) {
                                console.log(response);
                                if(response==='success'){
                                    THIS.text("Save Data");
                                    $("#bookLinkMsg").show();
                                    setTimeout(function () {
                                        $("#bookLinkMsg").fadeToggle(1000);
                                    },500);
                                }else{
                                    alert("Error occurred. Please Try again or contact support");
                                }
                            },
                            error:function (xhr, status) {
                                alert("Error occurred. Try again or contact support");
                            }
                        });
                    }else{
                        alert("Invalid Link, may be partially incorrect");
                    }
                }
                else {
                    alert("All field are mandatory");
                }
            });

            $("#saveYoutubeLink").click(function () {
                var THIS=$(this);
                var title=$("#videoLink #title").val();
                var category=$("#videoLink #category").val();
                var link=$("#videoLink #link").val();
                var short_description=$("#videoLink #short_description").val();
                //alert(title+"-"+category+"-"+link+"-"+short_description);
                if(title && category){
                    if(validateUrl(link)){
                        $.ajax({
                            url:'/ajax',
                            type:'post',
                            dataType:'text',
                            data:{
                                _token:"<?php echo csrf_token() ?>",
                                identifier:'videoLink',
                                title:title,
                                category:category,
                                link:link,
                                short_description:short_description
                            },
                            success:function (response) {
                                console.log(response);
                                if(response==='success'){
                                    THIS.text("Save Data");
                                    $("#videoLinkMsg").show();
                                    setTimeout(function () {
                                        $("#videoLinkMsg").fadeToggle(1000);
                                    },500);
                                }else{
                                    alert("Error occurred. Please Try again or contact support");
                                }
                            },
                            error:function (xhr, status) {
                                alert("Error occurred. Try again or contact support");
                            }
                        });
                    }else {
                        alert("Invalid link");
                    }
                }else{
                    alert("Title and category are mandatory");
                }
            });// saveYoutubeLink


            function shakeText(selector){
                $(selector).animate({'padding-left' : '50'} , 70 , function(){
                    $(selector).animate({'padding-left' : '0'} , 70)}
                );

            }
            var data, qNo=1;
            $("#add_more").click(function () {
                data='<br><div class="single_qa_row" style="border: 1px solid #c997a2 ; box-shadow: 2px 2px #bbbbbb; border-radius: 5px">'+
                    '<div>'+
                    '<input class="q form-control" type="text" placeholder="Question '+(++qNo)+'" style="width: 90%; margin-bottom: 3px">'+
                    '</div>'+
                    '<div>'+
                    '<input class="a form-control" type="text" placeholder="Answer (If you don\'t know, keep blank, people will answer)" style="width: 90%">'+
                    '</div>'+
                    '</div>';
                $("#qa_wrapper").append(data);
                data='';
                $("#viva_msg1").show();
                shakeText("#viva_msg1");
            }); //add_more

            var questions=[], answers=[];
            $("#viva_q_save_btn").click(function () {
                var i=0;
                $(".q").each(function () {
                    questions[i++]=$(this).val();
                });
                i=0;
                $(".a").each(function () {
                    answers[i++]=$(this).val();
                });

                var l=questions.length;
                var tmpQ=[], tmpA=[];
                for(var i=0;i<l;i++){
                    if(questions[i]){
                        tmpQ.push(questions[i]);
                        tmpA.push(answers[i]);
                    }
                }
                questions=tmpQ;
                answers=tmpA;

                $.ajax({
                    url:"/ajax",
                    type:"post",
                    dataType:"text",
                    data:{
                        _token: "<?php echo csrf_token(); ?>",
                        identifier: "viva_question",
                        questions: JSON.stringify(questions),
                        answers: JSON.stringify(answers)
                    },
                    success:function (response) {

                    },
                    error:function (xhr, status) {
                        console.log(xhr+"\n"+status);
                        alert("Error occured. Try again or contact support");
                    }
                });

                questions=[];answers=[];
            });
        });
    </script>

@endsection