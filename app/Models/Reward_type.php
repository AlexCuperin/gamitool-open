<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward_type extends Model{

    public $timestamps = false;
    protected $table = 'reward_types';

    /*Relationships*/
    public function rewards(){
        return $this->hasMany('App\Models\Reward', 'reward_type_id', 'id');
    }
    public function reward_conditions(){
        return $this->hasMany('App\Models\Reward_condition', 'reward_type_id', 'id');
    }
}
