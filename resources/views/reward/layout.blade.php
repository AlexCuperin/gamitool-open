@extends('layouts.app')


@section('particular_css')
    @include('gamification.css')
@endsection


@section('layout_content')
    @include('reward.grid')
@endsection

@section('particular_js')
    @include('reward.js.rewards')
    @include('reward.js.tablejs')

    <script>
        var reward  = @jsvar($reward);

        var engine = @jsvar($engine);
        var ld      = engine.gamification_design.learning_design;

        var reward_types  = @jsvar($reward_types);
        var badge_suites  = @jsvar($badge_suites);

        var priv_by_res = @jsvar($priv_by_res);
        var rr_types = @jsvar($rr_types);
        console.log(priv_by_res);

        $(document).ready(function(){

            create_table(ld.rows, ld.modules);
            populate_table(ld.resources, engine['gdesign_id']);
            $('#tr_rr').hide();

            if(reward) {
                // Add the ID of the reward to a hidden field to use it in the save() function @ controller
                $('#reward_id').val(reward.id);

                populate_general_info(reward);

                switch(reward.reward_type['name']){
                    case('Redeemable Rewards'):
                        populate_rr_info(reward);
                        $('#concrete_reward_id').val(reward.redeemable_rewards[0].id);
                        break;
                    case ('Points'):
                        $('#concrete_reward_id').val(reward.points[0].id);
                        $('#tr_quantity').show();
                        break;
                    case ('Levels'):
                        $('#concrete_reward_id').val(reward.levels[0].id);
                        break;
                    case('Badges'):
                        populate_badges_info(reward);
                        $('#concrete_reward_id').val(reward.badges[0].id);
                        $('#tr_suite').show();
                        break;
                }
            }

            $("#reward_type").change(function () {
               selectReward($("#reward_type").find(":selected").val());
            });

            $("#badge_suite").change(function () {
                if($("#badge_suite").find(":selected").val() == 'yes'){
                    $('#tr_badge').show();
                }else{
                    $('#tr_badge').hide();
                }
            });

            function validate() {
                $("#formid").validate();
            }
        });

    </script>
@endsection