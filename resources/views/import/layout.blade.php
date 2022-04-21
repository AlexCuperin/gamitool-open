@extends('layouts.app')

@section('layout_content')
    @include('import.grid')
@endsection

@section('particular_js')
    @include('import.js.tablejs')
    @include('import.js.courses')

    @include('utils.modals.redirect',
            ['modal_id'    => 'import_success',

             'title_txt'   => '',
             'button_txt'  => 'Go to Home Page',

             'html_code' => 'utils.inputs.import_success'
             ])

    <script>
        $('#data_instance_button').click(function(){
            button_loading();
            get_courses();
        });

        $('#course_select').change(function(){
            resources_loading();
            get_resources();
        });

        $('#import_button').click(function(){
           save_import();
        });

        $('#redirect-button').click(function(){
            window.location.href="{{URL::to('home')}}";
        });
    </script>
@endsection
