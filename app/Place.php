<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable=['name'];

    //
    public function student_lesson_pickups(){
      return $this->hasMany('App\StudentLesson','pickup');
    }

    public function student_lesson_sends(){
      return $this->hasMany('App\StudentLesson','send');
    }

    public function student_addresses(){
      return $this->hasMany('App\StudentLesson','address');
    }


    
}
