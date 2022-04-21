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
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark elegant-color-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" style="color:white">GamiTool for MOOCs</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div align="right">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto" style="color:white">
                        <li class=" nav-item dropdown">
                            Teacher Analytics
                            <i class="fa fa-bar-chart"></i>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!--Intro Section-->
    <section class="view hm-black-strong" style="background: url({{ asset('img/background_gam.png') }})center center;">
        <div class="container">
            <div class="divider-new pt-5" style="margin-bottom: 0px;">
                <h2 class="h2-responsive">
                    <i class="fa fa-bar-chart"></i>
                    &nbsp;Teacher Analytics&nbsp;
                    <i class="fa fa-gift"></i></h2>
            </div>

            @include('teachers.pills',          ['targetclass'=>'reports', 'targetid'=>'report'])

            @include('teachers.reports.report', ['targetclass'=>'reports', 'targetid'=>'report', 'reportid'=>'badges', 'gldx'=>$gld])
            @include('teachers.reports.report', ['targetclass'=>'reports', 'targetid'=>'report', 'reportid'=>'rr',     'gldx'=>$gldrr])
            @include('teachers.d3.histogram',   ['targetclass'=>'reports', 'targetid'=>'report', 'reportid'=>'comparison'])

            {{--@include('teachers.junk.list_in_boxes')--}}
        </div>
    </section>
    @include('partials/footer')

    @foreach($gld->gamification_engines as $ge)
        @include('teachers.modals.list_in_boxes', ['ge'=>$ge, 'reportid'=> 'badges'])
    @endforeach

    @foreach($gldrr->gamification_engines as $ge)
        @include('teachers.modals.list_in_boxes', ['ge'=>$ge, 'reportid'=> 'rr'])
    @endforeach

</body>
<script>

    $(document).ready(function(){
       $('#reportcomparison').show();
       // Testing
       //$('.pills[data-index="comparison"]').click();
    });

    $(".export-xls").click(function(e) {

        var targetable = $(this).attr('data-list');
        $table_id = $('#'+targetable).attr('id');
        $reward_name = $(this).attr('data-name');

        //getting values of current time for generating the file name
        var dt = new Date();
        var day = dt.getDate();
        var month = dt.getMonth() + 1;
        var year = dt.getFullYear();
        var hour = dt.getHours();
        var mins = dt.getMinutes();
        var postfix = day + "-" + month + "-" + year + "T" + hour + "-" + mins;
        //creating a temporary HTML link element (they support setting file names)
        var a = document.createElement('a');
        //For Firefox
        document.body.appendChild(a);
        //getting data from our div that contains the HTML table
        var data_type = 'data:application/vnd.ms-excel';

        var table_html="<table border='2px'><tr bgcolor='#87AFC6'>";
        var table_div = document.getElementById($table_id);
        for(j = 0 ; j < table_div.rows.length ; j++)
        {
            table_html=table_html+table_div.rows[j].innerHTML+"</tr>";
        }
        table_html = table_html + "</table>";
        table_html = table_html.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        table_html = table_html.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        table_html = table_html.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
        table_html = encodeURIComponent(table_html);

        a.href = data_type + ', ' + table_html;
        //setting the file name
        a.download = $reward_name + "_" + postfix + '.xls';
        //triggering the function
        a.click();
        //just in case, prevent default behaviour
        e.preventDefault();
    });
</script>
</html>