@extends('pages.contest.contest-base-layout')

@section('contest_type')
    Edit Contest
@endsection


@section('edit_contest')
    @if($success_msg = Session::get('success_msg'))
        <p class="text-info text-left">{!! $success_msg !!}</p>
        {{ session()->flush() }}
    @endif

    <form action="/contest/{{$contest->id}}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token()}}" />
        {{ method_field('put') }}
        <h2>Update Data</h2>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Contest Type</span>
            <div class="input-group col-md-8 col-xs-12">
                <select class="form-control" name="contest_type" required>
                    @foreach($contestCategories as $category)
                        @if($category == $contest->contest_type)
                            <option value="{{ $category }}" selected>{{ $category }}</option>
                        @else
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Title</span>
            <div class="input-group col-md-8 col-xs-12">
                <input type="text" name="title" class="form-control" value="{{ $contest->title }}" required>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Topic <small>[ Optional ]</small></span>
            <div class="input-group col-md-8 col-xs-12">
                <textarea name="topic" id="" class="col-md-12" rows="3">{{ $contest->topic }}</textarea>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Additional Information <small>[ Optional ]</small></span>
            <div class="input-group col-md-8 col-xs-12">
                <textarea name="additional_info" id="" class="col-md-12" rows="5">{{ $contest->additional_info }}</textarea>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Start Date Time</span>
            <div class="input-group col-md-8 col-xs-12">
                <input type="text" name="start_time" id="start_time" class="form-control" value="{{ $contest->start_time }}" required>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">End Date Time</span>
            <div class="input-group col-md-8 col-xs-12">
                <input type="text" name="end_time" id="end_time" class="form-control" value="{{ $contest->end_time }}" required>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Contest Policy <small>[ HTML allowed ]</small></span>
            <div class="input-group col-md-8 col-xs-12">
                <textarea name="contest_policy" id="" class="col-md-12 text-left" rows="5" required>{{ $contest->contest_policy }}</textarea>
            </div>
        </div>
        <div class="form-group col-md-12" style="display: none">
            <span for="" class="col-md-4 text-left">Question Paper [ pdf ]</span>
            <div class="input-group col-md-8 col-xs-12">
                <input type="file" name="question_file_link" class="form-control">
            </div>
        </div>
        <div class="form-group col-md-12" style="display: none">
            <span for="" class="col-md-4 text-left">Answer Script [ pdf ]</span>
            <div class="input-group col-md-8 col-xs-12">
                <input type="file" name="answer_file_link" class="form-control">
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Result Publish Date Time</span>
            <div class="input-group col-md-8 col-xs-12">
                <input type="text" name="result_publish_time" id="result_publish_time" class="form-control" value="{{ $contest->result_publish_time }}" required>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Registration Fee
                <p><small class="label-info">[ If FREE, just leave this field or write 0 (zero) ]</small></p>
            </span>
            <div class="input-group col-md-8 col-xs-12">
                <input type="number" name="reg_fee" class="form-control" min="0" value="{{ $contest->reg_fee }}" required>
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
                <input type="number" name="total_marks" class="form-control" min="1" value="{{ $contest->total_marks }}" required>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span for="" class="col-md-4 text-left">Allowed to participate students of</span>
            <div class="input-group col-md-8 col-xs-12">
                @if($allowedUsers == $instituteInfo->institute_name.';'.$instituteInfo->dept_name)
                    <p class="text-left"><input checked type="radio" name="allowed_users" value="{{ $instituteInfo->institute_name }};{{ $instituteInfo->dept_name }}">{{ $instituteInfo->institute_name }}, {{ $instituteInfo->dept_name }}</p>
                @else
                    <p class="text-left"><input type="radio" name="allowed_users" value="{{ $instituteInfo->institute_name }};{{ $instituteInfo->dept_name }}">{{ $instituteInfo->institute_name }}, {{ $instituteInfo->dept_name }}</p>
                @endif


                @if($allowedUsers == 'all;'.$instituteInfo->dept_name)
                    <p class="text-left"><input checked type="radio" name="allowed_users" value="all;{{ $instituteInfo->dept_name }}">{{ $instituteInfo->dept_name }} of any university</p>
                @else
                    <p class="text-left"><input type="radio" name="allowed_users" value="all;{{ $instituteInfo->dept_name }}">{{ $instituteInfo->dept_name }} of any university</p>
                @endif


                @if($allowedUsers == $instituteInfo->institute_name.';all')
                    <p class="text-left"><input checked type="radio" name="allowed_users" value="{{ $instituteInfo->institute_name }};all">Any department of {{ $instituteInfo->institute_name }}</p>
                @else
                    <p class="text-left"><input type="radio" name="allowed_users" value="{{ $instituteInfo->institute_name }};all">Any department of {{ $instituteInfo->institute_name }}</p>
                @endif

                @if($allowedUsers == 'all;all')
                    <p class="text-left"><input checked type="radio" name="allowed_users" value="all;all">Any department of any university</p>
                @else
                    <p class="text-left"><input type="radio" name="allowed_users" value="all;all">Any department of any university</p>
                @endif
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
