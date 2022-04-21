<div class="divider-new pt-5" style="margin-bottom: 0; margin-top: 0">
    <h2 class="h2-responsive wow fadeIn" data-wow-delay="0.2s">My Gamified Learning Designs</h2>
</div>

<!--Section: About-->
<section id="about" class="wow fadeIn" data-wow-delay="0.2s">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>Gamification Name</th>
            <th>Course Name</th>
            <th>Assoc.</th>
            {{--<th>Created at</th>--}}
            <th>Owner</th>
            <th class="centered">Options</th>
        </tr>
        </thead>
        <tbody>
        @foreach($user->gamification_designs as $indexKey => $gld)
        <tr class="gam_name_link">
            <th scope="row">{{$indexKey+1}}</th>
            <td>
                <div class="row">
                    <a class="more" href="{{route('gamification',['id' => $gld->id])}}">
                        {{$gld->name}}
                    </a>
                </div>
            </td>

            <td>{{$gld->learning_design->course_name}}</td>
            <td>{{count($gld->gamification_engines)}}</td>
            {{--<td>{{substr($gld->created_at,0,10)}}</td>--}}
            <td class="column_creator gam_send_email">
                <a class="more" href="mailto:{{$gld->creator->email}}?Subject=Gamitool" target="_top">
                    {{$gld->creator->name}} {{$gld->creator->lastname}}
                    <i class="fa fa-envelope hide" aria-hidden="true" title="send email"></i>
                </a>
            </td>
            <td class="column_options centered" style="width: 26%">
                <div class="row options extra-margin-left" style="white-space:nowrap;">
                    <div class="width-auto">
                        <a class="gam_name_edit option" data-index={{$gld->id}} data-name="{{$gld->name}}">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                            <span class="std-margin-left">edit name</span>
                        </a>
                    </div>
                    <div class="width-auto extra-margin-left">
                        @if(!is_null($gld->learning_design->import_metadata))
                            <a class="option deploy_imported"
                                    data-gld="{{$gld->id}}"
                                    data-id="{{$gld->learning_design->import_metadata->id}}"
                                    data-name="{{$gld->learning_design->import_metadata->course_name}}"
                                    data-url="{{$gld->learning_design->import_metadata->instance_url}}"
                                    data-type="{{$gld->learning_design->import_metadata->deploy_type->name}}"
                            >
                                <i class="fa fa-send" aria-hidden="true"></i>
                                <span class="std-margin-left">deploy</span>
                            </a>
                        @else
                        <form id="deploy_{{$gld->id}}" action="{{url('/gamification')}}/{{$gld->id}}/deploy/configuration" method="get">
                            <a class="option" onclick="$('#deploy_{{$gld->id}}').submit()" title="deploy">
                                <i class="fa fa-send" aria-hidden="true"></i>
                                <span class="std-margin-left">deploy</span>
                            </a>
                        </form>
                        @endif
                    </div>
                    <div class="width-auto extra-margin-left">
                        <a class="remove_gld option" data-index="{{$gld->id}}" data-name="{{$gld->name}}">
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
</section>