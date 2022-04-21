<?php

namespace App\Models\Deploys\Canvas2018;

use Illuminate\Database\Eloquent\Model;

class Student_submission extends Model{

    public $timestamps = true;
    protected $table = 'student_submissions';

    protected $fillable = [
        'student_id',
        'module_0',   'module_1',   'module_2',   'module_3',   'module_4',   'module_5',   'module_6',
        'attempts_0', 'attempts_1', 'attempts_2', 'attempts_3', 'attempts_4', 'attempts_5', 'attempts_6',
        'score_0',    'score_1',    'score_2',    'score_3',    'score_4',    'score_5',    'score_6'
    ];

    public function student(){
        return $this->belongsTo('App\Models\Deploys\Canvas2018\Student', 'student_id', 'id');
    }
}
