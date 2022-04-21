<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model{

    public $timestamps = true;
    protected $table = 'points';

    protected $fillable = [
        'reward_id'
    ];

    /*Relationships*/
    public function reward(){
        return $this->belongsTo('App\Models\Reward', 'reward_id', 'id');
    }
}
