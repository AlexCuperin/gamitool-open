<div id="template_actions" class="hide row std-margin-top action-row">
    <input class="action_id" type="hidden" />
    <div class="col-md-4 mb-r">
        <div class="action card">
            <table class="table table-bordered" style="margin-bottom: 0px; table-layout:fixed">
                <thead>
                <tr><th><p class="js_title"></p></th></tr>
                </thead>
                <tbody>
                <tr><td><select class="js_select width-100"></select></td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-8">
        <div class="rule-list row">
            <div class="template_rules col-md-4">
                <input class="rule_id" type="hidden" />
                <div class="rule card">
                    <table class="table table-bordered" style="margin-bottom: 0px; table-layout:fixed">
                        <thead>
                        <tr><th><p class="js_title"></p></th></tr>
                        </thead>
                        <tbody>
                            <tr><td>
                                <select onchange="manage_rule_parameters(this);" class="js_select width-100"></select>
                                <p class="js_tip hide"></p>
                                <input class="js_param1 width-100 hide" />
                                <input class="js_param2 width-100 hide" />
                            </td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <button type="button" data-target="" class="more_rules width-auto btn btn-secondary">
            More Rules
        </button>
        <button type="button" data-target="" class="less_rules width-auto btn btn-default">
            Less Rules
        </button>
    </div>
</div>