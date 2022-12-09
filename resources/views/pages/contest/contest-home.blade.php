@extends('pages.contest.contest-base-layout')

@section('contest_type')
    All Contest
    <p><small style="color: #f00">This Feature Is Under Development</small></p>
@endsection

@section('available_contests')
    <style>
        #available-contests{
            color: #000000;
        }
        .single-contest{
            background-color: rgba(228, 255, 0, 0.3);
            margin-top: 5px;
            border: 1px solid rgba(157, 176, 0, 0.81);
            border-bottom: 2px solid rgba(113, 107, 0, 0.94);
        }
        .contest-reg-btn, .cancel-contest-btn{
            background-color: rgb(204, 57, 0);
            color: #fff;
            padding: 2px 20px;
            cursor: pointer;
        }
        .contest-reg-btn:hover, .cancel-contest-btn:hover{
            background-color: rgb(255, 71, 0);
            border-bottom: 2px solid rgba(0, 0, 0, 0.8);
        }
    </style>

    @if( count($runningContest) == 0 )
        @if(count($availableContest) > 0)
            @foreach($availableContest as $contest)
                <div class="single-contest col-md-12">
                    <h2 class="text-center">{{ $contest->title }}</h2>
                    <div class="col-md-6 text-left"> <b>Contest Type :</b> <i>{{ $contest->contest_type }} Contest</i> </div>
                    <div class="col-md-6 text-left"> <b>Topic :</b> <i>{{ $contest->topic }}</i> </div>
                    <div class="col-md-6 text-left"> <b>Additional Info :</b> <i>{{ $contest->additional_info }}</i> </div>
                    <div class="col-md-6 text-left"> <b>Starts at :</b> <i>{{ \Carbon\Carbon::parse($contest->start_time)->format('j F h:i A') }}</i> </div>
                    <div class="col-md-6 text-left"> <b>Ends at :</b> <i>{{ \Carbon\Carbon::parse($contest->end_time)->format('j F h:i A') }}</i> </div>
                    @if($contest->reg_fee > 0)
                        <div class="col-md-6 text-left"> <b>Registration Fee :</b> <i>{{ $contest->reg_fee }} TK</i> </div>
                    @endif
                    @if($contest->creator_id != $userId)  <!-- i.e. he is NOT Creator/Admin  -->
                        @if(\Carbon\Carbon::parse(\Carbon\Carbon::now()) <= \Carbon\Carbon::parse($contest->end_time))
                            @if($contest->is_registered)
                                @if(\Carbon\Carbon::parse(\Carbon\Carbon::now()) >= \Carbon\Carbon::parse($contest->start_time) )
                                    @if(\Carbon\Carbon::parse(\Carbon\Carbon::now()) > \Carbon\Carbon::parse($contest->end_time) )
                                        <p class="col-md-12 text-center"><span style="color: rgba(245,0,0,0.8)" contest-id="{{ $contest->id }}">CONTEST IS OVER</span></p>
                                    @else
                                        <p class="col-md-12 text-center"><span class="go-for-contest-btn" contest-id="{{ $contest->id }}">Go For Contest</span></p>
                                    @endif
                                @else
                                    <p class="col-md-12 text-center"><span class="cancel-contest-btn" contest-id="{{ $contest->id }}">Cancel Registration</span></p>
                                @endif
                            @else
                                <p class="col-md-12 text-center"><span class="contest-reg-btn" contest-id="{{ $contest->id }}">Register</span></p>
                            @endif
                        @else
                            <p class="col-md-12 text-center" style="color: rgba(255,0,0,0.83)">CONTEST IS OVER</p>
                        @endif
                    @else <!-- he is creator/admin -->
                        @if(\Carbon\Carbon::parse(\Carbon\Carbon::now()) <= \Carbon\Carbon::parse($contest->end_time))
                            <p class="col-md-12 text-center">Total Registered : {{ $contest->total_registered }} </p>
                        @else
                        <p class="col-md-12 text-center" style="color: rgba(255,0,0,0.83)">Check Answer Scripts</p>
                        @endif
                    @endif
                </div>
            @endforeach
        @endif
    @else
        <p></p>
    @endif



    <script>
        $(document).ready(function () {
            $('.contest-reg-btn').click(function () {
                var THIS = $(this);
                var contestId = $(this).attr('contest-id');
                $.ajax({
                    url : '/contest/register',
                    type : 'post',
                    dataType : 'text',
                    data : {
                        _token : "<?php echo csrf_token() ?>",
                        contest_id : contestId,
                    }, success : function (response) {
                        if(response === 'registered'){
                            THIS.parent().replaceWith("<p class='col-md-12 text-center'><span class='cancel-contest-btn' contest-id='"+contestId+"'>Cancel Registration</span></p>");
                        }
                    }, error : function () {
                        alert("error");
                    }
                }); // ajax
            });

            $('.cancel-contest-btn').click(function () {
                var THIS = $(this);
                var contestId = $(this).attr('contest-id');
                $.ajax({
                    url : '/contest/register_cancel',
                    type : 'post',
                    dataType : 'text',
                    data : {
                        _token : "<?php echo csrf_token() ?>",
                        contest_id : contestId,
                    }, success : function (response) {
                        if(response === 'canceled'){
                            THIS.parent().replaceWith("<p class='col-md-12 text-center'><span class='contest-reg-btn' contest-id='"+contestId+"'>Register</span></p>");
                        }
                    }, error : function () {
                        alert("error");
                    }
                }); // ajax
                //console.log(contestId);
            });


            var contestToStart = 0;
            $('.go-for-contest-btn').click(function () {
                contestToStart = $(this).attr('contest-id');
                console.log(contestToStart);

                $('#contest-policy-box').show();
                $(this).parents('.single-contest').siblings().hide();

                $('#contest-policy-box h3').animate({
                    'margin-top' : '20px',
                    'opacity' : 0.6
                },400, function () {
                    $(this).css('margin-top', '50px');
                });
                $('#contest-policy-box h3').animate({
                    'margin-top' : '20px',
                    'opacity' : 1
                });

                $('#contest-policy-box .alert-text').animate({
                    'margin-top' : '0px',
                    'opacity' : 1
                }, 1500);
                $('#contest-policy-box .policy-text').animate({
                    'margin-top' : '0px',
                    'opacity' : 1
                }, 2000);
            });

            $('#policy-acceptInput').click(function () {
                var checked = $(this).is(':checked') ? true : false;
                if(checked) {
                    $('#start-contest').show();
                }
                else{
                    $('#start-contest').hide();
                }
            });


            $('#contest-policy-box form').submit(function (e) {
                $('#contest-policy-box form .contest-id').val(contestToStart);
            });



            $('#contest-submit-btn').click(function () {
                var submittedContestId = $(this).attr('contest-id');
                var submittedAns = $('#contest-answer'+submittedContestId).val();

                console.log(submittedContestId+'\n'+submittedAns);
            });

        }); //ready
    </script>

@endsection
