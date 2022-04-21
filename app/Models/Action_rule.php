<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action_rule extends Model{

    public $timestamps = true;
    protected $table = 'action_rule';

    protected $fillable = [
        'action_type_id', 'rule_type_id'
    ];
}
