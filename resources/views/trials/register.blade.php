@extends('layouts.app')

@section('title')
 {{isset($student) ? 'Edit Trial Student' : 'Register Trial Student'}}
@endsection

@section('content')
     
     @include('partials.alerts.error')

     <div class="card mt-4 mx-auto">
       <div class="card-header">
         <h3>{{isset($student) ? 'Edit Trial Student' : 'Register Trial Student'}}</h3>
         @if(!isset($student))
          <div class="form-group mt-4">
          <label for="name_search">名前検索でStudent ListやTrial Listの生徒情報を自動的に反映させることができます。</label>
          <input type="text" placeholder="Search with a name" class="form-control" id="name_search">
          </div>
        
          <div id="stu_list">
            <ul>
            </ul>
          </div>
         @endif
         @if(url()->previous('/waitings')===url('/waitings'))
          <div div class="alert alert-danger">ウェイティングのStatusから移行してきた場合は「Lesson」を必ず設定してください。</div>
         @endif  
         
       </div>
       <div class="card-body card-default">
       

         <form action="{{isset($student) ? route('trials.update',$student->id) : route('trials.store')}}" method="post">
           @csrf
           @if(isset($student))
             @method('PUT')
           @endif

           <input type="hidden" value="" name="student_id" id="student_id">

          
           @include('partials.form.grade')
           <div class="form-group">
              <label for="jaName">氏名 <span class="badge badge-danger ml-2">Required</span></label>
              <input type="text" name="jaName" id="jaName" class="form-control" value="{{isset($student) ? $student->student->jaName : old('jaName')}}">
           </div>
           <div class="form-group">
              <label for="kanaName">カナ<span class="badge badge-danger ml-2">Required</span></label>
              <input type="text" name="kanaName" id="kanaName" class="form-control" value="{{isset($student) ? $student->student->kanaName : old('kanaName')}}">
           </div>
           <div class="form-group">
              <label for="enName">Name<span class="badge badge-danger ml-2">Required</span></label>
              <input type="text" name="enName" id="enName" class="form-control" value="{{isset($student) ? $student->student->enName : old('enName')}}">
           </div>
           <div class="form-group">
              <label for="tel1">Tel 1<span class="badge badge-danger ml-2">Required</span></label>
              <input type="text" name="tel1" id="tel1" class="form-control" value="{{isset($student) ? $student->student->tel1 : old('tel1')}}">
           </div>
           <div class="form-group">
              <label for="tel2">Tel 2</label>
              <input type="text" name="tel2" id="tel2" class="form-control" value="{{isset($student) ? $student->student->tel2 : old('tel2')}}">
           </div>
           <div class="form-group">
              <label for="email1">Email 1<span class="badge badge-danger ml-2">Required</span></label>
              <input type="email" name="email1" id="email1" class="form-control" value="{{isset($student) ? $student->student->email1 : old('email1')}}">
           </div>
           <div class="form-group">
              <label for="email2">Email 2</label>
              <input type="email" name="email2" id="email2" class="form-control" value="{{isset($student) ? $student->student->email2 : old('email2')}}">
           </div>
           @include('partials.form.address')
           @include('partials.form.lesson')
           <div class="form-group">
             <label for="trialDate">Trial Date<span class="badge badge-danger ml-2">Required</span></label>
             <input type="date" name="trialDate" id="trialDate" class="form-control" value="{{isset($student) ? $student->trial_date : old('trialDate')}}">
           </div>
           @include('partials.form.bususe')
           @include('partials.form.pickup')
           @include('partials.form.send')
           <div class="form-group">
             <label for="province">Province</label>
             <input type="text" name="province" id="province" class="form-control" placeholder="" value="{{isset($student) ? $student->student->province : old('province')}}">
           </div>
           <div class="form-group">
             <label for="Note">Note</label>
             <input type="text" name="note" id="note" class="form-control" placeholder="Note for Bus Use" value="{{isset($student) ? $student->note : old('note')}}">
           </div>
           <button type="submit" class="btn btn-info">{{isset($student)? "Update" : "Create"}}</button>
         </form>

         
         <!-- The Modal for add place -->
          <div class="modal" id="add">
            <div class="modal-dialog">
              <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">Add Place</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                  <div class="modal-body">
                      <div class='alert alert-success' style="display:none">Place added successfully.</div>
                      <div class="form-group">
                      <label for="name">Name</label>
                      <input class="form-control" type="text" name="name" id="place_name" placeholder="Please type a name of the condo or mubaan">

                      </div>
                      
                      
                  </div>

                  <!-- Modal footer -->
                  <div class="modal-footer">
                      <button class="btn btn-info" id="place_create" type="button">Create</button>
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  </div>

              </div>
            </div>
          </div>
       </div>
       </div>


@endsection



@section('script')

<script>

  $(document).ready(function(){

    var students;

    // get student infomation to display list of students

    $('#name_search').keypress(function(e){
      if(e.which==13){
        $('#stu_list ul').empty();
        var name=$('#name_search').val();

        $.ajax({
          type:'POST',
          url:"{{route('trials.get_stu_info')}}",
          data:{name:name,_token:'{{csrf_token()}}'}
        }).done(function(data){
          students=data;
          for(var i=0;i<data.length;i++){
            $('#stu_list ul').append("<li data-id='"+i+"'>"+data[i]['student']['jaName']+"&nbsp&nbsp&nbsp<button class='import btn btn-primary btn-sm mb-2' type='button'>Import</button></li>");
          }
          
        })
      }
    });

    // press import button to fill out all input fields
    $('#stu_list').on('click','.import',function(){
      let number=$(this).parent('li').data('id');

      if(confirm("Are you sure you want to fill in input fields with this student's information ?")){
        $('.generated_alert').remove();
        $('.card-body').prepend("<div class='generated_alert alert alert-danger'>体験するLessonを選んでください。</div><div class='generated_alert alert alert-danger'>Bus Useについても確認してください。</div>");
        $('#grade').children("option[value='"+students[number]['student']['grade']+"']").attr('selected','selected');
        $('#student_id').val(students[number]['student_id']);

        $('#jaName').val(students[number]['student']['jaName']);
        $('#kanaName').val(students[number]['student']['kanaName']);
        $('#enName').val(students[number]['student']['enName']);
        $('#tel1').val(students[number]['student']['tel1']);
        $('#tel2').val(students[number]['student']['tel2']);
        $('#email1').val(students[number]['student']['email1']);
        $('#email2').val(students[number]['student']['email2']);
        $('#address').children("option[value='"+students[number]['student']['address_id']+"']").attr('selected','selected');
        $('#addDetails').val(students[number]['student']['addDetails']);
        $('#busUse').children("option[value='"+students[number]['bus']+"']").attr('selected','selected');
        $('#pickup').children("option[value='"+students[number]['pickup_id']+"']").attr('selected','selected');
        $('#pickupDetails').val(students[number]['pickup_details']);
        $('#send').children("option[value='"+students[number]['send_id']+"']").attr('selected','selected');
        $('#sendDetails').val(students[number]['send_details']);
        $('#province').val(students[number]['student']['province']);
        $('#note').val(students[number]['note']);

        $('#stu_list ul').empty();
        $('#name_search').val('');
      }
      


    });


    // add place ajax

    $('#place_create').click(function(){
      let place_name=$('#place_name').val();
      $.ajax({
          type:'POST',
          url:"{{route('settings/places/add_place_ajax')}}",
          data:{name:place_name,_token:"{{csrf_token()}}"}
        }).done(function(data){
          console.log(data);
          $('#place_name').val('');
          $('.modal-body .alert-success').show();
          setTimeout(function(){
              $('.alert-success').slideUp();
            }, 2000);
          $('#address').append("<option selected val="+data.id+">"+data.name+"</option>");
          $('#send').append("<option selected val="+data.id+">"+data.name+"</option>");
          $('#pickup').append("<option selected val="+data.id+">"+data.name+"</option>");
        });
    });

   
    






  });

</script>

@endsection


