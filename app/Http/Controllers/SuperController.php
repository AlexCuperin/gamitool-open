<?php

namespace App\Http\Controllers;

use App\Classes\Curl;
use App\Models\Deploys\Canvas2018\Canvas;
use App\Models\Deploys\Canvas2018\Student_activity;
use App\Models\Gamification_deploy;
use App\Models\Gamification_design;
use App\Models\Student;
use ErrorException;
use Illuminate\Support\Facades\DB;

class SuperController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reassign(){
        return $this->store('process_remaining');
    }

    /**
     */
    public function store($delete){
        $msg = "";

        // DELETE ROWS FROM TABLE IF DELETE OPTION IS ACTIVE
        if($delete === "delete") {
            $msg = "clear and ";
            DB::table('students')->delete();
        }

        // GET LIST OF STORED STUDENTS IN GAMITOOL DB
        $gamitool_students = Student::select('email_instance')->get()->pluck('email_instance');
        $count = count($gamitool_students);

        // CONNECTION TO CANVAS
        $token = "Authorization: Bearer 11~hVtsiGfbd02B8CyoDLfVei06U756E09WuGCzeoGDr7dKygNtsMEiLVVXdiaLrcAG";
        $site  = "learn.canvas.net";
        $course_id = 2188;
        $cURL = new Curl($token, $site);
        //$users = $cURL->get("/courses/".$course_id."/students");
        $enrollments = $cURL->get("/courses/".$course_id."/enrollments");
        $cURL->closeCurl();

        //return $enrollments;
        //return $gamitool_students;

        // PROCESSING DATA FROM CANVAS
        $canvas_students = collect($enrollments)->sortBy('created_at');
        if(count($canvas_students) > Student::THRES_3GROUPS)
            $num_groups = 3;
        else
            $num_groups = 2;

        $new_users = 0;

        // STORE NEW USERS
        foreach ($canvas_students as $key=>$enr) {


            // IF THE ENR DOES NOT HAVE ROLE
            //if( (!isset($enr)) || (!(array_key_exists('role', $enr)))) return $enr->user->name;

            if($enr->role == "StudentEnrollment") {
                $user = $enr->user;

                // IF THE USER DIDNT ACCEPT THE INVITATION
                // IT DOES NOT HAVE LOGIN_ID
                if((!isset($user)) || (!(array_key_exists('login_id', $user)))) continue;

                if($gamitool_students->search($user->login_id) === false) {
                    $student = new Student();
                    $student->gdeploy_id = $count%$num_groups+1;
                    $student->id_instance = $user->id;
                    $student->email_instance = $user->login_id;
                    $student->name_instance = $user->name;
                    $student->enrolled_at = $enr->created_at;
                    $student->save();


                    switch ($student->gdeploy_id) {
                        case("1"): //Gamification_B
                            $url = "/groups/" . "4474" . "/memberships"; // "4474"
                            break;
                        case("2"): //Gamification_RR
                            $url = "/groups/" . "4475" . "/memberships"; //"4475"

                            $url_extra_content = "/groups/" . "4453" . "/memberships"; //"4453"
                            $data_extra_content = ['user_id' => $student->id_instance];
                            try {
                                $cURL = new Curl($token, $site);
                                $cURL->post($url_extra_content, $data_extra_content);
                                $cURL->closeCurl();
                            } catch (ErrorException $e) {
                                error_log('-- exception :( -- SuperController Exception $cURL->post');
                                $m = $e->getMessage();
                                $f = $e->getFile();
                                $l = $e->getLine();
                                error_log("------------->");
                                error_log("$m $f:$l");
                                error_log($e->getTraceAsString());
                                error_log('+++++++++++++++++++++++++++++++++');
                            }
                            break;
                        case("3"): //Gamification_Control
                            $url = "/groups/" . "4452" . "/memberships"; //"4452"
                            break;
                    }
                    $data = ['user_id' => $student->id_instance];

                    try {
                        $cURL = new Curl($token, $site);
                        $cURL->post($url, $data);
                        $cURL->closeCurl();
                    } catch (ErrorException $e) {
                        error_log('-- exception :( -- SuperController Exception $cURL->post');
                        $m = $e->getMessage();
                        $f = $e->getFile();
                        $l = $e->getLine();
                        error_log("------------->");
                        error_log("$m $f:$l");
                        error_log($e->getTraceAsString());
                        error_log('+++++++++++++++++++++++++++++++++');
                    }

                    $count++;
                    $new_users++;
                }
            }
        }

        return $msg."success previous: ".count($gamitool_students)." and new users: ".$new_users;
    }

    public function view_stats(){
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

        $activities = Student_activity::all();

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
        //return $total_sub;

        foreach($gldrr->gamification_engines as $ge){
            foreach($ge->rewards as $reward){
                if($reward->reward_type->name == "Redeemable Rewards"){
                    if($reward->redeemable_rewards[0]->rr_type->name == "Teachers Evaluation"){
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

        

        return view('teachers.teacher_view',
                            ['gld'       => $gld,       'gldrr'     => $gldrr,
                             'total_act' => $total_act, 'total_sub' => $total_sub ]);
    }

    //public function check_stats(){}
}
