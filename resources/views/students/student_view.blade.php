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
    @include('students.css')

    {{-- Styles: PAGE-SPECIFIC CSS --}}
    @yield('particular_css')
    
    {{-- JS dependencies --}}
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script> <!-- JQuery -->
</head>
<body>
    @include('students.nav')

    @if($gdeploy_id == 3)
        @include('students.views.control')
    @else
        @include('students.views.rewards')
    @endif

    @include('partials/footer')
</body>
<script>
    $('.request_button').click(function(){
        $table = $(this).closest('.table');

        load_button($(this));
        send_request($(this),$table);
    });
    function load_button(button){
        button.html('<i class="fa fa-gear fa-spin std-margin-right"></i>Requesting...');
    }
    function button_success(button){
        button.removeClass('btn-primary request_button');
        button.addClass('btn-secondary disabled');
        button.html('<i class="fa fa-hand-peace-o"></i>Already Earned');
        //head.addClass('title-earned');
    }
    function button_fail(button){
        button.html('Request');
    }
    function show_notification() {
        var x = document.getElementById("snackbar");
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 100000);
    }
    function send_request(button, table){
        $.ajax({
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{url('/course')}}/{{$gdeploy_id}}/request",
            data: {
                student_id:  {{$student_id}},
                engine_id:   button.attr('data-engine')
            }
        }).done(function(result) {
            if(result == 0){
                $('#snackbar').css("background-color","lightgreen");
                $('#snackbar').find('.text').html("Congratulations! You have earned this reward.");
                show_notification();
                button_success(button);
                $(table).find('.title').addClass('title-earned');
                $(table).find('.fa-gift').css('color', 'lightgreen');
                $(table).find('.image').css('filter', 'grayscale(0%)');
            }else if(result == -1){
                $('#snackbar').css("background-color","indianred");
                $('#snackbar').find('.text').html("Sorry, the conditions to earn this reward are not yet satisfied.");
                show_notification();
                button_fail(button);
            }else if(result == -2){
                $('#snackbar').css("background-color","sandybrown");
                $('#snackbar').find('.text').html("Reward already earned. Refresh the page to see it in the 'already earned rewards' section.");
                show_notification();
                button_success(button);
            }else if(typeof(result)=='string'){
                $('#snackbar').css("background-color","indianred");
                $('#snackbar').find('.text').html("Sorry, the conditions to earn this reward are not yet satisfied: " + result);
                show_notification();
                button_fail(button);
            }else{
                console.log(result);
            }


            //TODO: MSG TO STUDENT

        }).fail(function(msg){
            console.log("Error requesting the Reward: ");
            console.log(msg);
        });
    }
</script>
</html>