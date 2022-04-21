<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model{

    public $timestamps = true;
    protected $table = 'rewards';

    protected $fillable = [
        'reward_type_id', 'name','url_image','quantity','engine_id'
    ];

    /*Relationships*/
    public function gamification_engine(){
        return $this->belongsTo('App\Models\Gamification_engine', 'engine_id', 'id');
    }
    public function reward_type(){
        return $this->belongsTo('App\Models\Reward_type', 'reward_type_id', 'id');
    }
    public function redeemable_rewards(){
        return $this->hasMany('App\Models\Redeemable_reward', 'reward_id', 'id');
    }
    public function points(){
        return $this->hasMany('App\Models\Point', 'reward_id', 'id');
    }
    public function levels(){
        return $this->hasMany('App\Models\Level', 'reward_id', 'id');
    }
    public function badges(){
        return $this->hasMany('App\Models\Badge', 'reward_id', 'id');
    }
}
