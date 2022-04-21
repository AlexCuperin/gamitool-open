<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable{
    use Notifiable;
    public $timestamps = true;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name', 'lastname','email', 'password', 'inst_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*Relationships*/
    public function institution(){
        return $this->belongsTo('App\Models\Institution', 'inst_id', 'id');
    }
    public function learning_designs(){
        return $this->belongsToMany('App\Models\Learning_design', 'learning_design_access','user_id','learning_id');
    }
    public function gamification_designs(){
        return $this->belongsToMany('App\Models\Gamification_design', 'gamification_design_access','user_id','gamification_id');
    }
    public function gamification_deploys(){
        return $this->hasMany('App\Models\Gamification_deploy', 'creator_id', 'id');
    }
}
