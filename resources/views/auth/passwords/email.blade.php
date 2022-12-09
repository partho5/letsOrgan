@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Send Password Reset Link
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="col-md-12 text-center">
                        <br> Check <br>
                        <div class="col-md-12">
                            <a href="https://mail.google.com/" target="_blank" class="col-md-6 col-sm-12 text-right">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTIFYu4g-gIh2R_rwGbRWd1Vnoq3QcGIxL7cqoSPaN7xwKnkptO8w" width="30px" height="20px">
                                Gmail
                            </a>
                            <a href="https://mail.yahoo.com/" target="_blank" class="col-md-6 col-sm-12 text-left">
                                <img src="https://pwnrules.com/wp-content/uploads/2015/11/yahoo-mail-logo.png" width="30px" height="20px">
                                Mail
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
