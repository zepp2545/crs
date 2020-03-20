<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Lesson;
use App\Place;
use App\LessonGroup;

class SettingsController extends Controller
{

    //lesson setting

    public function lesson(){
        return view('settings/lessons')->with('lessons',Lesson::orderBy('kana','asc')->get())->with('lessonGroups',LessonGroup::orderBy('kana','asc')->get());
    }



    public function lessons_update(Request $request,$id){
        DB::transaction(function()use($id,$request){
            $lesson=Lesson::find($id);

            $request->validate([
               'name'=>'required|unique:lessons,name,'.$id.'|max:255',
               'kana'=>'required|unique:lessons,kana,'.$id.'|max:255',
               'time'=>'required',
               'day'=>'required',
               'capacity'=>'required|integer'
            ]);


            $lesson->update([
            'name'=>$request->name,
            'kana'=>$request->kana,
            'time'=>$request->time,
            'day'=>$request->day,
            'capacity'=>$request->capacity
            ]);
            
        });

        return redirect(route('settings.lessons'))->with('success','Leson updated Successfully');
        
    }

    public function lessons_delete($id){

        DB::transaction(function()use($id){

            $lesson=Lesson::find($id);

            $lesson->delete();

        });

        return redirect(route('settings.lessons'))->with('success','Lesson deleted Successfully');

    }



    public function lesson_store(Request $request){
        DB::transaction(function()use($request){

            $request->validate([
               'name'=>'required|unique:lessons|max:255',
               'kana'=>'required|unique:lessons|max:255',
               'time'=>'required',
               'day'=>'required',
               'capacity'=>'required|integer'
            ]);


            Lesson::create([
            'name'=>$request->name,
            'kana'=>$request->kana,
            'time'=>$request->time,
            'day'=>$request->day,
            'capacity'=>$request->capacity
            ]);
            
        });

        return redirect(route('settings.lessons'))->with('success','Lesson Created Successfully');

    }

    //place setting

    public function place(){
        return view('settings/places')->with('places',Place::orderBy('name','asc')->get());
    }
    
    
    public function places_update(Request $request,$id){
        
        DB::transaction(function()use($id,$request){
            $place=Place::find($id);

            $request->validate([
               'name'=>'required|unique:places|max:255',
            ]);


            $place->update([
            'name'=>$request->name,
            ]);
            
        });

        return redirect(route('settings.places'))->with('success','Updated Successfully');
        
    }

    public function places_delete($id){

        DB::transaction(function()use($id){

            $place=Place::find($id);

            $place->delete();

        });

        return redirect(route('settings.places'))->with('success','Place deleted Successfully');

    }

    public function place_store(Request $request){
        DB::transaction(function()use($request){

            $request->validate([
               'name'=>'required|unique:places|max:255',
               
            ]);

            Place::create([
            'name'=>$request->name,
            
            ]);
            
        });

        return redirect(route('settings.places'))->with('success','Place Created Successfully');

    }


    public function add_place_ajax(Request $request){
        $result=DB::transaction(function()use($request){

            $request->validate([
               'name'=>'required|unique:places|max:255',
            ]);

            return Place::create([
            'name'=>$request->name
            ]);

        });

        return response()->json($result);
    }









}
