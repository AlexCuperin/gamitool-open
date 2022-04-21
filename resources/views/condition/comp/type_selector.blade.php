<div class="card wow fadeIn">

    <table class="table table-bordered" id="gamification_tab" style="margin-bottom: 0px">
        <thead><tr><th>
                <div class="owntooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;
                    <span class="owntooltiptext">GamiTool allows the configuration of multiple condition types:
                        <br>&nbsp;&nbsp;&nbsp;<u>Resource:</u> Students must perform actions within the course resources or platform (e.g. submit a quiz).
                            <br>&nbsp;&nbsp;&nbsp;<u>Group</u> [not implemented]: A configured % of group members must perform the configured actions.
                            <br>&nbsp;&nbsp;&nbsp;<u>Reward</u> [not implemented]: Students must earn previous course rewards (e.g. earn 100 points). </span>
                </div><b>Condition Types</b>
        </th></tr></thead>
        <tbody>
            @foreach($condition_types as $ct)
                <tr class="pointer @if(!$ct->enabled) deactivated @endif">
                    <td>{{$ct->name}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>