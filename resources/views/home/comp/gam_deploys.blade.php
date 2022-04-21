<div class="divider-new pt-5" style="margin-bottom: 0; margin-top: 0px">
    <h2 class="h2-responsive wow fadeIn" data-wow-delay="0.2s">My Deploys</h2>
</div>

<!--Section: About-->
<section id="about" class="wow fadeIn" data-wow-delay="0.2s" style="margin-bottom: 30px">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th>Gamification Design</th>
            <th>Course Name</th>
            <th>URL</th>
            <th>Platform</th>
            <th>Options</th>
        </tr>
        </thead>
        <tbody>
        <?php $i=1; ?>
        @foreach($user->gamification_designs as $indexKey => $gld)
            @foreach($gld->gamification_deploys as $indexKey => $g_deploy)
            <tr>
                <th scope="row">{{$i}}</th>
                <td>{{$gld->name}}</td>
                <td>{{$g_deploy->course_name}}</td>
                <td>{{$g_deploy->instance_url}}</td>
                <td>{{$g_deploy->deploy_types->name}}</td>
                <td>
                    <div class="row" style="white-space:nowrap;">
                        <div class="col-md-3">
                            <a class="remove_gdeploy option" data-index="{{$g_deploy->id}}" data-name="{{$gld->name}} ({{$g_deploy->course_name}})">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                <span class="std-margin-left">remove</span>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
            <?php $i++; ?>
            @endforeach
        @endforeach
        </tbody>
    </table>
</section>