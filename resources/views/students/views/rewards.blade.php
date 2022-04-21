<!--SnackBar-->
<div id="snackbar">
    <a class="close" data-dismiss="alert" aria-label="close" onClick="this.parentNode.classList.remove('show');">&times;</a>
    <p class="text"></p>
</div>
<!--Intro Section-->
<section class="view hm-black-strong" style="background: url({{ asset('img/background_gam.png') }})center center;">
    <div class="container">
        <div class="divider-new pt-5" style="margin-bottom: 0px;">
            <h2 class="h2-responsive">
                <i class="fa fa-gift" aria-hidden="true"></i>
                &nbsp;Course Rewards&nbsp;
                <i class="fa fa-hand-peace-o"></i></h2>
        </div>
        {{--<div class="row">
            <div class="col-lg-4 mb-r">
                <div class="card">
                    <table class="table table-bordered" style="text-align:center; margin-bottom: 0px">
                        <thead></thead>
                        <tbody>
                        <tr><td>
                                <a onclick="window.scrollTo(0,document.body.scrollHeight)" style="cursor: pointer">See earned rewards</a>
                                <!--Número de recompensas pendientes: {{count($gld->gamification_engines) - $total_earned}}-->
                            </td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>--}}
        <div class="row" >
            @foreach($gld->gamification_engines->sortby('created_at') as $ge)
                <div class="col-lg-4 mb-r">
                    <div class="card">
                        <table class="table table-bordered" style="text-align:center; margin-bottom: 0px">
                            <thead></thead>
                            <tbody>
                            <tr  class="title @if($ge->earned == true) title-earned @endif"><td><h6><b>{{$ge->name}}</b></h6></td></tr>
                            <tr><td class="info-reward" style="color: black">
                                    <?php $previousimg="" ?>
                                    @foreach($ge->rewards as $reward)
                                        @if($previousimg !== $reward->url_image)
                                            @if($reward->url_image)
                                                <?php $previousimg = $reward->url_image; ?>
                                                <img class="image" style="display:block; max-width: 125px; max-height: 125px; margin-left: auto; margin-right:auto;
                                                @if($ge->earned != true) filter:grayscale(100%); @endif"
                                                     src="{{$reward->url_image}}" height="20%" />
                                            @else
                                                <i class="fa    @if($ge->rewards[0]->reward_type->name == 'Redeemable Rewards') fa-gift
                                                                @elseif($ge->rewards[0]->reward_type->name == 'Badges') fa-sun-o
                                                                @elseif($ge->rewards[0]->reward_type->name == 'Levels') fa-battery-half
                                                                @elseif($ge->rewards[0]->reward_type->name == 'Points') fa-braille
                                                                @endif
                                                        fa-4x" style="color:@if($ge->earned == true) lightgreen @else lightgrey @endif">
                                                </i>
                                            @endif
                                        @endif
                                    @endforeach
                                </td></tr>
                            @if($reward->reward_type->name == "Redeemable Rewards")
                                <tr><td>
                                        <b>Privileges:</b>
                                        @foreach($ge->rewards as $reward)
                                            {{--<p>{{$reward->reward_type->name}}</p>--}}
                                            <br><i class="fa fa-gift"></i>&nbsp;{{$reward->name}}
                                        @endforeach
                                    </td></tr>
                            @endif
                            <tr><td>
                                    <p><b>Requirements:</b> {{$ge->description}}</p>
                                    @if($ge->earned == false)
                                        <button class="btn btn-primary request_button" data-engine="{{$ge->id}}" style="margin-top:10px">Request</button>
                                    @else
                                        <button class="btn btn-secondary disabled" style="margin-top:10px;">
                                            <i class="fa fa-hand-peace-o"></i>
                                            Already Earned
                                        </button>
                                    @endif
                                </td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>