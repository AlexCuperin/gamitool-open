<?php

namespace App\Console\Commands;

use App\Models\Deploys\Canvas2018\Canvas;
use App\Models\Deploys\Canvas2018\Student;
use App\Models\Deploys\Canvas2018\Student_activity;
use Illuminate\Console\Command;

class save_activity5 extends Command{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'activities:5_translatormaster';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Check if TRANSLATORMASTER activity was fulfilled and save the data for every student';

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

        // We need the submissions of 2 tasks to check the reward 5
        $subs1 = collect($canvas->get_task1_submissions());
        $this->info(date('Y-m-d H:i:s').' - '.count($subs1).' subs1 retrieved');
        $filter1 = $subs1->filter(function($s){ return $s->workflow_state != "unsubmitted";})->sortBy('user_id');
        $this->info('filtered '.count($filter1));

        $subs2 = collect($canvas->get_task2_submissions());
        $this->info(date('Y-m-d H:i:s').' - '.count($subs2).' subs2 retrieved');
        $filter2 = $subs2->filter(function($s){ return $s->workflow_state != "unsubmitted";})->sortBy('user_id');
        $this->info('filtered '.count($filter2));

        // Variables to summarize the result of the process
        $i = $new = $found = $donotfulfill = 0;

        // Process the checking of each student
        foreach ($filter2 as $s){
            $i++;
            $user_id = $s->user_id;

            if ($filter1->search(function($item) use ($user_id) { return $item->user_id == $user_id; })) {

                $u = $students->first(function($item) use ($user_id){return $item->id_instance == $user_id;});

                //error_log("iteration: $i - usersubmissionid: $user_id");
                //error_log(print_r($u, true));

                if(!$u->activities || !$u->activities->reward_5){

                    /* SAVE REWARD 5
                     * @param1: obj peer_reviews_rubrics
                     */
                    //$date = $u->save_reward_7($s);

                    $new++;
                    $act = Student_activity::where('student_id', $u->id)->first();
                    if (!$act) $act = new Student_activity();
                    $act->student_id = $u->id;
                    $act->reward_5 = $s->submitted_at;
                    //error_log($u->id_instance.' completed in '.$date);
                    $act->save();

                }else $found++;

            } else
                $donotfulfill++;

            // Output the partial summary
            $this->infotimer("$i/".count($filter2)." submissions processed of total students. New:$new - Found:$found - DonotFulfill: $donotfulfill");
        }

        $canvas->close();
        $this->alert(date('Y-m-d H:i:s')." - $i/".count($filter2)." submissions processed of total students. New:$new - Found:$found - DonotFulfill: $donotfulfill");
        return;
    }
}
