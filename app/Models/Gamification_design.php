<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gamification_design extends Model{

    public $timestamps = true;
    protected $table = 'gamification_designs';

    protected $fillable = [
        'name', 'creator_id','learning_id'
    ];

    /*Relationships*/
    public function creator(){
        return $this->belongsTo('App\Models\User', 'creator_id', 'id');
    }

    public function users(){
        return $this->belongsToMany('App\Models\User', 'gamification_design_access','gamification_id','user_id');
    }
    public function learning_design(){
        return $this->belongsTo('App\Models\Learning_design', 'learning_id', 'id');
    }
    public function gamification_engines(){
        return $this->hasMany('App\Models\Gamification_engine', 'gdesign_id', 'id');
    }
    public function gamification_deploys(){
        return $this->hasMany('App\Models\Gamification_deploy', 'gdesign_id', 'id');
    }
    public function badge_suites(){
        return $this->hasMany('App\Models\Badge_suite', 'gdesign_id', 'id');
    }
}
