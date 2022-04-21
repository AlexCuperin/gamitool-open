<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Learning_design extends Model{

    public $timestamps = true;
    protected $table = 'learning_designs';

    protected $fillable = [
        'course_name', 'modules','rows','creator_id'
    ];

    /*Relationships*/
    public function users(){
        return $this->belongsToMany('App\Models\User', 'learning_design_access','learning_id','user_id');
    }
    public function gamification_designs(){
        return $this->hasMany('App\Models\Gamification_design', 'learning_id', 'id');
    }
    public function import_metadata(){
        return $this->hasOne(Import_metadata::class, 'learning_id', 'id');
    }
    public function resources(){
        return $this->hasMany('App\Models\Resource', 'learning_id', 'id');
    }
    public function modulesobj(){
        return $this->hasMany(Module::class, 'learning_id', 'id')->orderBy('position');
    }
}
