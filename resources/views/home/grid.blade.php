<section style="min-height: 100%">
    <div class="container">

        @if(session()->has('alert_type'))
            <input type="hidden" id="alert_type" value="{!! session()->get('alert_type') !!}">
            <input type="hidden" id="alert_text" value="{!! session()->get('alert_text') !!}">
        @endif

            <div id="top_banner" class="alert alert-success alert-dismissable wow fadeIn hide" style="margin-top: 80px; margin-bottom: -85px">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <p id="top_banner_msg"></p>
            </div>

        @include('home.comp.ldesigns')

        @include('home.comp.gam_ldesigns')

        @include('home.comp.gam_deploys')

    </div>
</section>