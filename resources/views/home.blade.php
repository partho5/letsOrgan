@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading hidden">Dashboard</div>

                <div class="panel-body">
                    @include('pages.partial.introduction')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
