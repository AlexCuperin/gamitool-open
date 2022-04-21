<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule_type extends Model{

    public $timestamps = false;
    protected $table = 'rule_types';

    /*Relationships*/
    public function rules(){
        return $this->hasMany('App\Models\Rule', 'type_id', 'id');
    }
    public function action_types(){
        return $this->belongsToMany('App\Models\Action_type', 'action_rule','rule_type_id','action_type_id');
    }
}