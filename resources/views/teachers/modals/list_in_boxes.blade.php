<style>
    .modal table td{
        font-size: 11px !important;
    }
    .x-scroll{
        overflow-x: auto;
    }
</style>

<div class="modal fade font-size-small" id="list_student_modal_{{$reportid}}_{{$ge->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content x-scroll" style="width: 600px; ">
            <div class="modal-header">
                <h5 class="modal-title">
                    <p><b>{{$ge->name}}</b> ({{count($ge->rewarded_students)}})</p>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover" style="text-align:center; margin-bottom: 0px" id="table_list_{{$reportid}}_{{$ge->id}}">
                    <thead><tr>
                        <th>#</th>
                        <th>id</th>
                        <th>email</th>
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
                            <td>{{$student->email_instance}}</td>
                            <td>{{$student->name_instance}}</td>
                            <td>{{$student->pivot->created_at}}</td>
                            <td>
                                @if(isset($student->pivot->url_assignment))
                                    <a href="{{$student->pivot->url_assignment}}" target="_blank">see</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="export-xls btn btn-primary"
                        data-name="{{$ge->name}}" data-list="table_list_{{$reportid}}_{{$ge->id}}">
                            Export XLS
                </button>
            </div>
        </div>
    </div>
</div>