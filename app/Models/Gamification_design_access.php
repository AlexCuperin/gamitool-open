<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gamification_design_access extends Model{

    public $timestamps = true;
    protected $table = 'gamification_design_access';

    protected $fillable = [
        'user_id', 'gamification_id'
    ];
}
