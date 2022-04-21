<style>
    #{{$targetid}}{{$reportid}} th {
        padding: 10px;
    }

    #{{$targetid}}{{$reportid}} td {
        padding: 10px;
    }
</style>

<div class="row mb-r {{$targetclass}}" id="{{$targetid}}{{$reportid}}">
    <div class="card" style="width: 100%">
        <table class="table-bordered table-hover" style="width: 100%">
            <thead>
            <th></th>
            <th>Name</th>
            <th>Description</th>
            <th title="Number of total reward requests including several tries for each student">#total requests</th>
            <th title="Number of unique students that have requested this reward">#distinct students</th>
            <th title="Number of rewards issued / Students rewarded">#total issued</th>
            <th>Options</th> {{-- export xml AND see list--}}
            </thead>
            <tbody>
            @foreach($gldx->gamification_engines as $ge)
                <tr>
                    <td>
                        @if($ge->rewards[0]->url_image)
                            <img src="{{$ge->rewards[0]->url_image}}" width="50px"/>
                        @else
                            <i class="fa    @if($ge->rewards[0]->reward_type->name == 'Redeemable Rewards') fa-gift
                                            @elseif($ge->rewards[0]->reward_type->name == 'Badges') fa-sun-o
                                            @elseif($ge->rewards[0]->reward_type->name == 'Levels') fa-battery-half
                                            @elseif($ge->rewards[0]->reward_type->name == 'Points') fa-braille
                                            @endif
                                    " style="font-size: 3em; color: grey; text-align: center; display: block"></i>
                        @endif
                    </td>
                    <td>{{$ge->name}}</td>
                    <td>{{$ge->description}}</td>
                    <td class="centered">{{count($ge->count_requesting_students)}}</td>
                    <td class="centered">{{count($ge->count_requesting_students->unique('student_id'))}}</td>
                    <td class="centered">{{count($ge->rewarded_students)}}</td>
                    <td class="centered">
                        <a title="Export the {{$ge->name}} reward data to an XLS file" class="export-xls" href="#" data-name="{{$ge->name}}" data-list="table_list_{{$reportid}}_{{$ge->id}}">
                            <i class="fa fa-download"></i>
                        </a>
                        <a title="See the list of students that have earned the {{$ge->name}} reward" href="#" data-toggle="modal" data-target="#list_student_modal_{{$reportid}}_{{$ge->id}}">
                            <i class="fa fa-list"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>