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
    $last_inserted_id=0;
    $student_existance=false;

    DB::beginTransaction();

      try{
          //check if this post comes with imported student info. if empty if true,it means they filled in form on their own.
          if(empty($request->student_id)){
            $last_inserted_id=$this->check_existance_of_student($request);
          }else{
            $student_existance=Student::where('id',(int)$request->student_id)
            ->where('jaName',remove_space($request->jaName))->where('enName',$request->enName)->where('kanaName',remove_space($request->kanaName))
            ->where('grade',$request->grade)->exists();

            if($student_existance){
              $last_inserted_id=$request->student_id;
            }else{
              $last_inserted_id=$this->check_existance_of_student($request);   
            }
          }

          if($this->register_student_lesson($request,$last_inserted_id)){
            DB::commit();
            return redirect(route('waitings.index'))->with('success','New student registered successfully');
          }

      }catch(\Exception $e){
          DB::rollback();
          return redirect()->back()->with('student_existing_error',$e->getMessage());
      }

  }

  private function check_existance_of_student($request){
    $student_existance=Student::where(function($query) use ($request){
      $query->where('jaName',remove_space($request->jaName))->orWhere('enName',$request->enName)->orWhere('kanaName',remove_space($request->kanaName));
    })->where('email1',remove_space($request->email1))->where('grade',$request->grade)->where('address_id',$request->address)->exists();

    if($student_existance){
      throw new \Exception('この生徒はすでにデータベース上に存在します。名前検索から生徒情報をimportして登録してください。');
    }else{
      return $this->register_student($request);  
    }  
  }

  private function register_student($request){
    $student=Student::create([
      'grade'=>$request->grade,
      'jaName'=>remove_space($request->jaName),
      'kanaName'=>remove_space($request->kanaName),
      'enName'=>$request->enName,
      'tel1'=>$request->tel1,
      'tel2'=>$request->tel2,
      'email1'=>remove_space($request->email1),
      'email2'=>remove_space($request->email2),
      'address_id'=>$request->address,
      'addDetails'=>$request->addDetails,
      'province'=>$request->province
    ]);

    if($student->id){
      return $student->id;
    }
    
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

     return $student_lesson;

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
    $student=StudentLesson::whereBetween('status',[1,3])->find($id);

    return view('waitings.register')->with('student',$student)->with('lessons',LessonGroup::orderBy('kana','asc')->get())->with('places',Place::all());
  }

  public function update(CreateWaitingStudentRequest $request,$id)
  {
    DB::beginTransaction();

    try{

      $student_lesson=StudentLesson::whereBetween('status',[1,3])->find($id);

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
        'jaName'=>remove_space($request->jaName),
        'kanaName'=>remove_space($request->kanaName),
        'enName'=>$request->enName,
        'tel1'=>$request->tel1,
        'tel2'=>$request->tel2,
        'email1'=>remove_space($request->email1),
        'email2'=>remove_space($request->email2),
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
        $query->where('jaName','Like',"{remove_space($request->name)}%")->orWhere('kanaName','Like',"{remove_space($request->name)}%")->orWhere('enName','Like',"{remove_space($request->name)}%");
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
