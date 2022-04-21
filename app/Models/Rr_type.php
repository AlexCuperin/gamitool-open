<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rr_type extends Model{

    public $timestamps = false;
    protected $table = 'rr_types';

    /*Relationships*/
    public function redeemable_rewards(){
        return $this->hasMany('App\Models\Redeemable_reward', 'rr_type_id', 'id');
    }
    public function resource_types(){
        return $this->belongsToMany('App\Models\Resource_type', 'resource_rr','rr_type_id','resource_type_id');
    }
}
