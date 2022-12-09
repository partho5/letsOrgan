<?php

$pageTitle = "Upload";

?>


@extends('app')


@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">

        <div id="uploadContainer">
            @include('pages.partial._upload_container')
        </div>

        <hr>
        <div id="ad2" class="col-md-12">

        </div>

    </div>


    <script type="text/javascript" src="/assets/js/library.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            //starting upload Logic.........
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
                            datatype:'text',
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
            });
        });
    </script>

@endsection