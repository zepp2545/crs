<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Lesson;
use App\Place;
use App\StudentLesson;
use App\Student;
use App\Http\Requests\Waitings\CreateWaitingStudentRequest;

class WaitingStudentsController extends Controller
{
  public function index()
  {
    return view('waitings.index')->with('lessons',Lesson::all());
  }


  public function create()
  {
      return view('waitings.register')->with('lessons',Lesson::all())->with('places',Place::all());
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

      return redirect(route('waitings.create'))->with('success','New student registered successfully');
  }

  private function register_student_info($request){
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

     return $student->id;

  }

  private function register_student_lesson($request,$last_inserted_id){

     $student_lesson=StudentLesson::create([
       'student_id'=>$last_inserted_id,
       'lesson_id'=>$request->lesson,
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
    $students=StudentLesson::where('lesson_id',$lesson_id)->whereBetween('status',[1,3])->with(['student'=> function($query){
      $query->with('address');
    }])->with('lesson')->with('send')->with('pickup')->get();

    return response()->json($students);
  }

  public function edit($id)
  {
    $student=StudentLesson::whereBetween('status',[1,2])->find($id);

    return view('waitings.register')->with('student',$student)->with('lessons',Lesson::all())->with('places',Place::all());
  }

  public function update(CreateWaitingStudentRequest $request,$id)
  {
    DB::beginTransaction();

    try{

      $student_lesson=StudentLesson::whereBetween('status',[1,2])->find($id);

      $student_lesson->update([
        'lesson_id'=>$request->lesson,
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
  
  // search student info in waiting registration ajax
  public function get_stu_info(Request $request){
    $students=StudentLesson::whereHas('student',function($query)use($request){
      $query->where('jaName','Like',"%{$request->name}%")->orWhere('kanaName','Like',"%{$request->name}%")->orWhere('enName','Like',"%{$request->name}%");
    })->with('student')->get();

    return response()->json($students);
  }



}
