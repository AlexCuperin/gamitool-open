@extends('layouts.app')


@section('particular_css')
    @include('gamification.css')
@endsection


@section('layout_content')
    @include('gamification.grid')
    @include('partials.modal_engine')
@endsection

@section('particular_js')
    @include('gamification.js.tablejs')
    @include('gamification.js.gamification')

    @include('utils.modals.form_name',
            ['modal_id'    => 'remove',
             'method'      => 'POST',
             'action'      => '',

             'title_txt'   => '',
             'button_txt'  => 'Confirm',

             'form_inputs' => 'utils.inputs.remove'
             ])

    <script>
        var ld  = @jsvar($gld['learning_design']);
        var gam_engines  = @jsvar($gld['gamification_engines']);
        var gld = @jsvar($gld);

        $(document).ready(function(){

            if(ld) {
                //check_warnings(gld);
                create_table(ld.rows, ld.modules);
                populate_table(ld.resources, gld['id']);
            }

            if ($('#alert_typeA').val()){

                switch($('#alert_typeA').val()){
                    case('success'): $('#top_bannerA').removeClass('alert-warning'); $('#top_bannerA').addClass('alert-success'); break;
                    case('warning'): $('#top_bannerA').removeClass('alert-success'); $('#top_bannerA').addClass('alert-warning'); break;
                }

                $('#top_banner_msgA').text($('#alert_textA').val());
                $('#top_bannerA').removeClass('hide');

                //Alert auto close
                $("#top_bannerA").fadeTo(5500, 500).slideUp(1000, function(){
                    $(this).slideUp(500);
                });
            }

            if ($('#alert_typeB').val()){
                $('#top_banner_msgB').text($('#alert_textB').val());
                $('#top_bannerB').removeClass('hide');

                //Alert auto close
                $("#top_bannerB").fadeTo(3500, 500).slideUp(1000, function(){
                    $(this).slideUp(500);
                });
            }

            // underlying onhover options
            $('.option').hover(function(){
                $(this).find('.std-margin-left').addClass('underline');
            }, function(){
                $(this).find('.std-margin-left').removeClass('underline');
            });

            $('.remove_association').click(function(){
                var url = {!! json_encode(url('/gamification')) !!} + '/' + $(this).attr('data-gld') + '/engine/delete/' + $(this).attr('data-index');
                $('#remove').modal('show');
                $('#remove #modal-title').text('Remove Association');
                $('#remove #text-confirmation').text('Please confirm the association that you want to remove:');
                $('#remove #modal-form').attr('action', url);
                $('#remove #remove_name').text($(this).attr('data-name'));
            });

            $('.remove_condition').click(function(){
                var url = {!! json_encode(url('/engine')) !!} + '/' + $(this).attr('data-eng') + '/condition/delete/' + $(this).attr('data-index');
                $('#remove').modal('show');
                $('#remove #modal-title').text('Remove Condition');
                $('#remove #text-confirmation').text('Please confirm the condition that you want to remove:');
                $('#remove #modal-form').attr('action', url);
                $('#remove #remove_name').text($(this).attr('data-name'));
            });

            $('.remove_reward').click(function(){
                var url = {!! json_encode(url('/engine')) !!} + '/' + $(this).attr('data-eng') + '/reward/delete/' + $(this).attr('data-index');
                $('#remove').modal('show');
                $('#remove #modal-title').text('Remove Reward');
                $('#remove #text-confirmation').text('Please confirm the reward that you want to remove:');
                $('#remove #modal-form').attr('action', url);
                $('#remove #remove_name').text($(this).attr('data-name'));
            });

            $('.input_switch').change(function () {
                var condition_op;

                if($(this).prop('checked')) condition_op = 'or';
                else condition_op = 'and';

                $.ajax({
                    method: "POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{url('/gamification')}}" + "/" + gld.id + "/engine/edit/" + $(this).prop('data-index'),
                    data: {
                        engine_id:          $(this).attr('data-index'),
                        condition_engine:   condition_op,

                    },
                }).done(function(msg) {

                }).fail(function(msg){

                });
            });
        });

        $('#myModal').on('shown.bs.modal', function () {
            $('#name_engine').focus();
        });


    </script>

@endsection