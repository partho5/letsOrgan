<?php

$pageTitle = "Community";

?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <div class="col-md-12">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 hidden">
                <a href="/community/create">
                    <button class="btn spanBtn" type="button" id="createCommunityBtn">Create Community</button>
                </a>
            </div>
        </div>

        <br><br>
        <div>
            <h3>My Communities <small>({{$totalJoinedCommunities}})</small></h3>
            <div class="col-md-12 form-group">
                <p class="col-md-5 text-right">Root community : </p>
                <div class="col-md-6">
                    <select name="root_community" id="root_community" class="form-control col-md-6" root_community="{{$user->root_community}}">
                        @foreach( $joinedCommunityDetails as $joinedCom )
                            <?php
                                $joinedCom = $joinedCom[0];
                                $checkedState = ( $joinedCom->id == $user->root_community ) ? 'selected' : '';
                            ?>
                            <option value="{{$joinedCom->id}}" {{$checkedState}}>{{ $joinedCom->community_name or "" }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr>
            <div>
                @foreach($joinedCommunityDetails as $joinedCom)
                    <?php $joinedCom = $joinedCom[0]; ?>
                    <div class="singleCommunity">
                        <strong>
                            <a href="/community/view/{{$joinedCom->id}}">{{$joinedCom->community_name or ""}}</a>
                        </strong>

                        &nbsp;&nbsp;&nbsp;&nbsp;
                        @if( $joinedCom->isCreator )
                            <a href="/community/edit/{{$joinedCom->id}}"><span>Edit</span></a>
                        @endif
                        <p>{{$joinedCom->description or ""}}</p>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function(){
            $(document).on('change', '#root_community', function () {
                var rootCommunityId = $('#root_community').attr('root_community');
                var selectedVal = $('#root_community').val();
                if( rootCommunityId != selectedVal ){
                    if( confirm("Change Root Community ?") ){
                        $.ajax({
                            url : '/community/action/change_root_community',
                            type : 'post',
                            dataType : 'text',
                            data : {
                                _token : "<?php echo csrf_token() ?>",
                                root_community : selectedVal
                            },
                            success : function (response) {
                                if( response === 'updated' ){
                                    $('#root_community').attr('root_community', selectedVal);
                                }
                            },
                            error : function (xhr, status, error) {
                            }
                        }); //end ajax
                    } //end if confirm
                } //end rootCommunityId != selectedVal
            });
        });
    </script>
@endsection
