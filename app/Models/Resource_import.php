<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource_import extends Model{

    public $timestamps = true;
    protected $table = 'resource_import';

    protected $fillable = [
        'resource_id', 'import_id', 'instance_resource_id'
    ];
}
