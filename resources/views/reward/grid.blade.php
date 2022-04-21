<!-- Container 4 -->
<div id="rewards_div" class="container" style="transition: all 1s ease; min-height: 100%">

    <div clas="row" style="margin-top:75px;margin-bottom:-60px;">
        <a href="{{ URL::previous() }}" style="display:inline-block; font-size: large;"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;Return to Gamification Page</a>
        <p class="col-6" style="display: inline-block; float: right; font-size: small; text-align: right; padding-top: 5px"><b>{{$engine->name}}:</b> {{$engine->description}}</p>
    </div>

    <div class="divider-new col-md-12 wow fadeInDown" style="margin-bottom: 30px; padding: 0px">
        <h2 class="h2-responsive">Rewards</h2>
    </div>

    <!--Section: Best features-->
    <section id="best-features">

        <div class="row pt-3">
            <!--First columnn-->
            <div class="col-lg-3 mb-r">
                {{--<!--Card-->
                <div class="card wow fadeIn">

                    <table class="table table-bordered table-hover" id="gamification_tab" style="margin-bottom: 0px">
                        <thead>
                        <tr><th><p>Rewards</p><div></div></th></tr>
                        </thead>
                        <tbody>
                        @foreach($reward_types as $reward_type)
                            <tr><td id="{{$reward_type->name}}" onclick="selectReward(this)" class="pointer">{{$reward_type->name}}</td></tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!--/.Card-->--}}

                <!--Card-->
                <div id="preview_card" class="card wow fadeIn">

                    <table class="table table-bordered" style="margin-bottom: 0px">
                        <thead>
                        <tr><th><p>Reward Preview</p><div></div></th></tr>
                        </thead>
                        <tbody>
                        <tr><td style="padding-bottom:0">
                                <table class="table table-sm" style="height: 200px; table-layout: fixed">
                                    <thead></thead>
                                    <tbody style="text-align: center">
                                    <tr>
                                        <td>
                                            <div id="icon_preview">@if(isset($reward->url_image))<img style="max-width: 100%" src="{{$reward->url_image}}"/>@endif</div>
                                            <p id="name_preview" style="font-size: large; word-break: break-word">@if(isset($reward->name)){{$reward->name}}@endif</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td></tr>
                        </tbody>
                    </table>
                </div>
                <!--/.Card-->
            </div>
            <!--First columnn-->

            <!--Second columnn-->
            <div class="col-lg-9 mb-r" style="margin-top: -32px">
                <label>Complete the required information:</label>
                <form onsubmit="return validate()" method="POST" action="{{url('/engine')}}/{{$engine->id}}/reward/add">
                {{ csrf_field() }}
                <input name="reward_id" id="reward_id" type="hidden"/>
                <input name="concrete_reward_id" id="concrete_reward_id" type="hidden"/>
                <div class="card wow fadeIn">

                    <table class="table table-bordered" id="gamification_tab" style="margin-bottom: 0px">
                        <thead>
                        <tr><th><p>Reward Configuration</p><div></div></th></tr>
                        </thead>
                        <tbody>
                        <tr><td style="padding-bottom:0">
                                <table class="table table-sm">
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <td style="width: 20%"><label>Type*:</label></td>
                                            <td>
                                                <select name="reward_type" id="reward_type" style="width:60%" required="required">
                                                    <option value="">Select the reward type...</option>
                                                    @foreach($reward_types as $reward_type)
                                                    <option value="{{$reward_type->name}}">{{$reward_type->name}}</option>
                                                    @endforeach
                                                </select></td>
                                        </tr>
                                        <tr>
                                            <td><label>Name*:</label></td>
                                            <td><input name="reward_name" type="text" id="reward_name" style="width:60%" value="" required/></td>
                                        </tr>
                                        <tr>
                                            <td><label>Image (url):</label></td>
                                            <td><input name="reward_image" type="text" id="reward_image" enctype="multipart/form-data"/></td>
                                        </tr>
                                        <tr id="tr_quantity" class="hide">
                                            <td><label>Quantity:</label></td>
                                            <td><input name="reward_quantity" type="number" id="reward_quantity" style="width:10%" value="1"/></td>
                                        </tr>
                                        <tr id="tr_suite" class="hide">
                                            <td><label>Badge Suite?:</label></td>
                                            <td><select id="badge_suite" style="width:20%">
                                                    <option value="no" selected="selected">No</option>
                                                    <option value="yes">Yes</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td></tr>
                        <tr id="tr_badge" class="hide">
                            <td>
                                <table class="table table-sm">
                                    <thead></thead>
                                    <tbody>
                                    <tr>
                                        <td><label>Badge Suite:</label></td>
                                        <td><select name="select_suite" onchange="selectBadgeSuite(this)" id="select_suite">
                                                <option value="" selected="selected">Select a badge suite</option>
                                            @foreach($badge_suites as $suite)
                                                <option value="{{$suite->name}}">{{$suite->name}}</option>
                                            @endforeach
                                        </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Badge Quality:</label></td>
                                        <td><select name="badge_quality" id="badge_quality"></select></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <button type="button" data-target="" class="width-auto btn btn-secondary" disabled>
                                    New Suite
                                </button><label>&nbsp; Badge suites are temporarily disabled.</label>
                            </td>
                        </tr>
                        <tr id="tr_rr">
                            <td>
                                Select the Resource where the Privilege will be applied and complete the required information below:
                                <div class="ld-table">
                                    <table class="table" id="learning_design">
                                        <thead></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                    <input name="rr_resource" id="rr_resource" type="hidden" value="" />
                                    <table class="table table-sm">
                                        <thead></thead>
                                        <tbody>
                                        <tr>
                                            <td width="20%"><label>Privilege:</label></td>
                                            <td width="25%"><select name="privilege" id="privilege" required="required">
                                                    <option>Select first a resource on the table above</option>
                                                </select></td>
                                        </tr>
                                        <tr id="rr_param1" class="hide">
                                            <td><label></label></td>
                                            <td><input name="param1" id="param_1"/></td>
                                        </tr>
                                        </tbody>
                                    </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!--/.Card-->
                <div class="form wow fadeInUp" style="margin-top:20px; padding:0; float: right">
                    <button type="submit">Save Reward</button><br>
                </div>
            </form>
            </div>
            <!--Fourth columnn-->

        </div>
    </section>
</div>

