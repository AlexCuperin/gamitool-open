<div class="card wow fadeIn">

    <table class="table table-bordered" id="gamification_tab" style="margin-bottom: 0px">
        <thead><tr><th><p><b>Instance Type</b></p></th></tr></thead>
        <tbody>
            @foreach($deploy_types as $dt)
                <tr class="select_platform pointer @if(!$dt->enabled) deactivated @endif">
                    <td>
                        <i class="js_arrow fa fa-arrow-right hide"></i>
                        <span class="name">{{$dt->name}}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $('.select_platform').click(function(){
        if(!$(this).hasClass('deactivated')) {
            $('.js_arrow').addClass('hide');
            $('.select_platform').removeClass('selected');
            $(this).addClass('selected').find('.js_arrow').removeClass('hide');
        }
    });
    $(document).ready(function(){
       $('.select_platform').first().click();
    });
</script>