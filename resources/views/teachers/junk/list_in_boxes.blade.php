@foreach($gld->gamification_engines as $ge)
    <div class="col-lg-6 mb-r">
        <div class="card" style="height:700px; overflow-y: scroll">
            <table class="table table-bordered" id="engine_{{$ge->id}}" style="text-align:center; margin-bottom: 0px">
                <thead></thead>
                <tbody>
                <tr><td>
                        <h6><b class="ge_name">{{$ge->name}}</b><a style="font-size: 10px" class="close export-xml">Export .xml</a></h6>
                    </td></tr>
                <tr><td><p>{{$ge->description}}</p></td></tr>
                <tr><td>
                        <p><b>Issued Students:</b> {{count($ge->rewarded_students)}}</p>
                        <table class="table table-bordered table-hover" style="text-align:center; margin-bottom: 0px">
                            <thead><tr>
                                <th>#</th>
                                <th>id</th>
                                <th>name</th>
                                <th>date</th>
                                <th>+info</th>
                            </tr></thead>
                            <tbody>
                            <?php $i = 1; ?>
                            @foreach($ge->rewarded_students as $student)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$student->id_instance}}</td>
                                    <td>{{$student->name_instance}}</td>
                                    <td>{{$student->pivot->created_at}}</td>
                                    <td>@if(isset($student->pivot->url_assignment))<a href="{{$student->pivot->url_assignment}}">see</a><@endif</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>
@endforeach