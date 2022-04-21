<div class="divider-new pt-5" style="margin-bottom: 0;">
    <h2 class="h2-responsive wow fadeIn" data-wow-delay="0.2s">My Learning Designs</h2>
</div>

<div class="row pull-right">
    <div>
        <a class="import_design" ><button class="btn btn-primary">Import Design</button></a>
        <a href="{{url('/design/new')}}"><button class="btn btn-primary">Create Design</button></a>
    </div>
</div>

<!--Section: About-->
<section id="about" class="wow fadeIn" data-wow-delay="0.2s">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>Course Name</th>
            <th>Modules</th>
            <th>Gamifications</th>
            <th class="centered">Options</th>
        </tr>
        </thead>
        <tbody>
        @foreach($user->learning_designs as $indexKey => $ld)
            <tr>
                <th scope="row">{{$indexKey+1}}</th>
                <td>
                    <a class="more" href="{{route('design',['id' => $ld->id])}}">{{$ld->course_name}}</a>
                </td>
                <td>{{$ld->modules}}</td>
                <td>{{count($ld->gamification_designs)}}</td>
                <td class="centered" style="width: 26%">
                    <div class="row options extra-margin-left" style="white-space:nowrap;">
                        <div class="width-auto">
                            <a class="new_gam_link option" data-index={{$ld->id}}>
                                <i class="fa fa-gamepad" aria-hidden="true"></i>
                                <span class="std-margin-left">new gamification</span>
                            </a>
                        </div>
                        <div class="width-auto extra-margin-left">
                            <a class="remove_ld option" data-index="{{$ld->id}}" data-name="{{$ld->course_name}}">
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