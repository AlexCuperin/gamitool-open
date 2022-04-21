<form method="POST" action="{{url('/engine')}}/{{$engine->id}}/condition/add">
    {{ csrf_field() }}
    <input name="condition_id" id="condition_id" type="hidden"/>
    <input name="resource_condition_id" id="resource_condition_id" type="hidden"/>
<div id="resources_div" class="container" style="min-height: 100%">

    <div clas="row" style="margin-top:75px;margin-bottom:-60px;">
        <a href="{{ URL::previous() }}" style="display:inline-block; font-size: large;"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i>&nbsp;Return to Gamification Page</a>
        <p class="col-6" style="display: inline-block; float: right; font-size: small; text-align: right; padding-top: 5px"><b>{{$engine->name}}:</b> {{$engine->description}}</p>
    </div>

    <div class="divider-new col-md-12 wow fadeInDown" style="margin-bottom: 30px; padding: 0px">
        <h2 class="h2-responsive">Condition</h2>
    </div>

    <div class="row pt-3" style="margin-top: -20px">
        <div class="col-lg-3 mb-r">
            @include('condition.comp.type_selector')
        </div>

        <div class="col-lg-9 mb-r" style="margin-top: -20px">
            <p>[Description] Briefly describe the condition:</p>
            @include('condition.comp.description')
            <p>[Resource] Select the resource where the condition will be applied:</p>
            @include('condition.comp.table')
            <input id="resource_id" name="resource_id" type="hidden" />
        </div>
    </div>
</div>

<div id="actions_div" class="hide container transition">

    @include('condition.comp.actions')
</div>

<div id="save_button" class="container wow fadeInUp hide" style="text-align:right; margin-bottom:30px">
    <button onclick="save_parameters()" type="submit">Save Condition</button><br>
</div>
</form>
