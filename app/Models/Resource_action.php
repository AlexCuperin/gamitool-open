<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource_action extends Model{

    public $timestamps = true;
    protected $table = 'resource_action';

    protected $fillable = [
        'resource_type_id', 'action_type_id'
    ];
}
