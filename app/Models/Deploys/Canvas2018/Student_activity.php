<?php

namespace App\Models\Deploys\Canvas2018;

use Illuminate\Database\Eloquent\Model;

class Student_activity extends Model{

    public $timestamps = true;
    protected $table = 'student_activities';

    protected $fillable = [
        'student_id', 'reward_1', 'reward_2', 'reward_3', 'reward_4', 'reward_5', 'reward_6', 'reward_7'
    ];

    public function student(){
        return $this->belongsTo('App\Models\Deploys\Canvas2018\Student', 'student_id', 'id');
    }
}
