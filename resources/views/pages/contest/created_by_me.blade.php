<style>
    .contest-by-me{
        color: rgb(153, 43, 0) !important;
        background-color: rgba(255, 255, 255, 0.77);
        padding: 2px 20px;
        font-size: 20px;
    }
</style>

@extends('pages.contest.contest-base-layout')

@section('contest_type')
    Contests Created By Me
@endsection


@section('contest_create_by_me')
    @foreach($contestCreatedByMe as $contest)
        <ul class="text-left">
            <li><a class="contest-by-me" href="/contest/{{ $contest->id }}/edit">{{ $contest->title }}</a></li>
        </ul>
    @endforeach
@endsection
