<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Lesson;
use App\Place;
use App\StudentLesson;
use App\Student;
use App\Http\Requests\Trials\CreateStudentRequest;
use Carbon\Carbon;


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
        $students=StudentLesson::whereBetween('status',[4,8])->where(function($query){
          $query->whereNull('trial_date')->orWhere('trial_date','>',Carbon::now()->subYear());
        })->orderByRaw('trial_date is null desc')->orderBy('trial_date','desc')->get();
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
        return view('trials.register')->with('active',false)->with('lessons',Lesson::orderBy('kana','asc')->get())->with('places',Place::orderBy('name','asc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStudentRequest $request)
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
              return redirect(route('trials.index'))->with('success','New student registered successfully');
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
       if(empty($request->trial_Date)){
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
       }else{
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
       

       return $student_lesson;

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

      if($student->student->active_lessons->count() > 0){
        return view('trials.register')->with('student',$student)->with('lessons',Lesson::orderBy('kana','asc')->get())->with('active',true)->with('places',Place::orderBy('name','asc')->get());
      }else{
        return view('trials.register')->with('student',$student)->with('lessons',Lesson::orderBy('kana','asc')->get())->with('active',false)->with('places',Place::orderBy('name','asc')->get());
      }

      
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
          'note'=>$request->note
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
          $query->where('jaName','like',remove_space($request->searched_name).'%')->orWhere('kanaName','like',remove_space($request->searched_name).'%')->orWhere('enName','like',remove_space($request->searched_name).'%');
        })->whereNotNull('trial_date')->orderBy('created_at','desc')->get();

        return redirect(route('trials.index'))->with('searched_students',$students);
      }
    }

    // search student info in trial registration ajax
    public function get_stu_info(Request $request){
      $students=StudentLesson::groupBy('student_id')->whereHas('student',function($query)use($request){
        $query->where('jaName','like',remove_space($request->name).'%')->orWhere('kanaName','like',remove_space($request->name).'%')->orWhere('enName','like',remove_space($request->name).'%');
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
