<script>
    function setAttributes(elem, row, column){
        elem.setAttribute("ondragover",  "allowDrop(event)");
        elem.setAttribute("class",       "ld_content");
        elem.setAttribute("class",       "ld_content");
        elem.setAttribute("id",          "cell-"+(row)+(column));
        elem.setAttribute("data-row",    row);
        elem.setAttribute("data-column", column);
    }

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("text/html", ev.target.id);

    }

    function drop(ev) {
        ev.preventDefault();
        var data=ev.dataTransfer.getData("text/html");
        var nodeCopy = document.getElementById(data).cloneNode(true); /* By appending a ".cloneNode(true)", you will not move the original element, but create a copy. */
        //nodeCopy.id = data; /* We cannot use the same ID */
        //console.log(nodeCopy.innerHTML)
        var htmlcode = '<p class="modified" draggable="true" ondragstart="drag(event)" id="' + data + '"><input type=text value="" placeholder="<insert name>"/><br><i>' + nodeCopy.innerHTML + '</p>';
        if(ev.target.tagName == 'TD')
            ev.target.innerHTML = htmlcode;
        else
            ev.target.parentElement.innerHTML = htmlcode;
        set_modify_listeners();
    }

    function CreateRow() {
        var table = document.getElementById("learning_design");
        var number_columns = table.rows[0].cells.length;
        var number_rows = table.getElementsByTagName("tr").length;
        var row = table.insertRow(number_rows);
        for (var i=0;i<number_columns;i++){
            row.insertCell(i).setAttribute("ondrop", "drop(event)");
            setAttributes(row.cells[i], number_rows, i+1);
        }
    }

    function RemoveRow() {
        var table = document.getElementById("learning_design");
        var number_rows = table.getElementsByTagName("tr").length;
        if(number_rows > 2){
            table.deleteRow(number_rows-1);
        }
    }

    function CreateCol() {
        var tblHeadObj = document.getElementById("learning_design").tHead;
        var num_columns = tblHeadObj.rows[0].cells.length + 1;
        for (var h=0; h<tblHeadObj.rows.length; h++) {
            var newTH = document.createElement('th');
            tblHeadObj.rows[h].appendChild(newTH);
            newTH.innerHTML = '<input type="text" value="Module ' + num_columns+'"/>';
        }

        var column = document.getElementById("learning_design").tBodies[0];
        for (var i=0; i<column.rows.length; i++) {
            column.rows[i].insertCell(-1).setAttribute("ondrop", "drop(event)");
            setAttributes(column.rows[i].cells[(column.rows[0].cells.length-1)], i+1, num_columns);
        }
    }

    function RemoveCol() {
        var allRows = document.getElementById("learning_design").rows;
        for (var i=0; i<allRows.length; i++) {
            if (allRows[i].cells.length > 1) {
                allRows[i].deleteCell(-1);
            }
        }
    }

    function CleanTable() {
        var table = document.getElementById("learning_design");
        $('.ld_content > p').addClass('modified').text('');
    }
</script>