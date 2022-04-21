<div id="import_div" class="container" style="min-height: 100%">

    <a href="{{ route('home') }}" style="font-size: large;"><i class="fa fa-arrow-circle-left" aria-hidden="true" style="margin-top:80px;margin-bottom:-80px;"></i>&nbsp;Return to Home Page</a>

    <div class="divider-new pt-5 wow fadeInDown" style="margin-top: -50px">
        <h2 class="h2-responsive">Complete the Import Information</h2>
    </div>
    <div class="row pt-3">
        <div class="col-lg-3 mb-r">
            @include('import.comp.type_selector')
        </div>

        <div class="col-lg-9 mb-r wow fadeInDown">

            <div id="cred_div">
                @include('import.comp.instance_info')
            </div>

            <div id="course_selector_div" class="extra-margin-top hide">
                @include('import.comp.course')
            </div>

            <div id="resource_selector_div" class="extra-margin-top hide">
                @include('import.comp.table')
            </div>
            <button id="import_button" class="btn btn-primary pull-right hide" type="button">
                <i class="fa fa-save" aria-hidden="true"></i>
                <span class="std-margin-left">Import</span>
            </button>
        </div>
    </div>
</div>

<script>
    function button_loading(){
        var button = $('#data_instance_button');
        button.find('.fa-gear').addClass('fa-spin').removeClass('hide');
        button.find('.fa-check').addClass('hide');
        button.find('span').html('Connecting...');

        $('#course_selector_div').addClass('hide');
    }

    function button_done(){
        var button = $('#data_instance_button');
        button.find('.fa-gear').removeClass('fa-spin').addClass('hide');
        button.find('.fa-check').removeClass('hide');
        button.find('span').html('Connected');

        $('#course_selector_div').removeClass('hide');
    }

    function resources_loading(){
        $('.js_loading_resources').removeClass('hide');
        $('#resource_selector_div').addClass('hide');
    }

    function resources_loaded(){
        $('.js_loading_resources').addClass('hide');
        $('#resource_selector_div').removeClass('hide');
        $('#import_button').removeClass('hide');
    }
</script>