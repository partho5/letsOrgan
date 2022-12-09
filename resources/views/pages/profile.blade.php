<?php

$loggedIn = true ;

$filePathPrefix = "https://s3.ap-south-1.amazonaws.com/choriyedao";

?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <h3>
            Edit Profile
            <p><small id="name_of_user">{{$user->name}}</small></p>
        </h3>

        <div class="filedWapper form-group">
            <div class="col-md-4">Phone : </div>
            <div class="col-md-6"><input id="phoneField" type="text" class="form-control" value="{{$profileInfo->phone or ""}}"></div>
            <button id="saveBtn" class="col-md-2 btn btn-default">Save</button>
        </div>

        <div>
            <figure>
                <img src="{{$filePathPrefix}}{{$profileInfo->pp_url}}" alt="image" style="width: 200px; height: 200px;">
                <figcaption>Current Photo</figcaption>
            </figure>
        </div>

        <form action="/users/profile/update" method="post" enctype="multipart/form-data">
            <div class="filedWapper form-group">
                <div class="col-md-4">Photo : </div>
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="col-md-6"><input type="file" name="pp" class="form-control" required></div>
                <input type="submit" class="col-md-2" value="Upload">
            </div>
        </form>
    </div>

    <script src="/assets/js/ajaxMsgLibrary.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#saveBtn").click(function () {
                var phone = $("#phoneField").val();
                if( ! phone ){
                    phone = "not added";
                }
                var THIS = $(this);
                var wait4response = new Wait4Response();
                wait4response.eventFired(THIS, 'Updating...');
                $.ajax({
                    url : "/users/profile/update",
                    type : "post",
                    dataType : "text",
                    data : {
                        _token : "<?php echo csrf_token() ?>",
                        phone : phone,
                        userId : "<?php echo $userId; ?>"
                    },
                    success : function (response) {
                        //console.log(response);
                        wait4response.succeed(THIS, 'Updated', 3);
                    },
                    error : function (xhr, status) {
                        console.log(xhr+"\n"+status);
                        wait4response.failed(THIS, 'Failed. Try again', 7);
                    }
                });
            });
        });
    </script>
@endsection