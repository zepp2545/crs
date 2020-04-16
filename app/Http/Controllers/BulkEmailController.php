<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\BulkEmailRequest;
use App\Student;
use App\Mail\BulkEmail;
use App\Lesson;

class BulkEmailController extends Controller
{
   public function create(){
       return view('bulkemail.create')->with('lessons',Lesson::orderBy('kana','asc')->get());
   }

   public function send(BulkEmailRequest $request){
      DB::transaction(function () use ($request){
         $email_addresses;
         $path_storage=[]; 
         $bccs=[];
         $files=[];
         $data=[
            'subject'=>'',
            'addresses'=>'',
            'files'=>''
         ];
         
         if(isset($request->file1)){
            $file1=str_replace('public/','',$request->file('file1')->storeAs('public',$request->file('file1')->getClientOriginalName()));
            $files[]=$file1;
         }
   
         if(isset($request->file2)){
            $file2=str_replace('public/','',$request->file('file2')->storeAs('public',$request->file('file2')->getClientOriginalName()));
            $files[]=$file2;
         }
   
         if(isset($request->file3)){
            $file3=str_replace('public/','',$request->file('file3')->storeAs('public',$request->file('file3')->getClientOriginalName()));
            $files[]=$file3;
         }

         if(isset($request->file4)){
            $file4=str_replace('public/','',$request->file('file4')->storeAs('public',$request->file('file4')->getClientOriginalName()));
            $files[]=$file4;
         }

         if(isset($request->file5)){
            $file5=str_replace('public/','',$request->file('file5')->storeAs('public',$request->file('file5')->getClientOriginalName()));
            $files[]=$file5;
         }
          
         if(!empty($files)){
            $data['files']=$files;
            foreach($files as $file){
               $path_storage[]='public/'.$file;
            }
         }

         if(isset($request->lessons)){
   
             $email_addresses=Student::select('email1','email2')->whereHas('student_lessons',function($query) use ($request){
                 $query->whereIn('lesson_id',$request->lessons)->whereBetween('status',[7,8]);
            })->get();
   
           
         }elseif(isset($request->grades)){
           $email_addresses=Student::select('email1','email2')->whereIn('grade',$request->grades)->whereHas('student_lessons',function($query) use ($request){
               $query->whereBetween('status',[7,8]); 
          })->get();
         }
   
         $data['subject']=$request->title;

   
         foreach($email_addresses as $address){
           if(empty($address->email1)){
              continue;
           }else{
            $bccs[]=$address->email1;
           }
   
           if(empty($address->email2)){
              continue;
           }else{
            $bccs[]=$address->email2;
           }
           
         }
   
         if(!empty($bccs)){
            $data['addresses']=array_unique($bccs); 
		 }

   
         Mail::raw($request->body,function($message)use($data){
            $message->to('info@liclass.com','Liclass受付担当');
            $message->subject($data['subject']);
            $message->bcc($data['addresses']);
            if(!empty($data['files'])){
               foreach($data['files'] as $file){
                  $message->attach('storage/'.$file);
               }
            }   
         });
         
         Storage::delete($path_storage);
         return redirect(route('bulkemail.create'))->with('success','Email has been sent successfully.');

      });
      

   }
}
