<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge_suite extends Model{

    public $timestamps = true;
    protected $table = 'badge_suites';

    protected $fillable = [
        'name', 'description','num_badges'
    ];

    /*Relationships*/
    /*public function badges(){
        return $this->hasMany('App\Models\Badge', 'suite_id', 'id');
    }*/
    public function learning_design(){
        return $this->belongsTo('App\Models\Gamification_design', 'gdesign_id', 'id');
    }
}
