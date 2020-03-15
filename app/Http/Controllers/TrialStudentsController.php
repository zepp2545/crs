<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Lesson;
use App\Place;
use App\StudentLesson;
use App\Student;
use App\Http\Requests\Trials\CreateStudentRequest;


class TrialStudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(session('searched_students')){
        $students=session('searched_students');
      }else{
        $students=StudentLesson::whereBetween('status',[4,8])->whereNotNull('trial_date')->orderBy('created_at','desc')->get();
      }
      return view('trials.index')->with('students',$students);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trials.register')->with('lessons',Lesson::all())->with('places',Place::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStudentRequest $request)
    {
        DB::beginTransaction();

        try{
          $last_inserted_id=$this->register_student_info($request);

          $this->register_student_lesson($request,$last_inserted_id);


          DB::commit();

        }catch(\Exception $e){
          DB::rollback();
        }

        return redirect(route('trials.create'))->with('success','New student registered successfully');
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
         'trial_date'=>$request->trialDate,
         'status'=>4,
         'bus'=>$request->busUse,
         'pickup_id'=>$request->pickup,
         'pickup_details'=>$request->pickupDetails,
         'send_id'=>$request->send,
         'send_details'=>$request->sendDetails,
         'note'=>$request->note,
       ]);

    }

    public function lesson_ajax(Request $request){
      $lesson_id=$request->input('id');

      $students=StudentLesson::where('lesson_id',$lesson_id)->with('student')->with('lesson')->get();


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





    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $student=StudentLesson::whereBetween('status',[4,8])->find($id);

      return view('trials.register')->with('student',$student)->with('lessons',Lesson::all())->with('places',Place::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateStudentRequest $request,$id)
    {
      DB::beginTransaction();

      try{

        $student_lesson=StudentLesson::whereBetween('status',[4,8])->find($id);

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

      return redirect(route('trials.index'))->with('success','The student updated successfully');
    }


    // search trial student
    public function search(Request $request){

      if($request->input('type_of_list')==='trials'){
        $students=StudentLesson::whereBetween('status',[4,8])->whereHas('student',function($query) use ($request){
          $query->where('jaName','like','%'.$request->searched_name.'%')->orWhere('kanaName','like','%'.$request->searched_name.'%')->orWhere('enName','like','%'.$request->searched_name.'%');
        })->orderBy('created_at','desc')->get();

        return redirect(route('trials.index'))->with('searched_students',$students);
      }
    }

    // search student info in trial registration ajax
    public function get_stu_info(Request $request){
      $students=StudentLesson::groupBy('student_id')->whereHas('student',function($query)use($request){
        $query->where('jaName','Like',"%{$request->name}%")->orWhere('kanaName','Like',"%{$request->name}%")->orWhere('enName','Like',"%{$request->name}%");
      })->with('student')->get();
      return response()->json($students);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
