<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deploy_type extends Model{

    public $timestamps = false;
    protected $table = 'deploy_types';

    /*Relationships*/
    public function gamification_deploys(){
        return $this->hasMany('App\Models\Gamification_deploy', 'instance_type_id', 'id');
    }

    public function imported_learning_designs(){
        return $this->hasMany(Import_metadata::class, 'instance_type_id', 'id');
    }}
