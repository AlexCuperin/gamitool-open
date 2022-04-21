<?php

namespace App\Http\Controllers;

use App\Classes\Curl;
use App\Classes\Name_id;
use App\Models\Deploy_type;
use App\Models\Gamification_deploy;
use App\Models\Import_metadata;
use App\Models\Module;
use App\Models\Resource_condition;
use App\Models\Resource_deploy;
use App\Models\Resource_import;
use App\Models\Resource_type;
use App\Models\Resource;
use App\Models\Learning_design_access;
use App\Models\Learning_design;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Ozq\MoodleClient\Clients\Adapters\RestClient;
use Ozq\MoodleClient\Connection;

class ImportController extends Controller{
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

    public function import(){
        if ($_POST['import_origin'] == "lms"){
            $deploy_types = Deploy_type::all();
            return view('import.layout', ['deploy_types' => $deploy_types]);
        } elseif($_POST['import_origin'] == "authoring_tool"){
            return $this->show_view(null);
        }
        return $this->show_view(null);
    }

    public function get_courses(Request $request){

        switch($request['instance_type']){
            case('Canvas'):
                $controller = App::make('\App\Http\Controllers\Canvas\ImportControllerCanvas');
                return $controller->callAction('get_courses', [$request]);
                break;
            case('Open edX'):
                break;
            case('Moodle'):
                $controller = App::make('\App\Http\Controllers\Moodle\ImportControllerMoodle');
                return $controller->callAction('get_courses', [$request]);
                break;
        }
        return 'error';
    }

    /*public function convert_resource_type($resource, $token, $site, $course_id){

    }*/

    public function get_resources(Request $request){

        switch($request['instance_type']){
            case('Canvas'):
                $controller = App::make('\App\Http\Controllers\Canvas\ImportControllerCanvas');
                return $controller->callAction('get_resources', [$request]);
                break;
            case('Open edX'):
                break;
            case('Moodle'):
                $controller = App::make('\App\Http\Controllers\Moodle\ImportControllerMoodle');
                return $controller->callAction('get_resources', [$request]);
                break;
        }
        return 'error';
    }

    public function save(Request $request){
        /* 0. get user data */

        $user = Auth::user();

        /* 1. create LD, and access */
        $ld = new Learning_design();

        $ld->creator_id = $user->id;
        $ld->course_name = $request['course_name'];
        $ld->rows = (int)$request['rows'];
        $ld->modules = count($request['modules']);
        $ld->save();
        $ld_id = $ld->id;

        $imported_ld = new Import_metadata();
        $imported_ld->instance_type_id = Deploy_type::where('name', $request['instance_type'])->first()->id;
        $imported_ld->instance_url     = $request['instance_name'];
        $imported_ld->course_id        = $request['course_id'];
        $imported_ld->course_name      = ($request['course_name']) ? $request['course_name'] : null;
        $imported_ld->bearer           = $request['bearer'];
        $imported_ld->learning_id      = $ld_id;
        $imported_ld->save();

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

        /* 2. insert resources */

        $j = 1;
        foreach($request['modules'] as $module){

            $new_module = new Module();
            $new_module->name           = $module['name'];
            $new_module->learning_id    = $ld_id;
            $new_module->position       = $module['position'];
            $new_module->save();

            if(array_key_exists('resources',$module)) {
                $i = 1;
                foreach ($module['resources'] as $resource) {
                    $new_resource = new Resource();
                    $new_resource->learning_id = $ld_id;
                    $new_resource->name = $resource['name'];
                    $new_resource->row = $i;
                    $new_resource->module = $j;
                    $new_resource->module_id = $new_module->id;
                    $new_resource->type_id = $resource['type_id'];
                    $new_resource->save();

                    $resource_import = new Resource_import();
                    $resource_import->resource_id = $new_resource->id;
                    $resource_import->import_id = $imported_ld->id;
                    $resource_import->instance_resource_id = $resource['id_instance'];
                    $resource_import->save();

                    $i++;
                }
            }
            $j++;
        }

        return "ok";
    }
}
