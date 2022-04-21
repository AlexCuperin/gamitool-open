<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model{

    public $timestamps = true;
    protected $table = 'resources';

    protected $fillable = [
        'learning_id', 'module','row','type_id', 'name', 'module_id'
    ];

    /*Relationships*/
    public function learning_design(){
        return $this->belongsTo(Learning_design::class, 'learning_id', 'id');
    }
    public function resource_type(){
        return $this->belongsTo(Resource_type::class, 'type_id', 'id');
    }
    public function redeemable_rewards(){
        return $this->hasMany(Redeemable_reward::class, 'resource_id', 'id');
    }
    public function resource_conditions(){
        return $this->hasMany(Resource_condition::class, 'resource_id', 'id');
    }
    public function gamification_deploys(){
        return $this->belongsToMany(Gamification_deploy::class, 'resource_deploy','resource_id','deploy_id');
    }
    public function import_metadata(){
        return $this->belongsToMany(Import_metadata::class, 'resource_import','resource_id','import_id');
    }
    public function retrieve_instance_id($import_id){
        $id = Resource_import::where('import_id',$import_id)->where('resource_id',$this->id)->select('instance_resource_id')->first();
        return $id['instance_resource_id'];
    }
    public function moduleobj(){
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }
}
