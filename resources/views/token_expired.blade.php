<!DOCTYPE html>
<html lang="en" class="full-height">

    <head>
        @include('partials/head')
        {{-- Icons --}}
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"><!-- Font Awesome -->
        {{--<link rel="stylesheet" href={{ URL::asset('css/fontello.css') }}>--}}

        {{-- Style --}}
        <link rel="stylesheet" href={{ URL::asset('css/bootstrap.min.css') }}> <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href={{ URL::asset('css/mdb.min.css') }}>       <!-- Material Design Bootstrap -->
        <link rel="stylesheet" href={{ URL::asset('css/style.css') }}>         <!-- Style for all site -->
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark elegant-color-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" style="color:white">GamiTool for MOOCs</a>
            </div>
        </nav>
        <header>
            <!--Intro Section-->
            <section class="view intro-1">
                <div class="full-bg-img flex-center">
                    <div class="container" style="margin-top:100px; text-align:center; padding-bottom:400px">
                        <p style="font-size: 30px; color: black">
                            Por favor, vuelva a iniciar sesión en el entorno de aprendizaje para visualizar la página.
                        </p>
                        <br>
                        <p style="font-size: 30px; color: black">
                            Si este error persiste por favor póngase en contacto con los profesores responsables del curso.
                        </p>
                    </div>
                </div>
            </section>
            @include('partials/footer')

        </header>

    </body>

</html>