@extends('layouts.app')

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form class="form-horizontal validate" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <span class="badge">Personal Information</span>

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name" class="col-md-4 control-label">Name</label>

            <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name"
                       value="{{ old('name') }}" required autofocus>

                @if ($errors->has('name'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email"
                       value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class="col-md-4 control-label">Password</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

            <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control"
                       name="password_confirmation" required>
            </div>
        </div>

        <span class="badge">Basecamp Information</span>

        <div class="form-group{{ $errors->has('basecamp_org') ? ' has-error' : '' }}">
            <label for="basecamp_org" class="col-md-4 control-label">Basecamp Company Name</label>

            <div class="col-md-6">
                <input id="basecamp_org" type="text" class="form-control"
                       name="basecamp_org"
                       value="{{ old('basecamp_org') }}" required>

                @if ($errors->has('basecamp_org'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('basecamp_org') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('basecamp_api_key') ? ' has-error' : '' }}">
            <label for="basecamp_api_key" class="col-md-4 control-label">Basecamp API Key</label>

            <div class="col-md-6">
                <input id="basecamp_api_key" type="text" class="form-control"
                       name="basecamp_api_key"
                       value="{{ old('basecamp_api_key') }}" required>

                @if ($errors->has('basecamp_api_key'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('basecamp_api_key') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('basecamp_api_user_id') ? ' has-error' : '' }}">
            <label for="basecamp_api_user_id" class="col-md-4 control-label">Basecamp User
                ID</label>

            <div class="col-md-6">
                <input id="basecamp_api_user_id" type="text" class="form-control"
                       name="basecamp_api_user_id"
                       placeholder="eg 12345678"
                       value="{{ old('basecamp_api_user_id') }}" required>

                @if ($errors->has('basecamp_api_user_id'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('basecamp_api_user_id') }}</strong>
                                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                    Register
                </button>
            </div>
        </div>
    </form>
@endsection
