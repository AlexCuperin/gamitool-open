@extends('layouts.app')


@section('particular_css')
    @include('home.css')
@endsection


@section('layout_content')
    @include('home.grid')
    @include('utils.modals.form_name',
            ['modal_id'    => 'import_ld',
             'method'      => 'POST',
             'action'      => url('/design/import'),

             'title_txt'   => 'Import Learning Design',
             'button_txt'  => 'Continue',

             'form_inputs' => 'utils.inputs.import_ld'
             ])
    @include('utils.modals.form_name',
            ['modal_id'    => 'new_gamification',
             'method'      => 'POST',
             'action'      => url('/gamification/new'),

             'title_txt'   => 'New Gamification Design',
             'button_txt'  => 'Create Gamification Design',

             'form_inputs' => 'utils.inputs.new_gamification'
             ])
    @include('utils.modals.form_name',
            ['modal_id'    => 'gamification_edit_name',
             'method'      => 'POST',
             'action'      => url('/gamification/rename'),

             'title_txt'   => 'Rename Gamification Design',
             'button_txt'  => 'Update Gamification Design',

             'form_inputs' => 'utils.inputs.new_gamification'
             ])
    @include('utils.modals.form_name',
            ['modal_id'    => 'deploy_imported',
             'method'      => 'POST',
             'action'      => url('/gamification/deploy/imported'),

             'title_txt'   => 'Deploy Gamified Learning Design',
             'button_txt'  => 'Confirm',

             'form_inputs' => 'utils.inputs.deploy_imported'
             ])
    @include('utils.modals.form_name',
            ['modal_id'    => 'remove',
             'method'      => 'POST',
             'action'      => '',

             'title_txt'   => '',
             'button_txt'  => 'Confirm',

             'form_inputs' => 'utils.inputs.remove'
             ])

@endsection

@section('particular_js')
    @include('home.js.home')
@endsection