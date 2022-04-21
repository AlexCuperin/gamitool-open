<?php
    $modal_id = isset($modal_id) ? $modal_id : "myModal";
?>

<!-- Modal -->
<div class="modal fade" id="{{$modal_id}}" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-header">
                <h4 id="modal-title" class="modal-title" >{{$title_txt}}</h4>
                <button type="button" class="width-auto close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="row" style="margin:1px;">
                    @include($html_code)
                </div>
            </div>

            <div class="modal-footer">
                <button id="redirect-button" class="btn btn-primary">{{$button_txt}}</button>
            </div>

        </div>

    </div>
</div>

<script>
    $('#{{$modal_id}}').on('shown.bs.modal', function () {
        $(this).find('input').first().focus();
    })
</script>