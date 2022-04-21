<!-- Main container-->
<div class="container">

    @if(isset($alert_type))
        <input type="hidden" id="alert_typeA" value="{{$alert_type}}">
        <input type="hidden" id="alert_textA" value="{{$alert_text}}">
    @endif

    @if(session()->has('alert_type'))
        <input type="hidden" id="alert_typeB" value="{!! session()->get('alert_type') !!}">
        <input type="hidden" id="alert_textB" value="{!! session()->get('alert_text') !!}">
    @endif

    <div clas="row" style="margin-top:75px;margin-bottom:-55px;">
        <a href="{{ route('home') }}" style="display: inline-block; font-size: large;"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;Return to Home Page</a>
        <b style="display: inline-block; float: right; margin-top: 3px">{{$gld->name}}</b>
    </div>
    <div class="divider-new col-md-12" style="margin-bottom: 0px; padding: 0px">
        <h2 class="h2-responsive wow fadeIn" data-wow-delay="0.2s">Gamification Associations</h2>
    </div>

    <div id="top_bannerA" class="alert alert-warning alert-dismissable wow fadeIn hide" style="margin-top: 0px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <p id="top_banner_msgA"></p>
    </div>

    <div id="top_bannerB" class="alert alert-success alert-dismissable wow fadeIn hide" style="margin-top: 0px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <p id="top_banner_msgB"></p>
    </div>

    <div class="row" style="margin-bottom:20px">
        <div class="row pull-left">
                <a onclick="manageEngine('{{$gld->id}}','')" data-toggle="modal" data-target="#myModal"><button class="btn btn-default">New Association</button></a>
        </div>
    </div>

    <!--Section: About-->
    @foreach($gld->gamification_engines->sortBy('updated_at')->reverse() as $ge)
        <hr>
        <div class="association">
            <div class="row">
                <div class="col-md-10">
                    <p><b style="font-size: larger">{{$ge->name}}:&nbsp;</b>{{$ge->description}}</p>
                </div>
                <div class="col-md-2" style="text-align:right; white-space:nowrap;">
                    <a class="option" onclick="manageEngine('{{$ge->gdesign_id}}','{{$ge->id}}')" data-toggle="modal" data-target="#myModal">
                        <i class="fa fa-pencil" aria-hidden="true" title="edit"></i>
                        <span class="std-margin-left">edit info</span>
                    </a>
                    <a class="remove_association option" style="margin-left: 10px" data-index="{{$ge->id}}" data-gld="{{$gld->id}}" data-name="{{$ge->name}}: {{$ge->description}}">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                        <span class="std-margin-left">remove</span>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-r">
                    <div class="card wow fadeIn" data-wow-delay="0.2s">
                        <div class="row pull-right">
                            <div>
                                <a href="{{url('/engine/'.$ge->id.'/condition/new')}}"><button class="btn btn-primary">New Condition</button></a>
                            </div>
                            @if(count($ge->conditions) > 1)
                            <div style="margin: 17px 30px 0px auto">
                                <span style="font-size: small">Multiple conditions:&nbsp;&nbsp;AND</span>
                                <label class="switch">
                                    <input class="input_switch" data-index="{{$ge->id}}" type="checkbox" @if($ge->condition_op == 'or') checked @endif>
                                    <span class="slider round"></span>
                                </label>
                                <span style="font-size: small">OR</span>
                            </div>
                            @endif
                        </div>
                        <table class="table table-hover">
                            <thead><tr>
                                <th>#</th>
                                <th>Condition @if($ge->condition_op)@endif</th>
                                <th>Resource</th>
                                <th>Options</th>
                            </tr></thead>
                            <tbody>
                            @foreach($ge->conditions as $indexKey => $condition)
                                <tr>
                                    <td scope="row">{{$indexKey+1}}</td>
                                    <td><a class="more" href="{{route('condition',['engine_id' => $ge->id, 'id' => $condition->id])}}">{{$condition->description}}</a></td>
                                    <td>
                                        @if($condition->condition_type->name == 'Resource Condition'){{$condition->resource_conditions[0]->resource->name}}@endif</td>
                                    <td>
                                        <div class="row" style="white-space:nowrap;">
                                            <div class="col-md-3">
                                                <a class="remove_condition option" data-index="{{$condition->id}}" data-eng="{{$ge->id}}" data-name="{{$condition->description}} ({{$condition->condition_type->name}})">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                    <span class="std-margin-left">remove</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-6 mb-r">
                    <div class="card wow fadeIn" data-wow-delay="0.2s">
                        <div class="row pull-right">
                            <div>
                                <a href="{{url('engine/'.$ge->id.'/reward/new')}}"><button class="btn btn-secondary">New Reward</button></a>
                            </div>
                        </div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Reward</th>
                                <th>Reward Type</th>
                                <th>Img</th>
                                <th>Options</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($ge->rewards as $indexKey => $reward)
                                <tr>
                                    <td scope="row">{{$indexKey+1}}</td>
                                    <td><a class="more" href="{{route('reward',['engine_id' => $ge->id, 'id' => $reward->id])}}">{{$reward->name}}</a></td>
                                    <td>{{$reward->reward_type->name}}</td>
                                    <td>@if($reward->url_image)
                                            <img src="{{$reward->url_image}}" height="25px" />
                                        @endif</td>
                                    <td>
                                        <div class="row" style="white-space:nowrap;">
                                            <div class="col-md-3">
                                                <a class="remove_reward option" data-index="{{$reward->id}}" data-eng="{{$ge->id}}" data-name="{{$reward->name}} ({{$reward->reward_type->name}})">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                    <span class="std-margin-left">remove</span>
                                                </a>
                                            </div>
                                        </div>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="divider-new pt-5" style="margin-bottom: 5px; margin-top: 0; padding-top:0!important">
        <h2 class="h2-responsive wow fadeIn" data-wow-delay="0.2s">Gamification Summary</h2>
    </div>

    <div class="row pt-3">
        <div class="col-lg-9 mb-r">
            <!--Card-->
            <div class="card wow fadeIn ld-table" data-wow-delay="0.2s">
                <table class="table table-bordered" id="learning_design" style="margin-bottom: 0px">
                    <thead></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-3 mb-r">
            <!--Card-->
            <div class="card wow fadeIn" data-wow-delay="0.2s">
                <table class="table table-bordered" id="summary" style="margin-bottom: 0px">
                    <thead><tr>
                        <th><b>Summary</b></th>
                    </tr></thead>
                    <tbody>
                    <td>Click on the resources to show:<br>
                        <p><span class="dot" style="background-color: rgb(214, 234, 248)"></span> Conditions &copy;</p>
                        <p><span class="dot" style="background-color: rgb(232, 218, 239)"></span> Redeemable rewards &reg;</p></td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>