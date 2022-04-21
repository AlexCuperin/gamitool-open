<script>

    $(".readonly").on('keydown paste', function(e){
        e.preventDefault();
    });

    $('#reward_name').keyup(function() {
        $('#name_preview').text($('#reward_name').val());
    });

    $('#reward_image').focusout(function(){
       if($('#reward_image').val() != ""){
           $('#icon_preview').html('<img style="max-width: 100%" src="'+ $('#reward_image').val() +'"/>');
       }
    });

    function populate_general_info(reward){
        $('#reward_type').val(reward.reward_type['name']);
        $('#reward_name').attr('value',reward.name);
        $('#reward_image').attr('value',reward.url_image);
        $('#reward_quantity').attr('value',reward.quantity);
        selectReward($("#reward_type").find(":selected").val());
    }
    function populate_badges_info(reward){
        $('#badge_suite').val(reward.badge_suite['name']);
        $('#badge_quality').val(reward.suite_quality);
        //$('#tr_badge').show();
    }
    function populate_rr_info(reward){
        //they are now global variables
        var res = reward.redeemable_rewards[0].resource;
        var cell = $('#cell-'+res.row+res.module);

        selectResource(cell);

        $('#privilege').val(reward.redeemable_rewards[0].rr_type['id']);
        $('#tr_rr').show();

        if(reward.redeemable_rewards[0].rr_type['extra_parameters'] == 1){
            var input = document.getElementById('param_1');
            $('#rr_param1 label').text(reward.redeemable_rewards[0].rr_type['tip']);
            changeInputType(input, reward.redeemable_rewards[0].rr_type['input_type']);
            $("#param_1").prop('required','required');
            $('#param_1').val(reward.redeemable_rewards[0]['param_1']);
            $('#rr_param1').removeClass('hide');
        }
    }

    function selectReward(reward_type){
        //$('#gamification_tab').find('td').removeClass('selected_cell');
        //$(reward_type).addClass('selected_cell');

        $('#tr_suite').hide();
        $('#tr_badge').hide();
        $('#tr_rr').hide();
        $('#tr_quantity').hide();

        //$('#reward_type').val(reward_type.id);
        switch(reward_type) {
            case "Badges":
                $('#tr_suite').show();
                if($('#reward_image').val() == "") $('#icon_preview').html('<i class="fa fa-sun-o reward_icon_preview"></i>');
                break;
            case "Levels":
                if($('#reward_image').val() == "") $('#icon_preview').html('<i class="fa fa-battery-half reward_icon_preview"></i>');
                break;
            case "Points":
                $('#tr_quantity').show();
                if($('#reward_image').val() == "") $('#icon_preview').html('<i class="fa fa-braille reward_icon_preview"></i>');
                break;
            case "Redeemable Rewards":
                $('#tr_rr').show();
                if($('#reward_image').val() == "") $('#icon_preview').html('<i class="fa fa-gift reward_icon_preview"></i>');
                break;
            default:
                if($('#reward_image').val() == "") $('#icon_preview').html('');
                break;
        }

    }

    function filter_privileges(rtype){
        $('#privilege')
            .find('option')
            .remove();

        var list = priv_by_res.find(function(elem){ return elem.id == rtype.id });

        list.rr_types.forEach(function(priv){
            $('#privilege')
                .append('<option value="'+priv.id+'">'+priv.name+'</option>')
                .val(priv.id);
        });
        $('#privilege').append('<option selected value="">Select a privilege...</option>');

    }

    function selectResource(celldom){
        $('.ld_content').removeClass('selected_cell');
        var cell = $(celldom);
        cell.addClass('selected_cell');

        /* get the resource from array */
        var idx = cell.attr('data-index');
        var clicked_res = ld.resources[idx];
        $('#rr_resource').val(clicked_res.id);

        filter_privileges(clicked_res.resource_type)
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

    $('#privilege').change(function(){
        selectPrivilege($('#privilege'));
    });

    function selectPrivilege(selected_priv){
        $('#rr_param1').addClass('hide');
        $('#param_1').removeAttr('required');
        var privilege = rr_types.find(function(elem){ return elem.id == selected_priv.val() });
        var input = document.getElementById('param_1');

        if(privilege.extra_parameters == 1){
            $('#rr_param1 label').text(privilege.tip);
            changeInputType(input, privilege.input_type);
            $("#param_1").prop('required','required');
            $('#rr_param1').removeClass('hide');
        }
    }

    function selectBadgeSuite(option){
        //badge_suites[x].name
    }
</script>