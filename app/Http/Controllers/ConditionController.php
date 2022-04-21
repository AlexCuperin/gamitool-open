<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Action_type;
use App\Models\Redeemable_reward;
use App\Models\Resource_condition;
use App\Models\Resource_type;
use App\Models\Reward;
use App\Models\Reward_type;
use App\Models\Rule;
use App\Models\Rule_type;
use App\Models\Gamification_engine;
use App\Models\Resource;
use App\Models\Condition_type;
use App\Models\Condition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class ConditionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('auth');
    }

    public function add($engine_id, Request $request){

        $engine = Gamification_engine::where('id',$engine_id)->first();

        //Conditions
        if($request['condition_id']) {
            $condition = Condition::where('id', $request['condition_id'])->first();
        }else {
            $condition = new Condition();
        }
        $request['condition_type'] = "Resource Condition";
        $ctype = Condition_type::where('name', $request['condition_type'])->first();
        $condition->condition_type_id = $ctype['id'];
        $condition->description = $request['description'];
        $condition->engine_id = $engine_id;

        //return $condition;
        $condition->save();

        switch($ctype['name']){
            case('Reward Condition'):
                break;
            case('Group Condition'):
                break;
            case('Resource Condition'):
                if($request['resource_condition_id']) {
                    $resource_condition = Resource_condition::where('id', $request['resource_condition_id'])->first();
                }else {
                    $resource_condition = new Resource_condition();
                }
                $resource_condition->condition_id = $condition->id;
                $resource_condition->resource_id = $request['resource_id'];
                $resource_condition->resource_op = $request['resource_op'];
                $resource_condition->action_op = $request['condition_op'];

                //return $resource_condition;
                $resource_condition->save();

                $num_rules = explode(',', $request['num_rules']);
                //return $num_rules;

                for($i=1;$i<=$request['num_actions'];$i++){
                    if ($request['action_'.$i.'_select']) {
                        if ($request['action_' . $i . '_id']) {
                            $action = Action::where('id', $request['action_' . $i . '_id'])->first();
                        } else {
                            $action = new Action();
                        }
                        $action->res_cond_id = $resource_condition->id;
                        $action->type_id = $request['action_' . $i . '_select'];

                        /*if($i===3) {
                            $action_type = Action_type::where('id', $action->type_id)->first();
                            return $action_type;
                        }*/
                        $action->save();


                        for ($j = 1; $j <= $num_rules[$i]; $j++) {
                            if ($request['rule_' . $i . $j . '_select']) {
                                if ($request['rule_' . $i . $j . '_id']) {
                                    $rule = Rule::where('id', $request['rule_' . $i . $j . '_id'])->first();
                                } else {
                                    $rule = new Rule();
                                }
                                $rule->action_id = $action->id;
                                $rule->type_id = $request['rule_' . $i . $j . '_select'];

                                $rule_types = Rule_type::where('id', $rule->type_id)->first();

                                switch ($rule_types->extra_parameters) {
                                    case(0):
                                        break;
                                    case(1):
                                        $rule->param_1 = $request['rule_' . $i . $j . '_param1'];
                                        break;
                                    case(2):
                                        $rule->param_1 = $request['rule_' . $i . $j . '_param1'];
                                        $rule->param_2 = $request['rule_' . $i . $j . '_param2'];
                                }
                                $rule->save();
                            }
                        }
                    }
                }
                break;
        }
        return redirect()->route('gamification', ['id' => $engine->gdesign_id])
            ->with('alert_text', 'Condition Successfully Saved')
            ->with('alert_type', 'success');
    }

    private function show_view($engine_id, $condition){
        $engine = Gamification_engine::with('gamification_design.learning_design.resources.resource_conditions.condition.gamification_engine',
                        'gamification_design.learning_design.resources.resource_type',
                        'gamification_design.learning_design.resources.redeemable_rewards.reward.gamification_engine')
                    ->where('id',$engine_id)->first();

        //$action_types = Action_type::with('resource_types','rule_types')->get();
        //$rule_types   = Rule_type::with('action_types')->get();

        $condition_types = Condition_type::all();
        $actions_by_res  = Resource_type::with('action_types')->get();
        $rules_by_act    = Action_type::with('rule_types')->get();
        $rule_types      = Rule_type::all();


        //return $condition;
        return view('condition.layout',
                       ['engine'          => $engine,
                        'condition'       => $condition,
                        'condition_types' => $condition_types,
                        'actions_by_res'  => $actions_by_res,
                        'rules_by_act'    => $rules_by_act,
                        'rule_types'      => $rule_types,
                       ]);
    }

    public function create($engine_id){
        return $this->show_view($engine_id,null);
    }

    /**
     * Show the application dashboard.
     * @return \Illuminate\Http\Response
     */
    public function edit($engine_id, $condition_id)
    {
        $condition_info = Condition::where('id', $condition_id)->first();


        switch($condition_info->condition_type_id){
            case(1): // Resource Condition
                 $condition_info = Condition::with( 'resource_conditions.resource.resource_type',
                                                        'resource_conditions.actions.action_type.resource_types',
                                                        'resource_conditions.actions.rules.rule_type.action_types')
                            ->where('id',$condition_id)->first();
                break;
            case(2): // Group Condition
                $condition_info = Condition::with(  'group_conditions')
                    ->where('id',$condition_id)->first();
                break;
            case(3): // Reward Condition
                $condition_info = Condition::with(  'reward_conditions.reward_types')
                    ->where('id',$condition_id)->first();
                break;
        }
        $condition_types = Condition_type::get();
        return $this->show_view($engine_id,$condition_info);
    }

    public function delete($engine_id, $condition_id){
        try{
            $condition = Condition::where('id', $condition_id)->first();
            $condition->delete();
        }catch (Exception $e){
            //TODO: write reason to log
            return Redirect::back()->with('msg', 'Error deleting condition');
        }
        return Redirect::back()
            ->with('alert_text', 'Condition Successfully Removed')
            ->with('alert_type', 'success');
    }

}
