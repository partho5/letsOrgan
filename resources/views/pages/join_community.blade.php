<?php


$pageTitle = 'Join Community' ;

$currentYear = date('Y');

?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        @if($join_request == 'accepted')
            @if( ! $alreadyJoined )
                <div id="join-success-msg" style="display: none">
                    <h3>Welcome to our new community</h3>
                    <p>You have joined <a href="/community/view/{{$joinedCommunity->id}}">{{ $joinedCommunity->community_name }}</a> </p>
                </div>

                <br><br>

                <div id="session-dropdown-container" class="col-md-12" >
                    <p style="font-size: 20px">Academic session (When you got admitted in the university)</p>
                    <div class="col-md-4 col-md-offset-4">
                        <select id="academic-session" class="form-control col-md-4">
                            <option selected disabled>Select one</option>
                            @for($year = $currentYear+1 ; $year >= $currentYear-10 ; $year--)
                                <?php $val = ($year-1).' - '.$year ; ?>
                                <option value="{{ $val }}">{{ $val }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            @else
                <p>You are already a member of this community</p>
                <p>Please share this link who wants to join</p>
                <p><a href="{{ $joined_url }}">{{ $joined_url }}</a></p>
            @endif
        @else
            <p>Sorry ! You are visiting an incorrect link</p>
        @endif
    </div>

    <script>
        $(document).ready(function(){
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
                                $('#session-dropdown-container').hide();
                                $('#join-success-msg').show();
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
            });
        });
    </script>
@endsection