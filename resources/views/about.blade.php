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
                            <h1 class="h1-responsive font-bold wow fadeInDown" data-wow-delay="0.2s"><br>GAMIfication + authoring TOOL = GamiTool</h1>
                        </li>
                        <li>
                            <div class="about_container">
                                <div class="wow fadeInLeft about_column" data-wow-delay="0.2s" style="text-align: justify; width:45%">
                                    <p>GamiTool is a web-based system developed by GSIC-EMIC to allow practitioners to design, semi-automatically deploy and enact reward-based gamifications across multiple learning management systems and MOOC platforms.</p>
                                    <br>
                                    <p>Its simple but extensive datamodel and adapter-based architecture makes GamiTool very suitable for ICT non-expert practitioners and researchers that want to engage students within their courses.</p>
                                    <br>
                                    <p>For more information on how it brings magic into your courses, contact us!</p>
                                </div>
                                <div class="wow fadeInRight about_column" data-wow-delay="0.2s" style="width:55%">
                                    <div class="container">
                                        <div id="demo" class="carousel slide" data-ride="carousel">

                                            <!-- The slideshow -->
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <img src="{{ asset('img/ss1.svg') }}" alt="Home Page" width="580px" height="290px">
                                                </div>
                                                <div class="carousel-item">
                                                    <img src="{{ asset('img/ss2.svg') }}" alt="Gamification Page" width="580px" height="290px">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
