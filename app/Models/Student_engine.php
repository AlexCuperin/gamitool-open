<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student_engine extends Model{

    public $timestamps = true;
    protected $table = 'student_engine';

    protected $fillable = [
        'student_id', 'engine_id'
    ];

    public function gamification_deploys(){
        return $this->belongsTo('App\Models\Gamification_deploy', 'gdeploy_id', 'id');
    }
}
