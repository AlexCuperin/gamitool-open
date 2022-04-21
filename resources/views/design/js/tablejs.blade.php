<script>
    /**
     * CREATE TABLE
     * @param rows
     * @param columns
     */
    function create_table(rows, columns, modulesobj){
        var table = $('#learning_design');
        var thead = table.find('thead');
        var tbody = table.find('tbody');

        var trow = '';
        if(modulesobj.length)
            modulesobj.forEach(function(elem) {
                trow += '<th class="lh_content"><input type="text" value="' + elem.name + '"/></th>';
            });
        else
            for(j=1; j<=columns; j++) {
                trow += '<th class="lh_content"><input type="text" value="Module '+j+'"/></th>';
            }
        thead.append('<tr>'+trow+'</tr>');

        for(i=1; i<=rows; i++) {
            trow = '';
            for (j=1; j<=columns; j++)
                trow += '<td ondrop="drop(event)" ondragover="allowDrop(event)" id=cell-'+i+j+' class="ld_content" data-row='+i+' data-column='+j+'></td>';
            tbody.append('<tr>'+trow+'</tr>');
        }
    }

    /**
     * POPULATE TABLE
     * @param resources
     */
    function populate_table(resources){
        resources.forEach(function(elem){
            var cell_id = 'cell-'+elem.row+elem.module;
            var name = elem.resource_type.name;
            var resource_name = elem.name;
            $('#'+cell_id).html('<p draggable="true" ondragstart="drag(event)" id="'+name+'"><input type=text value="'+resource_name+'"/><br><i>' + name + '</i></p>');
        });
    }

    /**
     * SET LEARNING DATA
     * @param ld
     */
    function set_learning_data(ld){
        $('#course_id').val(ld.id);
        $('#course_name').val(ld.course_name);
    }

    function set_modify_listeners(){
        var cells = $('.ld_content > p');

        cells.dblclick(delete_cell);
        cells.find('input').keyup(modify_name);
    }

    function delete_cell(){
        $(this).addClass('modified');
        $(this).text('');
    }

    function modify_name(){
        $(this).parent().addClass('modified');
    }

    /**
     * SAVE LEARNING TABLE
     */
    function save_learning_table(){
        var json_resources = [];
        var json_modules = [];

        $('.ld_content').each(function(){
            if($(this).find('p').hasClass('modified')) {

                var single    = {row: 0, module: 0, rtype: "", name: ""};
                single.row    = $(this).attr('data-row');
                single.module = $(this).attr('data-column');
                single.rtype  = $(this).find('p').first().text();
                single.name   = $(this).find('p').find('input').val();

                json_resources.push(single);
            }
        });

        $('.lh_content').each(function(index){
                var single = {column: index+1, name: $(this).find('input').val()};
                json_modules.push(single);
        });

        //console.log(json_modules);

        $.fn.rowCount = function() {
            return $('tr', $(this).find('tbody')).length;
        };

        $.fn.columnCount = function() {
            return $('th', $(this).find('thead')).length;
        };

        //console.log(json_resources);

        $.ajax({
            method: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{url('/design')}}",
            data: {
                course_id:      $('#course_id').val(),
                course_name:    $('#course_name').val(),
                ld_rows:        $('#learning_design').rowCount(),
                ld_modules:     $('#learning_design').columnCount(),
                resource_array: json_resources,
                modules_array:  json_modules
            },
        }).done(function( msg ) {
            console.log(msg);
            setTimeout(btn_utils_hide_loader, 1000);
            //btn_utils_hide_loader();
            window.location="{{URL::to('home')}}";


            //TODO:H? Esto era lo que estaba antes para mostrar la notificación en la propia página
            //$('#top_banner_msg').show();
            //$('#top_banner_msg > span').text(msg);
        }).fail(function(msg){
            console.log(msg);
        });
    }
</script>