<?php

namespace App\Http\Controllers;

use App\Models\Gamification_design;
use App\Models\Gamification_design_access;
use App\Models\Gamification_engine;
use App\Models\Learning_design;
use App\Models\Reward_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\View\Engines\Engine;
use Illuminate\Support\Facades\Redirect;

class GamificationController extends Controller
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

    public function create(Request $request){

        $new_gam = new Gamification_design();
        $new_gam->name = $request['name_gamification'];
        $new_gam->learning_id = $request['learning_id'];
        $new_gam->creator_id = Auth::user()->id;
        $new_gam->save();

        $new_access = new Gamification_design_access();
        $new_access->user_id = Auth::user()->id;
        $new_access->gamification_id = $new_gam->id;
        $new_access->save();

        return redirect()->route('gamification', $new_gam->id)
            ->with('alert_text', 'Gamification Learning Design Successfully Created')
            ->with('alert_type', 'success');
    }

    public function rename(Request $request){
        $gamification = Gamification_design::where('id',$request['gld_id'])->first();
        $gamification->name = $request['name_gamification'];
        $gamification->save();

        return redirect()->route('home')
            ->with('alert_text', 'Gamification Learning Design Successfully Updated')
            ->with('alert_type', 'success');
    }

    // TODO: check this method, seems to be buggy
    public function edit(Request $request){

        $gamification = Gamification_design::where('id',$request['gld_id'])->first();
        $gamification->name = $request['name_gamification'];
        $gamification->learning_id = $request['learning_id'];
        $gamification->save();

        return redirect()->route('home')
            ->with('alert_text', 'Gamification Learning Design Successfully Updated')
            ->with('alert_type', 'success');
    }

    /**
     * Show the application dashboard.
     * @return \Illuminate\Http\Response
     */
    public function see($gam_id)
    {


        $gld = Gamification_design::
                with('learning_design.resources.resource_type',
                             'learning_design.resources.resource_conditions.condition.gamification_engine',
                             'learning_design.resources.redeemable_rewards.reward.gamification_engine',

                             'gamification_engines.rewards.reward_type',
                             'gamification_engines.rewards.redeemable_rewards.resource',
                             'gamification_engines.conditions.condition_type',
                             'gamification_engines.conditions.resource_conditions.resource',

                             'gamification_deploys')
                ->where('id',$gam_id)->first();

        $alert_type = '';
        $alert_text = '';

        if (count($gld->gamification_deploys) >= 1){

            $msg = 'Attention! You are editing a gamification design with ' . count($gld->gamification_deploys);
            if (count($gld->gamification_deploys) == 1) $msg = $msg . ' active deploy. Changes might affect it.';
            else $msg = $msg . ' active deploys. Changes may affect them.';

            $alert_type = 'warning';
            $alert_text = $msg;
        }

        return view('gamification.layout', ['gld' => $gld], compact('alert_text','alert_type'));
    }

    public function delete($gld_id){
        try{
            $gld = Gamification_design::where('id', $gld_id)->first();
            $gld->delete();
        }catch (Exception $e){

            return redirect()->route('home')
                ->with('alert_text', 'Error Removing the Gamification Learning Design')
                ->with('alert_type', 'fail');
        }
        return redirect()->route('home')
            ->with('alert_text', 'Gamification Learning Design Successfully Removed')
            ->with('alert_type', 'success');
    }

    public function create_engine(Request $request){

        $new_engine = new Gamification_engine();
        $new_engine->gdesign_id = $request['gld_engine'];
        $new_engine->name = $request['name_engine'];
        $new_engine->description = $request['description_engine'];
        $new_engine->condition_op = $request['condition_engine'];

        $new_engine->save();

        return redirect()->route('gamification', $request['gld_engine'])
            ->with('alert_text', 'Association Successfully Created')
            ->with('alert_type', 'success');
        //return $this->see($new_engine->gdesign_id);
    }

    public function edit_engine(Request $request){

        $engine = Gamification_engine::where('id',$request['engine_id'])->first();

        if($request['name_engine']) $engine->name = $request['name_engine'];
        if($request['description_engine']) $engine->description = $request['description_engine'];
        if($request['condition_engine']) $engine->condition_op = $request['condition_engine'];

        $engine->save();

        return Redirect::back()
            ->with('alert_text', 'Association Successfully Edited')
            ->with('alert_type', 'success');
        //return $this->see($engine->gdesign_id);
    }

    public function delete_engine($gam_id, $engine_id){
        try{
            $engine = Gamification_engine::where('id', $engine_id)->first();
            $engine->delete();
        }catch (Exception $e){
            //TODO: write reason to log
            return Redirect::back()->with('msg', 'Error deleting engine');
        }
        return Redirect::back()
            ->with('alert_text', 'Association Successfully Removed')
            ->with('alert_type', 'success');
    }
}
