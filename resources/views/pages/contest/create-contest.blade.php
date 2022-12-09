@extends('pages.contest.contest-base-layout')

@section('contest_type')
    Create Contest
@endsection

@section('create_contest')
    <form action="/contest" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token()}}" />
        <h2>These Fields Are Needed</h2>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Contest Type</span>
            <div class="input-group col-md-8 col-xs-12">
                <select class="form-control" name="contest_type" required>
                    <option value="" selected disabled>Choose One</option>
                    @foreach($contestCategories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                    <option disabled>Coming More...</option>
                </select>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Title</span>
            <div class="input-group col-md-8 col-xs-12">
                <input type="text" name="title" class="form-control" placeholder="Ex. KUET EEE Circuit Conest 2018" required>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Topic</span>
            <div class="input-group col-md-8 col-xs-12">
                <textarea name="topic" id="" class="col-md-12" rows="3" placeholder="Ex. Basic Circuit, Topic Name 1, Topic Name 2 etc..." required></textarea>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Registration confirmation message</span>
            <div class="input-group col-md-8 col-xs-12">
                <textarea name="reg_confirm_msg" id="" class="col-md-12" rows="3" required>Dear participant, thank you for your participation.......(replace with your own)</textarea>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Additional Information <small>[ Optional ]</small></span>
            <div class="input-group col-md-8 col-xs-12">
                <textarea name="additional_info" id="" class="col-md-12" rows="5" placeholder="This text will be sent to participants via email when they register"></textarea>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Start Date Time</span>
            <div class="input-group col-md-8 col-xs-12">
                <input type="text" name="start_time" id="start_time" class="form-control" required>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">End Date Time</span>
            <div class="input-group col-md-8 col-xs-12">
                <input type="text" name="end_time" id="end_time" class="form-control" required>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Contest Policy <small>[ HTML allowed ]</small></span>
            <div class="input-group col-md-8 col-xs-12">
                <textarea name="contest_policy" id="" class="col-md-12 text-left" rows="5" required>1. Must submit within specified time</textarea>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Question Paper [ pdf ]</span>
            <div class="input-group col-md-8 col-xs-12">
                <input type="file" name="question_file_link" class="form-control" required>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Answer Script [ pdf ]</span>
            <div class="input-group col-md-8 col-xs-12">
                <input type="file" name="answer_file_link" class="form-control" required>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Result Publish Date Time</span>
            <div class="input-group col-md-8 col-xs-12">
                <input type="text" name="result_publish_time" id="result_publish_time" class="form-control" required>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Registration Fee
                <p><small class="label-info">[ If FREE, just leave this field or write 0 (zero) ]</small></p>
            </span>
            <div class="input-group col-md-8 col-xs-12">
                <input type="number" name="reg_fee" class="form-control" min="0" value="0" required>
                <p class="label-info text-left">
                    <small>
                        Organizers may charge for organizing the contest.
                        They will inform participants how to pay them ( bKash, hand cash etc... ).
                        Mention in <b>Additional Info</b> field if payment needed.
                    </small>
                </p>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Total Marks</span>
            <div class="input-group col-md-8 col-xs-12">
                <input type="number" name="total_marks" class="form-control" min="1" required>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Allowed to participate students of</span>
            <div class="input-group col-md-8 col-xs-12">
                <p class="text-left"><input type="radio" name="allowed_users" value="{{ $instituteInfo->institute_name }};{{ $instituteInfo->dept_name }}">{{ $instituteInfo->institute_name }}, {{ $instituteInfo->dept_name }}</p>
                <p class="text-left"><input type="radio" name="allowed_users" value="all;{{ $instituteInfo->dept_name }}">{{ $instituteInfo->dept_name }} of any university</p>
                <p class="text-left"><input type="radio" name="allowed_users" value="{{ $instituteInfo->institute_name }};all">Any department of {{ $instituteInfo->institute_name }}</p>
                <p class="text-left"><input type="radio" name="allowed_users" checked value="all;all">Any department of any university</p>
            </div>
        </div>

        <div class="form-group col-md-12">
            <input type="submit" value="Save Data" class="col-md-4 col-md-offset-4">
        </div>
    </form>

    {{--bootstrap datetime picker--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#start_time, #end_time, #result_publish_time').datetimepicker({
                format : 'DD-MM-YYYY HH:mm',
            });
        });
    </script>
@endsection