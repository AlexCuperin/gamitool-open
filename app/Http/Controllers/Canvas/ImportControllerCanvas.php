<?php

namespace App\Http\Controllers\Canvas;

use App\Classes\Curl;
use App\Classes\Name_id;
use App\Classes\Module;
use App\Http\Controllers\ImportController;
use App\Models\Deploy_type;
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

class ImportControllerCanvas extends ImportController {
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct(){

        $this->middleware('auth');
    }

    public function get_courses(Request $request){

        $token = "Authorization: Bearer ".$request['bearer'];
        $site  = $request['instance_name'];

        $cURL = new Curl($token, $site);
        $courses = $cURL->get("/courses");
        $cURL->closeCurl();

        $i = 0;
        foreach($courses as $course) {
            $info_course[$i] = new Name_id();
            if (($course->enrollments[0]->type == "teacher")||($course->enrollments[0]->type == "designer")){
                $info_course[$i]->name = $course->name;
                $info_course[$i]->id = $course->id;
            }
            $i++;
        }

        return $info_course;
    }

    public function convert_resource_type($resource, $token, $site, $course_id, $rt){
        $res_type = ['name' => 'no name', 'id' => -1];
        switch($resource->type){
            case('Page'):
                $cURL = new Curl($token, $site);
                $page = $cURL->get("/courses/".$course_id."/pages/".$resource->page_url);
                $cURL->closeCurl();
                if(strpos($page[0]->editing_roles, "students") == false){
                    $res_type = $rt->where('name', 'Content Page')->first();
                }else{
                    $res_type = $rt->where('name', 'Wiki')->first();
                }
                break;
            case('Discussion'):
                $res_type = $rt->where('name', 'Discussion Forum')->first();
                break;
            case('Quiz'):
                $res_type = $rt->where('name', 'Quiz')->first();
                break;
            case('Assignment'):
                $cURL = new Curl($token, $site);
                $assignment = $cURL->get("/courses/".$course_id."/assignments/".$resource->content_id);
                $cURL->closeCurl();
                if($assignment[0]->peer_reviews == false){
                    $res_type = $rt->where('name', 'Assignment')->first();
                }else{
                    $res_type = $rt->where('name', 'Peer Review')->first();
                }
                break;
            case('File'):
                $res_type = $rt->where('name', 'File')->first();
                break;
            case('ExternalUrl'):
                $res_type = $rt->where('name', 'External URL')->first();
                break;
            case('ExternalTool'):
                $res_type = $rt->where('name', 'External Tool')->first();
                break;
        }
        return $res_type;
    }

    public function get_resources(Request $request){

        $token     = "Authorization: Bearer ".$request['bearer'];
        $site      = $request['instance_name'];
        $course_id = $request['course_id'];

        //pedimos los módulos y actividades
        $cURL = new Curl($token, $site);
        $modules        = $cURL->get("/courses/".$course_id."/modules?include[]=items");
        $cURL->closeCurl();

        //sacamos de nuestra DB los tipos de actividades soportados
        $rt = Resource_type::all();

        $i = 0;
        $info_module = array();
        foreach($modules as $module) {
            $info_module[$i] = new Module();
            $info_module[$i]->name = $module->name;
            $info_module[$i]->position = $module->position;
            $j = 0;
            foreach($module->items as $item) {
                $name = "unnamed";
                $id = 0;
                if (array_key_exists('name',$item)){
                    $name = $item->name;
                } elseif (array_key_exists('title',$item)){
                    $name = $item->title;
                }

                if (array_key_exists('content_id',$item)){
                    $id = $item->content_id;
                } elseif (array_key_exists('page_url',$item)){
                    $id = $item->page_url;
                }
                $res_type = $this->convert_resource_type($item, $token, $site, $course_id, $rt);
                //Comprobamos si el tipo de actividad o recurso NO convertible a GamiTool
                //@Alex TODO: Pasamos también la información del id_instance por si en el futuro lo queremos guardar
                if($res_type['id'] != -1) {
                    $info_module[$i]->resources[$j] =
                        ['name' => $name, 'id_instance' => $id, 'type_name' => $res_type->name, 'type_id' => $res_type->id];
                    $j++;
                }
            }
            $i++;
        }

        return $info_module;
    }
}
