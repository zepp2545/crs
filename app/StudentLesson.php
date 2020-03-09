<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentLesson extends Model
{
    //
    use SoftDeletes;

    protected $fillable=['student_id','lesson_id','trial_date','start_date','quit_date','status','bus','pickup_id','pickup_details','send_id','send_details','note'];

    public function student(){
      return $this->belongsTo('App\Student','student_id');
    }

    public function lesson(){
      return $this->belongsTo('App\Lesson','lesson_id');
    }

    public function pickup(){
      return $this->belongsTo('App\Place','pickup_id');
    }

    public function send(){
      return $this->belongsTo('App\Place','send_id');
    }

    public $statuses=[
      1=>'ウェイテング中',
      2=>'ウェイティングキャンセル済み',
      3=>'体験検討中',
      4=>'体験待ち',
      5=>'体験キャンセル済み',
      6=>'受講検討中',
      7=>'継続',
      8=>'申込書提出済',
      9=>'休会',
    ];

    public $bususes=[
      1=>'利用しない',
      2=>'往復',
      3=>'行きのみ',
      4=>'帰りのみ'
    ];
}
