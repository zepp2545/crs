<?php

namespace App\Http\Controllers;

use App\LessonGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LessonGroupController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            DB::transaction(function()use($request){
    
                $request->validate([
                   'name'=>'required|unique:lesson_groups|max:255',
                   
                ]);
    
                LessonGroup::create([
                'name'=>$request->name,
                
                ]);
                
            });
    
            return redirect(url()->previous())->with('success','Lesson Group Created Successfully')->with('lessonGroup',1);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LessonGroup  $lessonGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        DB::transaction(function()use($id,$request){
            $lesson=LessonGroup::find($id);

            $request->validate([
               'name'=>'required|unique:lesson_groups|max:255',
            ]);


            $lesson->update([
            'name'=>$request->name,
            ]);
            
        });

        return redirect(url()->previous())->with('success','Lesson Group Updated Successfully')->with('lessonGroup',1);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LessonGroup  $lessonGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::transaction(function()use($id){

            $lesson=LessonGroup::find($id);

            $lesson->delete();

        });

        return redirect(url()->previous())->with('success','Lesson Group Deleted Successfully')->with('lessonGroup',1);
    }
}
