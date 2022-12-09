<?php

$pageTitle = "Recycle Bin";

$filePathPrefix = "https://s3.ap-south-1.amazonaws.com/choriyedao";

?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <h3>Recover any file you intentionally/accidentally deleted</h3>
        <table class="table text-left">
            <tr>
                <th>Folder/File name</th> <th>Type</th>   <th>Location</th> <th>Action</th>
            </tr>
            @foreach($deletedDirFiles as $dirFile)
                <tr>
                    <td>{{ $dirFile->name }}</td>
                    <td>{{ $dirFile->file_ext == 'null' ? 'Folder' : 'File' }}</td>
                    <td>{{ $dirFile->full_dir_url }}</td>
                    <td id="{{ $dirFile->id }}" com-id="{{ $communityId }}" class="restore-btn btn btn-info">Restore</td>
                </tr>
            @endforeach
        </table>
    </div>

    <script>
        $(document).ready(function(){
            $('.restore-btn').click(function () {
                var THIS = $(this);
                var id = THIS.attr('id');
                var communityId = THIS.attr('com-id');

                $.ajax({
                    url : '/users/cloud/bin/restore',
                    type : 'post',
                    datType : 'text',
                    data : {
                        _token : "<?php echo csrf_token() ?>",
                        communityId : communityId,
                        id : id
                    },
                    success: function (response) {
                        console.log(response);
                        if(response === 'success'){
                            THIS.parent().fadeToggle(500);
                        }else if(response === 'exist'){
                            alert("Folder/File exists in cloud explorer");
                        }
                    },error : function (xhr, status, error) {
                        console.log(error);
                    }
                }); // ajax
            }); // restore-btn clicked
        }); // ready()
    </script>
@endsection