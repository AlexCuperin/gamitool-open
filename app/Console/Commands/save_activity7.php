<?php

namespace App\Console\Commands;

use App\Models\Deploys\Canvas2018\Canvas;
use App\Models\Deploys\Canvas2018\Student;
use App\Models\Deploys\Canvas2018\Student_activity;
use Illuminate\Console\Command;
use Exception;

class save_activity7 extends Command{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'activities:7_smartie';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Check if SMARTIE activity was fulfilled and save the data for every student';

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

        // Open a new Canvas instance
        $canvas = new Canvas();
        $canvas->open();

        // Retrieve the students from the database
        $students = Student::with('activities')->get();
        $num = count($students);

        // We need the rubric and submissions object to check the reward 7
        $submission = $canvas->get_submissions();
        $this->info(date('Y-m-d H:i:s').' - submissions retrieved');
        $rubric     = $canvas->get_rubric();
        $this->info(date('Y-m-d H:i:s').' - rubrics retrieved');

        // Variables to summarize the result of the process
        $i = $new = $found = $donotfulfill = 0;

        // Process the checking of each student
        foreach ($submission as $s){
            $i++;

            if ($s->workflow_state != "unsubmitted") {

                $user_id = $s->user_id;
                $u = $students->first(function($item) use ($user_id){return $item->id_instance == $user_id;});

                //error_log("iteration: $i - usersubmissionid: $user_id");
                //error_log(print_r($u, true));

                if(!$u->activities || !$u->activities->reward_7){

                    /* SAVE REWARD 7
                     * @param1: obj peer_reviews_rubrics
                     */
                    $date = $u->save_reward_7($rubric, $s->id);

                    if ($date) {
                        $new++;
                        $act = Student_activity::where('student_id', $u->id)->first();
                        if (!$act) $act = new Student_activity();
                        $act->student_id = $u->id;
                        $act->reward_7 = $date;
                        //error_log($u->id_instance.' completed in '.$date);
                        $act->save();
                    } else
                        $donotfulfill++;
                }else $found++;
            }

            // Output the partial summary
            $this->infotimer("$i/$num submissions processed of total students. New:$new - Found:$found - DonotFulfill: $donotfulfill");
        }

        $canvas->close();
        $this->alert(date('Y-m-d H:i:s')." - $i/$num submissions processed of total students. New:$new - Found:$found - DonotFulfill: $donotfulfill");
        return;
    }
}
