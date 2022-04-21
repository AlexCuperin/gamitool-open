<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Badge_suite;
use App\Models\Gamification_engine;
use App\Models\Level;
use App\Models\Point;
use App\Models\Redeemable_reward;
use App\Models\Reward;
use App\Models\Reward_type;
use App\Models\Resource;
use App\Models\Resource_type;
use App\Models\Learning_design;
use App\Models\Rr_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class RewardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function show_view($engine_id, $reward){
        $rt = Reward_type::get();

        $badge_suites = Badge_suite::get();
        $engine = Gamification_engine::with('gamification_design.learning_design.resources.resource_conditions.condition.gamification_engine',
                                            'gamification_design.learning_design.resources.resource_type',
                                            'gamification_design.learning_design.resources.redeemable_rewards.reward.gamification_engine')
            ->where('id',$engine_id)->first();

        $priv_by_res = Resource_type::with('rr_types')->get();

        $rr_types = Rr_type::get();

        return view('reward.layout', [ 'engine' => $engine,
                                            'reward' => $reward,
                                            'reward_types' => $rt,
                                            'badge_suites' => $badge_suites,
                                            'priv_by_res' => $priv_by_res,
                                            'rr_types' => $rr_types,
                    ]);
    }

    public function create($engine_id){
        return $this->show_view($engine_id,null);
    }

    /**
     * Show the application dashboard.
     * @return \Illuminate\Http\Response
     */
    public function edit($engine_id, $reward_id)
    {
        $reward_info = Reward::with('Reward_type')
                ->where('id', $reward_id)->first();

        switch($reward_info->reward_type->name){
            case('Redeemable Rewards'):
                 $reward_info = Reward::with('redeemable_rewards.rr_type.resource_types',
                                                      'redeemable_rewards.resource',
                                                      'reward_type')
                            ->where('id',$reward_id)->first();
                break;
            case('Points'):
                $reward_info = Reward::with('points',
                                                     'reward_type')
                    ->where('id',$reward_id)->first();
                break;
            case('Levels'):
                $reward_info = Reward::with('levels',
                                                     'reward_type')
                    ->where('id',$reward_id)->first();
                break;
            case('Badges'):
                $reward_info = Reward::with('badges',
                                                     'reward_type')
                    ->where('id',$reward_id)->first();
                break;
        }

        return $this->show_view($engine_id, $reward_info);
    }

    public function add($engine_id, Request $request){
        /*
         * reward_type
         * reward_name
         * reward_image
         * reward_quantity
         * engine_id
         *
         * privilege
         * rr_resource
         * param1
         */

        $engine = Gamification_engine::where('id',$engine_id)->first();

        if($request['reward_id']) {
            $reward = Reward::where('id', $request['reward_id'])->first();
        }else {
            $reward = new Reward();
        }

        $rtype = Reward_type::where('name', $request['reward_type'])->first();
        $reward->reward_type_id = $rtype['id'];
        $reward->name           = $request['reward_name'];
        $reward->url_image      = $request['reward_image'];
        $reward->quantity       = $request['reward_quantity'];
        $reward->engine_id      = $engine_id;

        switch($rtype['name']){
            case('Redeemable Rewards'):
                if($request['concrete_reward_id']) {
                    $concrete_reward = Redeemable_reward::where('id', $request['concrete_reward_id'])->first();
                }else {
                    $concrete_reward = new Redeemable_reward();
                }
                $concrete_reward->resource_id = $request['rr_resource'];
                $concrete_reward->rr_type_id  = $request['privilege'];
                $concrete_reward->param_1     = $request['param1'];
                break;
            case('Points'):
                if($request['concrete_reward_id']) {
                    $concrete_reward = Point::where('id', $request['concrete_reward_id'])->first();
                }else {
                    $concrete_reward = new Point();
                }
                break;
            case('Levels'):
                if($request['concrete_reward_id']) {
                    $concrete_reward = Level::where('id', $request['concrete_reward_id'])->first();
                }else {
                    $concrete_reward = new Level();
                }
                break;
            case('Badges'):
                if ($request['concrete_reward_id']) {
                    $concrete_reward = Badge::where('id', $request['concrete_reward_id'])->first();
                }else {
                    $concrete_reward = new Badge();
                }
                $concrete_reward->suite_id      = $request['badge_suite'];
                $concrete_reward->suite_quality = $request['badge_quality'];
                break;
        }

        // put this save at the last line to make both reward and specific reward to be saved

        $reward->save();
        $concrete_reward->reward_id   = $reward->id;
        $concrete_reward->save();

        return redirect()->route('gamification', ['id' => $engine->gdesign_id])
            ->with('alert_text', 'Reward Successfully Saved')
            ->with('alert_type', 'success');
    }

    public function delete($engine_id, $reward_id){
        try{
            $reward = Reward::where('id', $reward_id)->first();
            $reward->delete();
        }catch (Exception $e){
            //TODO: write reason to log
            return Redirect::back()->with('msg', 'Error deleting reward');
        }
        return Redirect::back()
            ->with('alert_text', 'Reward Successfully Removed')
            ->with('alert_type', 'success');
    }
}
