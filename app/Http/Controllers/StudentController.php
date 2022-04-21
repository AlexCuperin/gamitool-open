<?php

namespace App\Http\Controllers;

use App\Classes\Curl;
use App\Classes\User_access;
use App\Console\Commands\save_activity8;
use App\Models\Deploys\Canvas2018\Student_visit;
use App\Models\Gamification_deploy;
use App\Models\Gamification_design;
use App\Models\Gamification_engine;
use App\Models\Resource_deploy;
use App\Models\Student;
use App\Models\Student_engine;
use App\Models\User;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maknz\Slack\Facades\Slack;
use Psy\Exception\Exception;
use Sheets;
use Google;

class StudentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    //TODO: remove this method after testing
    /*
    public function test(){
        $deploy = Gamification_deploy::where('id',1)->first();
        $gld = Gamification_design::with(
            'gamification_engines.count_requesting_students',
            'gamification_engines.rewards.reward_type',
            'gamification_engines.rewards.badges',
            'gamification_engines.rewarded_students')
            ->where('id',$deploy->gdesign_id)->first();
        $deployrr = Gamification_deploy::where('id',2)->first();
        $gldrr = Gamification_design::with(
            'gamification_engines.count_requesting_students',
            'gamification_engines.rewards.reward_type',
            'gamification_engines.rewards.redeemable_rewards.resource',
            'gamification_engines.rewards.redeemable_rewards.rr_type',
            'gamification_engines.rewarded_students')
            ->where('id', $deployrr->gdesign_id)->first();
        return view('teachers.teacher_view', ['gld' => $gld, 'gldrr'=> $gldrr]);
    }*/

    private function notify_slack_new_assigment($email,$group){
        try{

            $slack_channel = '#asking_for_rewards';
            if(!App::environment('portal')) $slack_channel = '#testing_tests';

            $slack = Slack::to($slack_channel);
            $fields = [
                [
                    "title" => 'Email',
                    "value" => $email,
                    "short" => true,
                ],
                [
                    "title" => 'Group',
                    "value" => $group,
                    "short" => true,
                ],
            ];
            $slack->attach([
                "color" => 'default',
                "fields" => $fields,
            ]);

            $slack->withIcon(':new:')->send(":new: New Student Assigned to Group");

        }catch(Exception $e_slack){
            error_log('error sending new assigment to slack');
            error_log($e_slack->getTraceAsString());
            error_log('----------------------');
        }
    }

    private function notify_slack_new_reward_request($email,$deployid,$engine){
        try{

            $slack_channel = '#asking_for_rewards';
            if(!App::environment('portal')) $slack_channel = '#testing_tests';

            $slack = Slack::to($slack_channel);
            $fields = [
                [
                    "title" => 'Email',
                    "value" => $email,
                    "short" => true,
                ],
                [
                    "title" => 'Deploy id',
                    "value" => $deployid,
                    "short" => true,
                ],
                [
                    "title" => 'Engine Name',
                    "value" => $engine->name,
                    "short" => true,
                ],
                [
                    "title" => 'Engine id',
                    "value" => $engine->id,
                    "short" => true,
                ],
                [
                    "title" => 'Engine Description',
                    "value" => $engine->description,
                    "short" => false,
                ],
            ];
            $slack->attach([
                "color" => 'default',
                "fields" => $fields,
            ]);

            $slack->withIcon(':incoming_envelope:')->send(":incoming_envelope: New Request for Reward");

        }catch(Exception $e_slack){
            error_log('error sending new request to slack');
            error_log($e_slack->getTraceAsString());
            error_log('----------------------');
        }
    }

    private function notify_slack_reward_issued($email, $engine){
        try{

            $slack_channel = '#asking_for_rewards';
            if(!App::environment('portal')) $slack_channel = '#testing_tests';

            $slack = Slack::to($slack_channel);
            $fields = [
                [
                    "title" => 'Email',
                    "value" => $email,
                    "short" => true,
                ],
                [
                    "title" => 'Engine id',
                    "value" => $engine->id,
                    "short" => true,
                ],
            ];
            $slack->attach([
                "color" => 'default',
                "fields" => $fields,
            ]);

            $slack->withIcon(':trophy:')->send(":trophy: Reward Issued");

        }catch(Exception $e_slack){
            error_log('error sending reward issued to slack');
            error_log($e_slack->getTraceAsString());
            error_log('----------------------');
        }
    }

    private function notify_slack_attack($user, $post){
        try{
            $user_gamitool_email    = $user?$user->email:'NOUSER';
            $oauth_consumer_key     = array_key_exists('oauth_consumer_key',         $post)?$post['oauth_consumer_key']         :'NOPOST_consumer_key';
            $roles                  = array_key_exists('roles',                      $post)?$post['roles']                      :'NOPOST_roles';
            $student_id_instance    = array_key_exists('custom_canvas_user_id',      $post)?$post['custom_canvas_user_id']      :'NOPOST_id';
            $student_email_instance = array_key_exists('custom_canvas_user_login_id',$post)?$post['custom_canvas_user_login_id']:'NOPOST_email';
            $student_name_instance  = array_key_exists('lis_person_name_full',       $post)?$post['lis_person_name_full']       :'NOPOST_name';

            $slack_channel = '#medic_errors_v2';
            if(!App::environment('portal')) $slack_channel = '#testing_tests';

            $slack = Slack::to($slack_channel);
            $fields = [
                [
                    "title" => 'User gamitool email',
                    "value" => $user_gamitool_email,
                    "short" => true,
                ],
                [
                    "title" => 'OAC key',
                    "value" => $oauth_consumer_key,
                    "short" => true,
                ],
                [
                    "title" => 'Roles',
                    "value" => $roles,
                    "short" => true,
                ],
                [
                    "title" => 'Student id',
                    "value" => $student_id_instance,
                    "short" => true,
                ],
                [
                    "title" => 'Student email',
                    "value" => $student_email_instance,
                    "short" => true,
                ],
                [
                    "title" => 'Student name',
                    "value" => $student_name_instance,
                    "short" => true,
                ],
            ];
            $slack->attach([
                "color" => 'default',
                "fields" => $fields,
            ]);

            $slack->withIcon(':spiral_note_pad:')->send(":spiral_note_pad: Attack? received in ".App::environment());

        }catch(Exception $e_slack){
            error_log('error sending attack report to slack');
            error_log($e_slack->getTraceAsString());
            error_log('----------------------');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($deploy_id)
    {
        if ( (!array_key_exists('oauth_consumer_key', $_POST)) or
              $_POST['oauth_consumer_key'] != config('lms.handshake')){

            error_log('1.- sending attack report to slack');


            try {
                $user = Auth::user();
                $this->notify_slack_attack($user, $_POST);
            }catch (Exception $e_request){
                error_log('exception calling notify_slack_attack');
                error_log('-----------------------------------------');
                error_log($e_request->getTraceAsString());
                error_log('-----------------------------------------');
            }
            return view('token_expired');
        }

        $deploy = Gamification_deploy::where('id', $deploy_id)->first();

        switch($deploy->instance_type_id){
            //CANVAS
            case(1):
                $user_access = new User_access();
                $user_access->id = $_POST['custom_canvas_user_id'];
                $user_access->email = $_POST['custom_canvas_user_login_id'];
                $user_access->name = $_POST['lis_person_name_full'];
                $user_access->roles = $_POST['roles'];
                break;
            // MOODLE
            case(3):

                error_log(print_r($_POST, true));

                $user_access = new User_access();
                $user_access->id = $_POST['user_id'];
                $user_access->email = $_POST['ext_user_username'];
                $user_access->name = $_POST['lis_person_name_full'];
                $user_access->roles = $_POST['roles'];
                break;
            default:
                $user_access = new User_access();
                $user_access->id = $_POST['user_id'];
                $user_access->email = $_POST['ext_user_username'];
                $user_access->name = $_POST['lis_person_name_full'];
                $user_access->roles = $_POST['roles'];
                break;
        }

        if(strpos($user_access->roles, 'Instructor') !== false){

            /*
            $deploy = Gamification_deploy::where('id',$deploy_id)->first();


            $gld = Gamification_design::with(
                'gamification_engines.count_requesting_students',

                'gamification_engines.rewards.reward_type',
                'gamification_engines.rewards.badges',
                'gamification_engines.rewarded_students')
                ->where('id',$deploy->gdesign_id)->first();
            */

            $gldrr = Gamification_design::with(
                'gamification_engines.count_requesting_students',

                'gamification_engines.rewards.reward_type',
                'gamification_engines.rewards.redeemable_rewards.resource',
                'gamification_engines.rewards.redeemable_rewards.rr_type',
                'gamification_engines.rewarded_students')
                ->where('id', $deploy->gdesign_id)->first();

            /*
            $total_act = DB::table('student_activities')
                ->join('students', 'student_id', 'students.id')
                ->select(DB::raw('gdeploy_id, 
                                         count(reward_1) r1, count(reward_2) r2, count(reward_3) r3, count(reward_4) r4, 
                                         count(reward_5) r5, count(reward_6) r6, count(reward_7) r7, count(reward_8) r8'))
                ->groupBy('gdeploy_id')->get()->keyBy('gdeploy_id');

            $total_sub = DB::table('student_submissions')
                ->join('students', 'student_id', 'students.id')
                ->select(DB::raw('gdeploy_id, 
                                         count(module_0) r1, count(module_1) r2, count(module_1) r3, count(module_2) r4, 
                                         count(module_5) r5, count(module_4) r6, count(module_4) r7, count(module_5) r8,
                                         count(module_3) r9, count(module_6) r10'))
                ->groupBy('gdeploy_id')->get()->keyBy('gdeploy_id');
            */

            foreach($gldrr->gamification_engines as $ge){
                foreach($ge->rewards as $reward){
                    if($reward->reward_type->name == "Redeemable Rewards"){
                        if($reward->redeemable_rewards[0]->rr_type->name == "Instructor Revision"){
                            $assignment_id = $reward->redeemable_rewards[0]->resource->retrieve_instance_id(2);
                            if ($assignment_id) {
                                foreach ($ge->rewarded_students as $student) {
                                    $student->pivot->url_assignment = "https://learn.canvas.net/courses/".$deployrr->course_id."/gradebook/speed_grader?assignment_id=".$assignment_id."#%7B%22student_id%22%3A%22".$student->id_instance."%22%7D";
                                }
                            }
                        }
                    }
                }
            }

            //return view('teachers.teacher_view', ['gld' => $gld, 'gldrr'=> $gldrr, 'total_act'=>$total_act, 'total_sub'=>$total_sub]);
            return view('teachers.lite.teacher_view', ['gldrr'=> $gldrr]);

        } else if (strpos($user_access->roles, 'Learner') !== false) {///Or TA
            
            $student = Student::where('id_instance', $user_access->id)->first();
            //$count = Student::count();
            if (!$student) {
                $student = new Student();
                $student->gdeploy_id = $deploy_id; //$count%($count>Student::THRES_3GROUPS?3:2)+1;
                $student->id_instance = $user_access->id;
                $student->email_instance = $user_access->email;
                $student->name_instance = $user_access->name;
                $student->save();

                //$deploy_id = $student->gdeploy_id;
                //$deploy = Gamification_deploy::where('id',$deploy_id)->first();

                /*
                //Assignment to groups in Canvas for Gamification Forum
                $group4slack = "error"; //this should never be value 'error' at the end
                switch ($student->gdeploy_id){
                    case("1"): //Gamification_B
                        $url = "/groups/" . "4474" . "/memberships"; // "4474"
                        $group4slack = "Badges";
                        break;
                    case("2"): //Gamification_RR
                        $url = "/groups/" . "4475" . "/memberships"; // "4475"

                        $url_extra_content = "/groups/" . "4453" . "/memberships"; //"4453"
                        $data_extra_content = ['user_id'=>$student->id_instance];
                        $add_to_extra_content_group = $this->postCanvasAPI($url_extra_content, $data_extra_content, $deploy);
                        $group4slack = "RRs";
                        break;
                    case("3"): //Gamification_Control
                        $url = "/groups/" . "4452" . "/memberships"; //"4452"
                        $group4slack = "Control";
                        break;
                }

                $data = ['user_id'=>$student->id_instance];
                $add_to_group = $this->postCanvasAPI($url, $data, $deploy);

                try {
                    $this->notify_slack_new_assigment($student->email_instance, $group4slack);
                }catch (Exception $e_request){
                    error_log('exception calling notify_slack_new_assigment');
                    error_log('-----------------------------------------');
                    error_log($e_request->getTraceAsString());
                    error_log('-----------------------------------------');
                }
                */

            }else {
                //$deploy_id = $student->gdeploy_id;
                //$deploy = Gamification_deploy::where('id', $deploy_id)->first();
            }

            $gld = Gamification_design::with('gamification_engines.rewards.reward_type',
                'gamification_engines.rewards.redeemable_rewards.resource',
                'gamification_engines.rewards.redeemable_rewards.rr_type',
                'gamification_engines.rewards.points',
                'gamification_engines.rewards.levels',
                'gamification_engines.rewards.badges',
                'gamification_engines.rewarded_students')
                ->where('id',$deploy->gdesign_id)->first();

            $earned_engines = Student_engine::where('student_id',$student->id)->get();

            $total_earned = 0;
            foreach($gld->gamification_engines as $ge){
                $ge->earned = false;
                foreach($earned_engines as $ee){
                    if ($ge->id === $ee->engine_id){
                        if($ee->issued == 1) {
                            $ge->earned = true;
                            $total_earned++;
                        }
                    }
                }
            }
            $gld->gamification_engines = $gld->gamification_engines->sortBy('earned');

            return view('students.student_view', ['total_earned'=>$total_earned,'gld' => $gld, 'gdeploy_id' => $deploy_id, 'student_id' => $student->id, 'student_name' => $student->name_instance]);
        }

    }
    public function add_https($string){
        return "https://".$string;
        //return "http://".$string;
    }
    public function getCanvasAPI($url, $deploy){
        $cURL = new Curl($deploy->bearer, $deploy->instance_url);
        $canvas_info = $cURL->get($url);
        $cURL->closeCurl();
        return $canvas_info;
    }
    public function postCanvasAPI($url, $data, $deploy){
        try {
            $cURL = new Curl($deploy->bearer, $deploy->instance_url);
            $canvas_info = $cURL->post($url,$data);
            $cURL->closeCurl();
            return $canvas_info;
        }catch(ErrorException $e){
            
        }
    }
    public function formCanvasAPI($method, $url, $data, $deploy){
        $url = $this->add_https($deploy->instance_url."/api/v1".$url);
        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array($deploy->bearer,"Content-Type: application/x-www-form-urlencoded"));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $newoverride = curl_exec($ch);
            //$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            //$curl_errno = curl_errno($ch);

            curl_close($ch);

            return $newoverride;
        }catch(ErrorException $e){

        }
    }

    public function request($gdeploy_id, Request $request)
    {
        //SAVE REQUEST
        $student_request = new Student_engine();
        $student_request->student_id = $request->student_id;
        $student_request->engine_id = $request->engine_id;
        $student_request->issued = false;
        $student_request->save();

        //Check conditions
        //TODO: Sólo implementamos de momento las resource_conditions
        $engine = Gamification_engine::with('conditions.resource_conditions.resource.resource_type',
                                                     'conditions.resource_conditions.actions.action_type',
                                                     'conditions.resource_conditions.actions.rules.rule_type',
                                                     'rewards.reward_type',
                                                     'rewards.points',
                                                     'rewards.levels',
                                                     'rewards.badges',
                                                     'rewards.redeemable_rewards.resource',
                                                     'rewards.redeemable_rewards.rr_type')
            ->where('id',$request->engine_id)->first();

        $student = Student::where('id',$request->student_id)->first();
        $deploy = Gamification_deploy::where('id',$gdeploy_id)->first();

        try {
            $this->notify_slack_new_reward_request($student->email_instance, $gdeploy_id, $engine);
        }catch (Exception $e_request){
            error_log('exception calling notify_slack_new_reward_request');
            error_log('-----------------------------------------');
            error_log($e_request->getTraceAsString());
            error_log('-----------------------------------------');
        }

        $exists = Student_engine::where('student_id', $student->id)
                                ->where('engine_id', $request->engine_id)
                                ->where('issued', true)
                                ->first();
        if($exists) return -2; //TODO consider -2 in ajax method

        foreach($engine->conditions as $condition){
            foreach($condition->resource_conditions as $res_cond){
                $resource = Resource_deploy::where('deploy_id',$gdeploy_id)->where('resource_id',$res_cond->resource->id)->first();
                switch($res_cond->resource->resource_type->name){
                    case('Platform'):
                        foreach($res_cond->actions as $action) {
                            switch($action->action_type->name){
                                case("Invite a friend"):
                                    //TODO
                                    break;
                                case("Log in"):
                                    $pageviews = $this->getCanvasAPI("/users/".$student->id_instance."/page_views",$deploy);
                                    $i = 0;
                                    $logins = array();
                                    foreach($pageviews as $page){
                                        if($page->url == $this->add_https($deploy->instance_url."/?login_success=1")){
                                            $logins[$i] = $page;
                                            $i++;
                                        }
                                    }
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name){
                                            case("Do the action itself"):
                                                if ($logins){
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Do the action several times"):
                                                if (count($logins) >= $rule->param_1){
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach($logins as $login) {
                                                    $login_date = explode("T", $login->created_at);
                                                    if ($login_date[0] < $rule->param_1) {
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach($logins as $login) {
                                                    $login_date = explode("T", $login->created_at);
                                                    if (($login_date[0] > $rule->param_1) && ($login_date[0] < $rule->param_2)) {
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if (($logins) && ($students_rewarded <= $rule->param_1)){
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("At least some group members have to perform the action"):
                                                // TODO
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Log out"):
                                    //TODO
                                    break;
                                case("Mark as done"):
                                    //TODO
                                    break;
                                case("Send message to group"):
                                    ///api/v1/comm_messages --> desautorizado?
                                    break;
                                case("Send message to student"):
                                    $messages = $this->getCanvasAPI("/courses/".$deploy->course_id."/analytics/users/".$student->id_instance."/communication",$deploy);
                                    foreach($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach($messages[0] as $msg_day){
                                                    if($msg_day->studentMessages){
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach($messages[0] as $msg_day){
                                                    if($msg_day->studentMessages){
                                                        $counter = $counter + $msg_day->studentMessages;
                                                        if($counter >= $rule->param_1){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach($messages[0] as $key => $val){
                                                    if ($key < $rule->param_1){
                                                        if($val->studentMessages){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach($messages[0] as $key => $val){
                                                    if (($key > $rule->param_1)&&($key < $rule->param_2)){
                                                        if($val->studentMessages){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach($messages[0] as $msg_day){
                                                        if($msg_day->studentMessages){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Send message to teacher"):
                                    $messages = $this->getCanvasAPI("/courses/".$deploy->course_id."/analytics/users/".$student->id_instance."/communication",$deploy);
                                    foreach($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach($messages[0] as $msg_day){
                                                    if($msg_day->instructorMessages){
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach($messages[0] as $msg_day){
                                                    if($msg_day->instructorMessages){
                                                        $counter = $counter + $msg_day->studentMessages;
                                                        if($counter >= $rule->param_1){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach($messages[0] as $key => $val){
                                                    if ($key < $rule->param_1){
                                                        if($val->instructorMessages){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach($messages[0] as $key => $val){
                                                    if (($key > $rule->param_1)&&($key < $rule->param_2)){
                                                        if($val->instructorMessages){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach($messages[0] as $msg_day){
                                                        if($msg_day->instructorMessages){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Submit"):
                                    $submissions = $this->getCanvasAPI("/courses/".$deploy->course_id."/analytics/users/".$student->id_instance."/assignments",$deploy);
                                    foreach($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach ($submissions as $submission){
                                                    if($submission->submission->submitted_at){
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach ($submissions as $submission){
                                                    if($submission->submission->submitted_at){
                                                        $counter++;
                                                        if($counter >= $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach ($submissions as $submission){
                                                    if($submission->submission->submitted_at){
                                                        $date_submission = explode("T",$submission->submission->submitted_at);
                                                        if($date_submission[0] < $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach ($submissions as $submission){
                                                    if($submission->submission->submitted_at){
                                                        $date_submission = explode("T",$submission->submission->submitted_at);
                                                        if(($date_submission[0] > $rule->param_1)&&($date_submission[0] < $rule->param_2)) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach ($submissions as $submission){
                                                        if($submission->submission->submitted_at){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Update profile information"):
                                    ///users/:user_id/profile
                                    break;
                                case("Upload profile picture"):
                                    ///users/:user_id/avatars
                                    $profile = $this->getCanvasAPI("/users/".$student->id_instance."/profile",$deploy);
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                if (!strpos($profile[0]->avatar_url, '/images/messages/avatar-50.png')){
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Do the action several times"):
                                                // No se puede saber
                                                break;
                                            case("Do the action before a specific date"):

                                                break;
                                            case("Do the action between a specific time frame"):

                                                break;
                                            case("Be one of the first participants doing the action"):

                                                break;
                                            case("At least some group members have to perform the action"):
                                                // TODO
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Visit"):
                                    $activity = $this->getCanvasAPI("/courses/".$deploy->course_id."/analytics/users/".$student->id_instance."/activity",$deploy);
                                    foreach($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                //NO SENSE
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach ($activity[0]->page_views as $pages_per_day) {
                                                    $counter = $counter + $pages_per_day;
                                                    if ($counter >= $rule->param_1) {
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                //NO SENSE
                                                break;
                                            case("Do the action between a specific time frame"):
                                                //NO SENSE
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                //NO SENSE
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                            }
                        }
                        break;
                    case('Assignment'):
                        foreach($res_cond->actions as $action) {
                            switch($action->action_type->name){
                                case("Submit"):
                                    $submission = $this->getCanvasAPI("/courses/".$deploy->course_id."/assignments/".$resource->instance_resource_id."/submissions/".$student->id_instance."?include=submission_history", $deploy);
                                    foreach($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                if ($submission[0]->workflow_state != "unsubmitted") {
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach($submission[0]->submission_history as $submission) {
                                                    $counter++;
                                                    if ($counter >= $rule->param_1) {
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach($submission[0]->submission_history as $submission) {
                                                    $date_submission = explode("T","submission->submitted_at");
                                                    if ($date_submission[0] < $rule->param_1) {
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach($submission[0]->submission_history as $submission) {
                                                    $date_submission = explode("T","submission->submitted_at");
                                                    if (($date_submission[0] > $rule->param_1)&&($date_submission[0] < $rule->param_2)) {
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if (($submission[0]->workflow_state != "unsubmitted") && ($students_rewarded <= $rule->param_1)){
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("At least some group members have to perform the action"):
                                                // TODO
                                                break;
                                            case("Get a validity score lower than X"):
                                                // TODO
                                                break;
                                            case("Get a reliability score lower than X"):
                                                // TODO
                                                break;
                                            case("Get a score equal or higher than X"):
                                                /*if($submission[0]->score >= $rule->param_1){
                                                    $rule_requirement = true;
                                                }
                                                break;*/
                                                $assignment = $this->getCanvasAPI("/courses/".$deploy->course_id."/assignments/".$resource->instance_resource_id."/submissions/".$student->id_instance, $deploy);
                                                $rubric = $this->getCanvasAPI("/courses/".$deploy->course_id."/rubrics/".$rule->param_2."?include=peer_assessments", $deploy);
                                                $num_reviews = 0;
                                                $score = 0;

                                                if(array_key_exists('assessments', $rubric[0])) {
                                                    foreach ($rubric[0]->assessments as $assessment) {
                                                        if ($assessment->artifact_id == $assignment[0]->id) {
                                                            if($assessment->score && $assessment->score != 0) {
                                                                $score = $score + $assessment->score;
                                                                $num_reviews++;
                                                            }
                                                        }
                                                    }

                                                    if ($num_reviews) {
                                                        $score = $score / $num_reviews;
                                                        if ($score >= $rule->param_1) {
                                                            $rule_requirement = true;
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false) {
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Visit"):
                                    $page_views = $this->getCanvasAPI("/users/".$student->id_instance."/page_views",$deploy);
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach($page_views as $page){
                                                    if(($page->controller == "assignments")&&($page->action == "show")){
                                                        if(strpos($page->url,"/assignments/".$resource->instance_resource_id)){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach($page_views as $page){
                                                    if(($page->controller == "assignments")&&($page->action == "show")){
                                                        if(strpos($page->url,"/assignments/".$resource->instance_resource_id)){
                                                            $counter++;
                                                            if($counter >= $rule->param_1) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach($page_views as $page){
                                                    if(($page->controller == "assignments")&&($page->action == "show")){
                                                        if(strpos($page->url,"/assignments/".$resource->instance_resource_id)){
                                                            $date_visit = explode("T",$page->created_at);
                                                            if($date_visit[0] < $rule->param_1){
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach($page_views as $page){
                                                    if(($page->controller == "assignments")&&($page->action == "show")){
                                                        if(strpos($page->url,"/assignments/".$resource->instance_resource_id)){
                                                            $date_visit = explode("T",$page->created_at);
                                                            if(($date_visit[0] > $rule->param_1)&&($date_visit[0] < $rule->param_2)){
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach($page_views as $page){
                                                        if(($page->controller == "assignments")&&($page->action == "show")){
                                                            if(strpos($page->url,"/assignments/".$resource->instance_resource_id)){
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Mark as done"):
                                    //TODO
                                    break;
                            }
                        }
                        break;
                    case('Content Page'):
                        foreach($res_cond->actions as $action) {
                            switch($action->action_type->name){
                                case("Edit"):
                                    $revisions = $this->getCanvasAPI("/courses/" . $deploy->course_id . "/pages/" . $resource->instance_resource_id."/revisions", $deploy);
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach($revisions as $revision){
                                                    if($revision->edited_by->id == $student->id_instance){
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach($revisions as $revision){
                                                    if($revision->edited_by->id == $student->id_instance){
                                                        $counter++;
                                                        if($counter >= $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach($revisions as $revision){
                                                    if($revision->edited_by->id == $student->id_instance){
                                                        $date_revision = explode("T",$revision->updated_at);
                                                        if($date_revision[0] < $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach($revisions as $revision){
                                                    if($revision->edited_by->id == $student->id_instance){
                                                        $date_revision = explode("T",$revision->updated_at);
                                                        if(($date_revision[0] > $rule->param_1)&&($date_revision[0] < $rule->param_2)) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach($revisions as $revision){
                                                        if($revision->edited_by->id == $student->id_instance){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Visit"):
                                    $result = (new \App\Console\Commands\save_activity8)->realtime($student->id_instance);

                                    if(!$result){       //Faltan vídeos
                                        $visits = Student_visit::where('student_id', $student->id)->first();
                                        $text = "Te falta ver el vídeos repaso del Bloque";
                                        if(!$visits->is_watched(1)){$text .= " 0,";}
                                        if(!$visits->is_watched(2)){$text .= " 1,";}
                                        if(!$visits->is_watched(3)){$text .= " 2,";}
                                        if(!$visits->is_watched(4)){$text .= " 3,";}
                                        if(!$visits->is_watched(5)){$text .= " 4,";}
                                        if(!$visits->is_watched(6)){$text .= " 5";}
                                        return $text;
                                    }else{              //Todos los vídeos están vistos

                                        $student_request->issued = true;
                                        $student_request->save();

                                        try{
                                            $this->notify_slack_reward_issued($student->email_instance, $engine);
                                        }catch (Exception $e_request){
                                            error_log('exception calling notify_slack_reward_issued');
                                            error_log('-----------------------------------------');
                                            error_log($e_request->getTraceAsString());
                                            error_log('-----------------------------------------');
                                        }
                                        return 0;
                                    }

                                    $page = $this->getCanvasAPI("/courses/" . $deploy->course_id . "/pages/" . $resource->instance_resource_id, $deploy);
                                    $pageviews = $this->getCanvasAPI("/users/".$student->id_instance."/page_views", $deploy);
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach($pageviews as $pageview){
                                                    if($pageview->controller == "wiki_pages"){
                                                        if(strpos($pageview->url,"/pages/".$page[0]->url)){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach($pageviews as $pageview){
                                                    if($pageview->controller == "wiki_pages"){
                                                        if(strpos($pageview->url,"/pages/".$page[0]->url)){
                                                            $counter++;
                                                            if($counter >= $rule->param_1) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach($pageviews as $pageview){
                                                    if($pageview->controller == "wiki_pages"){
                                                        if(strpos($pageview->url,"/pages/".$page[0]->url)){
                                                            $date_visit = explode("T", $pageview->created_at);
                                                            if($date_visit[0] < $rule->param_1) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach($pageviews as $pageview){
                                                    if($pageview->controller == "wiki_pages"){
                                                        if(strpos($pageview->url,"/pages/".$page[0]->url)){
                                                            $date_visit = explode("T", $pageview->created_at);
                                                            if(($date_visit[0] > $rule->param_1)&&($date_visit[0] < $rule->param_2)) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach($pageviews as $pageview){
                                                        if($pageview->controller == "wiki_pages"){
                                                            if(strpos($pageview->url,"/pages/".$page[0]->url)){
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Mark as done"):
                                    //TODO
                                    break;
                            }
                        }
                        break;
                    case('Discussion Forum'):
                        foreach($res_cond->actions as $action) {
                            switch ($action->action_type->name) {
                                case("Participate"):
                                    $forum = $this->getCanvasAPI("/courses/" . $deploy->course_id . "/discussion_topics/" . $resource->instance_resource_id . "/view", $deploy);
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach ($forum[0]->participants as $participant) {
                                                    if ($participant->id == $student->id_instance) {
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                $found = false;
                                                foreach ($forum[0]->view as $entry) {
                                                    if ($entry->user_id == $student->id_instance) {
                                                        $counter++;
                                                        if ($counter >= $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                    foreach ($entry->replies as $reply) {
                                                        if ($reply->user_id == $student->id_instance) {
                                                            $counter++;
                                                            if ($counter >= $rule->param_1) {
                                                                $rule_requirement = true;
                                                                $found = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if($found = true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                $found = false;
                                                foreach($forum[0]->view as $entry) {
                                                    if ($entry->user_id == $student->id_instance){
                                                        $entry_date = explode("T", $entry->created_at);
                                                        if($entry_date[0] < $rule->param_1){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                    foreach($entry->replies as $reply) {
                                                        if($reply->user_id == $student->id_instance){
                                                            $reply_date = explode("T", $reply->created_at);
                                                            if($reply_date[0] < $rule->param_1){
                                                                $rule_requirement = true;
                                                                $found = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if($found = true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                $found = false;
                                                foreach($forum[0]->view as $entry) {
                                                    if ($entry->user_id == $student->id_instance){
                                                        $entry_date = explode("T", $entry->created_at);
                                                        if(($entry_date[0] > $rule->param_1) && ($entry_date[0] < $rule->param_2)){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                    foreach($entry->replies as $reply) {
                                                        if($reply->user_id == $student->id_instance){
                                                            $reply_date = explode("T", $reply->created_at);
                                                            if(($reply_date[0] > $rule->param_1) && ($reply_date[0] < $rule->param_2)){
                                                                $rule_requirement = true;
                                                                $found = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if($found = true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                foreach ($forum[0]->participants as $participant) {
                                                    if ($participant->id == $student->id_instance) {
                                                        $students_rewarded = Student_engine::where('engine_id', $engine->id)->count();
                                                        if ($students_rewarded <= $rule->param_1) {
                                                            $rule_requirement = true;
                                                        }
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("At least some group members have to perform the action"):
                                                // TODO
                                                break;
                                        }
                                        if ($rule_requirement == false) {
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Entry"):
                                    $entries = $this->getCanvasAPI("/courses/" . $deploy->course_id . "/discussion_topics/" . $resource->instance_resource_id . "/entries", $deploy);
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach ($entries as $entry) {
                                                    if ($entry->user_id == $student->id_instance) {
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach ($entries as $entry) {
                                                    if ($entry->user_id == $student->id_instance) {
                                                        $counter++;
                                                        if ($counter >= $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach($entries as $entry) {
                                                    if ($entry->user_id == $student->id_instance){
                                                        $entry_date = explode("T", $entry->created_at);
                                                        if($entry_date[0] < $rule->param_1){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach($entries as $entry) {
                                                    if ($entry->user_id == $student->id_instance){
                                                        $entry_date = explode("T", $entry->created_at);
                                                        if(($entry_date[0] > $rule->param_1) && ($entry_date[0] < $rule->param_2)){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                foreach ($entries as $entry) {
                                                    if ($entry->user_id == $student->id_instance) {
                                                        $students_rewarded = Student_engine::where('engine_id', $engine->id)->count();
                                                        if ($students_rewarded <= $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("At least some group members have to perform the action"):
                                                // TODO
                                                break;
                                        }
                                        if ($rule_requirement == false) {
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Answer"):
                                    $forum = $this->getCanvasAPI("/courses/" . $deploy->course_id . "/discussion_topics/" . $resource->instance_resource_id . "/view", $deploy);
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach ($forum[0]->view as $entry) {
                                                    foreach($entry->replies as $reply)
                                                    if ($reply->user_id == $student->id_instance) {
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                $found = false;
                                                foreach ($forum[0]->view as $entry) {
                                                    foreach ($entry->replies as $reply) {
                                                        if ($reply->user_id == $student->id_instance) {
                                                            $counter++;
                                                            if ($counter >= $rule->param_1) {
                                                                $rule_requirement = true;
                                                                $found = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if($found = true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                $found = false;
                                                foreach($forum[0]->view as $entry) {
                                                    foreach($entry->replies as $reply) {
                                                        if($reply->user_id == $student->id_instance){
                                                            $reply_date = explode("T", $reply->created_at);
                                                            if($reply_date[0] < $rule->param_1){
                                                                $rule_requirement = true;
                                                                $found = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if($found = true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                $found = false;
                                                foreach($forum[0]->view as $entry) {
                                                    foreach($entry->replies as $reply) {
                                                        if($reply->user_id == $student->id_instance){
                                                            $reply_date = explode("T", $reply->created_at);
                                                            if(($reply_date[0] > $rule->param_1) && ($reply_date[0] < $rule->param_2)){
                                                                $rule_requirement = true;
                                                                $found = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if($found = true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                foreach ($forum[0]->view as $entry) {
                                                    foreach($entry->replies as $reply)
                                                        if ($reply->user_id == $student->id_instance) {
                                                            $students_rewarded = Student_engine::where('engine_id', $engine->id)->count();
                                                            if ($students_rewarded <= $rule->param_1) {
                                                                $rule_requirement = true;
                                                            }
                                                            break;
                                                        }
                                                }
                                                break;
                                            case("At least some group members have to perform the action"):
                                                // TODO
                                                break;
                                        }
                                        if ($rule_requirement == false) {
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Give Like"):
                                    //TODO
                                    break;
                                case("Give Like to an entry"):
                                    //TODO
                                    break;
                                case("Give Like to an answer"):
                                    //TODO
                                    break;
                                case("Receive Like"):
                                    $forum = $this->getCanvasAPI("/courses/" . $deploy->course_id . "/discussion_topics/" . $resource->instance_resource_id . "/view", $deploy);
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                $found = false;
                                                foreach ($forum[0]->view as $entry) {
                                                    if (($entry->user_id == $student->id_instance) && (array_key_exists('rating_sum',$entry))) {
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                    foreach ($entry->replies as $reply) {
                                                        if (($reply->user_id == $student->id_instance) && (array_key_exists('rating_sum',$reply))) {
                                                            $rule_requirement = true;
                                                            $found = true;
                                                            break;
                                                        }
                                                    }
                                                    if ($found == true) {
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $found = false;
                                                if(array_key_exists('view',$forum[0])) {
                                                    foreach ($forum[0]->view as $entry) {
                                                        if ((array_key_exists('user_id', $entry))    &&
                                                            ($entry->user_id == $student->id_instance)   &&
                                                            (array_key_exists('rating_sum', $entry))) {
                                                            if ($entry->rating_sum >= $rule->param_1) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                        if(array_key_exists('replies',$entry)) {
                                                            foreach ($entry->replies as $reply) {
                                                                if ((array_key_exists('user_id', $reply))    &&
                                                                    ($reply->user_id == $student->id_instance)   &&
                                                                    (array_key_exists('rating_sum', $reply))) {
                                                                    if ($reply->rating_sum >= $rule->param_1) {
                                                                        $rule_requirement = true;
                                                                        $found = true;
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                            if ($found == true) {
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                $current_date = date('Y-m-d', time());
                                                $found = false;
                                                if ($current_date < $rule->param_1) {
                                                    foreach ($forum[0]->view as $entry) {
                                                        if ($entry->user_id == $student->id_instance) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                    foreach ($entry->replies as $reply) {
                                                        if ($reply->user_id == $student->id_instance) {
                                                            $rule_requirement = true;
                                                            $found = true;
                                                            break;
                                                        }
                                                    }
                                                    if ($found = true) {
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                //TODO
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $found = false;
                                                foreach ($forum[0]->view as $entry) {
                                                    if (($entry->user_id == $student->id_instance) && (array_key_exists('rating_sum',$entry))) {
                                                        $students_rewarded = Student_engine::where('engine_id', $engine->id)->count();
                                                        if ($students_rewarded <= $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                    foreach ($entry->replies as $reply) {
                                                        if (($reply->user_id == $student->id_instance) && (array_key_exists('rating_sum',$reply))) {
                                                            $students_rewarded = Student_engine::where('engine_id', $engine->id)->count();
                                                            if ($students_rewarded <= $rule->param_1) {
                                                                $rule_requirement = true;
                                                                $found = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if ($found == true) {
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("At least some group members have to perform the action"):
                                                // TODO
                                                break;
                                        }
                                        if ($rule_requirement == false) {
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Receive Like in an entry"):
                                    $entries = $this->getCanvasAPI("/courses/" . $deploy->course_id . "/discussion_topics/" . $resource->instance_resource_id . "/entries", $deploy);
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach ($entries as $entry) {
                                                    if (($entry->user_id == $student->id_instance) && (array_key_exists('rating_count',$entry))) {
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach ($entries as $entry) {
                                                    if ($entry->user_id == $student->id_instance) {
                                                        $counter = $counter + $entry->rating_count;
                                                        if ($counter >= $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                $current_date = date('Y-m-d', time());
                                                if($current_date < $rule->param_1) {
                                                    foreach($entries as $entry) {
                                                        if (($entry->user_id == $student->id_instance)&&(array_key_exists('rating_count',$entry))) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                //TODO
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                foreach ($entries as $entry) {
                                                    if (($entry->user_id == $student->id_instance)&&(array_key_exists('rating_count',$entry))) {
                                                        $students_rewarded = Student_engine::where('engine_id', $engine->id)->count();
                                                        if ($students_rewarded <= $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("At least some group members have to perform the action"):
                                                // TODO
                                                break;
                                        }
                                        if ($rule_requirement == false) {
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Receive Like in an answer"):
                                    $forum = $this->getCanvasAPI("/courses/" . $deploy->course_id . "/discussion_topics/" . $resource->instance_resource_id . "/view", $deploy);
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach ($forum[0]->view as $entry) {
                                                    foreach($entry->replies as $reply)
                                                        if (($reply->user_id == $student->id_instance)&&($reply->rating_count)) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                $found = false;
                                                foreach ($forum[0]->view as $entry) {
                                                    foreach ($entry->replies as $reply) {
                                                        if (($reply->user_id == $student->id_instance)&&($reply->rating_count)) {
                                                            $counter = $counter + $reply->rating_count;
                                                            if ($counter >= $rule->param_1) {
                                                                $rule_requirement = true;
                                                                $found = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if($found = true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                $current_date = date('Y-m-d', time());
                                                if($current_date < $rule->param_1) {
                                                    $found = false;
                                                    foreach ($forum[0]->view as $entry) {
                                                        foreach ($entry->replies as $reply) {
                                                            if (($reply->user_id == $student->id_instance)&&($reply->rating_count)) {
                                                                $rule_requirement = true;
                                                                $found = true;
                                                                break;
                                                            }
                                                        }
                                                        if ($found = true) {
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                //TODO
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                foreach ($forum[0]->view as $entry) {
                                                    foreach ($entry->replies as $reply){
                                                        if (($reply->user_id == $student->id_instance) && ($reply->rating_count)) {
                                                            $students_rewarded = Student_engine::where('engine_id', $engine->id)->count();
                                                            if ($students_rewarded <= $rule->param_1) {
                                                                $rule_requirement = true;
                                                            }
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("At least some group members have to perform the action"):
                                                // TODO
                                                break;
                                        }
                                        if ($rule_requirement == false) {
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Visit"):
                                    break;
                                case("Mark as read"):
                                    break;
                                case("Solve a question"):
                                    break;
                            }
                        }
                        break;
                    case('Quiz'):
                        foreach($res_cond->actions as $action) {
                            switch($action->action_type->name){
                                case("Submit"):
                                    $quiz_submissions = $this->getCanvasAPI("/courses/".$deploy->course_id."/quizzes/".$resource->instance_resource_id."/submissions", $deploy);
                                    $user_submission = null;

                                    foreach($quiz_submissions as $qs){
                                        foreach($qs->quiz_submissions as $submission) {
                                            if (($submission->user_id == $student->id_instance) && ($submission->workflow_state == "complete")) {
                                                $user_submission = $submission;
                                                break;
                                            }
                                        }
                                        if($user_submission != null){
                                            break;
                                        }
                                    }
                                    if($user_submission == null){ //El estudiante no tiene envíos para este assignment
                                        return -1;
                                    }
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name){
                                            case("Do the action itself"):
                                                if ($user_submission){
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Do the action several times"):
                                                if ($user_submission->attempt >= $rule->param_1){
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                $submission_date = explode("T", $user_submission->finished_at);
                                                if($submission_date[0] < $rule->param_1){
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                $submission_date = explode("T", $user_submission->finished_at);
                                                if(($submission_date[0] > $rule->param_1) && ($submission_date[0] < $rule->param_2)){
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if (($user_submission) && ($students_rewarded <= $rule->param_1)){
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("At least some group members have to perform the action"):
                                                // TODO
                                                break;
                                            case("Get a validity score lower than X"):
                                                // TODO
                                                break;
                                            case("Get a reliability score lower than X"):
                                                // TODO
                                                break;
                                            case("Get a score equal or higher than X"):
                                                $percentage_score = ($user_submission->kept_score * 100)/($user_submission->quiz_points_possible);
                                                if($percentage_score >= $rule->param_1){
                                                    $rule_requirement = true;
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Visit"):
                                    $page_views = $this->getCanvasAPI("/users/".$student->id_instance."/page_views",$deploy);
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach($page_views as $page){
                                                    if(($page->controller == "quizzes/quizzes")&&($page->action == "show")){
                                                        if(strpos($page->url,"/quizzes/".$resource->instance_resource_id)){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach($page_views as $page){
                                                    if(($page->controller == "quizzes/quizzes")&&($page->action == "show")){
                                                        if(strpos($page->url,"/quizzes/".$resource->instance_resource_id)){
                                                            $counter++;
                                                            if($counter >= $rule->param_1) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach($page_views as $page){
                                                    if(($page->controller == "quizzes/quizzes")&&($page->action == "show")){
                                                        if(strpos($page->url,"/quizzes/".$resource->instance_resource_id)){
                                                            $date_visit = explode("T",$page->created_at);
                                                            if($date_visit[0] < $rule->param_1){
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach($page_views as $page){
                                                    if(($page->controller == "quizzes/quizzes")&&($page->action == "show")){
                                                        if(strpos($page->url,"/quizzes/".$resource->instance_resource_id)){
                                                            $date_visit = explode("T",$page->created_at);
                                                            if(($date_visit[0] > $rule->param_1)&&($date_visit[0] < $rule->param_2)){
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach($page_views as $page){
                                                        if(($page->controller == "quizzes/quizzes")&&($page->action == "show")){
                                                            if(strpos($page->url,"/quizzes/".$resource->instance_resource_id)){
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                    }
                                    break;
                                case("Mark as done"):
                                    //TODO: I don't find it on API
                                    break;
                            }
                        }
                        break;
                    case('Peer Review'):
                        foreach($res_cond->actions as $action) {
                            switch($action->action_type->name){
                                case("Submit"):
                                    $peer_reviews = $this->getCanvasAPI("/courses/".$deploy->course_id."/assignments/".$resource->instance_resource_id."/peer_reviews", $deploy);
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name){
                                            case("Do the action itself"):
                                                foreach($peer_reviews as $review) {
                                                    if (($review->assessor_id == $student->id_instance)&&($review->workflow_state == "completed")){
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach($peer_reviews as $review) {
                                                    if (($review->assessor_id == $student->id_instance)&&($review->workflow_state == "completed")){
                                                        $counter++;
                                                        if($counter >= $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                //TODO
                                                break;
                                            case("Do the action between a specific time frame"):
                                                //TODO
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1){
                                                    foreach($peer_reviews as $review) {
                                                        if (($review->assessor_id == $student->id_instance)&&($review->workflow_state == "completed")){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Get a score equal or higher than X"):
                                                $assignment = $this->getCanvasAPI("/courses/".$deploy->course_id."/assignments/".$resource->instance_resource_id."/submissions/".$student->id_instance, $deploy);

                                                if($assignment[0]->workflow_state != "unsubmitted") {
                                                    $rubric = $this->getCanvasAPI("/courses/" . $deploy->course_id . "/rubrics/" . $rule->param_2 . "?include=peer_assessments", $deploy);
                                                    $num_reviews = 0;
                                                    $score = 0;
                                                    if (array_key_exists('assessments', $rubric[0])) {
                                                        foreach ($rubric[0]->assessments as $assessment) {
                                                            if ($assessment->artifact_id == $assignment[0]->id) {
                                                                if($assessment->score && $assessment->score != 0) {
                                                                    $score = $score + $assessment->score;
                                                                    $num_reviews++;
                                                                }
                                                            }
                                                        }

                                                        if ($num_reviews) {
                                                            $score = $score / $num_reviews;
                                                            if ($score >= $rule->param_1) {
                                                                $rule_requirement = true;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Comment"): //En cada peer review asignado
                                    $peer_reviews = $this->getCanvasAPI("/courses/".$deploy->course_id."/assignments/".$resource->instance_resource_id."/peer_reviews?include[]=submission_comments", $deploy);
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                $counter_submissions = 0;
                                                $counter_completed = 0;
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->assessor_id == $student->id_instance) {
                                                        $counter_submissions++;
                                                        foreach ($review->submission_comments as $comment) {
                                                            if ($comment->author_id == $student->id_instance) {
                                                                $counter_completed++;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($counter_submissions == $counter_completed) {
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter_submissions = 0;
                                                $counter_completed = 0;
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->assessor_id == $student->id_instance) {
                                                        $counter_submissions++;
                                                        $counter_times = 0;
                                                        foreach ($review->submission_comments as $comment) {
                                                            if ($comment->author_id == $student->id_instance) {
                                                                $counter_times++;
                                                                if ($counter_times == $rule->param_1) {
                                                                    $counter_completed++;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                if($counter_submissions == $counter_completed){
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                $counter_submissions = 0;
                                                $counter_completed = 0;
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->assessor_id == $student->id_instance) {
                                                        $counter_submissions++;
                                                        foreach ($review->submission_comments as $comment) {
                                                            if ($comment->author_id == $student->id_instance) {
                                                                $comment_date = explode("T", $comment->created_at);
                                                                if($comment_date[0] < $rule->param_1) {
                                                                    $counter_completed++;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($counter_submissions == $counter_completed) {
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                $counter_submissions = 0;
                                                $counter_completed = 0;
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->assessor_id == $student->id_instance) {
                                                        $counter_submissions++;
                                                        foreach ($review->submission_comments as $comment) {
                                                            if ($comment->author_id == $student->id_instance) {
                                                                $comment_date = explode("T", $comment->created_at);
                                                                if(($comment_date[0] > $rule->param_1)&&($comment_date[0] < $rule->param_2)) {
                                                                    $counter_completed++;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($counter_submissions == $counter_completed) {
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $counter_submissions = 0;
                                                $counter_completed = 0;
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach ($peer_reviews as $review) {
                                                        if ($review->assessor_id == $student->id_instance) {
                                                            $counter_submissions++;
                                                            foreach ($review->submission_comments as $comment) {
                                                                if ($comment->author_id == $student->id_instance) {
                                                                    $counter_completed++;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    if ($counter_submissions == $counter_completed) {
                                                        $rule_requirement = true;
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Comment with a minimum number of characters"): //En cada peer review asignado --> 100 Characters
                                    $num_characters = 100;
                                    $peer_reviews = $this->getCanvasAPI("/courses/".$deploy->course_id."/assignments/".$resource->instance_resource_id."/peer_reviews?include[]=submission_comments", $deploy);
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                $counter_submissions = 0;
                                                $counter_completed = 0;
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->assessor_id == $student->id_instance) {
                                                        $counter_submissions++;
                                                        foreach ($review->submission_comments as $comment) {
                                                            if (($comment->author_id == $student->id_instance)&&(strlen($comment->comment)>=$num_characters)){
                                                                $counter_completed++;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($counter_submissions == $counter_completed) {
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter_submissions = 0;
                                                $counter_completed = 0;
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->assessor_id == $student->id_instance) {
                                                        $counter_submissions++;
                                                        $counter_times = 0;
                                                        foreach ($review->submission_comments as $comment) {
                                                            if (($comment->author_id == $student->id_instance)&&(strlen($comment->comment)>=$num_characters)) {
                                                                $counter_times++;
                                                                if ($counter_times == $rule->param_1) {
                                                                    $counter_completed++;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                if($counter_submissions == $counter_completed){
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                $counter_submissions = 0;
                                                $counter_completed = 0;
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->assessor_id == $student->id_instance) {
                                                        $counter_submissions++;
                                                        foreach ($review->submission_comments as $comment) {
                                                            if (($comment->author_id == $student->id_instance)&&(strlen($comment->comment) >= $num_characters)) {
                                                                $comment_date = explode("T", $comment->created_at);
                                                                if($comment_date[0] < $rule->param_1) {
                                                                    $counter_completed++;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($counter_submissions == $counter_completed) {
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                $counter_submissions = 0;
                                                $counter_completed = 0;
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->assessor_id == $student->id_instance) {
                                                        $counter_submissions++;
                                                        foreach ($review->submission_comments as $comment) {
                                                            if (($comment->author_id == $student->id_instance)&&(strlen($comment->comment) >= $num_characters)) {
                                                                $comment_date = explode("T", $comment->created_at);
                                                                if(($comment_date[0] > $rule->param_1)&&($comment_date[0] < $rule->param_2)) {
                                                                    $counter_completed++;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($counter_submissions == $counter_completed) {
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $counter_submissions = 0;
                                                $counter_completed = 0;
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach ($peer_reviews as $review) {
                                                        if ($review->assessor_id == $student->id_instance) {
                                                            $counter_submissions++;
                                                            foreach ($review->submission_comments as $comment) {
                                                                if (($comment->author_id == $student->id_instance)&&(strlen($comment->comment) >= $num_characters)) {
                                                                    $counter_completed++;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    if ($counter_submissions == $counter_completed) {
                                                        $rule_requirement = true;
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Receive Comment"): //En cada peer review asignado
                                    $peer_reviews = $this->getCanvasAPI("/courses/".$deploy->course_id."/assignments/".$resource->instance_resource_id."/peer_reviews?include[]=submission_comments", $deploy);
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->user_id == $student->id_instance) {
                                                        if($review->submission_comments){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->user_id == $student->id_instance) {
                                                        if(count($review->submission_comments) >= $rule->param_1){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                $completed = false;
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->user_id == $student->id_instance) {
                                                        foreach ($review->submission_comments as $comment){
                                                            $date_comment = explode("T",$comment->created_at);
                                                            if($date_comment[0] < $rule->param_1){
                                                                $rule_requirement = true;
                                                                $completed = true;
                                                                break;
                                                            }
                                                        }
                                                        if($completed == true){
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                $completed = false;
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->user_id == $student->id_instance) {
                                                        foreach ($review->submission_comments as $comment){
                                                            $date_comment = explode("T",$comment->created_at);
                                                            if(($date_comment[0] > $rule->param_1)&&($date_comment[0] < $rule->param_2)){
                                                                $rule_requirement = true;
                                                                $completed = true;
                                                                break;
                                                            }
                                                        }
                                                        if($completed == true){
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach ($peer_reviews as $review) {
                                                        if ($review->user_id == $student->id_instance) {
                                                            if($review->submission_comments){
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Answers and clarifications"):
                                    $peer_reviews = $this->getCanvasAPI("/courses/".$deploy->course_id."/assignments/".$resource->instance_resource_id."/peer_reviews?include[]=submission_comments", $deploy);
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                $completed = false;
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->user_id == $student->id_instance) {
                                                        foreach($review->submission_comments as $comment) {
                                                            if ($comment->author_id == $student->id_instance) {
                                                                $rule_requirement = true;
                                                                $completed = true;
                                                                break;
                                                            }
                                                        }
                                                        if($completed == true){
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $num_clarifications = 0;
                                                $completed = false;
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->user_id == $student->id_instance) {
                                                        foreach($review->submission_comments as $comment) {
                                                            if ($comment->author_id == $student->id_instance) {
                                                                $num_clarifications++;
                                                                if($num_clarifications >= $rule->param_1) {
                                                                    $rule_requirement = true;
                                                                    $completed = true;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        if($completed == true){
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                $completed = false;
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->user_id == $student->id_instance) {
                                                        foreach($review->submission_comments as $comment) {
                                                            if ($comment->author_id == $student->id_instance) {
                                                                $date_comment = explode("T",$comment->created_at);
                                                                if($date_comment[0] < $rule->param_1) {
                                                                    $rule_requirement = true;
                                                                    $completed = true;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        if($completed == true){
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                $completed = false;
                                                foreach ($peer_reviews as $review) {
                                                    if ($review->user_id == $student->id_instance) {
                                                        foreach($review->submission_comments as $comment) {
                                                            if ($comment->author_id == $student->id_instance) {
                                                                $date_comment = explode("T",$comment->created_at);
                                                                if(($date_comment[0] > $rule->param_1)&&($date_comment[0] < $rule->param_2)) {
                                                                    $rule_requirement = true;
                                                                    $completed = true;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        if($completed == true){
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                $completed = false;
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach ($peer_reviews as $review) {
                                                        if ($review->user_id == $student->id_instance) {
                                                            foreach($review->submission_comments as $comment) {
                                                                if ($comment->author_id == $student->id_instance) {
                                                                    $rule_requirement = true;
                                                                    $completed = true;
                                                                    break;
                                                                }
                                                            }
                                                            if($completed == true){
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Fulfill the rubric"):
                                    $assessment = $this->getCanvasAPI("/courses/".$deploy->course_id."/assignments/".$resource->instance_resource_id, $deploy);
                                    $rubric = $this->getCanvasAPI("/courses/".$deploy->course_id."/rubrics/".$assessment[0]->rubric_settings->id."?include=assessments", $deploy);
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach($rubric->assessments as $assessment){
                                                    if($assessment->assessor_id == $student->id_instance){
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach($rubric->assessments as $assessment){
                                                    if($assessment->assessor_id == $student->id_instance){
                                                        $counter++;
                                                        if($counter >= $rule->param_1){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                //TODO
                                                break;
                                            case("Do the action between a specific time frame"):
                                                //TODO
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach($rubric->assessments as $assessment){
                                                        if($assessment->assessor_id == $student->id_instance){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Visit"):
                                    //TODO
                                    break;
                                case("Mark as done"):
                                    //TODO
                                    break;
                            }
                        }
                        break;
                    case('External Tool'):
                        foreach($res_cond->actions as $action) {
                            switch ($action->action_type->name) {
                                //TODO: Específico para el Spreadsheet indicado abajo
                                case('Google Spreadsheets: Insert new entry'):
                                    Sheets::setService(Google::make('sheets'));
                                    Sheets::spreadsheet('1qYFoqyiyXhlUM39Pbdl1ewh22Jb7r5OoUDiD3xP3Nr8');

                                    $rows = Sheets::sheet('Respuestas Glosario')->get();
                                    $header = $rows->pull(0);
                                    $values = Sheets::collection($header, $rows);
                                    $values = $values->pluck('Email address')->filter()->values();

                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach($values as $email){
                                                    if($email == $student->email_instance){
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach($values as $email){
                                                    if($email == $student->email_instance){
                                                        $counter++;
                                                        if($counter == $rule->param_1){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                            }
                        }
                        break;
                    case('External URL'):
                        break;
                    case('3DVW'):
                        break;
                    case('Wiki'):
                        foreach($res_cond->actions as $action) {
                            switch($action->action_type->name){
                                case("Edit"):
                                    $revisions = $this->getCanvasAPI("/courses/" . $deploy->course_id . "/pages/" . $resource->instance_resource_id."/revisions", $deploy);
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach($revisions as $revision){
                                                    if($revision->edited_by->id == $student->id_instance){
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach($revisions as $revision){
                                                    if($revision->edited_by->id == $student->id_instance){
                                                        $counter++;
                                                        if($counter >= $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach($revisions as $revision){
                                                    if($revision->edited_by->id == $student->id_instance){
                                                        $date_revision = explode("T",$revision->updated_at);
                                                        if($date_revision[0] < $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach($revisions as $revision){
                                                    if($revision->edited_by->id == $student->id_instance){
                                                        $date_revision = explode("T",$revision->updated_at);
                                                        if(($date_revision[0] > $rule->param_1)&&($date_revision[0] < $rule->param_2)) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach($revisions as $revision){
                                                        if($revision->edited_by->id == $student->id_instance){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Visit"):
                                    $page = $this->getCanvasAPI("/courses/" . $deploy->course_id . "/pages/" . $resource->instance_resource_id, $deploy);
                                    $pageviews = $this->getCanvasAPI("/users/".$student->id_instance."/page_views", $deploy);
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach($pageviews as $page){
                                                    if($page->controller == "wiki_pages"){
                                                        if(strpos($page->url,"/pages/".$page->url)){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach($pageviews as $page){
                                                    if($page->controller == "wiki_pages"){
                                                        if(strpos($page->url,"/pages/".$page->url)){
                                                            $counter++;
                                                            if($counter >= $rule->param_1) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach($pageviews as $page){
                                                    if($page->controller == "wiki_pages"){
                                                        if(strpos($page->url,"/pages/".$page->url)){
                                                            $date_visit = explode("T", $page->created_at);
                                                            if($date_visit[0] < $rule->param_1) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach($pageviews as $page){
                                                    if($page->controller == "wiki_pages"){
                                                        if(strpos($page->url,"/pages/".$page->url)){
                                                            $date_visit = explode("T", $page->created_at);
                                                            if(($date_visit[0] > $rule->param_1)&&($date_visit[0] < $rule->param_2)) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach($pageviews as $page){
                                                        if($page->controller == "wiki_pages"){
                                                            if(strpos($page->url,"/pages/".$page->url)){
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Mark as done"):
                                    //TODO
                                    break;
                            }
                        }
                        break;
                    case('File'):
                        foreach($res_cond->actions as $action) {
                            switch ($action->action_type->name) {
                                case("Open"):
                                    $pageviews = $this->getCanvasAPI("/users/".$student->id_instance."/page_views/", $deploy);
                                    foreach($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach($pageviews as $page){
                                                    if($page->controller == "canvadoc_sessions"){
                                                        if(strpos($page->url,"attachment_id%22:".$resource->instance_resource_id)){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach($pageviews as $page){
                                                    if($page->controller == "canvadoc_sessions"){
                                                        if(strpos($page->url,"attachment_id%22:".$resource->instance_resource_id)){
                                                            $counter++;
                                                            if($counter >= $rule->param_1){
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach($pageviews as $page){
                                                    if($page->controller == "canvadoc_sessions"){
                                                        if(strpos($page->url,"attachment_id%22:".$resource->instance_resource_id)){
                                                            $date_file = explode("T",$page->created_at);
                                                            if($date_file[0] < $rule->param_1){
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach($pageviews as $page){
                                                    if($page->controller == "canvadoc_sessions"){
                                                        if(strpos($page->url,"attachment_id%22:".$resource->instance_resource_id)){
                                                            $date_file = explode("T",$page->created_at);
                                                            if(($date_file[0] > $rule->param_1)&&($date_file[0] < $rule->param_2)){
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach($pageviews as $page){
                                                        if($page->controller == "canvadoc_sessions"){
                                                            if(strpos($page->url,"attachment_id%22:".$resource->instance_resource_id)){
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;

                            }
                        }
                        break;
                }
            }
        }

        //Rewarding
        foreach($engine->rewards as $reward){
            switch($reward->reward_type->name){
                case("Points"):
                    break;
                case("Levels"):
                    break;
                case("Badges"):
                    break;
                case("Redeemable Rewards"):
                    $resource = Resource_deploy::where('deploy_id',$gdeploy_id)->where('resource_id',$reward->redeemable_rewards[0]->resource->id)->first();
                    switch($reward->redeemable_rewards[0]->rr_type->name){
                        case("Unlock Resource"):
                            switch($reward->redeemable_rewards[0]->resource->resource_type->name){
                                case("Content Page"):
                                        // TODO: El id del grupo está metido a mano!! CUIDADO!!
                                        $url = "/groups/"."4476"."/memberships";
                                        $data = ['user_id'=>$student->id_instance];
                                        $change_group = $this->postCanvasAPI($url, $data, $deploy);
                                    break;
                                case("Quiz"):
                                    //POST /api/v1/courses/:course_id/quizzes/:quiz_id/extensions
                                    break;
                                case("Assignment"):
                                    //POST /api/v1/courses/:course_id/assignments/:assignment_id/overrides
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case("Final Certificate Discount"):
                            break;
                        case("Unlock Features"):
                            switch($reward->redeemable_rewards[0]->resource->resource_type->name){
                                default:
                                    //Con Registro en la base de datos
                                    break;
                            }
                            break;
                        case("Teacher Assistant"):
                            // Se puede hacer en nuestra instancia --> pendiente de preguntar en canvas.net
                            break;
                        case("Extra Attempts"):
                            switch($reward->redeemable_rewards[0]->resource->resource_type->name){
                                case("Quiz"):
                                    //TODO Cuidado, el número que se añade no es incremental, indica el total de intentos extra (incluyendo los ya realizados)!
                                    $url = "/courses/".$deploy->course_id."/quizzes/".$resource->instance_resource_id."/extensions";
                                    $data = ['quiz_extensions' => array(array('user_id'=>$student->id_instance,'extra_attempts'=>$reward->redeemable_rewards[0]->param_1))];
                                    $extra_attempts = $this->postCanvasAPI($url, $data, $deploy);
                                    break;
                                default:
                                    return -1;
                                    break;
                            }
                            break;
                        case("Extra Time"):
                            switch($reward->redeemable_rewards[0]->resource->resource_type->name){
                                case("Quiz"):
                                    $url = "/courses/".$deploy->course_id."/quizzes/".$resource->instance_resource_id."/extensions";
                                    $data = ['quiz_extensions' => array(array('user_id'=>$student->id_instance,'extra_time'=>$reward->redeemable_rewards[0]->param_1))];
                                    $extension = $this->postCanvasAPI($url, $data, $deploy);
                                    break;
                                default:
                                    return -1;
                                    break;
                            }
                            break;
                        case("Allow to Skip"):
                            switch($reward->redeemable_rewards[0]->resource->resource_type->name){
                                case("Quiz"):
                                    break;
                                case("Assignment"):
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case("Pass with Lower Score"):
                            switch($reward->redeemable_rewards[0]->resource->resource_type_name){
                                case("Quiz"):
                                    break;
                                case("Assignment"):
                                    break;
                            }
                            break;
                        case("Deadline Extension"):
                                    $found = false;

                                    if ($reward->redeemable_rewards[0]->resource->resource_type->name == "Quiz") {
                                        $quiz = $this->getCanvasAPI("/courses/" . $deploy->course_id . "/quizzes/" . $resource->instance_resource_id, $deploy);
                                        $assignment_id = $quiz[0]->assignment_id;
                                    }else if ($reward->redeemable_rewards[0]->resource->resource_type->name == "Assignment") {
                                        $assignment_id = $resource->instance_resource_id;
                                    }

                                    $overrides = $this->getCanvasAPI("/courses/".$deploy->course_id."/assignments/".$assignment_id."/overrides", $deploy);

                                    foreach($overrides as $override){
                                        if($override->title == "Redeemable Reward: ".$reward->redeemable_rewards[0]->id){
                                            $url = "/courses/".$deploy->course_id."/assignments/".$assignment_id."/overrides/".$override->id;
                                            $data = 'assignment_override[student_ids][]='.$student->id_instance;

                                            foreach($override->student_ids as $student_id){
                                                $data .= '&assignment_override[student_ids][]='.$student_id;
                                            }


                                            if (array_key_exists('title', $override))$data .= '&assignment_override[title]='.$override->title;
                                            if (array_key_exists('due_at', $override)) $data .= '&assignment_override[due_at]='.$override->due_at;
                                            if (array_key_exists('unlock_at', $override)) $data .= '&assignment_override[unlock_at]='.$override->unlock_at;
                                            if (array_key_exists('lock_at', $override)) $data .= '&assignment_override[lock_at]='.$override->lock_at;

                                            $this->formCanvasAPI("PUT", $url, $data, $deploy);
                                            $found = true;
                                            break;
                                        }
                                    }
                                    if($found == false){
                                        $url = "/courses/".$deploy->course_id."/assignments/".$assignment_id."/overrides";
                                        $extension_date = $reward->redeemable_rewards[0]->param_1."T21:59:00Z";

                                        $data = 'assignment_override[student_ids][]='.$student->id_instance;
                                        $data .= '&assignment_override[title]=Redeemable Reward: '.$reward->redeemable_rewards[0]->id;
                                        $data .= '&assignment_override[lock_at]='.$extension_date;
                                        $data .= '&assignment_override[due_at]='.$extension_date;

                                        $this->formCanvasAPI("POST", $url, $data, $deploy);
                                    }
                            break;
                        case("Re-open"):
                            //Con registro en la base de datos
                            break;
                        case("Instructor Revision"):
                            //Con registro en la base de datos
                            break;
                        case("Individual or Group"):
                            // Creación de grupo y asignación de tarea --> fácil pero interferible con Luisa
                            break;
                    }
                    break;
            }
        }
        //
        //Storing
        $student_request->issued = true;
        $student_request->save();

        try{
            $this->notify_slack_reward_issued($student->email_instance, $engine);
        }catch (Exception $e_request){
            error_log('exception calling notify_slack_reward_issued');
            error_log('-----------------------------------------');
            error_log($e_request->getTraceAsString());
            error_log('-----------------------------------------');
        }

        //Notifying
        return 0;
    }
}
