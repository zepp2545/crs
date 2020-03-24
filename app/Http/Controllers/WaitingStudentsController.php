<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Lesson;
use App\LessonGroup;
use App\Place;
use App\StudentLesson;
use App\Student;
use App\Http\Requests\Waitings\CreateWaitingStudentRequest;

class WaitingStudentsController extends Controller
{
  public function index()
  {
    return view('waitings.index')->with('lessons',LessonGroup::orderBy('kana','asc')->get());
  }


  public function create()
  {
      return view('waitings.register')->with('lessons',LessonGroup::orderBy('kana','asc')->get())->with('places',Place::orderBy('name','asc')->get());
  }



  public function store(CreateWaitingStudentRequest $request)
  {
      DB::beginTransaction();

      try{
        $last_inserted_id=$this->register_student_info($request);

        $this->register_student_lesson($request,$last_inserted_id);


        DB::commit();

      }catch(\Exception $e){
        DB::rollback();
        return $e->getMessage();
      }

      return redirect(route('waitings.index'))->with('success','New student registered successfully');
  }

  private function register_student_info($request){
    if(Student::where('id',(int)$request->student_id)->exists()){
      return $request->student_id;
    }else{
      $student=Student::create([
        'grade'=>$request->grade,
        'jaName'=>$request->jaName,
        'kanaName'=>$request->kanaName,
        'enName'=>$request->enName,
        'tel1'=>$request->tel1,
        'tel2'=>$request->tel2,
        'email1'=>$request->email1,
        'email2'=>$request->email2,
        'address_id'=>$request->address,
        'addDetails'=>$request->addDetails,
        'note'=>$request->note,
        'province'=>$request->province
      ]);
    }

     return $student->id;

  }

  private function register_student_lesson($request,$last_inserted_id){

     $student_lesson=StudentLesson::create([
       'student_id'=>$last_inserted_id,
       'lesson_group_id'=>$request->lesson,
       'status'=>1,
       'bus'=>$request->busUse,
       'pickup_id'=>$request->pickup,
       'pickup_details'=>$request->pickupDetails,
       'send_id'=>$request->send,
       'send_details'=>$request->sendDetails,
       'note'=>$request->note,
     ]);

  }



  public function get_student_ajax(Request $request){
    $lesson_id=$request->input('id');
    $students=StudentLesson::where('lesson_group_id',$lesson_id)->whereBetween('status',[1,4])->with(['student'=> function($query){
      $query->with('address');
    }])->orderBy('created_at','asc')->with('lesson_group')->with('send')->with('pickup')->get();

    return response()->json($students);
  }

  public function edit($id)
  {
    $student=StudentLesson::whereBetween('status',[1,2])->find($id);

    return view('waitings.register')->with('student',$student)->with('lessons',LessonGroup::orderBy('kana','asc')->get())->with('places',Place::all());
  }

  public function update(CreateWaitingStudentRequest $request,$id)
  {
    DB::beginTransaction();

    try{

      $student_lesson=StudentLesson::whereBetween('status',[1,2])->find($id);

      $student_lesson->update([
        'lesson_group_id'=>$request->lesson,
        'trial_date'=>$request->trialDate,
        'bus'=>$request->busUse,
        'pickup_id'=>$request->pickup,
        'pickup_details'=>$request->pickupDetails,
        'send_id'=>$request->send,
        'send_details'=>$request->sendDetails,
        'note'=>$request->note,
      ]);

      $student=Student::find($student_lesson->student_id);

      $student->update([
        'grade'=>$request->grade,
        'jaName'=>$request->jaName,
        'kanaName'=>$request->kanaName,
        'enName'=>$request->enName,
        'tel1'=>$request->tel1,
        'tel2'=>$request->tel2,
        'email1'=>$request->email1,
        'email2'=>$request->email2,
        'address_id'=>$request->address,
        'addDetails'=>$request->addDetails,
        'note'=>$request->note,
        'province'=>$request->province
      ]);

      DB::commit();

    }catch(\Exception $e){
      DB::rollback();
    }

    return redirect(route('waitings.index'))->with('success','The student updated successfully');
  }
  
    // search student info in trial registration ajax
    public function get_stu_info(Request $request){
      $students=StudentLesson::groupBy('student_id')->whereHas('student',function($query)use($request){
        $query->where('jaName','Like',"%{$request->name}%")->orWhere('kanaName','Like',"%{$request->name}%")->orWhere('enName','Like',"%{$request->name}%");
      })->with('student')->get();
      return response()->json($students);
    }


    public function change_status_ajax(Request $request){
      $student_lesson_id=$request->input('id');
      $status=$request->input('status');
      $student=StudentLesson::find($student_lesson_id);

      $student->status=$status;

      $student->save();
      
      return response()->json($student);

    }



}
