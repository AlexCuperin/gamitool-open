<?php

namespace App\Models\Deploys\Canvas2018;

use Illuminate\Database\Eloquent\Model;

class Student_visit extends Model{

    public $timestamps = true;
    protected $table = 'student_visits';

    protected $fillable = [
        'student_id', 'video_1', 'video_2', 'video_3', 'video_4', 'video_5', 'video_6'
    ];

    public function student(){
        return $this->belongsTo('App\Models\Deploys\Canvas2018\Student', 'student_id', 'id');
    }

    public function is_watched($i){
        return $this['video_'.$i];
    }
}
