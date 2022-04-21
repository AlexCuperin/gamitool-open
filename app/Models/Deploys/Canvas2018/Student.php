<?php

namespace App\Models\Deploys\Canvas2018;

use App\Http\Controllers\StudentController;

class Student extends \App\Models\Student {

    const DEFAULT_IMG_NAME = '%2Fimages%2Fmessages%2Favatar-50.png';

    /* Relationships */
    public function activities(){
        return $this->hasOne('App\Models\Deploys\Canvas2018\Student_activity', 'student_id','id');
    }
    public function submissions(){
        return $this->hasOne('App\Models\Deploys\Canvas2018\Student_submission', 'student_id','id');
    }
    public function visits(){
        return $this->hasOne('App\Models\Deploys\Canvas2018\Student_visit', 'student_id','id');
    }
    public function engagements(){
        return $this->hasMany('App\Models\Deploys\Canvas2018\Student_engagement', 'student_id','id');
    }

    /* Methods */
    private function check_profile_photo($profile){
        if(isset($profile[0]->avatar_url)) {
            $myavatar = $profile[0]->avatar_url;

            if (!strpos($myavatar, $this::DEFAULT_IMG_NAME))
                return true;
        }
        return false;
    }

    private function check_presentation($forum){
        foreach($forum[0]->view as $view){
            if(isset($view->user_id) && $view->user_id == $this->id_instance){
                return $view->created_at;
            }
        }
        return false;
    }

    public function save_reward_1($profile, $forum){
        // check if the phote was updated
        $updated = $this->check_profile_photo($profile);

        // check if the presentation was made if the photo was updated
        $date = ($updated)?$this->check_presentation($forum):false;

        // return presentation date if existed
        return $date;
    }

    public function check_reward_2($submisions0, $submisions1){
        $date0 = $date = false;

        foreach($submisions0 as $sub){
            if($sub->user_id == $this->id_instance){
                $date0 = $sub->finished_at;
                break;
            }
        }

        if($date0){
            foreach($submisions1 as $sub){
                if($sub->user_id == $this->id_instance){
                    if($sub->finished_at > $date0)
                        $date = $sub->finished_at;
                    else
                        $date = $date0;
                    break;
                }
            }
        }

        return $date;
    }

    public function check_reward_3($spreadsheet){
        $counter = 0;
        foreach($spreadsheet as $row){
            if($row['Email address'] == $this->email_instance){
                $counter++;
                if($counter == 3){

                    $a = strptime($row['Timestamp'], '%d/%m/%Y %H:%M:%S');
                    return date('m-d-Y H:i:s', mktime($a['tm_hour']+1, $a['tm_min']+1, $a['tm_sec']+1, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900));
                }
            }
        }
        return false;
    }

    public function save_reward_4($forum){

        if(isset($forum[0]->view)) {
            foreach ($forum[0]->view as $view) {

                if (isset($view->user_id) &&
                    $view->user_id == $this->id_instance &&
                    array_key_exists('rating_sum', $view) &&
                    $view->rating_sum >= 5)

                    return date("Y-m-d H:i:s");

                if (array_key_exists('replies', $view))
                    foreach ($view->replies as $reply)
                        if (isset($reply->user_id) &&
                            $reply->user_id == $this->id_instance &&
                            array_key_exists('rating_sum', $reply) &&
                            $reply->rating_sum >= 5)

                            return date("Y-m-d H:i:s");
            }
        }
        return false;
    }

    //public function save_reward_5($submission){
    //
    //}

    public function save_reward_6($peer_reviews){

        //error_log('instance_id:'.$this->id_instance);
        //error_log('-------------------------------');

        $counter = 0;
        $date = date('Y-m-dTH:i:s', strtotime("2011-01-01T00:00:00Z"));
        foreach($peer_reviews as $review) {
            if(!is_null($review)
              && array_key_exists('assessor_id', $review)
              && ($review->assessor_id == $this->id_instance)
              && ($review->workflow_state == "completed")
                ){
                $counter++;

                foreach ($review->submission_comments as $sc){
                    if($sc->author_id == $this->id_instance){
                        $auxdate = date('Y-m-dTH:i:s', strtotime($sc->created_at));

                        //error_log('date:::::::::'.$sc->created_at.' == '.$auxdate);

                        if($auxdate > $date) $date = $auxdate;
                    }
                }

                if($counter >= 4) {

                    return $date;
                }
            }
        }

        return false;
    }

    public function save_reward_7($rubric, $submission_id){

        $num_reviews = 0;
        $score = 0;
        if (array_key_exists('assessments', $rubric[0])) {
            foreach ($rubric[0]->assessments as $assessment) {
                if ($assessment->artifact_id == $submission_id) {
                    if($assessment->score && $assessment->score != 0) {
                        $score = $score + $assessment->score;
                        $num_reviews++;
                    }
                }
            }

            if ($num_reviews) {
                $score = $score / $num_reviews;
                if ($score >= 70) {

                    return date("Y-m-d H:i:s");
                }
            }
        }

        return false;
    }

    public function save_reward_8(){
        $visits = Student_visit::where('student_id', $this->id)->first();
        if(!$visits) return false;

        if($visits->video_1 && $visits->video_2 && $visits->video_3 &&
           $visits->video_4 && $visits->video_5 && $visits->video_6) {
                $max = max([$visits->video_1, $visits->video_2, $visits->video_3,
                            $visits->video_4, $visits->video_5, $visits->video_6]);
                return $max;
        }
        return false;
    }
}
