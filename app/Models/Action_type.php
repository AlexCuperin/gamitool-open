<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action_type extends Model{

    public $timestamps = false;
    protected $table = 'action_types';

    /*Relationships*/
    public function actions(){
        return $this->hasMany('App\Models\Actions', 'type_id', 'id');
    }
    public function rule_types(){
        return $this->belongsToMany('App\Models\Rule_type', 'action_rule','action_type_id','rule_type_id');
    }
    public function resource_types(){
        return $this->belongsToMany('App\Models\Resource_type','resource_action','action_type_id','resource_type_id');
    }
}
