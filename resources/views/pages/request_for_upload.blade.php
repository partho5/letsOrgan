<?php

$pageTitle = 'Request For Upload' ;


?>


@extends('app')


@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <div id="requestWrapper" class="col-md-12">
            <h3>Request Any File</h3>

            <div class="form-group">
                <label for="" class="col-md-5 text-left">File Name</label>
                <div class="input-group col-md-7">
                    <input type="text" id="file_name" class="form-control" placeholder="Ex : solid state electronics by Streetman">
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-md-5 text-left">What type of file</label>
                <div class="input-group col-md-7">
                    <input type="text" id="file_category" class="form-control" placeholder="Ex : Book/Video file">
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-md-5 text-left">Details(so that uploader can understand, what you actually need )</label>
                <div class="input-group col-md-7">
                    <textarea name="request_details" id="request_details" rows="4" class="form-control" placeholder="Optional"></textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4 col-md-offset-4">
                    <button id="requestUploadBtn" class="form-control btn spanBtn">Request</button>
                </div>
            </div>
        </div>
    </div>


    <script src="/assets/js/ajaxMsgLibrary.js"></script>
    <script>
        $(document).ready(function () {
            var wait4Response = new Wait4Response();

            $('#requestUploadBtn').click(function () {
                var THIS = $(this);
                var file_name = $('#file_name').val();
                var file_category = $('#file_category').val();
                var request_details = $('#request_details').val();

                $.ajax({
                    url : '/upload/request',
                    type : 'post',
                    dataType : 'text',
                    data : {
                        _token : "<?php echo csrf_token() ?>",
                        file_name : file_name,
                        file_category : file_category,
                        details : request_details
                    },
                    beforeSend : function () {
                        wait4Response.eventFired(THIS, "Requesting...");
                    },
                    success : function (response) {
                        if( response === 'success'){
                            $('#file_name').add('#file_category').add('#request_details').val('');
                            wait4Response.succeed(THIS, "Requested. Someone may upload the file", 7000);
                        }else{
                            wait4Response.failed(THIS, "Error occurred. Try again(or reload page)", 5000);
                        }
                    },
                    error : function (xhr, status, error) {
                        console.log(error);
                        wait4Response.failed(THIS, "Error occurred. Try again(or reload page)", 5000);
                    }
                })
            });
        });
    </script>
@endsection