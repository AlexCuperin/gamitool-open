<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model{

    public $timestamps = true;
    protected $table = 'conditions';

    protected $fillable = [
        'description', 'condition_type_id','engine_id'
    ];

    /*Relationships*/
    public function gamification_engine(){
        return $this->belongsTo('App\Models\Gamification_engine', 'engine_id', 'id');
    }
    public function condition_type(){
        return $this->belongsTo('App\Models\Condition_type', 'condition_type_id', 'id');
    }
    public function reward_conditions(){
        return $this->hasMany('App\Models\Reward_condition', 'condition_id', 'id');
    }
    public function group_conditions(){
        return $this->hasMany('App\Models\Group_condition', 'condition_id', 'id');
    }
    public function resource_conditions(){
        return $this->hasMany('App\Models\Resource_condition', 'condition_id', 'id');
    }
}
