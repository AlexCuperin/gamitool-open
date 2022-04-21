<script>
    function manageEngine(gld_id, engine_id){
        var title, button, action;

        if (engine_id){
            title = "Edit Gamification Association";
            button = "Update Association";
            action = '{{url('/gamification')}}/' + gld_id + '/engine/edit/' + engine_id;

            var name, description, condition;
            gam_engines.forEach(function(engine){
                if (engine['id'] == engine_id){
                    name = engine['name'];
                    description = engine['description'];
                    condition = engine['condition_op'];
                }
            });

            $('#name_engine').val(name);
            $('#description_engine').val(description);
            $('input[name=condition_engine][value="'+condition+'"]').prop('checked', true);
            $('#engine_id').val(engine_id);

        }else{
            title = "New Gamification Association";
            button = "Create Association";
            action = '{{url('/gamification')}}/' + gld_id + '/engine/new';
        }

        $('#gld_engine').val(gld_id);
        $('#modal-title').html(title);
        $('#modal-form').attr('action',action);
        $('#modal-button').html(button);
    }
</script>