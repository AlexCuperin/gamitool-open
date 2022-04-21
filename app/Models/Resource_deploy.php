<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource_deploy extends Model{

    public $timestamps = true;
    protected $table = 'resource_deploy';

    protected $fillable = [
        'resource_id', 'deploy_id', 'instance_resource_id'
    ];
}
