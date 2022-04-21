<?php

namespace App\Console\Commands;

use App\Models\Deploys\Canvas2018\Canvas;
use App\Models\Deploys\Canvas2018\Student;
use App\Models\Deploys\Canvas2018\Student_activity;
use App\Models\Deploys\Canvas2018\Student_visit;
use Illuminate\Console\Command;
use Exception;

class save_activity8 extends Command{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'activities:8_graduated';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Check if GRADUATED activity was fulfilled and save the data for every student';

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

    public function realtime($id_instance){
        /* SAVE REWARD 8
         * @param1: obj peer_reviews_rubrics
         */
        $s = Student::where('id_instance', $id_instance)->first();
        $canvas = new Canvas();
        $canvas->open();

        $date = $s->save_reward_8();

        /* IF THE STUDENT HAS NO LOCAL (STUDENT_VISITS) DATA TO FULFILL THE REWARD
         * we call canvas for the activity
         */
        if(!$date){
            error_log('asking for activity of user-id-instance: ' . $s->id_instance);
            try {
                $activity = $canvas->get_activity($s->id_instance);
            } catch (Exception $e) {
                error_log('************************ student missing from canvas - error retrieving activities');
                return false;
            }

            $visits = Student_visit::where('student_id', $s->id)->first();
            if(!$visits){
                $visits = new Student_visit();
                $visits->student_id = $s->id;
            }

            $yesterday = date('Y-m-dT00:00:00+02:00',strtotime("-1 days"));

            $keys = collect($activity[0]->page_views)->keys();
            $keys->push('2019-01-01T18:00:00+02:00');
            $ikey=0;
            foreach ($activity[0]->page_views as $key => $npv) {

                $ikey++;

                /* IF $key is not in the range of we continue for the following */
                if($key < $yesterday) continue;
                error_log('asking for date:' . $key . ' --> '.$keys[$ikey].' # ' . $npv);

                $pages_views = $canvas->get_pageviews($s->id_instance, $key, $keys[$ikey]);

                //error_log(print_r($pages_views, true));

                foreach ($pages_views as $pv) {
                    try{
                        if (!$visits->video_1 && strpos($pv->url, "/pages/video-repaso-bloque-0"))
                            $visits->video_1 = $pv->created_at;
                        elseif (!$visits->video_2 && strpos($pv->url, "/pages/video-repaso-bloque-1"))
                            $visits->video_2 = $pv->created_at;
                        elseif (!$visits->video_3 && strpos($pv->url, "/pages/video-repaso-bloque-2"))
                            $visits->video_3 = $pv->created_at;
                        elseif (!$visits->video_4 && strpos($pv->url, "/pages/video-repaso-bloque-3"))
                            $visits->video_4 = $pv->created_at;
                        elseif (!$visits->video_5 && strpos($pv->url, "/pages/video-repaso-bloque-4"))
                            $visits->video_5 = $pv->created_at;
                        elseif (!$visits->video_6 && strpos($pv->url, "/pages/video-repaso-bloque-5"))
                            $visits->video_6 = $pv->created_at;
                    }catch (Exception $e){
                        error_log('************************ student non authorized URL not found in pageviews');
                        continue;
                    }
                }
            }
            $visits->save();

            /* WE check again after re-populate visits table */
            $date = $s->save_reward_8();
        }
        $canvas->close();
        return $date;
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
            //if($i < 936) continue;

            if(!$s->activities || !$s->activities->reward_8){

                /* SAVE REWARD 8
                 */
                $date = $s->save_reward_8();

                /* IF THE STUDENT HAS NO LOCAL (STUDENT_VISITS) DATA TO FULFILL THE REWARD
                 * we call canvas for the activity
                 */
                if(!$date){
                    error_log('asking for activity of user-id-instance: ' . $s->id_instance);
                    try {
                        $activity = $canvas->get_activity($s->id_instance);
                    } catch (Exception $e) {
                        error_log('************************ student missing from canvas - error retrieving activities');
                        $donotfulfill++;
                        continue;
                    }

                    $visits = Student_visit::where('student_id', $s->id)->first();

                    /* TESTING AREA **********************************************/
                    /*$visits = Student_visit::where('student_id', 738618)->first();
                    if(!$visits) {
                        error_log('!?');
                        error_log(print_r($visits, true));
                    }elseif(is_null($visits)){
                        error_log('null?');
                        error_log(print_r($visits, true));
                    }elseif(empty($visits)){
                        error_log('empty?');
                        error_log(print_r($visits, true));
                    }elseif(empty($count)){
                        error_log('count?');
                        error_log(print_r($visits, true));
                    }
                    return;
                    */
                    /*************************************************************/

                    if(!$visits){
                        $visits = new Student_visit();
                        $visits->student_id = $s->id;
                    }

                    $yesterday = date('Y-m-dT00:00:00+02:00',strtotime("-2 days"));

                    if(isset($activity[0]->page_views)) {
                        $keys = collect($activity[0]->page_views)->keys();
                        $keys->push('2019-01-01T18:00:00+02:00');
                        $ikey = 0;
                        foreach ($activity[0]->page_views as $key => $npv) {

                            $ikey++;

                            /* IF $key is not in the range of we continue for the following */
                            if ($key < $yesterday) continue;
                            error_log('asking for date:' . $key . ' --> ' . $keys[$ikey] . ' # ' . $npv);
                            if ($npv > 100)
                                $this->alert("************************ WARNING #pv > 100");

                            $pages_views = $canvas->get_pageviews($s->id_instance, $key, $keys[$ikey]);
                            foreach ($pages_views as $pv) {
                                try {
                                    if (!$visits->video_1 && strpos($pv->url, "/pages/video-repaso-bloque-0"))
                                        $visits->video_1 = $pv->created_at;
                                    elseif (!$visits->video_2 && strpos($pv->url, "/pages/video-repaso-bloque-1"))
                                        $visits->video_2 = $pv->created_at;
                                    elseif (!$visits->video_3 && strpos($pv->url, "/pages/video-repaso-bloque-2"))
                                        $visits->video_3 = $pv->created_at;
                                    elseif (!$visits->video_4 && strpos($pv->url, "/pages/video-repaso-bloque-3"))
                                        $visits->video_4 = $pv->created_at;
                                    elseif (!$visits->video_5 && strpos($pv->url, "/pages/video-repaso-bloque-4"))
                                        $visits->video_5 = $pv->created_at;
                                    elseif (!$visits->video_6 && strpos($pv->url, "/pages/video-repaso-bloque-5"))
                                        $visits->video_6 = $pv->created_at;
                                } catch (Exception $e) {
                                    error_log('************************ student non authorized URL not found in pageviews');
                                    $donotfulfill++;
                                    continue;
                                }
                            }
                        }
                        $visits->save();

                        /* WE check again after re-populate visits table */
                        $date = $s->save_reward_8();
                    }
                }

                /* IF there is a date
                 * we save it!
                 */
                if ($date) {
                    $new++;
                    $act = Student_activity::where('student_id', $s->id)->first();
                    if (!$act){ $act = new Student_activity(); $act->student_id = $s->id;}
                    $act->reward_8 = $date;
                    $act->save();
                } else
                    $donotfulfill++;
            }else $found++;

            // Output the partial summary
            $this->infotimer("$i/$num submissions processed of total students. New:$new - Found:$found - DonotFulfill: $donotfulfill");
        }

        $canvas->close();
        $this->alert(date('Y-m-d H:i:s')." - $i/$num submissions processed of total students. New:$new - Found:$found - DonotFulfill: $donotfulfill");
        return;
    }
}
