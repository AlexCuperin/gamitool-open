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
            trow += '<th style="border: 1px solid #e9ecef">Module ' + (j-1) + '</th>';
        }
        thead.html('<tr>'+trow+'</tr>');

        tbody.append('<tr><td colspan="'+ columns +'" onclick="selectResource(this)" id="cell-00" class="ld_content pointer text-center"></td></tr>');
        for(i=1; i<=rows; i++) {
            trow = '';
            for (j=1; j<=columns; j++)
                trow += '<td onclick="selectResource(this)" id="cell-'+i+j+'" class="pointer ld_content"></td>';
            tbody.append('<tr>'+trow+'</tr>');
        }
    }

    function populate_table(resources, gld_id) {
        //console.log(resources);

        resources.forEach(function (elem, index) {
            var cell_id = 'cell-' + elem.row + elem.module;
            var name = elem.resource_type.name;
            var resource_name = elem.name;
            var condition = false;
            var reward = false;
            var cell_text = "";

            cell_text = resource_name + '<br><i>(' + name + ')</i>';

            elem.redeemable_rewards.forEach(function (rr) {
                if ((reward === false) && (rr.reward.gamification_engine['gdesign_id'] === gld_id)) {
                    cell_text = '&reg;' + '&nbsp;' + cell_text;
                    reward = true;
                }
            });

            elem.resource_conditions.forEach(function (res_cond) {
                if ((condition === false) && (res_cond.condition.gamification_engine['gdesign_id'] === gld_id)) {
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
</script>