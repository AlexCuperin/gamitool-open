@extends('layouts.app')

@section('particular_css')
    <link rel="stylesheet" href={{ URL::asset('css/form_style.css') }}>
@endsection


@section('layout_content')

<header>
        <!--Intro Section-->
        <section class="view intro-1 hm-black-strong">
            <div class="full-bg-img flex-center">
                <div class="container">
                    <ul>
                        <li>
                            <h1 class="h1-responsive font-bold wow fadeInDown" data-wow-delay="0.2s"><br>Your Gamification Design Tool</h1>
                        </li>
                        <li>
                            <div class="form wow fadeInUp" data-wow-delay="0.2s">
                                <div class="circled extra-margin-bottom"><img class="image-circled" src="{{ asset('img/hat.svg') }}"/></div>

                                <form class="form-horizontal" method="POST" action="{{ route('login') }}"> {{ csrf_field() }}
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        @if ($errors->has('email'))
                                            <span class="help-block extra-margin-top" style="color:darkred">
                                            <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="email" required>
                                        {{--@if ($errors->has('email'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif--}}
                                    </div>

                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <input id="password" type="password" class="form-control" name="password" placeholder="password" required>
                                        {{--@if ($errors->has('password'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif--}}
                                    </div>

                                    <div class="form-group" style="font-size:12px; color:black;">
                                        <input type="checkbox" name="remember" style="width:10px" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </div>
                                    <div>
                                        <button type="submit" class="width-100">LOGIN</button>
                                    </div>
                                    <p class="message">Not registered? <a>Create an account</a></p>
                                    <p class="message" style="margin-top:0">Mmm.. passwhat? <a>Reset your password</a></p>
                                    {{--<p class="message">Not registered? <a href="{{ route('register') }}">Create an account</a></p>
                                    <p class="message" style="margin-top:0">Mmm.. passwhat? <a href="{{ route('password.request') }}">Reset your password</a></p>--}}
                                </form>
                            </div>
                        </li>
                        <!-- <li>
                            <p class="lead mt-4 mb-5 wow fadeInDown">Digital advertising agency focused on today's consumers</p>
                        </li>
                        <li>
                            <a target="_blank" href="https://mdbootstrap.com/getting-started/" class="btn btn-primary btn-lg wow fadeInLeft" data-wow-delay="0.2s" rel="nofollow">Sign up!</a>
                            <a target="_blank" href="https://mdbootstrap.com/material-design-for-bootstrap/" class="btn btn-default btn-lg wow fadeInRight" data-wow-delay="0.2s" rel="nofollow">Learn more</a>
                        </li> -->
                    </ul>
                </div>
            </div>
        </section>
    </header>
@endsection
