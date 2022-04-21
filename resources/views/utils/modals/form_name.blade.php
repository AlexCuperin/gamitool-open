{{--

$modal_id : modal id    (def. myModal)

$method   : GET or POST (def. GET)
$action   : url

$title_txt  : title of the modal
$button_txt : text for the button

$form_inputs: url where the input html is contained

e.g.
    <input id="learning_id"       name="learning_id"       type="hidden" value=""/>
    <input id="gld_id"            name="gld_id"            type="hidden" value=""/>
    <input id="name_gamification" name="name_gamification" type="text"   value="" autofocus/>
--}}

<?php
    $modal_id = isset($modal_id) ? $modal_id : "myModal";
    $method   = isset($method)   ? $method   : "GET";
?>

<!-- Modal -->
<div class="modal fade" id="{{$modal_id}}" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <form id="modal-form"
                  class="form-horizontal"
                  method="{{$method}}"
                  action="{{$action}}">

                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title" >{{$title_txt}}</h4>
                    <button type="button" class="width-auto close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="row" style="margin:1px;">
                        @include($form_inputs)
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="modal-button" type="submit" class="btn btn-primary">{{$button_txt}}</button>
                </div>
                {{ csrf_field() }}
            </form>
        </div>

    </div>
</div>

<script>
    $('#{{$modal_id}}').on('shown.bs.modal', function () {
        $(this).find('input').first().focus();
    })
</script>