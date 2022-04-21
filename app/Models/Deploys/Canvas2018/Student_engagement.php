<?php

namespace App\Models\Deploys\Canvas2018;

use Illuminate\Database\Eloquent\Model;

class Student_engagement extends Model{

    public $timestamps = true;
    protected $table = 'student_engagement';

    protected $fillable = [
        'student_id', 'page_views', 'participations', 'submissions'
    ];

    public function student(){
        return $this->belongsTo('App\Models\Deploys\Canvas2018\Student', 'student_id', 'id');
    }
}
