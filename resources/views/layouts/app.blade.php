<!DOCTYPE html>
<html lang="en" class="full-height">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport"              content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token"            content="{{ csrf_token() }}">
        <meta property="og:url"            content="{{ url()->current() }}" />
        <meta property="og:type"           content="website" />
        <meta property="og:title"          content="GamiTool" />
        <meta property="og:description"    content="A tool to design and implement gamification into MOOCs involving different types of rewards such as points, badges and redeemable rewards." />
        {{--<meta property="og:image"         content="https://portal.wineseq.com/images/shop/tubos.jpg" />--}} {{-- Min 200x200 --}}
        <title>GamiTool</title>

        {{-- Favicon --}}
        {{-- TODO: look for a favicon --}}

        {{-- Icons --}}
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"><!-- Font Awesome -->
        {{--<link rel="stylesheet" href={{ URL::asset('css/fontello.css') }}>--}}

        {{-- Style --}}
        <link rel="stylesheet" href={{ URL::asset('css/bootstrap.min.css') }}> <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href={{ URL::asset('css/mdb.min.css') }}>       <!-- Material Design Bootstrap -->
        <link rel="stylesheet" href={{ URL::asset('css/style.css') }}>         <!-- Style for all site -->

        {{-- Fonts --}}
        {{--<link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>--}}

        {{-- TODO: clean --}}
        @include('partials/head')

        {{-- Styles: PAGE-SPECIFIC CSS --}}
        @yield('particular_css')

        {{-- JS dependencies --}}
        <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script> <!-- JQuery -->
    </head>
    <body id="app-layout">
        @include('partials/navbar')

        @yield('layout_content')

        @include('partials/footer')

        {{-- Javascript: CUSTOM GLOBAL JS --}}
        <script src="{{ asset('js/popper.min.js')       }}"></script><!-- Bootstrap dropdown -->
        <script src="{{ asset('js/bootstrap.min.js')    }}"></script><!-- Bootstrap core JavaScript -->
        <script src="{{ asset('js/mdb.min.js')          }}"></script><!-- MDB core JavaScript -->
        <script>new WOW().init();</script><!-- Animations init-->

        {{-- Javascript: CUSTOM PAGE-SPECIFIC JS --}}
        @yield('particular_js')

    </body>
</html>