<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model{

    public $timestamps = true;
    protected $table = 'badges';

    protected $fillable = [
        'reward_id', 'suite_id','suite_quality'
    ];

    /*Relationships*/
    public function reward(){
        return $this->belongsTo('App\Models\Reward', 'reward_id');
    }
    /*public function badge_suite(){
        return $this->belongsTo('App\Models\Badge_suite', 'suite_id', 'id');
    }*/
}
