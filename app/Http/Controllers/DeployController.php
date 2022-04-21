<?php

namespace App\Http\Controllers;

use App\Classes\Curl;
use App\Classes\Name_id;
use App\Classes\User_access;

use App\Models\Deploy_type;
use App\Models\Gamification_deploy;
use App\Models\Gamification_design;
use App\Models\Import_metadata;
use App\Models\Redeemable_reward;
use App\Models\Resource_deploy;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Ozq\MoodleClient\Clients\Adapters\RestClient;
use Ozq\MoodleClient\Connection;


class DeployController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function configure($gld_id)
    {

        /*$connection = new Connection('https://moodle.gsic.uva.es/', 'daf1a5f66a47b26e608dd8cb2cb6dbf6'); //'8bec4444f10dc2f86c79c4f3882663a0');
        $client = new RestClient($connection);
        $courses = $client->sendRequest('local_wstemplate_hello_world', ['course_id' => 2,
                                                                                  'section_name' => 'LAST REWARDS',
                                                                                  'section_description' => 'descripcion last',
                                                                                  'tool_url' => 'https://gamitool.gsic.uva.es/course/1',
                                                                                  'resource_key' => 'cxzaQWE.456',
                                                                                  'password' => 'secret']);
        return $courses;*/

        /*$cURL           = new Curl('Authorization: Bearer 9520~TuhSrckjBnbluDOZfIC4DukR1I8e95wj1prNHmQG2AcQePJdPPnfAEDWXaeKDQcB', 'gsic-emic.instructure.com');
        $modules        = $cURL->get("/courses/6/assignments/824");
        $cURL->closeCurl();
        return $modules;*/

        //9520~TuhSrckjBnbluDOZfIC4DukR1I8e95wj1prNHmQG2AcQePJdPPnfAEDWXaeKDQcB

        /*$connection = new Connection('https://moodle.gsic.uva.es/', '9835f47c642cf3f58bc1b73645135f62'); //'8bec4444f10dc2f86c79c4f3882663a0');
        $client = new RestClient($connection);
        $courses = $client->sendRequest('local_wstemplate_hello_world', ['cartridgeurl' => 'https://gamitool.gsic.uva.es/cartridge.xml', 'key' => "cxzaQWE.456", 'secret' => "secret"]);
        return $courses;*/

            /*try {

            /*$url = 'https://gsic-emic.instructure.com/api/v1/courses/4/assignments/617/overrides';
            $method = "POST";
            $data = 'assignment_override[student_ids][]=32';
            $data .= '&assignment_override[title]=PRUEBAAAAAA';
            $data .= '&assignment_override[unlock_at]=2018-02-09T22:53:00Z';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array($token,"Content-Type: application/x-www-form-urlencoded"));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $newoverride = curl_exec($ch);
            curl_close($ch);


        }catch(ErrorException $e){

        }*/


        $gld = Gamification_design::with('learning_design.resources.resource_type',
                                                  'learning_design.resources.resource_conditions.condition.gamification_engine',
                                                  'learning_design.resources.redeemable_rewards.reward.gamification_engine')
            ->where('id',$gld_id)->first();
        $deploy_types = Deploy_type::all();

        return view('deploy.layout', ['gld' => $gld, 'deploy_types' => $deploy_types]);
    }

    public function get_courses($gld_id, Request $request){

        switch($request['instance_type']){
            case('Canvas'):
                $controller = App::make('\App\Http\Controllers\Canvas\DeployControllerCanvas');
                return $controller->callAction('get_courses', [$gld_id, $request]);
                break;
            case('Open edX'):
                break;
            case('Moodle'):
                $controller = App::make('\App\Http\Controllers\Moodle\DeployControllerMoodle');
                return $controller->callAction('get_courses', [$gld_id, $request]);
                break;
        }
        return 'error';
    }

    public function get_resources($gld_id, Request $request){

        switch($request['instance_type']){
            case('Canvas'):
                $controller = App::make('\App\Http\Controllers\Canvas\DeployControllerCanvas');
                return $controller->callAction('get_resources', [$gld_id, $request]);
                break;
            case('Open edX'):
                break;
            case('Moodle'):
                $controller = App::make('\App\Http\Controllers\Moodle\DeployControllerMoodle');
                return $controller->callAction('get_resources', [$gld_id, $request]);
                break;
        }
        return 'error';
    }

    public function deploy_imported(Request $request){

        $gld_id = $request['gld_id'];
        if($request['deploy_imported'] == 'yes'){
            $import = Import_metadata::with('resources','deploy_type')->where('id',$request['import_id'])->first();

            $data = new Request();
            $data['bearer'] = $import->bearer;
            $data['instance_type'] = $import->deploy_type->name;
            $data['instance_name'] = $import->instance_url;
            $data['course_name'] = $import->course_name;
            $data['course_id'] = $import->course_id;
            $data['tab_name'] = "GamiTool"; //Para el futuro: dejar al usuario que elija el nombre
            $data['description'] = "";

            $i = 0;
            foreach($import->resources as $resource){ //[ { resource_id: id, instance_res_id: id}];
                $res[$i]['resource_id'] = $resource->pivot->resource_id;
                $res[$i]['instance_res_id'] = $resource->retrieve_instance_id($import->id);
                $i++;
            }
            $data['resources'] = $res;


            switch($import->deploy_type->name){
                case('Canvas'):
                    $controller = App::make('\App\Http\Controllers\Canvas\DeployControllerCanvas');
                    $result = $controller->callAction('deploy', [$gld_id, $data]);
                    break;
                case('Open edX'):
                    break;
                case('Moodle'):
                    $controller = App::make('\App\Http\Controllers\Moodle\DeployControllerMoodle');
                    return $controller->callAction('deploy', [$gld_id, $data]);
                    break;
            }

            return redirect()->route('home')
                ->with('alert_text', 'Gamification Learning Design Successfully Deployed in ' . $data['instance_type'])
                ->with('alert_type', 'success');

        }else
            return $this->configure($request['gld_id']);
    }


    public function deploy($gld_id, Request $request){

        switch($request['instance_type']){
            case('Canvas'):
                $controller = App::make('\App\Http\Controllers\Canvas\DeployControllerCanvas');
                return $controller->callAction('deploy', [$gld_id, $request]);
                break;
            case('Open edX'):
                break;
            case('Moodle'):
                $controller = App::make('\App\Http\Controllers\Moodle\DeployControllerMoodle');
                return $controller->callAction('deploy', [$gld_id, $request]);
                break;
        }
        return 'error';
    }

    public function delete($deploy_id){
        try{
            $gdeploy = Gamification_deploy::where('id', $deploy_id)->first();
            switch($gdeploy->deploy_types->name){
                case('Canvas'):
                    $controller = App::make('\App\Http\Controllers\Canvas\DeployControllerCanvas');
                    $controller->callAction('remove', [$gdeploy]);
                    break;
                case('Open edX'):
                    break;
                case('Moodle'):
                    $controller = App::make('\App\Http\Controllers\Moodle\DeployControllerMoodle');
                    $controller->callAction('remove', [$gdeploy]);
                    break;
            }
            $gdeploy->delete();
            }catch (Exception $e){
                //TODO: write reason to log
                return Redirect::back()->with('msg', 'Error deleting deploy');
            }

        return redirect()->route('home')
            ->with('alert_text', 'Deployment Successfully Removed from GamiTool and ' . $gdeploy->deploy_types->name)
            ->with('alert_type', 'success');
    }
}
