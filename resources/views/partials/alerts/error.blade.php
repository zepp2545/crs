@if($errors->any())
 @foreach($errors->all() as $error)
   <div class="alert alert-danger">
     {{$error}}
   </div>
 @endforeach
@endif

@if(session("student_existing_error"))
   <div class="alert alert-danger">
     {{session('student_existing_error')}}
   </div>
@endif