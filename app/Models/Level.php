<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model{

    public $timestamps = true;
    protected $table = 'levels';

    protected $fillable = [
        'reward_id'
    ];

    /*Relationships*/
    public function reward(){
        return $this->belongsTo('App\Models\Reward', 'reward_id', 'id');
    }
}
