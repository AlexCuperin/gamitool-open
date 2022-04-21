<?php

namespace App\Http\Controllers\Moodle;

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
use Illuminate\Support\Collection;
use Ozq\MoodleClient\Clients\Adapters\RestClient;
use Ozq\MoodleClient\Connection;

class ImportControllerMoodle extends ImportController {
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

    private function connect_moodle(String $site, String $token, String $function, Array $params){
        $connection = new Connection($site, $token);
        $client = new RestClient($connection);
        $response = $client->sendRequest($function, $params);
        return $response;
    }

    public function get_courses(Request $request){

        $token = $request['bearer'];
        $site  = $request['instance_name'];

        $courses = $this->connect_moodle($site, $token, 'core_course_get_courses',[]);

        $i = 0;
        foreach($courses as $course) {
            $info_course[$i] = new Name_id();
            $info_course[$i]->name = $course['fullname'];
            $info_course[$i]->id = $course['id'];
            $i++;
        }

        return $info_course;
    }

    public function convert_resource_type($type_instance, Collection $rt){
        $res_type = ['name' => 'no name', 'id' => -1];
        switch($type_instance){
            case('page'):
                $res_type = $rt->where('name', 'Content Page')->first();
                break;
            case('lesson'):
                $res_type = $rt->where('name', 'Content Page')->first();
                break;
            case('forum'):
                $res_type = $rt->where('name', 'Discussion Forum')->first();
                break;
            case('quiz'):
                $res_type = $rt->where('name', 'Quiz')->first();
                break;
            case('assign'):
                $res_type = $rt->where('name', 'Assignment')->first();
                break;
            case('workshop'):
                $res_type = $rt->where('name', 'Peer Review')->first();
                break;
            case('wiki'):
                $res_type = $rt->where('name', 'Wiki')->first();
                break;
            case('resource'):
                $res_type = $rt->where('name', 'File')->first();
                break;
            case('url'):
                $res_type = $rt->where('name', 'External URL')->first();
                break;
            case('lti'):
                $res_type = $rt->where('name', 'External Tool')->first();
                break;
        }
        return $res_type;
    }

    public function get_resources(Request $request){

        $token     = $request['bearer'];
        $site      = $request['instance_name'];
        $course_id = $request['course_id'];

        //pedimos los módulos y actividades
        $modules = $this->connect_moodle($site, $token, 'core_course_get_contents',['courseid' => $course_id]);

        //sacamos de nuestra DB los tipos de actividades soportados
        $rt = Resource_type::all();

        $i = 0;
        $info_module = array();
        foreach($modules as $module) {
            $info_module[$i] = new Module();
            $info_module[$i]->name = $module['name'];
            $info_module[$i]->position = $module['section']+1;
            $j = 0;
            foreach($module['modules'] as $item) {
                $res_type = $this->convert_resource_type($item['modname'], $rt);
                //Comprobamos si el tipo de actividad o recurso NO convertible a GamiTool
                //@Alex TODO: Pasamos también la información del id_instance por si en el futuro lo queremos guardar
                if($res_type['id'] != -1) {
                    $info_module[$i]->resources[$j] =
                        ['name' => $item['name'], 'id_instance' => $item['instance'], 'type_name' => $res_type['name'], 'type_id' => $res_type['id']];
                    $j++;
                }
            }
            $i++;
        }

        return $info_module;
    }

}
