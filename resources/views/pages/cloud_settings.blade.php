<?php

$pageTitle = "Settings";

$currentYear = date('Y');

$Lib = new \App\Library\Library();

?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <h3>{{ $community_name }} <small> >> </small> Settings</h3>
        Community can be switched from left bar in <strong>My Cloud</strong>

        <div id="academic-session-container" class="col-md-12">
            <h3 style="color: #000">Academic Session</h3>
            <div class="form-group col-md-12">
                <span class="col-md-8 text-left" style="font-size: 20px">Select academic session (When you got admitted)</span>
                <div class="input-group col-md-4">
                    <select class="form-control" id="academic-session">
                        <option selected disabled>Select One</option>
                        @for($year = $currentYear+1; $year > $currentYear - 10 ; $year--)
                            <?php
                            $optionVal = ($year-1).' - '.$year;
                            $selectedState = ($optionVal == $academicSession) ? 'selected' : '';
                            ?>
                            <option value="{{ $optionVal }}" {{ $selectedState }}>{{ $optionVal }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>

        <div id="desktop_sync_container" class="col-md-12">
            <h3 style="color: #000">Desktop/Mobile Client Synchronization</h3>
            <div class="form-group">
                <div class="input-group">
                    <input id="desktop-client-input" type="text" value="{{ $auth_token }}" class="col-md-8 form-control">
                    <span id="settings_copy_token_btn" class="input-group-addon">Copy</span>
                </div>
                <span style="display: none" id="token-copy-msg">Copied. Now go back to the software and paste it</span>
            </div>
            <hr>

            <h3>Automatically Download Only :</h3>
            <div class="form-group">
                @foreach($rootDirs as $dir)
                    <div class="allowed-to-sync col-md-6">
                        <input type="radio" name="syncable" {{ $dir->id == $dirIdToSync ? "checked" : "" }} value="{{$dir->id}}">
                        <span>{{$dir->name}}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>


    <script>
        $(document).ready(function () {
            $('#academic-session').on('change', function () {
                var mySession = $(this).val();

                if( confirm("My academic session is "+mySession) ){
                    $.ajax({
                        url : '/users/community/settings',
                        type : 'post',
                        dataType : 'text',
                        data : {
                            _token : "<?php echo csrf_token() ?>",
                            mySession : mySession
                        },
                        success : function (response) {
                            console.log(response);
                            if(response == 'success' ){

                            }else{

                            }
                        },
                        error : function () {

                        }
                    }); // ajax
                } // if confirm
                else{
                    $(this).val($.data(this, 'current')); // added parenthesis (edit)
                    return false;
                    // https://stackoverflow.com/questions/3963855/jquery-how-to-undo-a-select-change
                }
                console.log(mySession);
            }); // #academic-session click

            $('#settings_copy_token_btn').click(function(){
                $('#desktop-client-input').select();
                document.execCommand('copy');
                $('#token-copy-msg').slideDown(1000);
            }); // settings_copy_token_btn click


            $('.allowed-to-sync input').click(function () {
                var selectedDirId = $('.allowed-to-sync input[type="radio"]:checked').val();
                if(confirm("Sure change folder?")){
                    updateSyncableDir(selectedDirId);
                }else{
                    //todo
                }
            });










            function updateSyncableDir(selectedDirId){
                $.ajax({
                    url : '/users/community/settings/update_selected_dir',
                    type : 'post',
                    dataType : 'text',
                    data : {
                        _token : "<?php echo csrf_token() ?>",
                        selectedDirId : selectedDirId
                    },
                    success : function (response) {
                        console.log(response);
                    },error : function (xhr, errorMsg, code) {
                        alert("Opps ! Error occurred. Try again or contact support");
                    }
                });
            }

        }); //reday()
    </script>
@endsection