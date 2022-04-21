<?php

namespace App\Console\Commands;

use App\Models\Deploys\Canvas2018\Canvas;
use App\Models\Deploys\Canvas2018\Student;
use App\Models\Deploys\Canvas2018\Student_activity;
use App\Models\Deploys\Canvas2018\Student_visit;
use Illuminate\Console\Command;
use Exception;

class first_visitpopulation extends Command{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'activities:8_firstvisitpopulation';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Retrieves and stores all highlighted visited pages into the database';

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

        // Variables to summarize the result of the process
        $i = $new = $found = $donotfulfill = 0;

        // Process the checking of each student
        foreach ($students as $s){
            $i++;

            $act = Student_visit::where('student_id', $s->id)->first();

            if (!$act) {
                $act = new Student_visit();
                $act->student_id = $s->id;
            }else{
                continue;
            }

            error_log('asking for activity of user-id-instance: ' . $s->id_instance);
            try {
                $activity = $canvas->get_activity($s->id_instance);
            } catch (Exception $e) {
                error_log('***** student missing from canvas - error retrieving activities');
                continue;
            }

            $keys = collect($activity[0]->page_views)->keys();
            $keys->push('2019-01-01T18:00:00+02:00');
            $ikey=0;
            foreach ($activity[0]->page_views as $key => $npv) {
                $ikey++;
                error_log('asking for date:' . $key . ' --> '.$keys[$ikey].' # ' . $npv);
                if($npv > 100)
                    $this->alert("******* WARNING #pv > 100");

                $pages_views = $canvas->get_pageviews($s->id_instance, $key, $keys[$ikey]);
                foreach ($pages_views as $pv) {
                    if(isset($pv->url)) {
                        if (!$act->video_1 && strpos($pv->url, "/pages/video-repaso-bloque-0"))
                            $act->video_1 = $pv->created_at;
                        elseif (!$act->video_2 && strpos($pv->url, "/pages/video-repaso-bloque-1"))
                            $act->video_2 = $pv->created_at;
                        elseif (!$act->video_3 && strpos($pv->url, "/pages/video-repaso-bloque-2"))
                            $act->video_3 = $pv->created_at;
                        elseif (!$act->video_4 && strpos($pv->url, "/pages/video-repaso-bloque-3"))
                            $act->video_4 = $pv->created_at;
                        elseif (!$act->video_5 && strpos($pv->url, "/pages/video-repaso-bloque-4"))
                            $act->video_5 = $pv->created_at;
                        elseif (!$act->video_6 && strpos($pv->url, "/pages/video-repaso-bloque-5"))
                            $act->video_6 = $pv->created_at;
                    }
                }
            }

            /* SAVE THE INFO */
            $act->save();

            // Output the partial summary
            $this->infotimer("$i/$num students processed of total students. New:$new - Found:$found - DonotFulfill: $donotfulfill");
        }

        $canvas->close();
        $this->alert(date('Y-m-d H:i:s')." - $i/$num students processed of total students. New:$new - Found:$found - DonotFulfill: $donotfulfill");
        return;
    }
}
