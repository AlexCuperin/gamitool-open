<?php

namespace App\Console\Commands;

use App\Models\Deploys\Canvas2018\Canvas;
use App\Models\Deploys\Canvas2018\Student;
use App\Models\Deploys\Canvas2018\Student_activity;
use App\Models\Deploys\Canvas2018\Student_engagement;
use App\Models\Deploys\Canvas2018\Student_submission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class save_submissions extends Command{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'activities:9_submissions';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Stores the date of submission of mandatory module task';

    /**
     * Create a new command instance.
     * @return void
     */
    public function __construct(){ parent::__construct(); }

    /**
     * This call to info but with a minimum $time diff
     * @param $msg
     * @param int $time
     */
    private $last_timer = null;
    private function infotimer($msg, $time = 2){
        $skip = false;
        if(!is_null($this->last_timer)){
            $diff = microtime(true) - $this->last_timer;
            if($diff < $time){
                $skip = true;
            }
        }

        if($skip) return;
        $this->last_timer = microtime(true);

        $this->info($msg);
    }

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle(){
        $this->info($this->description);

        $students = Student::all();

        // Open a new Canvas instance
        $canvas = new Canvas();
        $canvas->open();

        /* START THE PROCESSING OF MANDATORY TASKS */

        $sub0 = $canvas->get_quizsubmissions(0);
        $this->info('retrieved object 1/7');
        $this->process_sub($students, $sub0, 0);
        $this->info('1/7 proccessed: quiz0-submissions');

        $sub1 = $canvas->get_quizsubmissions(1);
        $this->info('retrieved object 2/7');
        $this->process_sub($students, $sub1, 1);
        $this->info('2/7 proccessed: quiz1-submissions');

        $sub2 = $canvas->get_generic_sub(24037);
        $this->info('retrieved object 3/7');
        $this->process_assigments($students, $sub2, 2);
        $this->info('3/7 proccessed: assigment1');

        $sub3 = $canvas->get_generic_sub(24616);
        $this->info('retrieved object 4/7');
        $this->process_assigments($students, $sub3, 3);
        $this->info('4/7 proccessed: assigment2');

        $sub4 = $canvas->get_generic_sub(24050);
        $this->info('retrieved object 5/7');
        $this->process_assigments($students, $sub4, 4);
        $this->info('5/7 proccessed: assigment3');

        $sub5 = $canvas->get_generic_sub(24617);
        $this->info('retrieved object 6/7');
        $this->process_assigments($students, $sub5, 5);
        $this->info('6/7 proccessed: assigment4');

        $sub6 = $canvas->get_quizsubmissions(6);
        $this->info('retrieved object 7/7');
        $this->process_sub($students, $sub6, 6);
        $this->info('7/7 proccessed: quiz6-submissions');

        $canvas->close();

        $this->alert(date('Y-m-d H:i:s')." - mandatory subs finished");
        return;
    }

    private function process_assigments($students, $assigments, $ix){
        foreach ($assigments as $assigment){
            if($assigment->workflow_state == 'unsubmitted') continue;
            if(!isset($assigment->user_id)) continue;

            foreach ($students as $student){
                if($student->id_instance == $assigment->user_id){
                    $ss  = Student_submission::where('student_id', $student->id)->first();
                    $sid = $student->id;
                }
            }

            if(!$ss){
                $ss = new Student_submission();
                $ss->student_id = $sid;
            }

            $ss['module_'.$ix]   = $assigment->submitted_at;
            $ss['attempts_'.$ix] = $assigment->attempt;
            $ss['score_'.$ix]    = $assigment->score;
            $ss->save();
        }
    }

    private function process_sub($students, $sub, $ix){
        foreach ($sub as $sx){
            foreach ($sx->quiz_submissions as $submission){

                if(!($submission->workflow_state == 'complete')) continue;

                foreach ($students as $student){
                    if($student->id_instance == $submission->user_id){
                        $ss  = Student_submission::where('student_id', $student->id)->first();
                        $sid = $student->id;
                    }
                }

                if(!$ss){
                    $ss = new Student_submission();
                    $ss->student_id = $sid;
                }

                $ss['module_'.$ix]   = $submission->finished_at;
                $ss['attempts_'.$ix] = $submission->attempt;
                $ss['score_'.$ix]    = $submission->score;
                $ss->save();
            }
        }
    }
}
