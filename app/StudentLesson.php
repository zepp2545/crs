<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentLesson extends Model
{
    //
    use SoftDeletes;

    protected $fillable=['student_id','lesson_id','trial_date','start_date','quit_date','status','bus','pickup_id','pickup_details','send_id','send_details','note','lesson_group_id'];

    public function student(){
      return $this->belongsTo('App\Student','student_id');
    }

    public function lesson(){
      return $this->belongsTo('App\Lesson','lesson_id');
    }

    public function lesson_group(){
      return $this->belongsTo('App\LessonGroup','lesson_group_id');
    }

    public function pickup(){
      return $this->belongsTo('App\Place','pickup_id');
    }

    public function send(){
      return $this->belongsTo('App\Place','send_id');
    }

    public $bususes=[
      1=>'利用しない',
      2=>'往復',
      3=>'行きのみ',
      4=>'帰りのみ'
    ];
}
