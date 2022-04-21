<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model{

    public $timestamps = true;
    protected $table = 'modules';

    protected $fillable = [
        'name', 'position', 'learning_id'
    ];

    /*Relationships*/
    public function resources(){
        return $this->hasMany(Resource::class, 'module_id', 'id');
    }
    public function learning_design(){
        return $this->belongsTo(Learning_design::class, 'learning_id', 'id');
    }
}
