<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource_type extends Model{

    public $timestamps = false;
    protected $table = 'resource_types';

    /*Relationships*/
    public function resources(){
        return $this->hasMany('App\Models\Resource', 'type_id', 'id');
    }
    public function rr_types(){
        return $this->belongsToMany('App\Models\Rr_type', 'resource_rr','resource_type_id','rr_type_id');
    }
    public function action_types(){
        return $this->belongsToMany('App\Models\Action_type','resource_action','resource_type_id','action_type_id');
    }
}
