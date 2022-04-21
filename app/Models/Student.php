<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model{

    const THRES_3GROUPS = 800;

    public $timestamps = true;
    protected $table = 'students';

    protected $fillable = [
        'gdeploy_id','id_instance','email_instance','name_instance','enrolled_at'
    ];

    /*Relationships*/
    public function gamification_engine(){
        return $this->belongsToMany('App\Models\Gamification_engine', 'student_engine','student_id','engine_id');
    }
    public function gamification_deploys(){
        return $this->belongsTo('App\Models\Gamification_deploy', 'gdeploy_id', 'id');
    }
}
