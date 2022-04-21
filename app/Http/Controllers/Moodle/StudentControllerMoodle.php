<?php

namespace App\Http\Controllers\Moodle;

use App\Classes\Curl;
use App\Classes\User_access;
use App\Console\Commands\save_activity8;
use App\Http\Controllers\StudentController;
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
use Ozq\MoodleClient\Clients\Adapters\RestClient;
use Ozq\MoodleClient\Connection;
use Psy\Exception\Exception;
use Sheets;
use Google;

class StudentControllerMoodle extends StudentController
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

        $deploy = Gamification_deploy::where('id',$deploy_id)->first();


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
                        if($reward->redeemable_rewards[0]->rr_type->name == "Teachers Evaluation"){
                            $assignment_id = $reward->redeemable_rewards[0]->resource->retrieve_instance_id(2);
                            if ($assignment_id) {
                                foreach ($ge->rewarded_students as $student) {
                                    //$student->pivot->url_assignment = "https://learn.canvas.net/courses/".$deployrr->course_id."/gradebook/speed_grader?assignment_id=".$assignment_id."#%7B%22student_id%22%3A%22".$student->id_instance."%22%7D";
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

    private function connect_moodle(String $site, String $token, String $function, Array $params){
        $connection = new Connection($site, $token);
        $client = new RestClient($connection);
        $response = $client->sendRequest($function, $params);
        return $response;
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
                            switch ($action->action_type->name) {
                                case("Invite a friend"):
                                    //TODO
                                    break;
                                case("Log in"):
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
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
                                        if ($rule_requirement == false) {
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Log out"):
                                    break;
                                case("Mark as done"):
                                    break;
                                case("Send message to group"):
                                    break;
                                case("Send message to student"):
                                    break;
                                case("Send message to teacher"):
                                    break;
                                case("Submit"):
                                    break;
                                case("Update profile information"):
                                    break;
                                case("Upload profile picture"):
                                    break;
                                case("Visit"):
                                    break;
                            }
                        }
                        break;
                    case('Assignment'):
                        foreach($res_cond->actions as $action) {
                            switch($action->action_type->name){
                                case("Submit"):
                                    foreach($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                $submission = $this->connect_moodle($deploy->site, $deploy->bearer,'mod_assign_get_participant', ['assignid'=>$resource->instance_resource_id, 'userid'=>$student->id_instance]);
                                                if($submission['submitted'] == true){
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("Do the action several times"):
                                                //In Moodle you can re-submit assignments but it counts as the same submissions
                                                break;
                                            case("Do the action before a specific date"):
                                                $aux = explode('/',$rule->param_1);
                                                $date_mdy_1 = $aux[1].'/'.$aux[0].'/'.$aux[2];
                                                $submissions = $this->connect_moodle($deploy->site, $deploy->bearer,'mod_assign_get_submissions', ['assignmentids'=>array($resource->instance_resource_id), 'status'=>"submitted", 'before'=>strtotime($date_mdy_1)]);
                                                foreach($submissions['assignments']['0']['submissions'] as $submission){
                                                    if($submission['userid'] == $student->id_instance){
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                $aux = explode('/',$rule->param_1);
                                                $date_mdy_1 = $aux[1].'/'.$aux[0].'/'.$aux[2];
                                                $aux = explode('/',$rule->param_2);
                                                $date_mdy_2 = $aux[1].'/'.$aux[0].'/'.$aux[2];
                                                $submissions = $this->connect_moodle($deploy->site, $deploy->bearer,'mod_assign_get_submissions', ['assignmentids'=>array($resource->instance_resource_id), 'status'=>"submitted", 'before'=>strtotime($date_mdy_1), 'since'=>strtotime($date_mdy_2)]);
                                                foreach($submissions['assignments']['0']['submissions'] as $submission){
                                                    if($submission['userid'] == $student->id_instance){
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                $submission = $this->connect_moodle($deploy->site, $deploy->bearer,'mod_assign_get_participant', ['assignid'=>$resource->instance_resource_id, 'userid'=>$student->id_instance]);
                                                if(($submission['submitted'] == true) && ($students_rewarded <= $rule->param_1)){
                                                    $rule_requirement = true;
                                                }
                                                break;
                                            case("At least some group members have to perform the action"):
                                                break;
                                            case("Get a validity score lower than X"):
                                                break;
                                            case("Get a reliability score lower than X"):
                                                break;
                                            case("Get an upper or equal score than X"):
                                                $grades = $this->connect_moodle($deploy->site, $deploy->bearer,'mod_assign_get_grades', ['assignmentids'=>array($resource->instance_resource_id)]);
                                                foreach($grades['assignments']['0']['grades'] as $grade){
                                                    if($grade['userid'] == $student->id_instance){
                                                        if ($grade['grade'] >= $rule->param_1) {
                                                            $rule_requirement = true;
                                                        }
                                                        break;
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
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
                                                break;
                                            case("Do the action before a specific date"):
                                                break;
                                            case("Do the action between a specific time frame"):
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Mark as done"):
                                    break;
                            }
                        }
                        break;
                    case('Content Page'):
                        foreach($res_cond->actions as $action) {
                            switch($action->action_type->name){
                                case("Edit"):
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
                                                break;
                                            case("Do the action before a specific date"):
                                                break;
                                            case("Do the action between a specific time frame"):
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Visit"):
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
                                                break;
                                            case("Do the action before a specific date"):
                                                break;
                                            case("Do the action between a specific time frame"):
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Mark as done"):
                                    break;
                            }
                        }
                        break;
                    case('Discussion Forum'):
                        $discussions = $this->connect_moodle($deploy->instance_url, $deploy->bearer, 'mod_forum_get_forum_discussions_paginated', ['forumid'=>$resource->instance_resource_id]);
                        foreach($res_cond->actions as $action) {
                            switch ($action->action_type->name) {
                                case("Participate"):
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach ($discussions['discussions'] as $discussion){
                                                    $posts = $this->connect_moodle($deploy->instance_url, $deploy->bearer, 'mod_forum_get_forum_discussion_posts', ['discussionid'=>$discussion['discussion']]);
                                                    foreach ($posts['posts'] as $post) {
                                                        if($post['userid'] == $student->id_instance){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                    if ($rule_requirement == true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach ($discussions['discussions'] as $discussion){
                                                    $posts = $this->connect_moodle($deploy->instance_url, $deploy->bearer, 'mod_forum_get_forum_discussion_posts', ['discussionid'=>$discussion['discussion']]);
                                                    foreach ($posts['posts'] as $post) {
                                                        if($post['userid'] == $student->id_instance){
                                                            $counter++;
                                                            if($counter >= $rule->param_1) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if ($rule_requirement == true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach ($discussions['discussions'] as $discussion){
                                                    $posts = $this->connect_moodle($deploy->instance_url, $deploy->bearer, 'mod_forum_get_forum_discussion_posts', ['discussionid'=>$discussion['discussion']]);
                                                    foreach ($posts['posts'] as $post) {
                                                        if($post['userid'] == $student->id_instance){
                                                            if(date( 'd/m/Y',$post['created']) <= $rule->param_1) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if ($rule_requirement == true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach ($discussions['discussions'] as $discussion){
                                                    $posts = $this->connect_moodle($deploy->instance_url, $deploy->bearer, 'mod_forum_get_forum_discussion_posts', ['discussionid'=>$discussion['discussion']]);
                                                    foreach ($posts['posts'] as $post) {
                                                        if($post['userid'] == $student->id_instance){
                                                            if((date( 'd/m/Y',$post['created']) >= $rule->param_1) && (date( 'd/m/Y',$post['created']) < $rule->param_2)) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if ($rule_requirement == true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                foreach ($discussions['discussions'] as $discussion){
                                                    $posts = $this->connect_moodle($deploy->instance_url, $deploy->bearer, 'mod_forum_get_forum_discussion_posts', ['discussionid'=>$discussion['discussion'],'sortby'=>'created','sortdirection'=>'ASC']);
                                                    foreach ($posts['posts'] as $i => $post) {
                                                        if($post['userid'] == $student->id_instance){
                                                            $students_rewarded = Student_engine::where('engine_id', $engine->id)->count();
                                                            if ($students_rewarded <= $rule->param_1) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if ($rule_requirement == true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("At least some group members have to perform the action"):
                                                break;
                                        }
                                        if ($rule_requirement == false) {
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Entry"):
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach ($discussions['discussions'] as $discussion){
                                                    if($discussion['userid'] == $student->id_instance){
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach ($discussions['discussions'] as $discussion){
                                                    if($discussion['userid'] == $student->id_instance){
                                                        $counter++;
                                                        if($counter >= $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach ($discussions['discussions'] as $discussion){
                                                    if($discussion['userid'] == $student->id_instance){
                                                        if(date( 'd/m/Y',$discussion['created']) <= $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach ($discussions['discussions'] as $discussion){
                                                    if($discussion['userid'] == $student->id_instance){
                                                        if((date( 'd/m/Y',$discussion['created']) >= $rule->param_1) && (date( 'd/m/Y',$discussion['created']) < $rule->param_2)) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                foreach ($discussions['discussions'] as $discussion){
                                                    if($discussion['userid'] == $student->id_instance){
                                                        $students_rewarded = Student_engine::where('engine_id', $engine->id)->count();
                                                        if ($students_rewarded <= $rule->param_1) {
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                                break;
                                            case("At least some group members have to perform the action"):
                                                break;
                                        }
                                        if ($rule_requirement == false) {
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Answer"):
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                foreach ($discussions['discussions'] as $discussion){
                                                    $posts = $this->connect_moodle($deploy->instance_url, $deploy->bearer, 'mod_forum_get_forum_discussion_posts', ['discussionid'=>$discussion['discussion']]);
                                                    foreach ($posts['posts'] as $post) {
                                                        if($post['parent'] != 0 && $post['userid'] == $student->id_instance){
                                                            $rule_requirement = true;
                                                            break;
                                                        }
                                                    }
                                                    if ($rule_requirement == true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach ($discussions['discussions'] as $discussion){
                                                    $posts = $this->connect_moodle($deploy->instance_url, $deploy->bearer, 'mod_forum_get_forum_discussion_posts', ['discussionid'=>$discussion['discussion']]);
                                                    foreach ($posts['posts'] as $post) {
                                                        if($post['parent'] != 0 && $post['userid'] == $student->id_instance){
                                                            $counter++;
                                                            if($counter >= $rule->param_1) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if ($rule_requirement == true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach ($discussions['discussions'] as $discussion){
                                                    $posts = $this->connect_moodle($deploy->instance_url, $deploy->bearer, 'mod_forum_get_forum_discussion_posts', ['discussionid'=>$discussion['discussion']]);
                                                    foreach ($posts['posts'] as $post) {
                                                        if($post['parent'] != 0 && $post['userid'] == $student->id_instance){
                                                            if(date( 'd/m/Y',$post['created']) <= $rule->param_1) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if ($rule_requirement == true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach ($discussions['discussions'] as $discussion){
                                                    $posts = $this->connect_moodle($deploy->instance_url, $deploy->bearer, 'mod_forum_get_forum_discussion_posts', ['discussionid'=>$discussion['discussion']]);
                                                    foreach ($posts['posts'] as $post) {
                                                        if($post['parent'] != 0 && $post['userid'] == $student->id_instance){
                                                            if((date( 'd/m/Y',$post['created']) >= $rule->param_1) && (date( 'd/m/Y',$post['created']) < $rule->param_2)) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if ($rule_requirement == true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                foreach ($discussions['discussions'] as $discussion){
                                                    $posts = $this->connect_moodle($deploy->instance_url, $deploy->bearer, 'mod_forum_get_forum_discussion_posts', ['discussionid'=>$discussion['discussion'],'sortby'=>'created','sortdirection'=>'ASC']);
                                                    foreach ($posts['posts'] as $i => $post) {
                                                        if($post['parent'] != 0 && $post['userid'] == $student->id_instance){
                                                            $students_rewarded = Student_engine::where('engine_id', $engine->id)->count();
                                                            if ($students_rewarded <= $rule->param_1) {
                                                                $rule_requirement = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if ($rule_requirement == true){
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("At least some group members have to perform the action"):
                                                break;
                                        }
                                        if ($rule_requirement == false) {
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Give Like"):
                                    break;
                                case("Give Like to an entry"):
                                    break;
                                case("Give Like to an answer"):
                                    break;
                                case("Receive Like"):
                                    //Se puede pero un poco percal
                                    //Igual que en Canvas, no se puede saber cuando, sólo la suma total
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
                                                break;
                                            case("Do the action before a specific date"):
                                                break;
                                            case("Do the action between a specific time frame"):
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                break;
                                            case("At least some group members have to perform the action"):
                                                break;
                                        }
                                        if ($rule_requirement == false) {
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Receive Like in an entry"):
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
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
                                        if ($rule_requirement == false) {
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Receive Like in an answer"):
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
                                                break;
                                            case("Do the action before a specific date"):
                                                break;
                                            case("Do the action between a specific time frame"):
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                break;
                                            case("At least some group members have to perform the action"):
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
                                    $attempts = $this->connect_moodle($deploy->site, $deploy->bearer, 'mod_quiz_get_user_attempts', ['quizid'=>$resource->instance_resource_id,'userid'=>$student->id_instance, 'status'=>'finished']);
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name){
                                            case("Do the action itself"):
                                                foreach($attempts['attempts'] as $attempt){
                                                    $rule_requirement = true;
                                                    break;
                                                }
                                                break;
                                            case("Do the action several times"):
                                                $counter = 0;
                                                foreach($attempts['attempts'] as $attempt){
                                                    $counter++;
                                                    if($counter <= $rule->param_1){
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action before a specific date"):
                                                foreach($attempts['attempts'] as $attempt){
                                                    if(date( 'd/m/Y',$attempt['timefinish']) <= $rule->param_1) {
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Do the action between a specific time frame"):
                                                foreach($attempts['attempts'] as $attempt){
                                                    if(date( 'd/m/Y',$attempt['timefinish']) >= $rule->param_1 && date( 'd/m/Y',$attempt['timefinish']) <= $rule->param_2) {
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                $students_rewarded = Student_engine::where('engine_id',$engine->id)->count();
                                                if ($students_rewarded <= $rule->param_1) {
                                                    foreach ($attempts['attempts'] as $attempt) {
                                                        $rule_requirement = true;
                                                        break;
                                                    }
                                                }
                                                break;
                                            case("At least some group members have to perform the action"):
                                                break;
                                            case("Get a validity score lower than X"):
                                                break;
                                            case("Get a reliability score lower than X"):
                                                break;
                                            case("Get an upper or equal score than X"):
                                                foreach($attempts['attempts'] as $attempt){
                                                    if($attempt['sumgrades'] <= $rule->param_1){
                                                        $rule_requirement = true;
                                                        break;
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
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
                                                break;
                                            case("Do the action before a specific date"):
                                                break;
                                            case("Do the action between a specific time frame"):
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                break;
                                        }
                                    }
                                    break;
                                case("Mark as done"):
                                    break;
                            }
                        }
                        break;
                    case('Peer Review'):
                        foreach($res_cond->actions as $action) {
                            switch($action->action_type->name){
                                case("Submit"):
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name){
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
                                                break;
                                            case("Do the action before a specific date"):
                                                break;
                                            case("Do the action between a specific time frame"):
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                break;
                                            case("Get an upper or equal score than X"):
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Comment"): //En cada peer review asignado
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
                                                break;
                                            case("Do the action before a specific date"):
                                                break;
                                            case("Do the action between a specific time frame"):
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Comment with a minimum number of characters"): //En cada peer review asignado --> 100 Characters
                                    $num_characters = 100;
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
                                                break;
                                            case("Do the action before a specific date"):
                                                break;
                                            case("Do the action between a specific time frame"):
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Receive Comment"): //En cada peer review asignado
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
                                                break;
                                            case("Do the action before a specific date"):
                                                break;
                                            case("Do the action between a specific time frame"):
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Answers and clarifications"):
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
                                                break;
                                            case("Do the action before a specific date"):
                                                break;
                                            case("Do the action between a specific time frame"):
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Fulfill the rubric"):
                                    foreach($action->rules as $rule){
                                        $rule_requirement = false;
                                        switch($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
                                                break;
                                            case("Do the action before a specific date"):
                                                break;
                                            case("Do the action between a specific time frame"):
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Visit"):
                                    break;
                                case("Mark as done"):
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
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
                                                break;
                                            case("Do the action before a specific date"):
                                                break;
                                            case("Do the action between a specific time frame"):
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Visit"):
                                    foreach ($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
                                                break;
                                            case("Do the action before a specific date"):
                                                break;
                                            case("Do the action between a specific time frame"):
                                                break;
                                            case("Be one of the first participants doing the action"):
                                                break;
                                        }
                                        if ($rule_requirement == false){
                                            return -1;
                                        }
                                    }
                                    break;
                                case("Mark as done"):
                                    break;
                            }
                        }
                        break;
                    case('File'):
                        foreach($res_cond->actions as $action) {
                            switch ($action->action_type->name) {
                                case("Open"):
                                    foreach($action->rules as $rule) {
                                        $rule_requirement = false;
                                        switch ($rule->rule_type->name) {
                                            case("Do the action itself"):
                                                break;
                                            case("Do the action several times"):
                                                break;
                                            case("Do the action before a specific date"):
                                                break;
                                            case("Do the action between a specific time frame"):
                                                break;
                                            case("Be one of the first participants doing the action"):
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
                                    break;
                                case("Quiz"):
                                    break;
                                case("Assignment"):
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
                            break;
                        case("Extra Attempts"):
                            switch($reward->redeemable_rewards[0]->resource->resource_type->name){
                                case("Quiz"):
                                    break;
                                default:
                                    return -1;
                                    break;
                            }
                            break;
                        case("Extra Time"):
                            switch($reward->redeemable_rewards[0]->resource->resource_type->name){
                                case("Quiz"):
                                    break;
                                default:
                                    return -1;
                                    break;
                            }
                            break;
                        case("Skip"):
                            switch($reward->redeemable_rewards[0]->resource->resource_type->name){
                                case("Quiz"):
                                    break;
                                case("Assignment"):
                                    break;
                                default:
                                    break;
                            }
                            break;
                        case("Lower Score"):
                            switch($reward->redeemable_rewards[0]->resource->resource_type_name){
                                case("Quiz"):
                                    break;
                                case("Assignment"):
                                    break;
                            }
                            break;
                        case("Extending Due Date"):
                            break;
                        case("Re-open"):
                            break;
                        case("Teachers Evaluation"):
                            break;
                        case("Individual or Collective"):
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
