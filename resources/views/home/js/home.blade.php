<script>

    $(document).ready(function(){

        //Alerts
        if ($('#alert_type').val()){
            $('#top_banner_msg').text($('#alert_text').val());
            $('#top_banner').removeClass('hide');

            //Alert auto close
            $("#top_banner").fadeTo(3500, 500).slideUp(1000, function(){
                $(this).slideUp(500);
            });
        }

        // add particular data to the modal and launch it
        $('.new_gam_link').click(function(){

            $('#new_gamification').modal('show');
            $('#new_gamification #learning_id').val($(this).attr('data-index'));
        });

        // add particular data to the modal and launch it
        $('.import_design').click(function(){

            $('#import_ld').modal('show');
            $('#new_gamification #learning_id').val($(this).attr('data-index'));
        });

        // add particular data to the modal and launch it
        $('.gam_name_edit').click(function(){
            $('#gamification_edit_name').modal('show');
            $('#gamification_edit_name #gld_id').val($(this).attr('data-index'));
            $('#gamification_edit_name #name_gamification').val($(this).attr('data-name'));
        });

        // add particular data to the modal and launch it
        $('.deploy_imported').click(function(){

            $('#deploy_imported').modal('show');
            $('#deploy_imported #gld_id').val($(this).attr('data-gld'));
            $('#deploy_imported #import_id').val($(this).attr('data-id'));
            $('#deploy_imported #course_platform').text($(this).attr('data-type'));
            $('#deploy_imported #course_site').text($(this).attr('data-url'));
            $('#deploy_imported #course_name').text($(this).attr('data-name'));
        });

        $('.remove_ld').click(function(){
            var url = {!! json_encode(url('/design/delete')) !!} + '/' + $(this).attr('data-index');
            $('#remove').modal('show');
            $('#remove #modal-title').text('Remove Learning Design');
            $('#remove #text-confirmation').text('Please confirm the learning design that you want to remove:');
            $('#remove #modal-form').attr('action', url);
            $('#remove #remove_name').text($(this).attr('data-name'));
        });

        $('.remove_gld').click(function(){
            var url = {!! json_encode(url('/gamification/delete')) !!} + '/' + $(this).attr('data-index');
            $('#remove').modal('show');
            $('#remove #modal-title').text('Remove Gamified Learning Design');
            $('#remove #text-confirmation').text('Please confirm the gamification learning design that you want to remove:');
            $('#remove #modal-form').attr('action', url);
            $('#remove #remove_name').text($(this).attr('data-name'));
        })

        $('.remove_gdeploy').click(function(){
            var url = {!! json_encode(url('/gamification/deploy/delete')) !!} + '/' + $(this).attr('data-index');
            $('#remove').modal('show');
            $('#remove #modal-title').text('Remove Deploy');
            $('#remove #text-confirmation').text('Please confirm the gamification deploy that you want to remove:');
            $('#remove #modal-form').attr('action', url);
            $('#remove #remove_name').text($(this).attr('data-name'));
        });

        {{--// show options for gamification entries
        $('.gam_name_link').hover(function(){
            $(this).find('.options').removeClass('hide');
        }, function(){
            $(this).find('.options').addClass('hide');
        });--}}

        // show envelope detail
        $('.gam_send_email').hover(function(){
            $(this).find('.fa-envelope').removeClass('hide');
        }, function(){
            $(this).find('.fa-envelope').addClass('hide');
        });

        // underlying onhover options
        $('.option').hover(function(){
            $(this).find('.std-margin-left').addClass('underline');
        }, function(){
            $(this).find('.std-margin-left').removeClass('underline');
        });
    });

</script>