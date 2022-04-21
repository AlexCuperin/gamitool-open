<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group_condition extends Model{

    public $timestamps = true;
    protected $table = 'group_conditions';

    protected $fillable = [
        'condition_id', 'student_percentage'
    ];

    /*Relationships*/
    public function condition(){
        return $this->belongsTo('App\Models\Condition', 'condition_id', 'id');
    }
}
