<?php

use App\Profile;
use Illuminate\Support\Facades\Auth;

if(Auth::check()){
    $loggedIn =true ;
    $userId = Auth::id();
    $profileInfo = Profile::where('user_id', $userId)->get();
    $profileInfo = $profileInfo[0];

    $currentCommunityName = 'Oops ! Error Occurred. Please Reload Page';
    foreach($allCommunities as $community){
        if ( $community->community_id == $currentCommunityId){
            $currentCommunityName = $community->community_name;
        }
    }
}else{
    $loggedIn = false ;
    $userId = 0;
    $profileInfo = null;
}

$myLibrary = new \App\Library\Library();
$isAdmin = $myLibrary->isAdmin($userId, $myLibrary->getCurrentCommunityId($userId));

?>

<!DOCTYPE html>
<html>
<head>
    <title>My Cloud</title>
    <style>
        body{
            padding-bottom: 10%;
        }
    </style>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.9.2/jquery.ui.widget.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.iframe-transport/1.0.1/jquery.iframe-transport.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.18.0/js/jquery.fileupload.js"></script>--}}

    {{--<link href="https://fonts.googleapis.com/css?family=Galada" rel="stylesheet">--}}
    {{--<link rel="stylesheet" type="text/css" href="/assets/font/font-awesome.min.css" />--}}

    <script src="/jquery.form.js"></script>

    <link rel="stylesheet" href="/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.11.2/jquery-ui.min.js"></script>
    @if($isAdmin)
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.ui-contextmenu/1.18.1/jquery.ui-contextmenu.min.js"></script>
    @endif

    <script src="/assets/js/ajaxMsgLibrary.js" type="text/javascript"></script>


    <link rel="stylesheet" type="text/css" href="/assets/font/font.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" media="screen" />


</head>
<body>
<div class="container">
    @include('pages.partial.menu-bar')
</div>

<div class="container row text-center" id="fileExplorer">
    <div id="topBarContainer">
        <div id="explorerNavBar">
            <div id="directoryBar" class="text-left">
                <span id="cloudExplorerSpan">
                    <img src="/assets/images/cloud-icon.png" alt="">
                    Cloud Explorer
                    <img src="/assets/images/cloud-icon.png" alt="">
                </span>
                <span id="backBtn">
                    <img  src="/assets/images/back-btn.png" alt="Back">
                    <span id="backBtnText" class="btn btn-default" style="background-color: #ff3a00; color: #fff">Back To Previous Folder</span>
                </span>
                <span id="rootDirectory">{{$username}}{!! $full_dir_url !!}</span>

            </div>
        </div>
        <div id="topBar1">
            <ul class="list-inline text-left">
                <li tabindex="1">View</li>
                <li tabindex="1">Tools</li>
                <li onclick="window.location.replace('/users/cloud/{{$currentCommunityId}}');">Reload</li>
                <li tabindex="1">Help</li>

                <li tabindex="1"><a href="/community/teachers?com={{ urlencode($currentCommunityName) }}&comId={{ $currentCommunityId }}">Teachers</a></li>
                <li tabindex="1"><a href="/community/courses?com={{ urlencode($currentCommunityName) }}&comId={{ $currentCommunityId }}">Courses</a></li>
                @if($isAdmin)
                <li tabindex="1" id="uploadFileIcon" data-toggle="collapse" data-target="#uploadContainer">Upload File</li>
                <li tabindex="1" id="createDirBtn">New Folder</li>
                @endif
                <span id="active_cloud_name" class="hidden" >{{ $currentCommunityName }}</span>
                {{--<img id="createDirBtn" src="/assets/images/createDirBtn.png" alt="Create Folder" title="Create Folder">--}}
            </ul>
        </div>
        <div id="topBar2">
            <ul class="list-inline text-left">

            </ul>
        </div>
    </div>

    <div class="container">
        <div class="container-fluid row" id="fileContainer">

            <div class="col-md-9 col-sm-push-3" id="rightBar">

                <div id="uploadContainer" class="collapse col-md-12">
                    @include('pages.partial._upload_container')
                </div>

                <p id="incomplete-feature-msg">
                    You can copy/cut file. But 'folder copy/cut' is under development.
                    <span class="font-f00">Please help me being patient.</span>
                </p>
                <div id="dirContainer" class="col-md-12">
                    @if( isset($dirFiles) )
                        @foreach($dirFiles as $dirOrFile)
                            @if($dirOrFile->file_ext == "null")
                                <div class="dirWrapper col-md-12" tabindex="1">
                                    <span class="dir hasmenu col-md-8" cloud_id="{{$dirOrFile->id}}"><img src="/assets/images/folder-icon.png" alt="">{{ $dirOrFile->name }}</span>
                                    <span class="col-md-4">
                                         <ul class="list-inline">
                                             <li class="hidden" class="cloudBtn"><a target="_blank" href="/users/cloud/view/{{$dirOrFile->id}}/">Details</a></li>
                                         </ul>
                                    </span>
                                </div>
                            @else
                                <div class="fileWrapper col-md-12" tabindex="1">
                                    <span class="file hasmenu col-md-8" cloud_id="{{$dirOrFile->id}}"><span style="color: #1b6d85" class="hasmenuA" target="_blank" href="/users/cloud/view/{{$dirOrFile->id}}" cloud_id="{{$dirOrFile->id}}">
                                            <img src="https://images.otwojob.com/product/D/B/s/DBsW2fTnUs8fuNy.png" width="20px" height="20px">
                                            {{ $dirOrFile->name }}
                                        </span></span>
                                    <span class="col-md-4">
                                         <ul class="list-inline">
                                             <li class="cloudBtn"><a target="_blank" href="/users/cloud/view/{{$dirOrFile->id}}">Details</a></li>
                                             <li class="cloudBtn"><a target="_blank" href="/users/cloud/preview/{{$dirOrFile->id}}">Preview</a></li>
                                             <li class="cloudBtn"><a href="/users/cloud/download/{{$dirOrFile->id}}">Download</a></li>
                                         </ul>
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="col-md-3 col-sm-pull-9" id="leftBar">
                <ul class="hidden">
                    <li id="cloudIcon">My Cloud</li>
                    <ul>
                        <li class="dirs">Pictures</li>
                        <li class="dirs">Holy Moments</li>
                        <li class="dirs">Picnic
                            <ul>
                                <li class="dirs">Sylhet Tour</li>
                                <li class="dirs">Sundarban</li>
                            </ul>
                        </li>
                        <li class="dirs">
                            Study
                            <ul>
                                <li class="dirs">GRE</li>
                                <li class="dirs">Academic</li>
                                <li class="dirs">
                                    Programming
                                    <ul>
                                        <li class="dirs">Java</li>
                                        <li class="dirs">Laravel</li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="dirs">Documents</li>
                    </ul>
                </ul>

                <div class="col-md-12">
                    @if( count($allCommunities) > 0 )
                        <h4>Switch to another community</h4>

                        <select id="cloud_communities" class="form-control">
                            @foreach($allCommunities as $community)
                                <?php $selectedStatus = ($community->community_id == $currentCommunityId) ? 'selected' : '' ?>
                                <option {{$selectedStatus}} value="{{ $community->community_id }}">{{ $community->community_name }}</option>
                            @endforeach
                        </select>
                        <br>
                        <a class="btn spanBtn" href="" id="switchCloudBtn">Switch Cloud</a>
                        <hr><div><a href="/users/cloud/bin/{{ $currentCommunityId }}"><img id="recycle_bin" src="/assets/images/recycle-bin.png" alt="Recycle Bin" title="Recycle Bin"></a></div><hr>
                        <div id="cloud_persons">
                            <select name="" id="person_select" class="form-control">
                                <option selected disabled>Select a person</option>
                                @foreach($allPeople as $singlePerson)
                                    <option value="{{ $singlePerson->user_id }}">{{ $myLibrary->getUserName($singlePerson->user_id) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <br>
                        <div id="cloud_contributor_action">
                            <select name="" id="action_select" class="form-control">
                                <option selected disabled>Action</option>
                                <option value="makeContributor">Make Contributor</option>
                                <option value="removeContributor">Remove From Contributors</option>
                            </select>
                        </div><br>

                        @if( $userRole == 'contributor')
                            <button class="btn spanBtn" id="executeBtn">Execute Action</button>
                        @else
                            <p>Only contributors can execute this action</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="/assets/js/library.js"></script>
    <script>
        /* START ajax Library */
        function cutCopyDirFile(dirFileCategory, name, getDestinationFullDir, cloudId, command){
            $.ajax({
                url : '/users/cloud/cut_copy_dir_file',
                type : 'post',
                dataType : 'text',
                data : {
                    _token : "<?php echo csrf_token() ?>",
                    name : name,
                    full_dir_url : getDestinationFullDir,
                    cloud_id : cloudId,
                    command : command
                },
                success : function (response) {
                    if( dirFileCategory === 'dir' ){
                        alert("Only File can be copied/cut. Folder copy/cut feature is under development");
                        $('#incomplete-feature-msg').show();
                        /* =====NOT IMPLEMENTED YET============ */
//                        var dataToAppend = dataToAppendForNewDir(cloudId, name);
//                        $('#dirContainer').prepend(dataToAppend);
                    }else if( dirFileCategory === 'file' ){
                        var dataToAppend = dataToAppendForNewFile(cloudId, name);
                        $('#dirContainer').prepend(dataToAppend);
                    }
                    console.log(response);
                },
                error : function (xhr) {
                    console.log(xhr);
                }
            });//ajax
        } //cutCopyDirFile()

        function deleteDirFile(cloudId, dirOrFile, target){
            $.ajax({
                url : '/users/cloud/delete_dir_file',
                type : 'post',
                dataType : 'text',
                data : {
                    _token : "{{csrf_token()}}",
                    id : cloudId,
                    dirOrFile : dirOrFile
                },
                success : function (response) {
                    if( response === 'deleted' ){
                        var wrapperName = (dirOrFile === 'file') ? 'file' : 'dir';
                        var THIS = target.closest('.'+wrapperName);
                        THIS.add( THIS.next() ).fadeToggle(1000);
                    }
                },error : function () {
                }
            }); //ajax
        } //deleteDirFile()

        function setCurrentCommunity4cloud(communityId) {
            $.ajax({
                url : '/users/cloud/save_current_cloud',
                type : 'post',
                dataType : 'text',
                data : {
                    _token : "{{ csrf_token() }}",
                    communityId : communityId
                }
            }); //ajax
        } // setCurrentCommunity4cloud()



        function renameDir(cloudId, dirOrFile, oldName, newName){
            $.ajax({
                url : '/users/cloud/rename_dir',
                type : 'post',
                dataType : 'text',
                data : {
                    _token : "<?php echo csrf_token() ?>",
                    id : cloudId,
                    dirOrFile : dirOrFile,
                    oldName : oldName,
                    newName : newName
                },
                success : function (response) {
                    console.log(response);
                },error : function (xhr, text, code) {
                }
            });
        }

        /* END ajax Library */
    </script>

    <script type="text/javascript">
        $(document).ready(function () {

            $("#closeUploadContainerBtn").click(function () {
                $("#uploadFileIcon").trigger("click");
            });

            var parentDir = "/";
            var full_dir_url = "/";


            /* >> default value before any user interaction */
            parentDir = $(".dirPartInBar:nth-last-child(1)").text();
            full_dir_url="";
            var l =  $(".dirPartInBar").length;
            $(".dirPartInBar").each(function (index) {
                if(index<l){
                    full_dir_url += "/"+$(this).text();
                }
            });

            full_dir_url = full_dir_url? full_dir_url : "/";
            parentDir = parentDir ? parentDir : "/";
            /* << default value before any user interaction */


            $("#createDirBtn").click(function () {
                var domToAppened = '<div class="dirElement">' +
                    '<input class="dirNameInput" type="text" placeholder="Folder Name">' +
                    '<img class="saveDirBtn" src="/assets/images/saveDirBtn.jpg" alt="Save" title="Save">' +
                    '<img class="deleteDirBtn" src="/assets/images/deleteDirBtn.png" alt="Delete" title="Delete">' +
                    '</div>';
                $("#dirContainer").prepend(domToAppened);
                $('.dirNameInput').focus();
            }); // createDirBtn clicked

            $(document).on("mouseenter", ".deleteDirBtn", function () {
                $(this).prev().prev().css("background-color", "#ffd9c2");
            });
            $(document).on("mouseleave", ".deleteDirBtn", function () {
                $(this).prev().prev().css("background-color", "#f2f5e9");
            });

            $(document).on("click", ".deleteDirBtn", function () {
                var txt = $(this).prev().prev().val();
                txt = txt ? ("'"+txt+"'") : "";
                if(confirm("Delete folder "+txt+" ?")){
                    $(this).add($(this).prev()).hide();
                    $(this).add( $(this).prev().prev() ).hide();
                }
            });


            $(document).on("click", ".saveDirBtn", function () {
                var thisPrev = $(this).prev();

                if( $.trim(thisPrev.val()) !=='' ){// i.e. if input val is not empty
                    var dirName = thisPrev.val();

                    if( validDirName(dirName) ){
                        if( ! directoryExist(dirName) ){
                            $(this).add( $(this).next() ).hide();
                            //var domToAppend = '<div class="dir" tabindex="1">'+(thisPrev.val())+'</div>';

                            //var domToAppend = dataToAppendForNewDir('', thisPrev.val());
                            //thisPrev.replaceWith(domToAppend);

                            var dirParent = $(".dirPartInBar").last().text();
                            var full_dir_url="";
                            $(".dirPartInBar").each(function () {
                                full_dir_url += "/"+$(this).text();
                            });
//alert(full_dir_url);
                            $.ajax({
                                url : "/users/cloud",
                                type : "post",
                                dataType : "text",
                                data : {
                                    _token : "<?php echo csrf_token() ?>",
                                    identifier : "saveDir",
                                    name : dirName,
                                    parent_dir : dirParent,
                                    full_dir_url : full_dir_url,
                                    possessed_by_community : "<?php echo $currentCommunityId; ?>"
                                },
                                success : function (id) {
                                    if ( ! isNaN(id) ){
                                        var domToAppend = dataToAppendForNewDir(id, thisPrev.val());
                                        thisPrev.replaceWith(domToAppend);
                                    }
                                },
                                error : function (xhr, status) {
                                    console.log(xhr+"\n"+status);
                                }
                            }); //end ajax call

                        }//check if directory already exists
                        else{
                            alert(dirName+" already exists");
                        }
                    } // if directory name contains valid characters
                    else{
                        alert("*,/,& these are invalid character in folder name");
                    }
                }//check if dirName empty or not
                else{
                    thisPrev.attr('placeholder', 'Give a folder name');
                }
            });


            var clickType = "click"; // single click
            if(mobileOrTablet()){
                //do nothing
            }else{
                clickType = "dblclick";
            }

            $(document).on(clickType, ".dir", function () {
                var curDir = $(this).html();
                var curDirFullPath = $("#rootDirectory").html();
                curDirFullPath += '<span tabindex="1" class="dirPartInBar">'+curDir+'</span>';

                curDir = $(this).text();

                $("#rootDirectory").html(curDirFullPath);

                $("#dirContainer").html("");


                parentDir = $(".dirPartInBar").last().text();
                //console.log(parentDir);


                full_dir_url = "";
                $(".dirPartInBar").each(function (index) {
                    full_dir_url += "/"+$(this).text();
                });

//alert(full_dir_url);
                $.ajax({
                    url : "/users/cloud",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "<?php echo csrf_token() ?>",
                        identifier : "loadDirFiles",
                        parent_dir : parentDir,
                        name : curDir,
                        full_dir_url : full_dir_url,
                        possessed_by_community : "<?php echo $currentCommunityId; ?>"
                    },
                    success : function (response) {
                        //console.log(response);
                        response.forEach(function(row){
                            //console.log(row.name+"--"+row.id);
                            var domToAppend ;
                            if(row.file_ext == "null"){
                                domToAppend = '<div class="dirWrapper col-md-12" tabindex="1">' +
                                                    '<span class="dir hasmenu col-md-8" cloud_id="'+(row.id)+'">'+(row.name)+'</span>'+
                                                    '<span class="col-md-4">'+
                                                        '<ul class="list-inline">'+
                                                        '<li class="hidden" class="cloudBtn"><a target="_blank" href="/users/cloud/view/'+(row.id)+'">Details</a></li>'+
                                                        '</ul>'+
                                                    '</span>'+
                                              '</div>';
                            }else{
                                domToAppend = '<div class="fileWrapper col-md-12" tabindex="1">'+
                                    '<span class="file hasmenu col-md-8" cloud_id="'+(row.id)+'"><a target="_blank" href="/users/cloud/view/'+(row.id)+'">'+(row.name)+'</a></span>'+
                                    '<span class="col-md-4">'+
                                    '<ul class="list-inline">'+
                                    '<li class="cloudBtn"><a target="_blank" href="/users/cloud/view/'+(row.id)+'">Details</a></li>'+
                                    '<li class="cloudBtn"><a target="_blank" href="/users/cloud/preview/'+(row.id)+'">Preview</a></li>'+
                                    '<li class="cloudBtn"><a target="_blank" href="/users/cloud/download/'+(row.id)+'">Download</a></li>'+
                                    '</ul>'+
                                    '</span>'+
                                    '</div>';
                            }
                            $("#dirContainer").append(domToAppend);
                        });
                    },
                    error : function (xhr, status) {
                        console.log(xhr+"\n"+status);
                    }
                }); //END ajax call for fetch dir files names
            });

            $(document).on("click", ".dirPartInBar", function () {
                var curDir = $(this).text();

                //console.log(curDir);
            });

            $("#backBtn").click(function () {
                var curDir = $(".dirPartInBar").last().text();
                if(curDir){
                    parentDir = $(".dirPartInBar:nth-last-child(2)").text();
                    full_dir_url="";
                    var l =  $(".dirPartInBar").length;
                    $(".dirPartInBar").each(function (index) {
                        if(index<l-1){
                            full_dir_url += "/"+$(this).text();
                        }
                    });



                    $("#rootDirectory .dirPartInBar").last().remove();
                    //console.log("==="+$("#rootDirectory .dirPartInBar").last().text()+"===");

                    $("#dirContainer").html("");

                    full_dir_url = full_dir_url? full_dir_url : "/";
                    parentDir = parentDir ? parentDir : "/";

                    $.ajax({
                        url : "/users/cloud",
                        type : "post",
                        dataType : "json",
                        async : false,
                        data : {
                            _token : "<?php echo csrf_token() ?>",
                            identifier : "loadDirFiles",
                            parent_dir : parentDir,
                            name : curDir,
                            full_dir_url : full_dir_url,
                            possessed_by_community : "<?php echo $currentCommunityId; ?>"
                        },
                        success : function (response) {
                            //console.log(response);
                            response.forEach(function(row){
                                //console.log(row.name+"--"+row.id);
                                var domToAppend ;
                                if(row.file_ext == "null"){
                                    domToAppend = domToAppend = '<div class="dirWrapper col-md-12" tabindex="1">' +
                                        '<span class="dir hasmenu col-md-8" cloud_id="'+(row.id)+'">'+(row.name)+'</span>'+
                                        '<span class="col-md-4">'+
                                        '<ul class="list-inline">'+
                                        '<li class="hidden" class="cloudBtn"><a target="_blank" href="/users/cloud/view/'+(row.id)+'">Details</a></li>'+
                                        '</ul>'+
                                        '</span>'+
                                        '</div>';
                                }else{
                                    domToAppend = '<div class="fileWrapper col-md-12" tabindex="1">'+
                                        '<span class="file hasmenu col-md-8" cloud_id="'+(row.id)+'"><a target="_blank" href="/users/cloud/view/'+(row.id)+'">'+(row.name)+'</a></span>'+
                                        '<span class="col-md-4">'+
                                        '<ul class="list-inline">'+
                                        '<li class="cloudBtn"><a target="_blank" href="/users/cloud/view/'+(row.id)+'">Details</a></li>'+
                                        '<li class="cloudBtn"><a target="_blank" href="/users/cloud/preview/">Preview</a></li>'+
                                        '<li class="cloudBtn"><a target="_blank" href="/users/cloud/download/">Download</a></li>'+
                                        '</ul>'+
                                        '</span>'+
                                        '</div>';
                                }
                                $("#dirContainer").append(domToAppend);
                            });
                        },
                        error : function (xhr, status) {
                            console.log(xhr+"\n"+status);
                        }
                    }); //END ajax call for fetch dir files names

                }else{
                    //alert("You are already in root directory");
                }
            }); //#backBtn click



            //starting upload Logic.........
            var activeCategory=$("#radio-container input[type='radio']:checked").val();

            $('#'+activeCategory).siblings().hide();
            $('#'+activeCategory).show();

            $("#radio-container input").click(function () {
                activeCategory=$("#radio-container input[type='radio']:checked").val();
                $('#'+activeCategory).siblings().hide();
                $('#'+activeCategory).show();
            });


            $("form").on('submit', function (e) {
                //my_cloud upload action for #submitBtn
                $(".parentDir").val(parentDir);
                $(".full_dir_url").val(full_dir_url);
                $('.possessed_by_community').val(
                    "<?php echo $currentCommunityId; ?>"
                );

                $('.loader_img').show();
                //alert({{ $currentCommunityId }});
                //$('#cloud_communities option:selected').val()

                return true;
            });


            $('#cloud_communities').on('change', function(){
                var selectedCommunityId = $(this).val();
                $('#switchCloudBtn').attr('href', '/users/cloud/'+selectedCommunityId);

                setCurrentCommunity4cloud(selectedCommunityId); //performs an ajax call
            });


            $("#save-youtube-btn").click(function () {
                var THIS=$(this);
                var title=$("#videoLink #title").val();
                var category=$("#videoLink #category").val();
                var link=$("#videoLink #link").val();
                var description=$("#videoLink #description").val();

                var wait4response = new Wait4Response();
                if(title && category){
                    if(validateUrl(link)){
                        wait4response.eventFired(THIS, 'Saving...');

                        $.ajax({
                            url:'/upload/save_youtube_link',
                            type:'post',
                            dataType:'text',
                            data:{
                                _token:"<?php echo csrf_token() ?>",
                                title:title,
                                category:category,
                                link:link,
                                description: description,
                                possessed_by_community : "<?php echo $currentCommunityId; ?>"
                            },
                            success:function (response) {
                                if( ! isNaN(response) ){
                                    wait4response.succeed(THIS, 'Saved', 6);
                                    $('#title, #category, #link, #description').val('');
                                    //console.log(response);
                                }else{
                                    wait4response.failed(THIS, "Couldn't save. Try again", 6);
                                    alert("Error occurred. Please Try again or contact support");
                                }
                            },
                            error:function (xhr, status, error) {
                                wait4response.failed(THIS, "Couldn't save. Try again. Or contact support", 6);
                                //alert("Error occurred. Try again or contact support");
                            }
                        });
                    }else {
                        alert("Invalid link");
                    }
                }else{
                    alert("Title and category are mandatory");
                }
            }); // #save-youtube-link clicked


            $('#executeBtn').click(function () {
                var userId = $('#person_select option:selected').val();
                var action = $('#action_select option:selected').val();

                //console.log(userId+'--'+action);
                var THIS = $(this);
                var wait4Response = new Wait4Response();
                wait4Response.eventFired(THIS, 'Executing...');
                $.ajax({
                    url : '/users/cloud/execute_contributor_action',
                    type : 'post',
                    dataType : 'text',
                    data : {
                        _token : "<?php  echo csrf_token() ?>",
                        userId : userId,
                        action : action,
                        communityId : "<?php  echo $currentCommunityId; ?>"
                    },success : function (response) {
                        if( response === 'success' ){
                            wait4Response.succeed(THIS, 'Successfull', 3);
                        }else{
                            wait4Response.failed(THIS, "Couldn't execute this action", 7);
                        }
                    },error : function (xhr, status) {
                        wait4Response.failed(THIS, "Couldn't execute this action", 7);
                    }
                })
            });


            //contextMenu
            var CLIPBOARD;
            var command ='';
            var txt = '';
            var cloudId = 0;
            var dirFileCategory = '';

            $(document).contextmenu({
                delegate: "#dirContainer",
                autoFocus: true,
                preventContextMenuForPopup: true,
                preventSelect: true,
                taphold: true,

                menu: [{
                    title: "Cut",
                    cmd: "cut",
//                    uiIcon: "ui-icon-scissors"
                },
                    {
                    title: "Copy",
                    cmd: "copy",
//                    uiIcon: "ui-icon-copy"
                },
                    {
                    title: "Paste",
                    cmd: "paste",
//                    uiIcon: "ui-icon-clipboard",
                    disabled: true
                },
                    {
                    title: "Delete",
                    cmd : "delete",
//                    uiIcon : "ui-icon-trash"
                },
                    {
                        title: "Rename",
                        cmd : "rename",
//                        uiIcon : "ui-icon-rename"
                    },
//                    {
//                        title: "More",
//                        children: [{
//                            title: "Sub 1 (callback)",
//                            action: function (event, ui) {
//                                alert("action callback sub1");
//                            }
//                        }, {
//                            title: "Edit <kbd>[F2]</kbd>",
//                            cmd: "sub2",
//                            tooltip: "Edit the title"
//                        }, ]
//                    }
                ],
                // Handle menu selection to implement a fake-clipboard
                select: function (event, ui) {
                    var $target = ui.target;

                    switch (ui.cmd) {
                        case "cut" :
                            txt = $target.text();
                            CLIPBOARD = isRightClickedInBlank(txt) ? "" : null;
                            command = 'cut';
                            cloudId = $target.closest('.hasmenu').attr('cloud_id');
                            dirFileCategory = $target.closest('.hasmenu').attr('class').split(' ')[0];
                            break;
                        case "copy":
                            txt = $target.text();
                            CLIPBOARD = isRightClickedInBlank(txt) ? "" : null;
                            command = 'copy';
                            cloudId = $target.closest('.hasmenu').attr('cloud_id');
                            dirFileCategory = $target.closest('.hasmenu').attr('class').split(' ')[0];
                            console.log(command +' '+txt+' from '+cloudId);
                            break;
                        case "delete":
                            txt = $target.text();
                            CLIPBOARD = isRightClickedInBlank(txt) ? "" : null;
                            command = 'delete';
                            cloudId = cloudId = $target.closest('.hasmenu').attr('cloud_id');
                            //console.log('file /dir of id '+cloudId+' should be deleted');
                            dirFileCategory = $target.closest('.hasmenu').attr('class').split(' ')[0];
                            if(confirm("Sure Delete ?")){
                                deleteDirFile(cloudId, dirFileCategory, $target.closest('.hasmenu'));
                            }
                            break;
                        case "paste":
                            CLIPBOARD = "";
                            var dirFileToProcess = txt;
                            cutCopyDirFile(dirFileCategory, dirFileToProcess, getDestinationFullDir(), cloudId, command);
                            console.log(command +' '+txt+' and paste to '+getDestinationFullDir());
                            break;
                        case "rename":
                            dirFileCategory = $target.closest('.hasmenu').attr('class').split(' ')[0];
                            var oldName = $target.text();
                            setFakeCookie("oldName", oldName);
                            command = 'rename';
                            cloudId = $target.closest('.hasmenu').attr('cloud_id');
                            $target.replaceWith(
                                "<input type='text' value='"+oldName+"' class='to-rename'>"+
                                "<span class='spanBtn rename-btn'>Rename</span>"
                            );
                            $('.to-rename').focus();
                            //console.log("rename from "+txt+"-- cloud id"+cloudId+" "+dirFileCategory);
                            break;
                    }

                    // Optionally return false, to prevent closing the menu now
                },
                // Implement the beforeOpen callback to dynamically change the entries
                beforeOpen: function (event, ui) {
                    var $menu = ui.menu,
                        $target = ui.target,
                        extraData = ui.extraData; // passed when menu was opened by call to open()

                    // console.log("beforeOpen", event, ui, event.originalEvent.type);

                    ui.menu.zIndex($(event.target).zIndex() + 1);

                    $(document)
                    //				.contextmenu("replaceMenu", [{title: "aaa"}, {title: "bbb"}])
                    //				.contextmenu("replaceMenu", "#options2")
                    //				.contextmenu("setEntry", "cut", {title: "Cuty", uiIcon: "ui-icon-heart", disabled: true})
//                        .contextmenu("setEntry", "cut", "Cut '" + $target.text() + "'")
//                        .contextmenu("setEntry", "copy", "Copy '" + $target.text() + "'")
                        .contextmenu("setEntry", "paste", "Paste" + (CLIPBOARD ? " '" + CLIPBOARD + "'" : ""))
                        .contextmenu("enableEntry", "paste", (CLIPBOARD !== ""));

                    // Optionally return false, to prevent opening the menu now
                }
            });//context menu


            $(document).on("click", '.rename-btn', function(){
                var newName = $(this).prev().val();
                if(newName){
                    // ! empty
                    if(validDirName(newName)){
                        $(this).prev().replaceWith(
                            '<span class="dir hasmenu col-md-8" cloud_id="'+cloudId+'">'+newName+'</span>'
                        );
                        $(this).hide();
                        renameDir(cloudId, dirFileCategory, getFakeCookie("oldName"), newName);
                    }
                    else{
                        alert("*,/,& these are invalid character as folder name");
                    }
                }else{
                    alert("Cann't be empty");
                }
            }); // .rename-btn click



        }); //end reday() function
    </script>
</div>
</body>
</html>
