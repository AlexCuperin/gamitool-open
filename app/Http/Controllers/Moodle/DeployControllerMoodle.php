<?php

namespace App\Http\Controllers\Moodle;

use App\Classes\Curl;
use App\Classes\Name_id;
use App\Classes\User_access;

use App\Http\Controllers\DeployController;
use App\Models\Deploy_type;
use App\Models\Gamification_deploy;
use App\Models\Gamification_design;
use App\Models\Redeemable_reward;
use App\Models\Resource_deploy;
use App\Models\Resource_type;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ozq\MoodleClient\Clients\Adapters\RestClient;
use Ozq\MoodleClient\Connection;


class DeployControllerMoodle extends DeployController
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

    private function connect_moodle(String $site, String $token, String $function, Array $params){
        $connection = new Connection($site, $token);
        $client = new RestClient($connection);
        $response = $client->sendRequest($function, $params);
        return $response;
    }


    public function get_courses($gld_id, Request $request){

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

    private function general_filter($array){
        $i = 0;
        $aux = false;
        foreach($array as $elem) {
            $object[$i] = new Name_id();
            $object[$i]->name = $elem['name'];
            $object[$i]->id = $elem['id'];
            $i++;
            $aux = true;
        }
        if ($aux === true) return $object;
        else               return;
    }

    public function get_resources($gld_id, Request $request){

            $token     = $request['bearer'];
            $site      = $request['instance_name'];
            $course_id = $request['course_id'];


            $row_assignments        = $this->connect_moodle($site, $token, 'mod_assign_get_assignments',['courseids'=>array($course_id)]);
            $row_quizzes            = $this->connect_moodle($site, $token, 'mod_quiz_get_quizzes_by_courses',['courseids'=>array($course_id)]);
            $row_pages              = $this->connect_moodle($site, $token,'mod_page_get_pages_by_courses', ['courseids'=>array($course_id)]);
            $row_discussion_forums  = $this->connect_moodle($site, $token,'mod_forum_get_forums_by_courses', ['courseids'=>array($course_id)]);
            //$row_external_tools     = // THERE IS NO API FOR IT!
            $row_external_urls      = $this->connect_moodle($site, $token,'mod_url_get_urls_by_courses', ['courseids'=>array($course_id)]);
            //$row_files              = $cURL->get("/courses/" . $course_id . "/files");
            $row_peer_reviews       = $this->connect_moodle($site, $token,'mod_workshop_get_workshops_by_courses', ['courseids'=>array($course_id)]);
            $row_wikis              = $this->connect_moodle($site, $token,'mod_wiki_get_wikis_by_courses', ['courseids'=>array($course_id)]);

            $course["assignments"]    = $this->general_filter($row_assignments['courses'][0]['assignments']);
            $course["quizzes"]        = $this->general_filter($row_quizzes['quizzes']);
            $course["content_pages"]  = $this->general_filter($row_pages['pages']);
            $course["forums"]         = $this->general_filter($row_discussion_forums);
            //$course["external_tools"] = $this->general_filter($row_external_tools);
            $course["external_urls"]  = $this->general_filter($row_external_urls['urls']);
            //$course["files"]          = $this->file_filter($row_files);
            $course["peer_reviews"]   = $this->general_filter($row_peer_reviews['workshops']);
            $course["wikis"]          = $this->general_filter($row_wikis['wikis']);
            $course["3DVW"]           = $course["external_urls"];

        return $course;
    }

    public function deploy($gld_id, Request $request){

        $token     = $request['bearer'];
        $site      = $request['instance_name'];
        $platform  = $request['instance_type'];
        $course_id = $request['course_id'];
        $resources = $request['resources']; // [{ resource_id: id, instance_res_id: id}];
        $tab_name  = $request['tab_name'];
        $descript  = $request['description'];

        error_log('-----------------------------');
        error_log(print_r($resources, true));
        error_log('-----------------------------');

        $gdeploy = new Gamification_deploy();
        $gdeploy->gdesign_id       = $gld_id;
        $gdeploy->instance_type_id = Deploy_type::where('name', $platform)->first()->id;
        $gdeploy->instance_url     = $site;
        $gdeploy->course_id        = $course_id;
        $gdeploy->creator_id       = Auth::user()->id;
        $gdeploy->bearer           = $token;
        $gdeploy->save();

        foreach($resources as $res){
            $rdeploy = new Resource_deploy();
            $rdeploy->deploy_id            = $gdeploy->id;
            $rdeploy->resource_id          = $res['resource_id'];
            $rdeploy->instance_resource_id = $res['instance_res_id'];
            $rdeploy->save();
        }

        $url = url('/course/'.$gdeploy->id);

        $courses = $this->connect_moodle($site, $token,'local_wstemplate_hello_world',
            ['course_id' => $course_id,
            'section_name' => $tab_name,
            'section_description' => $descript,
            'tool_url' => $url,
            'resource_key' => config('lms.handshake'),
            'password' => 'secret']);

        return $courses;
    }
}
