<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource_condition extends Model{

    public $timestamps = true;
    protected $table = 'resource_conditions';

    protected $fillable = [
        'condition_id', 'resource_id','resource_op','action_op'
    ];

    /*Relationships*/
    public function condition(){
        return $this->belongsTo('App\Models\Condition', 'condition_id', 'id');
    }
    public function resource(){
        return $this->belongsTo('App\Models\Resource', 'resource_id', 'id');
    }
    public function actions(){
        return $this->hasMany('App\Models\Action', 'res_cond_id', 'id');
    }
}
