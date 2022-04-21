<?php

namespace App\Http\Controllers;

use App\Classes\Curl;
use App\Classes\Name_id;

use App\Models\Deploy_type;
use App\Models\Module;
use App\Models\Resource_condition;
use App\Models\Resource_type;
use App\Models\Resource;
use App\Models\Learning_design_access;
use App\Models\Learning_design;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class DesignController extends Controller{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct(){

        $this->middleware('auth');
    }

    private function show_view($ld){
        $rt = Resource_type::get();
        return view('design.layout', ['ld' => $ld, 'rtypes' => $rt]);
    }

    public function create(){
        return $this->show_view(null);
    }

    public function edit($ld_id){

        if($ld_id > -1) {
            $ld = Learning_design::with('resources.resource_type',
                                                 'modulesobj',
                                                 'gamification_designs.gamification_deploys')
                ->where('learning_designs.id', $ld_id)->first();

            $ld_access = Learning_design_access::where('learning_id', $ld->id)
                ->where('user_id', Auth::user()->id)->first();

            //TODO: @H create 403-forbidden view and code for exception handler
            if(!$ld_access) abort(404);
        }else
            abort(404);

        /*TODO: hack to keep consistent the values ---> Alex necesita comentar esto!! por qué se puso??*/
        $ld->rows    = $ld->resources->max('row');
        $ld->modules = max($ld->modulesobj->count(), $ld->resources->max('module'));

        return $this->show_view($ld);
    }

    public function save(Request $request){
        /* 0. get user data */

        $user = Auth::user();

        /* 1. retrieve the course */
        $ld_id = $request->course_id;
        $ld = Learning_design::with('resources.resource_type')->where('id', $ld_id)->first();
        if(!$ld){
            /* 1.1 create new if it does not exists */
            $ld = new Learning_design();

            $ld->creator_id = $user->id;
            $ld->course_name = $request['course_name'];
            $ld->rows = $request->ld_rows;
            $ld->modules = $request->ld_modules;
            $ld->save();
            $ld_id = $ld->id;

            $ldaccess = new Learning_design_access();
            $ldaccess->user_id = $user->id;
            $ldaccess->learning_id = $ld->id;
            $ldaccess->save();

            $resource_platform = new Resource();
            $resource_platform->learning_id = $ld->id;
            $resource_platform->module = 0;
            $resource_platform->row = 0;
            $resource_platform->name = 'Platform';
            $platform_type = Resource_type::where('name','Platform')->first();
            $resource_platform->type_id = $platform_type->id;
            $resource_platform->save();
        }else{
            /* 1.2.1 check if the user has access to the course */
            $access = Learning_design_access::where('user_id', Auth::user()->id)->where('learning_id', $ld_id)->first();
            if(!$access) return "forbidden";

            /* 1.2.2 update course name */
            $ld->course_name = $request['course_name'];
            $ld->rows = $request->ld_rows;
            $ld->modules = $request->ld_modules;
            $ld->save();
        }


        $modules = $request['modules_array'];
        $allres = Resource::with('moduleobj')->where('learning_id', $ld_id)->where('module', '!=', 0)->orderBy('module')->get();
        $allmod = $allres->unique('module')->values()->all();

        foreach($allmod as $mod){

            $index = $mod->module;

            $module = Module::firstOrNew(['position' => $index, 'learning_id' => $ld_id]);
            $module->position = $index;
            $module->name = $modules[$index-1]['name'];
            $module->learning_id = $ld_id;
            $module->save();

            $xres = $allres->where('module', $index);
            foreach($xres as $res){
                $res->module_id = $module->id;
                $res->save();
            }
        }

        /* 4. update or insert new resources */
        foreach(collect($request['resource_array']) as $res){

            $dbres = Resource::with('moduleobj')->where('learning_id', $ld_id)->where('module', $res['module'])->where('row', $res['row'])->first();

            if(!$dbres){
                $dbres              = new Resource();
                $dbres->row         = $res['row'];
                $dbres->module      = $res['module'];
                $dbres->learning_id = $ld_id;
            }

            //TODO: Retrieve all the resources once and manage them instead of retrieving them every time
            $type_id = Resource_type::where('name', $res['rtype'])->select('id')->first();

            if(!$type_id) {
                $dbres->delete();
            }else {
                $dbres->name = substr($res['name'], 0, 200);
                $dbres->type_id = $type_id->id;
                $dbres->save();
            }
        }

        return "ok";
    }

    public function delete($ld_id){
        try{
            $ld = Learning_design::where('id', $ld_id)->first();
            //return ['object' => $ld, 'id' => $ld_id];
            $ld->delete();
        }catch (Exception $e){
            //TODO: write reason to log
            return Redirect::back()->with('msg', 'Error deleting learning design');
        }
        return redirect()->route('home')
            ->with('alert_text', 'Learning Design Successfully Removed')
            ->with('alert_type', 'success');
    }
}
