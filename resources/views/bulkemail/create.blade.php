@extends('layouts.app')

@section('title')
 {{'Bulk Email'}}
@endsection

@section('content')
     
     @include('partials.alerts.error')
     @include('partials.alerts.success')
     

     <div class="card  mt-4 mx-auto emailContainer">
       <div class="card-header">
         <h3>{{'Bulk Email'}}</h3>
         <ul>
           <li>このメールは既存生のみに送信されますのでご注意ください。体験生には個別にメールを送ってください。</li>
           <li>同じメールアドレス（兄弟生）には１通のみメールが送信されます。</li>
         </ul>
         
       </div>
       <div class="card-body card-default">
         <form action="{{route('bulkemail.send')}}" method="post" enctype='multipart/form-data'>
           @csrf
           <div class="form-group bcc">
                <label for="checkType">Bcc<span class="badge badge-danger ml-2">Required</span></label>
                <select name="checkType" id="checkType" class="form-control">
                   <option selected disabled>Please Select</option>
                   <option value="grade">Grade</option>
                   <option value="lessons">Lesson</option>
                </select>
           </div>
           <div class="form-group lessonCheck d-none">
             <p>Bcc by Lesson<span class="badge badge-danger ml-2">Required</span></p>
             <p>複数曜日のレッスン(中学部本科,中3理社特訓)はどちらかだけ選択すれば送信されます。両方選んでも1件だけ送信されます。</p>
               @foreach($lessons as $lesson)
                 <div class="form-check form-check-inline">
                    <input type="checkbox" id="lesson_{{$lesson->id}}" class="form-check-input" name="lessons[]" value="{{$lesson->id}}">
                    <label for="lesson_{{$lesson->id}}" class="form-check-label">{{$lesson->name}}</label>
                 </div>
               @endforeach
                
           </div>
           <div class="form-group gradeCheck d-none">
             <p>Bcc by Grade<span class="badge badge-danger ml-2">Required</span></p>
               @foreach(config('const.grades') as $grade)
                 <div class="form-check form-check-inline">
                    
                    <input type="checkbox" id="{{$grade}}" class="form-check-input" name="grades[]" value="{{$grade}}">
                    <label for="{{$grade}}" class="form-check-label">{{$grade}}</label>
                 </div>
               @endforeach
                
           </div>
           
           
            <div class="form-group">
                <label for="title">Title<span class="badge badge-danger ml-2">Required</span></label>
                <input type="text" name="title" id="title" class="form-control" placeholder="type email title here">
            </div>
            <div class="form-group">
                <label for="file1">File 1</label>
                <input type="file" name="file1" id="file1" class="form-control">
                <label for="file2">File 2</label>
                <input type="file" name="file2" id="file2" class="form-control">
                <label for="file3">File 3</label>
                <input type="file" name="file3" id="file3" class="form-control">
                <label for="file3">File 4</label>
                <input type="file" name="file4" id="file4" class="form-control">
                <label for="file3">File 5</label>
                <input type="file" name="file5" id="file5" class="form-control">
            </div>
           <div class="form-group">
              <label for="body">Body<span class="badge badge-danger ml-2">Required</span></label>
              <textarea name="body" id="body" class="form-control" rows="30" placeholder="write content of email">
              </textarea>
           </div>

           <button type="submit" class="btn btn-info">Send</button>


          </form>
              
         </div>
       
       </div>
     </div>

@endsection


@section('script')
    <script>

    $(document).ready(function(){
      // show either bcc by grade or bcc by lesson
      $('#checkType').change(function(){
        let type=$(this).val();
        if(type==='grade'){
          $('.lessonCheck').addClass('d-none');
          $('.gradeCheck').removeClass('d-none');
          $('.lessonCheck').find('input:checkbox').prop('checked',false);
        }else{
          $('.gradeCheck').addClass('d-none');
          $('.lessonCheck').removeClass('d-none');
          $('.gradeCheck').find("input:checkbox").prop('checked',false);
        }
      });




    });


    </script>


@endsection('script')