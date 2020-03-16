<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonGroup extends Model
{
    protected $fillable=['name','kana'];

    public function student_lessons(){
        return $this->hasMany('App\StudentLesson','lesson_group_id');
      }
}
