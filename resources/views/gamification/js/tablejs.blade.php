<script>
    /**
     * CREATE TABLE
     * @param rows
     * @param columns
     */
    function create_table(rows, columns){
        var table = $('#learning_design');
        var thead = table.find('thead');
        var tbody = table.find('tbody');

        var trow = '';
        for(j=1; j<=columns; j++) {
            trow += '<th>Module ' + (j-1) + '</th>';
        }
        thead.append('<tr>'+trow+'</tr>');

        tbody.append('<tr><td colspan="'+ columns +'" onclick="hoverCell(this)" id="cell-00" class="ld_content pointer text-center"></td>');
        for(i=1; i<=rows; i++) {
            trow = '';
            for (j=1; j<=columns; j++)
                trow += '<td onclick="hoverCell(this)" id="cell-'+i+j+'" class="pointer ld_content"></td>';
            tbody.append('<tr>'+trow+'</tr>');
        }
    }

    /**
     * POPULATE TABLE
     * @param resources
     */
    function populate_table(resources, gld_id){
        //console.log(resources);

        resources.forEach(function(elem, index){
            var cell_id = 'cell-'+elem.row+elem.module;
            var name = elem.resource_type.name;
            var resource_name = elem.name;
            var condition = false;
            var reward = false;
            var cell_text = "";

            cell_text = resource_name + '<br><i>(' + name + ')</i>';

            elem.redeemable_rewards.forEach(function(rr){
                if ((reward === false) && (rr.reward.gamification_engine['gdesign_id'] === gld_id)){
                    cell_text = '&reg;' + '&nbsp;' + cell_text;
                    reward = true;
                }
            });

            elem.resource_conditions.forEach(function(res_cond){
               if ((condition === false) && (res_cond.condition.gamification_engine['gdesign_id'] === gld_id)){
                   cell_text = '&copy;' + '&nbsp;' + cell_text;
                   condition = true;
               }
            });

            $('#'+cell_id).html('<p style="font-size:small">' + cell_text + '</p>').attr('data-index', index);

            //Coloring the cells
            if(cell_text.includes('&reg;') && cell_text.includes('&copy;')) $('#'+cell_id).css('background-color','#e9e7ff');
            else if(cell_text.includes('&copy;')) $('#'+cell_id).css('background-color', '#d6eaf8');
            else if(cell_text.includes('&reg;')) $('#'+cell_id).css('background-color','#e8daef');
        });
    }

    function showEngine(engine, id, cond_rew){
        var conditions = "";
            engine.conditions.forEach(function(condition){
                if((cond_rew === "condition") && (id === (condition['id']))){
                    conditions = conditions + "<br><p style='background-color:#d6eaf8'>* " + condition['description'] + "</p>";
                }else {
                    conditions = conditions + "<p>* " + condition['description'] + "</p>";
                }
            });
        var rewards = "";
            engine.rewards.forEach(function(reward){
                if((cond_rew === "reward") && (id === reward['id'])){
                    if(reward['redeemable_rewards'][0]) rewards = rewards + "<p style='background-color:#e8daef; text-align: right; display:block;'>* " + reward['name'] + " @ " + reward['redeemable_rewards'][0].resource['name'] + "</p>";
                    else rewards = rewards + "<p style='background-color:#e8daef; text-align: right; display:block;'>* " + reward['name'] + "</p>";
                }else {
                    if(reward['redeemable_rewards'][0]) rewards = rewards + "<p style='text-align: right; display:block;'>* " + reward['name'] + " @ " + reward['redeemable_rewards'][0].resource['name'] + "</p>";
                    else rewards = rewards + "<p style='text-align: right; display:block;'>* " + reward['name'] + "</p>";
                }
            });

        var condition_op ="";
        if (engine['condition_op'])
            condition_op = "[" + engine['condition_op'] + "]";

        var html =
            "<tr><td>\n" +
            "    <b>Association: </b>" + engine['name'] +
            "    <hr><b>Conditions " + condition_op + ": </b>" + conditions +
            "    <br><b style='text-align: right; display: block;'>Rewards: </b>" + rewards +
            "</td></tr>";

        return html;
    }

    function hoverCell(celldom){
        $('.ld_content').removeClass('selected_cell');

        var cell = $(celldom);
        cell.addClass('selected_cell');
        var idx = cell.attr('data-index');
        var res = ld.resources[idx];
        //console.log(res);

        var summary = $('#summary');
        var tbody = summary.find('tbody');
        var html;

        gam_engines.forEach(function(engine){

            ld.resources[idx].resource_conditions.forEach(function(res_condition){
                if (res_condition.condition['engine_id'] === engine['id']){
                    html = html + showEngine(engine, res_condition.condition['id'],"condition");
                }
            });

            ld.resources[idx].redeemable_rewards.forEach(function(rr){
                if (rr.reward['engine_id'] === engine['id']){
                    html = html + showEngine(engine, rr.reward['id'],"reward");
                }
            });

        });

        if(!html){
            html = "<tr><td><p>This resource hasn't been gamified yet.</p></td></tr>";
        }
        tbody.html(html);

    }
</script>