<?php

namespace App\Console\Commands;

use App\Models\Deploys\Canvas2018\Canvas;
use App\Models\Deploys\Canvas2018\Student;
use App\Models\Deploys\Canvas2018\Student_activity;
use App\Models\Deploys\Canvas2018\Student_engagement;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class save_engagement extends Command{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'activities:0_engagement';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Stores the current engagement of the course into the DB';

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

        // We need the engagement from canvas course
        $list = $canvas->get_engagement();

        // Add data to an array to later save in bulk
        $i = 0;
        $dataSet = [];
        foreach ($list as $row){
            $i++;

            array_push($dataSet, [
                'student_id_instance' => $row->id,
                'page_views'          => $row->page_views,
                'participations'      => $row->participations,
                'submissions'         => $row->tardiness_breakdown->on_time+
                                         $row->tardiness_breakdown->late,
                'created_at'          => date('Y-m-d H:i:s')
            ]);
        }
        DB::table('student_engagement')->insert( $dataSet );

        $canvas->close();
        $this->alert(date('Y-m-d H:i:s')." - $i users stored.");
        return;
    }
}
