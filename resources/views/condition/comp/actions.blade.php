<div class="divider-new wow fadeInDown" style="margin-top: 0px; padding-top: 0px; margin-bottom: 20px">
    <h2 class="h2-responsive">Select the Actions & Rules</h2>
</div>

<div id="actions_op" class="hide" style="padding-left: 0px">
    <input id="num_actions" name="num_actions"  type="hidden" />
    <input id="num_rules"   name="num_rules"    type="hidden" />
    <span>[Operand] Select the operand for multiple actions:&nbsp;&nbsp;
        <input type="radio" name="condition_op" value="and" checked required/><label>AND</label>
        <input type="radio" name="condition_op" value="or"/><label>OR</label>
    </span>
</div>

<div id="action_list"></div>
<div class="row">
    <button type="button" id="more_actions" class="width-auto btn btn-primary">
        More Actions
    </button>
    <button type="button" id="less_actions" class="width-auto btn btn-default">
        Less Actions
    </button>
</div>

@include('condition.comp.action_template')