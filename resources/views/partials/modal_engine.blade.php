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
                            <label>Visible<br>Name*: </label>
                        </div>
                        <div class="col-md-9">
                            <input id="gld_engine" name="gld_engine" type="hidden" value="" />
                            <input id="engine_id" name="engine_id" type="hidden" value="" />
                            <input id="name_engine" name="name_engine" type="text" value="" required autofocus/>
                        </div>
                    </div>
                    <div class="row" style="white-space:nowrap; margin:1px; padding-top:15px;">
                        <div class="col-md-3">
                            <label>Visible<br>Description*: </label>
                        </div>
                        <div class="col-md-9">
                            <input id="description_engine" name="description_engine" type="text" value="" required/>
                        </div>
                    </div>
                    <div class="row hide" style="white-space:nowrap; margin:1px; padding-top:15px;">
                        <div class="col-md-7">
                            <div class="owntooltip"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;
                                <span class="owntooltiptext">AND: students must perform all the configured conditions to earn the associated rewards.
                                <br>OR: students must perform at least one of the cofigured conditions to earn the associated rewards.</span>
                            </div><label>For multiple conditions, Operand:</label>
                        </div>
                        <div class="col-md-5">
                            <input id="" name="condition_engine" type="radio" value="and" checked>&nbsp;AND</input>
                            &nbsp;&nbsp;<input id="" name="condition_engine" type="radio" value="or">&nbsp;OR</input>
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