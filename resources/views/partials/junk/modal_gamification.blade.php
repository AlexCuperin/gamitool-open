<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form id="modal-form" class="form-horizontal" method="POST" action="">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title" ></h4>
                    <button type="button" class="width-auto close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="row" style="white-space:nowrap; margin:1px;">
                        <div class="col-md-3">
                            <label>Name: </label>
                        </div>
                        <div class="col-md-9">
                            <input id="learning_id"       name="learning_id"       type="hidden" value=""/>
                            <input id="gld_id"            name="gld_id"            type="hidden" value=""/>
                            <input id="name_gamification" name="name_gamification" type="text"   value="" autofocus/>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="modal-button" type="submit" class="btn btn-primary"></button>
                </div>
            </form>
        </div>

    </div>
</div>