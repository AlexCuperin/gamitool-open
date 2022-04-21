@extends('layouts.app')

@section('particular_css')
    <link rel="stylesheet" href={{ URL::asset('css/form_style.css') }}>
@endsection


@section('layout_content')

    <header>
    <section class="view intro-1 hm-black-strong">
        <div class="full-bg-img flex-center">
            <div class="container">
                <ul>
                    <li>
                        <h1 class="h1 font-bold"><br>Registration Form</h1>
                    </li>
                    <li>
                        <div class="form" style="color:black; text-align:left; padding-top:10px; padding-bottom:5px;">

                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label">Name:</label>
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label for="lastname" class="control-label">Last Name:</label>
                            <input id="lastname" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}">

                            @if ($errors->has('lastname'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('lastname') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">E-Mail Address:</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('institution') ? ' has-error' : '' }}">
                            <label for="institution" class="control-label">Institution:</label>
                            <select id="institution" type="select" class="form-control" name="institution" value="{{ old('institution') }}" required>
                                <option value="1">Universidad de Valladolid</option>
                                <option value="2">UPF</option>
                                <option value="3">UC3M</option>
                            </select>
                            @if ($errors->has('institution'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('institution') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">Password:</label>
                            <input id="password" type="password" class="form-control" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="control-label">Confirm Password:</label>

                            <div class="">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                                <button type="submit" class="btn btn-primary" style="margin:0">Register</button>
                        </div>
                    </form>
                </div>
                    </li>
                </ul>
        </div>
    </div>
    </section>
    </header>
@endsection
