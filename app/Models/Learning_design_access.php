<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Learning_design_access extends Model{

    public $timestamps = true;
    protected $table = 'learning_design_access';

    protected $fillable = [
        'user_id', 'learning_id'
    ];
}
