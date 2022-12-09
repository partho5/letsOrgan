@extends('app')

@section('pageContent')
    <div class="bar middle-bar col-md-7 text-center">
        <h3><small>Request for uploading a file</small></h3>
        <div class="col-md-12 text-center" title="Also look at requests by other">
            <input id="type" type="text" class="form-control" placeholder="Book or Video or what?">
            <input id="category" type="text" class="form-control" placeholder="Category (e.g. C# tutorial or Java book etc)">
            <input id="details" type="text" class="form-control" placeholder="[ optional ] Details">
            <button class="btn btn-primary" id="save">Invite others to upload</button>
            <span id="msg" style="display: none; color: #2ba028">Saved</span>
        </div>
        <div id="ad2" class="col-md-12">
            advertisement here
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#save").click(function () {
                var THIS=$(this);
                var type=$("#type").val();
                var category=$("#category").val();
                var details=$("#details").val();

                if(type && category){
                    THIS.text("Sending Invitation");
                    $.ajax({
                        url:'/ajax',
                        type:'post',
                        dataType:'text',
                        data:{
                            _token : "<?php echo csrf_token(); ?>",
                            identifier : 'upload-request',
                            type : type,
                            category : category,
                            details : details
                        },
                        success:function (response) {
                            if (response==='success'){
                                THIS.text("Invite others to upload");
                                $("#msg").show();
                                setTimeout(function () {
                                    $("#msg").fadeToggle(1000);
                                }, 500);
                            }else {
                                alert("Error ! Try again or contact support");
                            }
                        }
                    });
                }else {
                    alert("First 2 fields are mandatory");
                }
            });
        });
    </script>
@endsection