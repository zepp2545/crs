<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
   protected $fillable=['grade','jaName','kanaName','enName','tel1','tel2','email1','email2','address_id','addDetails','note','province'];

   public function student_lessons(){
     return $this->hasMany('App\StudentLesson','student_id');
   }


   public function address(){
     return $this->belongsTo('App\Place','address_id');
   }


  //  retirieve all data with status value 継続 or 入会届提出済み or 休会
   public function active_lessons(){
     return $this->hasMany('App\StudentLesson','student_id')->whereBetween('status',[7,9]);
   }

   // retirieve all student_lessons including the trashed, which is used in Trial delete.
   public function student_lessons_with_trashed(){
     return $this->hasMany('App\StudentLesson','student_id')->withTrashed();
   }


}
