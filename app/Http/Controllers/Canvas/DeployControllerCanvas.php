<?php

namespace App\Http\Controllers\Canvas;

use App\Classes\Curl;
use App\Classes\Name_id;
use App\Classes\User_access;

use App\Http\Controllers\DeployController;
use App\Models\Deploy_type;
use App\Models\Gamification_deploy;
use App\Models\Gamification_design;
use App\Models\Redeemable_reward;
use App\Models\Resource_deploy;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ozq\MoodleClient\Clients\Adapters\RestClient;
use Ozq\MoodleClient\Connection;


class DeployControllerCanvas extends DeployController
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

    public function get_courses($gld_id, Request $request){

            $token = "Authorization: Bearer ".$request['bearer'];
            $site  = $request['instance_name'];

            $cURL = new Curl($token, $site);
            $courses = $cURL->get("/courses");
            $cURL->closeCurl();

            $i = 0;
            foreach($courses as $course) {
                $info_course[$i] = new Name_id();
                if (($course->enrollments[0]->type === "teacher")||($course->enrollments[0]->type === "designer")){
                    $info_course[$i]->name = $course->name;
                    $info_course[$i]->id = $course->id;
                }
                $i++;
            }

        return $info_course;
    }

    private function general_filter($array){
        $i = 0;
        $aux = false;
        foreach($array as $elem) {
            $object[$i] = new Name_id();
            $object[$i]->name = $elem->name;
            $object[$i]->id = $elem->id;
            $i++;
            $aux = true;
        }
        if ($aux === true) return $object;
        else               return;
    }

    private function page_filter($pages , $role){
        $i = 0;
        $aux = false;
        $role = (string)$role;
        foreach($pages as $page) {
            $role_x = (string)$page->editing_roles;
            if (strpos($role_x,$role)!==false) {
                $object[$i] = new Name_id();
                $object[$i]->name = $page->title;
                $object[$i]->id = $page->page_id;
                $i++;
                $aux = true;
            }
        }
        if($aux === true)     return $object;
        else                  return;
    }

    private function forum_filter($forums){
        $i = 0;
        $aux = false;
        foreach($forums as $forum) {
            $object[$i] = new Name_id();
            $object[$i]->name = $forum->title;
            $object[$i]->id = $forum->id;
            $i++;
            $aux = true;
        }
        if ($aux === true) return $object;
        else               return;
    }

    private function file_filter($files){
        $i = 0;
        $aux = false;
        foreach($files as $file) {
            $object[$i] = new Name_id();
            $object[$i]->name = $file->filename;
            $object[$i]->id = $file->id;
            $i++;
            $aux = true;
        }
        if ($aux === true) return $object;
        else               return;
    }

    private function peer_review_filter($assignments){
        $i = 0;
        $aux = false;
        foreach($assignments as $assignment) {
            if ($assignment->peer_reviews == true) {
                $object[$i] = new Name_id();
                $object[$i]->name = $assignment->name;
                $object[$i]->id = $assignment->id;
                $i++;
                $aux = true;
            }
        }
        if ($aux === true) return $object;
        else               return;

    }

    public function get_resources($gld_id, Request $request){

            $token     = "Authorization: Bearer ".$request['bearer'];
            $site      = $request['instance_name'];
            $course_id = $request['course_id'];

            $cURL = new Curl($token, $site);
            $row_assignments        = $cURL->get("/courses/" . $course_id . "/assignments");
            $row_pages              = $cURL->get("/courses/" . $course_id . "/pages");
            $row_discussion_forums  = $cURL->get("/courses/" . $course_id . "/discussion_topics");
            $row_external_tools     = $cURL->get("/courses/" . $course_id . "/external_tools");
            //$row_external_urls      = $cURL->get("/courses/" . $course_id . "/items"); // THERE IS NO API FOR IT!
            $row_files              = $cURL->get("/courses/" . $course_id . "/files");
            $row_quizzes            = $cURL->get("/courses/" . $course_id . "/quizzes");
            $cURL->closeCurl();

            $course["assignments"]    = $this->general_filter($row_assignments);
            $course["content_pages"]  = $this->page_filter($row_pages,"teachers");
            $course["forums"]         = $this->forum_filter($row_discussion_forums);
            $course["external_tools"] = $this->general_filter($row_external_tools);
            //$course->external_urls  = $this->filter($row_external_urls);
            $course["files"]          = $this->file_filter($row_files);
            $course["peer_reviews"]   = $this->peer_review_filter($row_assignments);
            $course["quizzes"]        = $this->forum_filter($row_quizzes);
            $course["wikis"]          = $this->page_filter($row_pages,"students");
            $course["3DVW"]           = $course["external_tools"];

        return $course;
    }

    public function deploy($gld_id, Request $request){

        $token     = "Authorization: Bearer ".$request['bearer'];
        $site      = $request['instance_name'];
        $platform  = $request['instance_type'];
        $course_name = $request['course_name'];
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
        $gdeploy->course_name      = $course_name;
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

        $cURL = new Curl($token, $site);

        $url = url('/course/'.$gdeploy->id);

        //----------Creación de herramienta externa
        $external_tool = $cURL->post("/courses/". $course_id . "/external_tools", array(

                    "external_tool" => array(
                        "name"           => $tab_name,
                        "description"    => $descript,
                        "privacy_level"  => "public",
                        "consumer_key"   => config('lms.handshake'),
                        "shared_secret"  => "secret",
                        "workflow_state" => "public",
                        "url"            => $url,
                    "account_navigation" => array(
                        "url"              => $url,
                        "text"             => $tab_name,
                        "label"            => $tab_name,
                        "enabled"          => "true",
                        "selection_width"  => "800",
                        "selection_height" => "800"
                    ),
                    "course_navigation" => array(
                        "url"              => $url,
                        "text"             => $tab_name,
                        "label"            => $tab_name,
                        "enabled"          => "true",
                        "selection_width"  => "800",
                        "selection_height" => "800"
                    ),
                    "user_navigation" => array(
                        "url"              => $url,
                        "text"             => $tab_name,
                        "label"            => $tab_name,
                        "enabled"          => "true",
                        "selection_width"  => "800",
                        "selection_height" => "800"
                    ),
                    "not_selectable" => "false"
                )
        ));

        $cURL->closeCurl();
        $gdeploy->external_tool_id = $external_tool[0]->id;
        $gdeploy->save();

        return "DEPLOY OK";
    }

    public function add_https($string){
        return "https://".$string;
        //return "http://".$string;
    }

    public function remove($gdeploy){

        $token              = $gdeploy->bearer;
        $course_id          = $gdeploy->course_id;
        $external_tool_id   = $gdeploy->external_tool_id;
        $url = $this->add_https($gdeploy->instance_url."/api/v1/courses/" . $course_id . "/external_tools/" . $external_tool_id);

        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array($token, "Content-Type: application/x-www-form-urlencoded"));

            $info_back = curl_exec($ch);
            curl_close($ch);

        }catch(ErrorException $e){

        }
        return $info_back;

    }
}
