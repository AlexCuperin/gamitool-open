<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redeemable_reward extends Model{

    public $timestamps = true;
    protected $table = 'redeemable_rewards';

    protected $fillable = [
        'reward_id', 'rr_type_id','param_1','resource_id'
    ];

    /*Relationships*/
    public function reward(){
        return $this->belongsTo('App\Models\Reward', 'reward_id');
    }
    public function resource(){
        return $this->belongsTo('App\Models\Resource', 'resource_id', 'id');
    }
    public function rr_type(){
        return $this->belongsTo('App\Models\Rr_type', 'rr_type_id', 'id');
    }
}
