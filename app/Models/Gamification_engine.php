<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gamification_engine extends Model{

    public $timestamps = true;
    protected $table = 'gamification_engines';

    protected $fillable = [
        'gdesign_id', 'name','description','condition_op'
    ];

    /*Relationships*/
    public function gamification_design(){
        return $this->belongsTo('App\Models\Gamification_design', 'gdesign_id', 'id');
    }
    public function rewards(){
        return $this->hasMany('App\Models\Reward', 'engine_id', 'id');
    }
    public function conditions(){
        return $this->hasMany('App\Models\Condition', 'engine_id', 'id');
    }
    public function students(){
        return $this->belongsToMany('App\Models\Student', 'student_engine','engine_id','student_id');
    }
    public function rewarded_students(){
        return $this->belongsToMany('App\Models\Student', 'student_engine','engine_id','student_id')
            ->wherePivot('issued',true)
            ->withPivot('created_at')
            ->orderBy('student_engine.created_at', 'asc');
    }

    public function requesting_students(){
        return $this->belongsToMany('App\Models\Student', 'student_engine','engine_id','student_id')
            ->orderBy('student_engine.created_at', 'asc');
    }

    public function count_requesting_students(){
        return $this->belongsToMany('App\Models\Student', 'student_engine','engine_id','student_id')
            ->select('student_id');
    }
}
