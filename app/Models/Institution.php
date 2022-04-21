<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model{

    public $timestamps = true;
    protected $table = 'institutions';

    public static function get_names(){
        return Institution::select('name')->get();
    }

    /*Relationships*/
    public function users(){
        return $this->hasMany('\App\Models\User', 'inst_id', 'id');
    }
}
