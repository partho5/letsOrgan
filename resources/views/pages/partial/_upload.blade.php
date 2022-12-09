<div class="bar middle-bar col-md-7 text-center">
    <h2><small>Upload <b>:</b></small></h2>
    <hr>
    <div id="radio-container">
        <label for=""><input type="radio" name="fileType" value="book">Book(pdf)</label>
        <label for=""><input type="radio" name="fileType" value="bookLink">Download link of a book</label>
        <label for=""><input type="radio" checked="checked" name="fileType" value="otherFile">Slide/Other File</label>
        <label for=""><input type="radio" name="fileType" value="videoLink">Youtube video link</label>
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
    </div>
    <hr>
    <div id="ad2" class="col-md-12">
        advertisement here
    </div>


</div>