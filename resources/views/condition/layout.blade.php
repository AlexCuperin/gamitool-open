@extends('layouts.app')


@section('particular_css')
    @include('gamification.css')
@endsection


@section('layout_content')
    @include('condition.grid')
@endsection

@section('particular_js')
    {{--@include('condition.js.conditions')--}}
    @include('condition.js.functions')
    @include('reward.js.tablejs')
    <script>
        var condition    = @jsvar($condition);
        var act_by_res   = @jsvar($actions_by_res);
        var rul_by_act   = @jsvar($rules_by_act);
        var engine       = @jsvar($engine);
        var rule_types   = @jsvar($rule_types);
        var ld           = engine.gamification_design.learning_design;
        var rtype_id     = -1;
        var atype_id     = -1;
        var gnum_actions = 1;
        var gnum_rules   = [];

        $(document).ready(function(){
            create_table(ld.rows, ld.modules);
            populate_table(ld.resources,engine.gdesign_id);

            if(condition) {

                $('#condition_id').val(condition.id);
                $('#description').val(condition.description);
                $('#resource_condition_id').val(condition.resource_conditions[0].id);

                //Resource
                var cond = condition.resource_conditions[0].resource;
                var cell = $('#cell-'+cond.row+cond.module);
                selectResource(cell);
                //Resource Operand
                if (condition.resource_conditions[0].resource_op){
                    $("input[name=resource_op][value=" + condition.resource_conditions[0].resource_op + "]").prop('checked', true);
                }else {
                    $("input[name=resource_op][value='']").prop('checked', true);
                }
                //Action Operand
                $("input[name=condition_op][value=" + condition.resource_conditions[0].action_op + "]").prop('checked', true);

                //Configure Actions & Rules

                if(condition.resource_conditions[0].actions.length > 1) $('#actions_op').removeClass('hide');
                condition.resource_conditions[0].actions.forEach(function(action, index){
                    add_action_row();
                    $('#action_'+(index+1)+'_id').val(action.id);
                    $('select[name=action_'+(index+1)+'_select] option[value="'+action.type_id+'"]').prop('selected',true);
                    var rule_select = $('select[name=rule_'+(index+1)+'1_select]');
                    gnum_rules[(index+1)] = 1;
                    fill_select_by_action(rule_select, action.type_id);
                    $('#rule_'+(index+1)+'1_id').val(action.rules[0].id);
                    $('select[name=rule_'+(index+1)+'1_select] option[value="'+action.rules[0].type_id+'"]').prop('selected',true);

                    if(action.rules[0].param_1) {
                        $param_1 = $('#rule_'+(index+1)+'1_param1');
                        $tip = $('#rule_'+(index+1)+'1_tip');

                        $tip.html(action.rules[0].rule_type.tip);
                        $tip.removeClass('hide');
                        $param_1.val(action.rules[0].param_1);
                        $param_1.removeClass('hide');
                    }
                    if(action.rules[0].param_2){
                        $param_2 = $('#rule_'+(index+1)+'1_param2');

                        $param_2.val(action.rules[0].param_2);
                        $param_2.removeClass('hide');
                    }

                    action.rules.forEach(function(rule, key){
                        if(key>=1) {
                            add_rule_column("action_" + (index + 1));
                            $('#rule_'+(index+1)+(key+1)+'_id').val(action.rules[key].id);
                            $('select[name=rule_'+(index+1)+(key+1)+'_select] option[value="'+action.rules[key].type_id+'"]').prop('selected',true);
                            if(action.rules[key].param_1){
                                var param_1 = $('#rule_'+(index+1)+(key+1)+'_param1');
                                var tip = $('#rule_'+(index+1)+(key+1)+'_tip');

                                param_1.val(action.rules[key].param_1);
                                param_1.removeClass('hide');
                                tip.html(action.rules[key].rule_type.tip);
                                tip.removeClass('hide');
                            }
                            if(action.rules[key].param_2){
                                var param_2 = $('#rule_'+(index+1)+(key+1)+'_param2');
                                param_2.val(action.rules[key].param_2);
                                param_2.removeClass('hide');
                            }
                        }
                    });
                });
                //Show Resource Operands
                $('#operands').removeClass('hide');
                //Show Actions Section
                $('#actions_div').removeClass('hide');
                //Show Save Button
                $('#save_button').removeClass('hide');
            }

            $('.ld_content').click(function(){
                reset_actions();
                //$('#operands').removeClass('hide');
                add_action_row();

                $('#actions_div').removeClass('hide');
                $('#save_button').removeClass('hide');

                $('html, body').animate({
                    scrollTop: elem.offset().top
                }, 1000);

            });

            $('#more_actions').click(function(){
                add_action_row();
                if(gnum_actions > 2) $('#actions_op').removeClass('hide');
            });

            $('#less_actions').click(function(){
                remove_action_row();
                if(gnum_actions == 2) $('#actions_op').addClass('hide');
            });
        });
    </script>
@endsection