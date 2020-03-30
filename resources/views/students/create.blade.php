@extends('layouts.app')

@section('title')
 Create Student
@endsection

@section('content')
<h2>Register Basic Info</h2>
     
     @include('partials.alerts.error')
     @include('partials.alerts.success')
 
     <div class="card  mt-4 mx-auto">
       <div class="card-header">
         <h3>Create Student</h3>
         <ul>
          <li>Basic Infoは生徒情報の名前などの基本情報を登録できます。</li>
          <li>生徒の受講講座の登録はLesson Infoから行ってください。</li>  
         </ul>
       </div>
       <div class="card-body card-default">
         <form action="{{route('students.store')}}" method="post">
           @csrf

            <div class="form-group">
              <label for="grade">Grade<span class="badge badge-danger ml-2">Required</span></label>
              <select class="form-control" name="grade" id="grade">
                <option disabled selected>Please Select</option>
                @foreach(Config::get('const.grades') as $grade)
                  <option value="{{$grade}}" {{$grade==old('grade') ? 'selected' : ''}}>{{$grade}}</option>
                @endforeach
              </select>
            </div>
           <div class="form-group">
              <label for="jaName">氏名 <span class="badge badge-danger ml-2">Required</span></label>
              <input type="text" name="jaName" id="jaName" class="form-control" value="{{old('jaName')}}">
           </div>
           <div class="form-group">
              <label for="kanaName">かな<span class="badge badge-danger ml-2">Required</span></label>
              <input type="text" name="kanaName" id="kanaName" class="form-control" value="{{old('kanaName')}}">
           </div>
           <div class="form-group">
              <label for="enName">Name<span class="badge badge-danger ml-2">Required</span></label>
              <input type="text" name="enName" id="enName" class="form-control" value="{{old('enName')}}">
           </div>
           <div class="form-group">
              <label for="tel1">Tel 1<span class="badge badge-danger ml-2">Required</span></label>
              <input type="text" name="tel1" id="tel1" class="form-control" value="{{old('tel1')}}">
           </div>
           <div class="form-group">
              <label for="tel2">Tel 2</label>
              <input type="text" name="tel2" id="tel2" class="form-control" value="{{old('tel2')}}">
           </div>
           <div class="form-group">
              <label for="email1">Email 1<span class="badge badge-danger ml-2">Required</span></label>
              <input type="email" name="email1" id="email1" class="form-control" value="{{old('email1')}}">
           </div>
           <div class="form-group">
              <label for="email2">Email 2</label>
              <input type="email" name="email2" id="email2" class="form-control" value="{{old('email2')}}">
           </div>
           <div class="form-group">
              <label for="address">Address <span class="badge badge-danger ml-2">Required</span>@include('partials.add_place')</label>
              <select class="form-control" name="address" id="address">
                <option selected disabled>---</option>
                @foreach($places as $place)
  
                      <option value="{{$place['id']}}" {{$place['id']==old('address') ? 'selected' : ''}}>{{$place['name']}}</option>

                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="addressDetails">Address Details</label>
              <input type="text" id="addressDetails" name="addressDetails" class="form-control" placeholder="e.g. Room No, Buiding No" value="{{old('addressDetails')}}">
            </div>
           <div class="form-group">
             <label for="province">Province</label>
             <input type="text" name="province" id="province" class="form-control" placeholder="" value="{{old('province')}}">
           </div>
           <div class="form-group">
             <label for="Note">Note</label>
             <input type="text" name="note" id="note" class="form-control" placeholder="Note for Bus Use" value="{{old('note')}}">
           </div>
           <button type="submit" name="button" class="btn btn-info">Save</button>
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
          $('.lesson_info .card-body').prepend("<div class='alert alert-danger'>それぞれのレッスン内のPick Up、Sendも変更してください。</div>")
          
        });
    });


    

   });
 
 </script>

@endsection


