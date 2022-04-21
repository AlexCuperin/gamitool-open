<script>
    /**
     * CREATE TABLE
     * @param rows
     * @param columns
     */
    function create_table(modules){
        var table = $('#learning_design');
        var thead = table.find('thead');
        var tbody = table.find('tbody');

        columns = modules.length;
        rows = 0;

        var trow = '';
        for(j=0; j<columns; j++) {
            trow += '<th style="border: 1px solid #e9ecef">' + modules[j].name + '</th>';
            if (modules[j]['resources'].length > rows){
                rows = modules[j]['resources'].length;
            }
        }
        thead.html('<tr>'+trow+'</tr>');

        //tbody.append('<tr><td colspan="'+ columns +'" id="cell-00" class="ld_content pointer text-center"></td></tr>');
        for(i=1; i<=rows; i++) {
            trow = '';
            for (j=1; j<=columns; j++)
                trow += '<td id="cell-'+i+j+'" class="pointer ld_content"></td>';
            tbody.append('<tr>'+trow+'</tr>');
        }
    }

    function populate_table(modules) {
        //console.log(resources);

        modules.forEach(function (module, index) {
            module['resources'].forEach(function (resource, index2){
                var cell_id = 'cell-' + (index2+1) + (index+1);
                var type = resource.type_name;
                var name = resource.name;

                $('#' + cell_id).html('<p>' + type + ':<br>' + name + '</p>').attr('data-index', index);
            });
        });
    }
</script>