<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource_rr extends Model{

    public $timestamps = true;
    protected $table = 'resource_rr';

    protected $fillable = [
        'resource_type_id', 'rr_type_id'
    ];
}
