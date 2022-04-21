<button id="{{$btn_utils_id or 'btn_utils_id'}}">
    <span id="btn_utils_text">
        {{$btn_utils_text or 'Click here'}}
    </span>
    <span id="btn_utils_loader">
        @include('utils.loader_gif', ['loadergif_id' => 'btn_utils_loader_gif', 'size' => 20, 'pretext' => 'Updating...'])
    </span>
</button>

<script>

    function btn_utils_show_loader(){
        $('#btn_utils_text').hide();
        $('#btn_utils_loader').show();
    }

    function btn_utils_hide_loader(){
        $('#btn_utils_text').show();
        $('#btn_utils_loader').hide();
    }

    $(document).ready(function(){
        $('#btn_utils_loader').hide();
        $('#{{$btn_utils_id}}').click(function(){
            btn_utils_show_loader();
        });
    });
</script>