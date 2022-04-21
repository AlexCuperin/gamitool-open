@extends('layouts.app')


@section('particular_css')
    @include('design.css')
@endsection


@section('layout_content')
    @include('design.grid')
@endsection


@section('particular_js')
    @include('design.js.dragjs')
    @include('design.js.tablejs')

    <script>
        var ld  = @jsvar($ld);
        console.log(ld);

        $(document).ready(function(){

            if(ld) {
                check_warnings(ld);
                create_table(ld.rows, ld.modules, ld.modulesobj);
                populate_table(ld.resources);
                set_learning_data(ld);
            }else{
                create_table(5, 5, '');
            }

            // ADD click-function to save table button
            $('#btn_save_table').click(save_learning_table);
            set_modify_listeners();
        });

        function check_warnings(ld){
            if(ld.gamification_designs.length >= 1){
                msg = 'Attention! You are editing a design used for ' + ld.gamification_designs.length;
                if (ld.gamification_designs.length == 1) msg = msg + ' gamification';
                else msg = msg + ' gamifications';
                i = 0;
                //función de héctor
                if(i > 0){
                    msg = msg + ' and ' + i + ' deploy/s';
                }
                if(ld.gamification_designs.length + i == 1) msg = msg + '. Changes may affect it.';
                else msg = msg + '. Changes may affect them.';

                $('#top_banner_msg span').html(msg);
                $('#top_banner_msg').addClass('alert-warning');
                $('#top_banner_msg').show();
            }
        }

    </script>

@endsection