<div class="row">
    {{--
    <div class="col-lg-4 mb-r">
        <div class="card">
            <table class="table table-bordered" style="text-align:center; margin-bottom: 0px">
                <thead></thead>
                <tbody>
                <tr><td class="pills pointer boldblack" data-index="badges">
                        <a>Badges</a>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>
    --}}
    <div class="col-lg-4 mb-r">
        <div class="card">
            <table class="table table-bordered" style="text-align:center; margin-bottom: 0px">
                <thead></thead>
                <tbody>
                <tr><td class="pills pointer boldblack" data-index="rr">
                        <a>Redeemable Rewards</a>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>
    {{--
    <div class="col-lg-4 mb-r">
        <div class="card">
            <table class="table table-bordered boldblack" style="text-align:center; margin-bottom: 0px">
                <thead></thead>
                <tbody>
                <tr><td class="pills pointer selected" data-index="comparison">
                        <a>STATS</a>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>
    --}}
</div>

<script>
    $(document).ready(function(){
       $('.pills').click(function(){
            $('.pills').removeClass('selected').addClass('boldblack');
            $(this).removeClass('boldblack').addClass('selected');

            var ixx=$(this).attr('data-index');
            $('.{{$targetclass}}').hide();
            $('#{{$targetid}}'+ixx).show();
        });
    });
</script>