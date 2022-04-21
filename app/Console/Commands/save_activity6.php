<?php

namespace App\Console\Commands;

use App\Models\Deploys\Canvas2018\Canvas;
use App\Models\Deploys\Canvas2018\Student;
use App\Models\Deploys\Canvas2018\Student_activity;
use Illuminate\Console\Command;

class save_activity6 extends Command{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'activities:6_expertreviewer';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Check if EXPERTREVIEWER activity was fulfilled and save the data for every student';

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

        // We need the peer reviews to check the reward 6
        $peer_reviews = $canvas->get_peer_review();
        //error_log(print_r($peer_reviews, true));
        //return;

        $this->info(date('Y-m-d H:i:s').' - peer reviews retrieved');

        // Variables to summarize the result of the process
        $i = $new = $found = $donotfulfill = 0;

        // Process the checking of each student
        foreach ($students as $u){
            $i++;

            if(!$u->activities || !$u->activities->reward_6){

                /* SAVE REWARD 6
                 * @param1: obj peer_reviews
                 */
                $date = $u->save_reward_6($peer_reviews);

                if($date){
                    $new++;
                    $act = Student_activity::where('student_id', $u->id)->first();
                    if(!$act) $act = new Student_activity();
                    $act->student_id = $u->id;
                    $act->reward_6 = $date;
                    //error_log($u->id_instance.' completed in '.$date);
                    $act->save();
                }else
                    $donotfulfill++;

            }else $found++;

            // Output the partial summary
            $this->infotimer("$i/$num users processed. New:$new - Found:$found - DonotFulfill: $donotfulfill");
        }

        $canvas->close();
        $this->alert(date('Y-m-d H:i:s')." - $i/$num users processed. New:$new - Found:$found - DonotFulfill: $donotfulfill");
        return;
    }
}
