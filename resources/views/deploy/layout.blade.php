@extends('layouts.app')

@section('layout_content')
    @include('deploy.grid')
@endsection

@section('particular_js')
    @include('deploy.js.tablejs')
    @include('deploy.js.courses')

    <script>
        var gld = @jsvar($gld);
        var ld = gld.learning_design;

        $(document).ready(function(){
            create_table(ld.rows, ld.modules);
            populate_table(ld.resources,gld.id);

        });

        $('#data_instance_button').click(function(){
            button_loading();
            get_courses();
        });

        $('#course_select').change(function(){
            resources_loading();
            get_resources();
            $('.Platform').html('<option>Course Platform</option>');
        });

        $('#deploy_button').click(function(){
           send_deployment();
        });
    </script>
@endsection
