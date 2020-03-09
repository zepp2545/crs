<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    //
    protected $fillable=['name','day','time','capacity'];

    public function student_lessons(){
      return $this->hasMany('App\StudentLesson','lesson_id');
    }

}
