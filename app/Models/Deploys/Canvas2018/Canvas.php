<?php

namespace App\Models\Deploys\Canvas2018;

use App\Classes\Curl;
use Illuminate\Database\Eloquent\Model;
use Sheets;
use Google;

class Canvas extends Model{

    const TOKEN_TEST = "Authorization: Bearer 9520~Cdd4MGnCJXjLyCDzAnZ15PZSMPwCyx4sl7x9Oly7rrsWFwFNEIpRpno3RkaDF3nJ";
    const SITE_TEST  = "gsic-emic.instructure.com";

    const TOKEN      = "Authorization: Bearer 11~BckgTSPnSeYtDHLlWh4KgDM4cZLVsoIePM0be4CY2S9w265RWDdzJKB936na36k8";
    const SITE       = "learn.canvas.net";

    const COURSE_ID     = 2188;
    const CAFE_ID       = 37413;
    const QUIZ_0_ID     = 18372;
    const QUIZ_1_ID     = 18382;
    const QUIZ_6_ID     = 18377;
    const PARTEXT_ID    = 38507;
    const ASSIGNMENT_ID = 24037;
    const RUBRIC_ID     = 1969;
    const TASK1_ID      = 24048;
    const TASK2_ID      = 24049;

    private $cURL;

    public function open_test(){ $this->cURL = new Curl($this::TOKEN_TEST, $this::SITE_TEST); }
    public function open(){ $this->cURL = new Curl($this::TOKEN, $this::SITE); }
    public function close(){ $this->cURL->closeCurl(); }
    public function connect_get($url){ return $this->cURL->get($url); }
    public function connect_post($url, $data){ return $this->cURL->post($url, $data); }

    /*********************************************************/
    /* Specific OBJECT interesting for general user activity */
    /*-------------------------------------------------------*/
    public function get_engagement(){
        return $this->connect_get('/courses/'.$this::COURSE_ID.'/analytics/student_summaries');
    }

    public function get_generic_sub($id){
        return $this->connect_get('/courses/'.$this::COURSE_ID.'/assignments/'.$id.'/submissions/');
    }


    /*********************************************************/
    /* Specific OBJECTS interesting for the activity rewards */
    /*-------------------------------------------------------*/
    /* REWARD 1: WELCOME
     * - profile
     * - forum
     */
    public function get_profile($user_id){
        return $this->connect_get("/users/".$user_id."/profile");
    }
    public function get_forum(){
        return $this->connect_get('/courses/'.$this::COURSE_ID.'/discussion_topics/'.$this::CAFE_ID.'/view');
    }

    /* REWARD 2: QUIZ MASTER
     * - quiz submissions
     * - filter submissions
     */
    public function get_quizsubmissions($quiz_number){
        switch ($quiz_number){
            case 0:  $quiz_id = $this::QUIZ_0_ID; break;
            case 1:  $quiz_id = $this::QUIZ_1_ID; break;
            case 6:  $quiz_id = $this::QUIZ_6_ID; break;
            default: $quiz_id = $this::QUIZ_0_ID;
        }
        return $this->connect_get('/courses/'.$this::COURSE_ID.'/quizzes/'.$quiz_id.'/submissions');
    }

    public function filter_submissions($quiz_submissions){
        return collect($quiz_submissions)
            ->pluck('quiz_submissions')
            ->collapse()
            ->filter(function($sub, $key){

                return $sub->workflow_state === 'complete' && $sub->kept_score >= 18;
            })->values();
    }

    /* REWARD 3: GLOSSARY MASTER
     * - google spreadsheet
     */
    public function get_spreadsheet(){
        Sheets::setService(Google::make('sheets'));
        Sheets::spreadsheet('1qYFoqyiyXhlUM39Pbdl1ewh22Jb7r5OoUDiD3xP3Nr8');

        $rows = Sheets::sheet('Respuestas Glosario')->get();
        $header = $rows->pull(0);
        return Sheets::collection($header, $rows);
    }

    /* REWARD 4: TEXT PROVIDER
     * - specific forum parallel texts
     */
    public function get_forum_partext(){
        return $this->connect_get('/courses/'.$this::COURSE_ID.'/discussion_topics/'.$this::PARTEXT_ID.'/view');
    }

    /* REWARD 5: TRANSLATOR MASTER
     *
     */
    public function get_task1_submissions(){
        return $this->connect_get('/courses/'.$this::COURSE_ID.'/assignments/'.$this::TASK1_ID.'/submissions/');
    }
    public function get_task2_submissions(){
        return $this->connect_get('/courses/'.$this::COURSE_ID.'/assignments/'.$this::TASK2_ID.'/submissions/');
    }

    /* REWARD 6: EXPERT REVIEWER
     * - get the peer-reviews
     */
    public function get_peer_review(){
        //return $this->connect_get('/courses/'.$this::COURSE_ID.'/assignments/'.$this::ASSIGNMENT_ID.'/peer_reviews');
        return $this->connect_get('/courses/'.$this::COURSE_ID.'/assignments/'.$this::ASSIGNMENT_ID.'/peer_reviews?include[]=submission_comments');
    }

    /* REWARD 7: SMARTIE
     * - get task submissions
     */
    public function get_submissions(){
        return $this->connect_get('/courses/'.$this::COURSE_ID.'/assignments/'.$this::ASSIGNMENT_ID.'/submissions/');
    }
    // get the rubrics
    public function get_rubric(){
        return $this->connect_get('/courses/'.$this::COURSE_ID.'/rubrics/'.$this::RUBRIC_ID.'?include=peer_assessments');
    }

    /* REWARD 8: GRADUATED
     * - get activity of the user
     */
    public function get_activity($user_id){
        return $this->connect_get('/courses/'.$this::COURSE_ID.'/analytics/users/'.$user_id.'/activity');
    }
    public function get_pageviews($user_id, $start, $end){
        return $this->connect_get('/users/'.$user_id.'/page_views?start_time='.$start.'&end_time='.$end);
    }
}
