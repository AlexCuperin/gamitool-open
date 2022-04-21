<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model{

    public $timestamps = true;
    protected $table = 'actions';

    protected $fillable = [
        'res_cond_id', 'type_id'
    ];

    public function resource_condition(){
        return $this->belongsTo('App\Models\Resource_condition', 'res_cond_id', 'id');
    }
    public function action_type(){
        return $this->belongsTo('App\Models\Action_type', 'type_id', 'id');
    }

    public function rules(){
        return $this->hasMany('App\Models\Rule', 'action_id', 'id');
    }
}
