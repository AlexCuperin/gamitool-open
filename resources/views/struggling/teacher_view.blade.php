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
    <link rel="stylesheet" href={{ URL::asset('css/struggling.css') }}>

    {{-- Fonts --}}
    {{--<link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>--}}

    {{-- TODO: clean --}}
    {{--@include('students.css') --}}

    {{-- Styles: PAGE-SPECIFIC CSS --}}

    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script> <!-- JQuery -->

    {{--@yield('particular_css') --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    {{-- JS dependencies --}}
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
                    <i class="fa fa-bar-chart"></i></i>
                    &nbsp;Analíticas para el Profesor&nbsp;
                    <i class="fa fa-medkit"></i></h2>
            </div>
            <div style="background-color:white">
            <div class="row" id="graphics">

            </div>
            <div class="row" style="margin: 10px">
                <h4 style="margin-bottom:20px">Estudiantes que solicitaron ayuda:</h4>
                <table id="example" class="table table-hover table-responsive-md" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Reason</th>
                        <th>Urgency</th>
                        <th>Source</th>
                        <th>Explanation</th>
                        <th>Timestamp</th>
                        <th>Solved?</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Gurú Sara</td>
                        <td>Content-related</td>
                        <td>High</td>
                        <td>Teacher</td>
                        <td>Explanation</td>
                        <td>2011/04/25</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>Profesor Edú</td>
                        <td>Quiz-related</td>
                        <td>Medium</td>
                        <td>Teacher</td>
                        <td>Explanation</td>
                        <td>2011/07/25</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Asen</td>
                        <td>Rewards</td>
                        <td>Low</td>
                        <td>I don't mind</td>
                        <td>Explanation</td>
                        <td>2009/01/12</td>
                        <td><No></No></td>
                    </tr>
                    </tbody>
                </table>

            </div>
            </div>

        </div>
    </section>
    @include('partials/footer')
</body>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
        $('.dataTables_wrapper').find('label').each(function() {
            $(this).parent().append($(this).children());
        });
        $('select').addClass('mdb-select');
        $('.mdb-select').material_select();
    });
</script>
</html>