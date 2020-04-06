<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Students\UpdateStudentRequest;
use App\Http\Requests\Students\UpdateStudentLessonRequest;
use App\Lesson;
use App\Place;
use App\Student;
use App\StudentLesson;

class StudentsController extends Controller
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
        $students=Student::whereHas('active_lessons',function($query){
          $query->whereBetween('status',[7,8]);
		})->orderByRaw("case
			   when grade='H1' then 1
               when grade='J3' then 2
               when grade='J2' then 3
               when grade='J1' then 4
               when grade='E6' then 5
               when grade='E5' then 6
               when grade='E4' then 7
               when grade='E3' then 8
               when grade='E2' then 9
               when grade='E1' then 10
               when grade='K' then 11
               else 11
               end")->get();
      }
      return view('students.index')->with('students',$students);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('students.create')->with('lessons',Lesson::orderBy('kana','asc')->get())->with('places',Place::orderBy('name','asc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
      // store individual student's basic info 
      DB::beginTransaction();

      try{
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

        DB::commit();
      }catch(\Exception $e){
        DB::rollback();
      }
      
      return redirect(route('students.edit',['id'=>$student]))->with('success','Student Basic info is updated successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      return view('students.register')->with('student',Student::find($id))->with('lessons',Lesson::orderBy('kana','asc')->get())->with('places',Place::orderBy('name','asc')->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, $id)
    {
        // update individual student's basic info 
        DB::beginTransaction();

        try{
          $student=Student::find($id);
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
        
        return redirect(route('students.edit',['id'=>$id]))->with('success','Student Basic info is updated successfully.');
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


     // search Regular student
     public function search(Request $request){

      if($request->input('type_of_list')==='students'){
        $students=Student::whereHas('active_lessons',function($query) use ($request){
          $query->whereBetween('status',[7,9]);
        })->where('jaName','like','%'.$request->searched_name.'%')->orWhere('kanaName','like','%'.$request->searched_name.'%')->orWhere('enName','like','%'.$request->searched_name.'%')
        ->orderByRaw("case
        when grade='J3' then 1
        when grade='J2' then 2
        when grade='J1' then 3
        when grade='E6' then 4
        when grade='E5' then 5
        when grade='E4' then 6
        when grade='E3' then 7
        when grade='E2' then 8
        when grade='E1' then 9
        when grade='K' then 10
        else 11
        end")->get();

        return redirect(route('students.index'))->with('searched_students',$students);
      }
    }

    //update active lesson of an individual
    public function lesson_update(UpdateStudentLessonRequest $request,$id){
      DB::beginTransaction();

      $active_lesson=StudentLesson::find($id);
      

      try{

        $active_lesson->update([
            'lesson_id'=>$request->lesson,
            'bus'=>$request->busUse,
            'pickup_id'=>$request->pickup,
            'pickup_details'=>$request->pickupDetails,
            'send_id'=>$request->send,
            'send_details'=>$request->sendDetails,
            'status'=>$request->status,
            'start_date'=>$request->start_date,
            'quit_date'=>$request->quit_date
        ]);

        DB::commit();
      }catch(\Exception $e){
        DB::rollback();
      }

      return redirect(route('students.edit',['id'=>$active_lesson->student_id]))->with('success','Lesson info is updated successfully.');

      
    }


    //store lesson of an individual this has to be removed later
    public function student_lesson_store(UpdateStudentLessonRequest $request,$id){
      DB::beginTransaction();
      
      try{
        $student_lesson=StudentLesson::create([
            'student_id'=>$id,
            'lesson_id'=>$request->lesson,
            'bus'=>$request->busUse,
            'pickup_id'=>$request->pickup,
            'pickup_details'=>$request->pickupDetails,
            'send_id'=>$request->send,
            'send_details'=>$request->sendDetails,
            'status'=>$request->status,
            'start_date'=>$request->start_date,
            'quit_date'=>$request->quit_date
        ]);

        DB::commit();
      }catch(\Exception $e){
        DB::rollback();
      }

      return redirect(route('students.edit',['id'=>$student_lesson->student_id]))->with('success','Lesson info is created successfully.');
      
    }

    public function lesson_delete($id){
      DB::beginTransaction();
      try{
        $student_lesson=StudentLesson::find($id);

        $student_lesson->delete();

        DB::commit();
      }catch(\Exception $e){
        DB::rollback();
      }

      return redirect(route('students.edit',['id'=>$student_lesson->student_id]))->with('success','Lesson is deleted successfully.');
      
    }

    public function dropouts(){
      if(session('searched_students')){
        $students=session('searched_students');
      }else{
        $students=StudentLesson::onlyTrashed()->orderBy('deleted_at','desc')->get();
      }

      return view('students/dropouts')->with('students',$students);
    }


    public function restore($id){
      $student=StudentLesson::onlyTrashed()->findOrFail($id);

      $student->restore();

      return back()->with('success','The student is restored successfully');
    }

    public function lessonList(){
      return view('students.lessonList')->with('lessons',Lesson::all());
    }

    public function lessonList_ajax(Request $request){

      $students=StudentLesson::where('lesson_id',$request->id)->where(function($query){
        $query->whereBetween('status',[6,9])->orWhere('status',4);
      })->with('student')->with('pickup')->with('send')->with('lesson')->get();
      
      if($students->count()===0){
        $students=Lesson::find($request->id);
      }


      return response()->json($students);
    }
    
    // search quit student
     public function quit_search(Request $request){

      if($request->input('type_of_list')==='quit'){
        $students=StudentLesson::onlyTrashed()->whereHas('student',function($query) use ($request){
          $query->where('jaName','like','%'.$request->searched_name.'%')->orWhere('kanaName','like','%'.$request->searched_name.'%')->orWhere('enName','like','%'.$request->searched_name.'%');
        })->orderBy('deleted_at','desc')->get();

        return redirect(route('students.dropouts'))->with('searched_students',$students);
      }
    }



  
}

