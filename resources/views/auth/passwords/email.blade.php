<!DOCTYPE html>
<html lang="en" class="full-height">

<head>
    @include('partials/head')

</head>
<body>
<header>
@include('partials/navbar')

<section class="view intro-1 hm-black-strong">
    <div class="full-bg-img flex-center">
        <div class="container">
            <ul>
                <li>
                    <h1 class="h1-responsive font-bold wow fadeInDown" data-wow-delay="0.2s"><br>Password Reset</h1>
                </li>
                <li>
                    <div class="form wow fadeInUp" data-wow-delay="0.2s" style="color:black; text-align:left">

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">E-Mail Address:</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                                <button type="submit" class="btn btn-primary" style="margin:0">Send Password Reset Link</button>
                        </div>
                    </form>
                </div>
            </div>
                </li>
            </ul>
        </div>
    </div>
</section>
@include('partials/footer')
</body>
</html>
