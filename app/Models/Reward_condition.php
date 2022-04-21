<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward_condition extends Model{

    public $timestamps = true;
    protected $table = 'reward_conditions';

    protected $fillable = [
        'condition_id', 'reward_type_id','param_1'
    ];

    /*Relationships*/
    public function condition(){
        return $this->belongsTo('App\Models\Condition', 'condition_id', 'id');
    }
    public function reward_type(){
        return $this->belongsTo('App\Models\Reward_type', 'reward_type_id', 'id');
    }
}
