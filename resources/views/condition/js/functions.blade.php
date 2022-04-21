<script>
    function selectResource(celldom) {
        $('.ld_content').removeClass('selected_cell');
        var cell = $(celldom);
        cell.addClass('selected_cell');
        var idx = cell.attr('data-index');
        var clicked_res = ld.resources[idx];
        rtype_id = clicked_res.resource_type.id;
        $('#resource_id').val(clicked_res.id);
    }

    function remove_action_row(){
        if(gnum_actions === 2){
            //TODO: Message: There must be at least one action in the condition
        }else {
            gnum_actions--;
            var action_row = $('#action_' + gnum_actions);
            action_row.remove();
        }
    }

    function add_action_row(){
        var row = $('#template_actions').clone();

        row.attr('id', 'action_'+gnum_actions);

        row.find('.action_id').attr('id', 'action_'+gnum_actions+'_id');
        row.find('.action_id').attr('name', 'action_'+gnum_actions+'_id');

        //filling ACTIONS
        row.find('.action .js_title').html('Action '+gnum_actions);
        var select = row.find('.action .js_select');
        select.attr('name', 'action_'+gnum_actions+'_select');
        fill_select_by_resource(select);
        select.change(function(){
            var value = $(this).val();
            if(value)
                fill_select_by_action(row.find('.rule .js_select'), value);
        });

        //filling first RULE
        gnum_rules[gnum_actions] = 1;
        row.find('.rule .js_title').html('Rule 1');

        row.find('.rule_id').attr('id', 'rule_' + gnum_actions + gnum_rules[gnum_actions] + '_id');
        row.find('.rule_id').attr('name', 'rule_' + gnum_actions + gnum_rules[gnum_actions] + '_id');

        row.find('.rule .js_select').attr('name', 'rule_' + gnum_actions + gnum_rules[gnum_actions] + '_select');
        row.find('.rule .js_tip').attr('id', 'rule_' + gnum_actions + gnum_rules[gnum_actions] + '_tip');
        row.find('.rule .js_param1').attr('name', 'rule_' + gnum_actions + gnum_rules[gnum_actions] + '_param1');
        row.find('.rule .js_param1').attr('id', 'rule_' + gnum_actions + gnum_rules[gnum_actions] + '_param1');
        row.find('.rule .js_param2').attr('name', 'rule_' + gnum_actions + gnum_rules[gnum_actions] + '_param2');
        row.find('.rule .js_param2').attr('id', 'rule_' + gnum_actions + gnum_rules[gnum_actions] + '_param2');
        row.find('.template_rules').attr('id', 'rule_'+gnum_actions+gnum_rules[gnum_actions]);

        //Button "New Rule"
        var morerules = row.find('.more_rules');
        morerules.attr('data-target', row.attr('id'));
        morerules.click(function(){
            add_rule_column($(this).attr('data-target'));
        });

        //Button "Remove Rule"
        var lessrules = row.find('.less_rules');
        lessrules.attr('data-target', row.attr('id'));
        lessrules.click(function(){
            remove_rule_column($(this).attr('data-target'));
        });

        row.removeClass('hide');
        $('#action_list').append(row);

        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
        gnum_actions++;
    }

    function add_rule_column(row_id){
        var row = $('#'+row_id);
        var col = row.find('.template_rules').first().clone();

        var aux = row_id.split('_');
        var action_id = aux[1];
        gnum_rules[action_id]++;

        col.attr('id', 'rule_'+action_id+gnum_rules[action_id]);
        col.find('.js_title').html('Rule '+gnum_rules[action_id]);
        col.find('.js_select').attr('name', 'rule_' + (gnum_actions-1) + gnum_rules[(gnum_actions-1)] + '_select');
        col.find('.js_tip').attr('id', 'rule_' + (gnum_actions-1) + gnum_rules[(gnum_actions-1)] + '_tip');
        col.find('.js_param1').attr('name', 'rule_' + (gnum_actions-1) + gnum_rules[(gnum_actions-1)] + '_param1');
        col.find('.js_param1').attr('id', 'rule_' + (gnum_actions-1) + gnum_rules[(gnum_actions-1)] + '_param1');
        col.find('.js_param2').attr('name', 'rule_' + (gnum_actions-1) + gnum_rules[(gnum_actions-1)] + '_param2');
        col.find('.js_param2').attr('id', 'rule_' + (gnum_actions-1) + gnum_rules[(gnum_actions-1)] + '_param2');

        col.find('.rule_id').attr('id', 'rule_' + (gnum_actions-1) + gnum_rules[(gnum_actions-1)] + '_id');
        col.find('.rule_id').attr('name', 'rule_' + (gnum_actions-1) + gnum_rules[(gnum_actions-1)] + '_id');

        col.find('.js_tip').addClass('hide');
        col.find('.js_param1').addClass('hide');
        col.find('.js_param2').addClass('hide');

        row.find('.rule-list').append(col);
    }

    function remove_rule_column(row_id){
        var aux = row_id.split('_');
        var action_id = aux[1];

        if(gnum_rules[action_id] === 1){
            //TODO: Message: There must be at least one rule in the condition
        }else {
            var rule = $('#rule_' + action_id + gnum_rules[action_id]);
            rule.remove();
            gnum_rules[action_id]--;
        }
    }

    function fill_select_by_resource(select){
        var list = act_by_res.find(function(elem){ return elem.id == rtype_id });

        list.action_types.forEach(function(action){
            select
                .append('<option value="'+action.id+'">'+action.name+'</option>')
                .val(action.id);
        });
        select.append('<option selected value="">Select an action...</option>');
    }

    function fill_select_by_action(select, atype_id){
        select.empty();
        var list = rul_by_act.find(function(elem){ return elem.id == atype_id });

        list.rule_types.forEach(function(rule){
            select
                .append('<option value="'+rule.id+'">'+rule.name+'</option>')
                .val(rule.id);
        });
        select.append('<option selected value="">Select a rule...</option>');
    }

    function changeInputType(oldObject, oType) {
        var newObject = document.createElement('input');
        newObject.type = oType;
        if(oldObject.name) newObject.name = oldObject.name;
        if(oldObject.id) newObject.id = oldObject.id;
        if(oldObject.className) newObject.className = oldObject.className;
        oldObject.parentNode.replaceChild(newObject,oldObject);
        return newObject;
    }

    function show_rule_parameters(tip, param1, param2, rtype_id){
        param1.removeAttribute("required");
        param2.removeAttribute("required");

        rule_types.forEach(function(rule){
            if (rule.id == rtype_id){
                tip.innerHTML = rule.tip;
                tip.classList.remove('hide');
                if (rule.extra_parameters >= 1){
                    param1 = changeInputType(param1, rule.input_type);
                    param1.setAttribute('required','required');
                    param1.classList.remove('hide');
                    if (rule.extra_parameters === 2) {
                        param2 = changeInputType(param2, rule.input_type);
                        param2.setAttribute('required','required');
                        param2.classList.remove('hide');
                    }
                }
            }
        });
    }

    function findAncestor(el, cls){
        while ((el = el.parentElement) && !el.classList.contains(cls));
        return el;
    }

    function manage_rule_parameters(select){
        rule_types.forEach(function(rule_type){
            select_id = select.options[select.selectedIndex].value;
            if(select_id == rule_type.id) {
                var rule_card = findAncestor(select,'rule');
                var tip = rule_card.getElementsByClassName('js_tip');
                var param1 = rule_card.getElementsByClassName('js_param1');
                var param2 = rule_card.getElementsByClassName('js_param2');

                tip[0].classList.add('hide');
                param1[0].classList.add('hide');
                param2[0].classList.add('hide');

                show_rule_parameters(tip[0], param1[0], param2[0], rule_type.id);
            }
        });
    }

    function save_parameters(){
        //if todos los select de action tienen valor y no es "Select..." modal de completa y fin.
        //else if todos los select de rules tienen valor y no es "Select..." modal de completa y fin.
        //else: todos los campos parece que están rellenados, proseguimos con el action del button.

        $('#num_actions').val(gnum_actions);
        $('#num_rules').val(gnum_rules);
    }

    function reset_actions(){
        gnum_actions = 1;
        $('#action_list').empty();
    }
</script>