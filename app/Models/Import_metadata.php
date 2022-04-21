<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Import_metadata extends Model{

    public $timestamps = true;
    protected $table = 'import_metadata';

    protected $fillable = [
        'learning_id','instance_type_id','instance_url','course_id','course_name','bearer'
    ];

    /*Relationships*/
    public function resources(){
        return $this->belongsToMany(Resource::class, 'resource_import','import_id','resource_id');
    }
    public function learning_design(){
        return $this->belongsTo(Learning_design::class, 'learning_id', 'id');
    }
    public function deploy_type(){
        return $this->belongsTo(Deploy_type::class, 'instance_type_id', 'id');
    }
}
