<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condition_type extends Model{

    public $timestamps = false;
    protected $table = 'condition_types';

    /*Relationships*/
    public function conditions(){
        return $this->hasMany('App\Models\Condition', 'condition_type_id', 'id');
    }

}
