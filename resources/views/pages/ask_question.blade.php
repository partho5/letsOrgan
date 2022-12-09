<?php

$pageTitle = 'Ask Question';

?>

@extends('app')

@section('middleBarContent')
    <div id="middleBar" class="bars col-md-7 col-sm-push-2">
        <h3>Geeks will answer your question</h3>
        <p class="font-f00">This feature is still under development, you can ask but cann't edit your question</p>
        <div id="askWrapper" class="col-md-12">
            <div class="form-group col-md-12">
                <label for="" class="col-md-2 text-left">Title</label>
                <div class="input-group col-md-10">
                    <input name="q_title" id="q_title" type="text" class="form-control" placeholder="As short as possible">
                </div>
            </div>

            <div class="form-group col-md-12">
                <label for="" class="col-md-2 text-left">Details</label>
                <div class="input-group col-md-10">
                    <textarea name="q_details" id="q_details" rows="4" class="form-control" placeholder="Optional"></textarea>
                </div>
            </div>

            <div class="form-group col-md-12">
                <label for="" class="col-md-2 text-left">Topic</label>
                <div class="input-group col-md-10">
                    <input type="text" name="q_topic" id="q_topic" class="form-control" placeholder="Topic name helps people find your question">
                </div>
            </div>

            <div class="form-group col-md-12">
                <div class="input-group col-md-4 col-md-offset-4">
                    <button name="q_ask_btn" id="q_ask_btn" class="form-control">Ask</button>
                </div>
            </div>
        </div>
        <p id="my_question"></p>
    </div>


    <script src="/assets/js/ajaxMsgLibrary.js"></script>
    <script>
        $(document).ready(function () {

            var wait = new Wait4Response();

            $('#q_ask_btn').click(function () {
                var THIS = $(this);

                var q_title = $('#q_title').val();
                var q_details = $('#q_details').val();
                var q_topic = $('#q_topic').val();
                q_topic = JSON.stringify(q_topic);

                wait.eventFired( THIS , "wait...");
                $.ajax({
                    url : '/users/ask',
                    type : 'post',
                    dataType : 'text',
                    data : {
                        _token : "<?php echo csrf_token() ?>",
                        question : q_title,
                        details : q_details,
                        topic : q_topic
                    },
                    success : function (response) {
                        if( ! isNaN(response) ){
                            wait.succeed(THIS, 'question posted', 2000);
                            $('#q_title').val('');
                            $('#q_details').val('');
                            $('#q_topic').val('');
                            $('#my_question').html(
                                'Find you question here : <a href="/users/question/'+response+'">'+q_title+'</a>'
                            );
                        }else{
                            wait.failed(THIS, 'Error occurred. Try again or contact support', 2000);
                        }
                    },
                    error : function () {
                        wait.failed(THIS, 'Error occurred. Try again or contact support', 2000);
                    }
                });
                //console.log(q_title+'\n'+q_details+'\n'+q_topic);
            });
        });
    </script>
@endsection
