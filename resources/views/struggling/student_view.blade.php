<!DOCTYPE html>
<html lang="en" class="full-height">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport"              content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token"            content="{{ csrf_token() }}">
    <meta property="og:url"            content="{{ url()->current() }}" />
    <meta property="og:type"           content="website" />
    <meta property="og:title"          content="Ayuda" />
    <meta property="og:description"    content="" />
    {{--<meta property="og:image"         content="https://portal.wineseq.com/images/shop/tubos.jpg" />--}} {{-- Min 200x200 --}}
    <title>Ayuda</title>

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
    {{--@include('students.css') --}}

    {{-- Styles: PAGE-SPECIFIC CSS --}}
    {{--@yield('particular_css') --}}

    {{-- JS dependencies --}}
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script> <!-- JQuery -->
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark elegant-color-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" style="color:white">Struggling Button</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div align="right">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto" style="color:white">
                        <li>
                            &nbsp;Buzón de Ayuda
                            <i class="fa fa-ambulance fa-flip-horizontal" aria-hidden="true"></i>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!--Intro Section-->
    <section class="view hm-black-strong" style="background: url({{ asset('img/background_gam.png') }})center center;">
        <div class="container" style="height:620px">
            <div class="divider-new pt-5" style="margin-bottom: 0px;">
                <h2 class="h2-responsive">
                    <i class="fa fa-ambulance" aria-hidden="true"></i>
                    &nbsp;Buzón de Ayuda&nbsp;
                    <i class="fa fa-medkit"></i></h2>
            </div>
            <div class="row" id="explanation">
                <div class="col-lg-12 mb-r">
                    <p>Este cuadro explicará en qué consite esta página</p>
                </div>
            </div>
            <div class="row" id="reason">
                <div class="col-lg-2 mb-r">
                    <p>REASON:</p>
                </div>
                <div class="col-lg-10 mb-r">
                    <select>
                        <option value="Option 1">Option 1</option>
                        <option value="Option 2">Option 2</option>
                        <option value="Option 3">Option 3</option>
                        <option value="Option 4">Option 4</option>
                    </select>
                </div>
            </div>

            <div class="row" id="urgency">
                <div class="col-lg-2 mb-r">
                    <p>URGENCY:</p>
                </div>
                <div class="col-lg-10 mb-r">
                    <select>
                        <option value="Option 1">Option 1</option>
                        <option value="Option 2">Option 2</option>
                        <option value="Option 3">Option 3</option>
                        <option value="Option 4">Option 4</option>
                    </select>
                </div>
            </div>

            <div class="row" id="source">
                <div class="col-lg-2 mb-r">
                    <p>SOURCE OF HELP:</p>
                </div>
                <div class="col-lg-10 mb-r">
                    <select>
                        <option value="Option 1">Option 1</option>
                        <option value="Option 2">Option 2</option>
                        <option value="Option 3">Option 3</option>
                    </select>
                </div>
            </div>

            <div class="row" id="description">
                <div class="col-lg-2 mb-r">
                    <p>BRIEF EXPLANATION OF THE PROBLEM:</p>
                </div>
                <div class="col-lg-10 mb-r">
                    <textarea style="background-color: white; resize:none"></textarea>
                </div>
            </div>
            <div style="justify-content: center; display:flex">
                <button class="btn btn-secondary" style="background-color: indianred">
                    ¡Necesito Ayuda!
                    <i class="fa fa-medkit" aria-hidden="true"></i>
                </button>
            </div>

        </div>
    </section>
    @include('partials/footer')
</body>
<script>

</script>
</html>