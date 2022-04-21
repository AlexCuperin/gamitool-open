<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model{

    public $timestamps = true;
    protected $table = 'rules';

    protected $fillable = [
        'action_id', 'type_id','param_1','param_2'
    ];

    /*Relationships*/
    public function action(){
        return $this->belongsTo('App\Models\Action', 'action_id', 'id');
    }
    public function rule_type(){
        return $this->belongsTo('App\Models\Rule_type', 'type_id', 'id');
    }
}
