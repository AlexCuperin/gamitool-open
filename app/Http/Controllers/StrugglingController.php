<?php

namespace App\Http\Controllers;

use App\Classes\Curl;
use App\Models\Gamification_deploy;
use App\Models\Gamification_design;
use App\Models\Gamification_engine;
use App\Models\Resource_deploy;
use App\Models\Student;
use App\Models\Student_engine;
use App\Models\User;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\Exception\Exception;

class StrugglingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($_POST['oauth_consumer_key'] != config('canvas.handshake'))
            return response()->json(['error' => 'Not authorized.'], 403);

        //if (strpos($_POST['roles'], 'Instructor') !== false){

      return view('struggling.teacher_view');

  //      } else if (strpos($_POST['roles'], 'Learner') !== false) {

            /*$student = new Struggling_visitor();
            $student->student_id = $_POST['custom_canvas_user_id'];
            $student->canvas_course_id = $_POST['custom_canvas_course_id'];
            $student->save();*/

      //      return view('struggling.student_view');
    //    }

    }
}
