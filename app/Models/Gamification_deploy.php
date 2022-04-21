<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gamification_deploy extends Model{

    public $timestamps = true;
    protected $table = 'gamification_deploys';

    protected $fillable = [
        'gdesign_id','instance_type_id','instance_url','course_name','course_id','creator_id', 'external_tool_id'
    ];

    /*Relationships*/
    public function creator(){
        return $this->belongsTo('App\Models\User', 'creator_id', 'id');
    }
    public function resource(){
        return $this->belongsToMany('App\Models\Resource', 'resource_deploy','deploy_id','resource_id');
    }
    public function gamification_design(){
        return $this->belongsTo('App\Models\Gamification_design', 'gdesign_id', 'id');
    }
    public function deploy_types(){
        return $this->belongsTo('App\Models\Deploy_type', 'instance_type_id', 'id');
    }
    public function students(){
        return $this->hasMany('App\Models\Students', 'gdeploy_id', 'id');
    }
}
